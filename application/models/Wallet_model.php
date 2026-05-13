<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet_model extends CI_Model
{
	protected $schema_ready = FALSE;
	protected $allowed_fund_types = array('cash_topup', 'historical_credit');

	public function attach_latest_transaction_to_turn($patient_id, $type, $amount, $turn_id)
	{
		$this->ensure_schema();

		$patient_id = (int) $patient_id;
		$turn_id = (int) $turn_id;
		$type = trim((string) $type);
		$amount = round((float) $amount, 2);

		if ($patient_id <= 0 || $turn_id <= 0 || $amount <= 0 || !in_array($type, array('topup', 'deduction'), TRUE)) {
			return FALSE;
		}

		$transaction = $this->db
			->select('id')
			->from('patient_wallet_transactions')
			->where('patient_id', $patient_id)
			->where('type', $type)
			->where('amount', $amount)
			->where('turn_id IS NULL', NULL, FALSE)
			->order_by('id', 'desc')
			->limit(1)
			->get()
			->row_array();

		if (!$transaction) {
			return FALSE;
		}

		return $this->db
			->where('id', (int) $transaction['id'])
			->update('patient_wallet_transactions', array(
				'turn_id' => $turn_id,
			));
	}

	public function get_balance($patient_id)
	{
		$this->ensure_schema();
		$this->ensure_wallet_exists($patient_id);

		$row = $this->db
			->select('balance')
			->from('patient_wallet')
			->where('patient_id', (int) $patient_id)
			->limit(1)
			->get()
			->row_array();

		return $row ? (float) $row['balance'] : 0.00;
	}

	public function get_balance_breakdown($patient_id)
	{
		$this->ensure_schema();
		$this->ensure_wallet_exists($patient_id);

		$balances = array(
			'cash_topup' => 0.00,
			'historical_credit' => 0.00,
		);

		$rows = $this->db
			->select('type, fund_type, COALESCE(SUM(amount), 0) AS total_amount', FALSE)
			->from('patient_wallet_transactions')
			->where('patient_id', (int) $patient_id)
			->group_by('type')
			->group_by('fund_type')
			->get()
			->result_array();

		foreach ($rows as $row) {
			$fund_type = $this->normalize_fund_type($row['fund_type'] ?? NULL);

			if (!isset($balances[$fund_type])) {
				continue;
			}

			$amount = round((float) ($row['total_amount'] ?? 0), 2);

			if (($row['type'] ?? '') === 'topup') {
				$balances[$fund_type] = round($balances[$fund_type] + $amount, 2);
				continue;
			}

			if (($row['type'] ?? '') === 'deduction') {
				$balances[$fund_type] = round($balances[$fund_type] - $amount, 2);
			}
		}

		$balances['cash_topup'] = max(0, round($balances['cash_topup'], 2));
		$balances['historical_credit'] = max(0, round($balances['historical_credit'], 2));
		$balances['total'] = round($balances['cash_topup'] + $balances['historical_credit'], 2);

		return $balances;
	}

	public function ensure_wallet_exists($patient_id)
	{
		$this->ensure_schema();

		$patient_id = (int) $patient_id;

		if ($patient_id <= 0) {
			return FALSE;
		}

		$wallet = $this->db
			->select('id')
			->from('patient_wallet')
			->where('patient_id', $patient_id)
			->limit(1)
			->get()
			->row_array();

		if ($wallet) {
			return TRUE;
		}

		return $this->db->insert('patient_wallet', array(
			'patient_id' => $patient_id,
			'balance' => 0.00,
		));
	}

	public function top_up_cash($patient_id, $amount, $turn_id = NULL, $note = NULL)
	{
		return $this->top_up($patient_id, $amount, $turn_id, $note, 'cash_topup');
	}

	public function top_up_historical($patient_id, $amount, $turn_id = NULL, $note = NULL)
	{
		return $this->top_up($patient_id, $amount, $turn_id, $note, 'historical_credit');
	}

	public function top_up($patient_id, $amount, $turn_id = NULL, $note = NULL, $fund_type = 'cash_topup')
	{
		$this->ensure_schema();

		$patient_id = (int) $patient_id;
		$amount = round((float) $amount, 2);
		$fund_type = $this->normalize_fund_type($fund_type);

		$this->ensure_wallet_exists($patient_id);

		if ($amount <= 0) {
			return $this->get_balance($patient_id);
		}

		$current_balance = $this->get_balance($patient_id);
		$new_balance = round($current_balance + $amount, 2);

		$updated = $this->db
			->where('patient_id', $patient_id)
			->update('patient_wallet', array('balance' => $new_balance));

		if (!$updated) {
			return FALSE;
		}

		$inserted = $this->db->insert('patient_wallet_transactions', array(
			'patient_id' => $patient_id,
			'turn_id' => $turn_id ? (int) $turn_id : NULL,
			'type' => 'topup',
			'fund_type' => $fund_type,
			'amount' => $amount,
			'note' => $note,
		));

		if (!$inserted) {
			return FALSE;
		}

		return $new_balance;
	}

	public function deduct_prioritized($patient_id, $amount, $turn_id = NULL, $note = NULL)
	{
		$this->ensure_schema();

		$remaining = round((float) $amount, 2);
		$allocations = array(
			'historical_credit' => 0.00,
			'cash_topup' => 0.00,
		);

		foreach (array('historical_credit', 'cash_topup') as $fund_type) {
			if ($remaining <= 0) {
				break;
			}

			$deducted = $this->deduct($patient_id, $remaining, $turn_id, $note, $fund_type);

			if ($deducted === FALSE) {
				return FALSE;
			}

			$allocations[$fund_type] = round((float) $deducted, 2);
			$remaining = round($remaining - (float) $deducted, 2);
		}

		$allocations['deducted_amount'] = round($allocations['historical_credit'] + $allocations['cash_topup'], 2);

		return $allocations;
	}

	public function deduct($patient_id, $amount, $turn_id = NULL, $note = NULL, $fund_type = 'cash_topup')
	{
		$this->ensure_schema();

		$patient_id = (int) $patient_id;
		$amount = round((float) $amount, 2);
		$fund_type = $this->normalize_fund_type($fund_type);

		$this->ensure_wallet_exists($patient_id);

		if ($amount < 0) {
			$amount = 0.00;
		}

		$balance_breakdown = $this->get_balance_breakdown($patient_id);
		$current_bucket_balance = round((float) ($balance_breakdown[$fund_type] ?? 0), 2);
		$current_balance = $this->get_balance($patient_id);
		$actual_deducted = min($current_bucket_balance, $amount);
		$new_balance = round($current_balance - $actual_deducted, 2);

		$updated = $this->db
			->where('patient_id', $patient_id)
			->update('patient_wallet', array('balance' => $new_balance));

		if (!$updated) {
			return FALSE;
		}

		$inserted = $this->db->insert('patient_wallet_transactions', array(
			'patient_id' => $patient_id,
			'turn_id' => $turn_id ? (int) $turn_id : NULL,
			'type' => 'deduction',
			'fund_type' => $fund_type,
			'amount' => $actual_deducted,
			'note' => $note,
		));

		if (!$inserted) {
			return FALSE;
		}

		return $actual_deducted;
	}

	public function reverse_turn_topups($patient_id, $turn_id, $note = NULL)
	{
		$this->ensure_schema();

		$patient_id = (int) $patient_id;
		$turn_id = (int) $turn_id;

		if ($patient_id <= 0 || $turn_id <= 0) {
			return 0.00;
		}

		$rows = $this->db
			->from('patient_wallet_transactions')
			->where('patient_id', $patient_id)
			->where('turn_id', $turn_id)
			->where('type', 'topup')
			->group_start()
				->where('note IS NULL', NULL, FALSE)
				->or_where('note NOT LIKE', 'REVERSAL:%')
			->group_end()
			->order_by('id', 'asc')
			->get()
			->result_array();

		$total_reversed = 0.00;

		foreach ($rows as $row) {
			$deducted = $this->deduct(
				$patient_id,
				(float) ($row['amount'] ?? 0),
				$turn_id,
				$this->reversal_note($note),
				$row['fund_type'] ?? 'cash_topup'
			);

			if ($deducted === FALSE) {
				return FALSE;
			}

			$total_reversed = round($total_reversed + (float) $deducted, 2);
		}

		return $total_reversed;
	}

	public function reverse_turn_deductions($patient_id, $turn_id, $note = NULL)
	{
		$this->ensure_schema();

		$patient_id = (int) $patient_id;
		$turn_id = (int) $turn_id;

		if ($patient_id <= 0 || $turn_id <= 0) {
			return 0.00;
		}

		$rows = $this->db
			->from('patient_wallet_transactions')
			->where('patient_id', $patient_id)
			->where('turn_id', $turn_id)
			->where('type', 'deduction')
			->group_start()
				->where('note IS NULL', NULL, FALSE)
				->or_where('note NOT LIKE', 'REVERSAL:%')
			->group_end()
			->order_by('id', 'asc')
			->get()
			->result_array();

		$total_reversed = 0.00;

		foreach ($rows as $row) {
			$balance = $this->top_up(
				$patient_id,
				(float) ($row['amount'] ?? 0),
				$turn_id,
				$this->reversal_note($note),
				$row['fund_type'] ?? 'cash_topup'
			);

			if ($balance === FALSE) {
				return FALSE;
			}

			$total_reversed = round($total_reversed + (float) ($row['amount'] ?? 0), 2);
		}

		return $total_reversed;
	}

	public function reverse_topup($patient_id, $amount, $turn_id, $note = NULL)
	{
		$this->ensure_schema();

		$patient_id = (int) $patient_id;
		$amount = round((float) $amount, 2);
		$turn_id = $turn_id === NULL ? NULL : (int) $turn_id;

		$this->ensure_wallet_exists($patient_id);

		if ($amount <= 0) {
			return 0.00;
		}

		return $this->deduct($patient_id, $amount, $turn_id, $this->reversal_note($note), 'cash_topup');
	}

	public function reverse_deduction($patient_id, $amount, $turn_id, $note = NULL)
	{
		$this->ensure_schema();

		$patient_id = (int) $patient_id;
		$amount = round((float) $amount, 2);
		$turn_id = $turn_id === NULL ? NULL : (int) $turn_id;

		$this->ensure_wallet_exists($patient_id);

		if ($amount <= 0) {
			return $this->get_balance($patient_id);
		}

		return $this->top_up($patient_id, $amount, $turn_id, $this->reversal_note($note), 'cash_topup');
	}

	public function get_transactions($patient_id, $limit = 20)
	{
		$this->ensure_schema();

		return $this->db
			->from('patient_wallet_transactions')
			->where('patient_id', (int) $patient_id)
			->order_by('created_at', 'desc')
			->order_by('id', 'desc')
			->limit((int) $limit)
			->get()
			->result_array();
	}

	public function get_turn_balance_effect($turn_id)
	{
		$this->ensure_schema();

		$turn_id = (int) $turn_id;
		$effects = array(
			'cash_topup' => 0.00,
			'historical_credit' => 0.00,
			'total' => 0.00,
		);

		if ($turn_id <= 0) {
			return $effects;
		}

		$rows = $this->db
			->select('fund_type, COALESCE(SUM(CASE WHEN type = "topup" THEN amount ELSE -amount END), 0) AS net_amount', FALSE)
			->from('patient_wallet_transactions')
			->where('turn_id', $turn_id)
			->group_by('fund_type')
			->get()
			->result_array();

		foreach ($rows as $row) {
			$fund_type = $this->normalize_fund_type($row['fund_type'] ?? NULL);
			$effects[$fund_type] = round((float) ($row['net_amount'] ?? 0), 2);
		}

		$effects['total'] = round((float) $effects['cash_topup'] + (float) $effects['historical_credit'], 2);

		return $effects;
	}

	public function reverse_turn_balance_effect($patient_id, $turn_id, $note = NULL)
	{
		$this->ensure_schema();

		$patient_id = (int) $patient_id;
		$turn_id = (int) $turn_id;

		if ($patient_id <= 0 || $turn_id <= 0) {
			return 0.00;
		}

		$effects = $this->get_turn_balance_effect($turn_id);
		$total_reversed = 0.00;

		foreach (array('historical_credit', 'cash_topup') as $fund_type) {
			$net_amount = round((float) ($effects[$fund_type] ?? 0), 2);

			if (abs($net_amount) < 0.01) {
				continue;
			}

			if ($net_amount > 0) {
				$deducted = $this->deduct(
					$patient_id,
					$net_amount,
					$turn_id,
					$this->reversal_note($note),
					$fund_type
				);

				if ($deducted === FALSE) {
					return FALSE;
				}

				$total_reversed = round($total_reversed + (float) $deducted, 2);
				continue;
			}

			$balance = $this->top_up(
				$patient_id,
				abs($net_amount),
				$turn_id,
				$this->reversal_note($note),
				$fund_type
			);

			if ($balance === FALSE) {
				return FALSE;
			}

			$total_reversed = round($total_reversed + abs($net_amount), 2);
		}

		return $total_reversed;
	}

	protected function ensure_schema()
	{
		if ($this->schema_ready) {
			return;
		}

		if (!$this->db->table_exists('patient_wallet')) {
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `patient_wallet` (
					`id` int unsigned NOT NULL AUTO_INCREMENT,
					`patient_id` int unsigned NOT NULL,
					`balance` decimal(12,2) NOT NULL DEFAULT 0.00,
					`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`),
					UNIQUE KEY `patient_wallet_patient_id_unique` (`patient_id`),
					CONSTRAINT `patient_wallet_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
			");
		}

		if (!$this->db->table_exists('patient_wallet_transactions')) {
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `patient_wallet_transactions` (
					`id` int unsigned NOT NULL AUTO_INCREMENT,
					`patient_id` int unsigned NOT NULL,
					`turn_id` int unsigned DEFAULT NULL,
					`type` enum('topup','deduction') NOT NULL,
					`fund_type` enum('cash_topup','historical_credit') NOT NULL DEFAULT 'cash_topup',
					`amount` decimal(12,2) NOT NULL,
					`note` varchar(255) DEFAULT NULL,
					`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`),
					KEY `patient_wallet_transactions_patient_id_index` (`patient_id`),
					KEY `patient_wallet_transactions_turn_id_index` (`turn_id`),
					CONSTRAINT `patient_wallet_transactions_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
					CONSTRAINT `patient_wallet_transactions_turn_fk` FOREIGN KEY (`turn_id`) REFERENCES `turns` (`id`) ON DELETE SET NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
			");
		}

		$this->ensure_fund_type_column();

		$this->schema_ready = TRUE;
	}

	protected function ensure_fund_type_column()
	{
		$column = $this->column_definition('patient_wallet_transactions', 'fund_type');

		if (!$column) {
			$this->db->query("ALTER TABLE `patient_wallet_transactions` ADD COLUMN `fund_type` enum('cash_topup','historical_credit') NOT NULL DEFAULT 'cash_topup' AFTER `type`");
			return;
		}

		$expected = "enum('cash_topup','historical_credit')";

		if (strtolower((string) $column['Type']) !== $expected || strtoupper((string) $column['Null']) !== 'NO' || (string) $column['Default'] !== 'cash_topup') {
			$this->db->query("ALTER TABLE `patient_wallet_transactions` MODIFY COLUMN `fund_type` enum('cash_topup','historical_credit') NOT NULL DEFAULT 'cash_topup'");
		}

		$this->db
			->where('fund_type IS NULL', NULL, FALSE)
			->or_where('fund_type', '')
			->update('patient_wallet_transactions', array('fund_type' => 'cash_topup'));
	}

	protected function column_definition($table, $column)
	{
		$query = $this->db->query("SHOW COLUMNS FROM `{$table}` LIKE " . $this->db->escape($column));
		return $query ? $query->row_array() : NULL;
	}

	protected function normalize_fund_type($fund_type)
	{
		$fund_type = trim((string) $fund_type);

		if (in_array($fund_type, $this->allowed_fund_types, TRUE)) {
			return $fund_type;
		}

		return 'cash_topup';
	}

	protected function reversal_note($note)
	{
		$note = trim((string) $note);
		return $note === '' ? 'REVERSAL:' : 'REVERSAL: ' . $note;
	}
}
