<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet_model extends CI_Model
{
	protected $schema_ready = FALSE;

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

	public function top_up($patient_id, $amount, $turn_id = NULL, $note = NULL)
	{
		$this->ensure_schema();

		$patient_id = (int) $patient_id;
		$amount = round((float) $amount, 2);

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
			'amount' => $amount,
			'note' => $note,
		));

		if (!$inserted) {
			return FALSE;
		}

		return $new_balance;
	}

	public function deduct($patient_id, $amount, $turn_id = NULL, $note = NULL)
	{
		$this->ensure_schema();

		$patient_id = (int) $patient_id;
		$amount = round((float) $amount, 2);

		$this->ensure_wallet_exists($patient_id);

		if ($amount < 0) {
			$amount = 0.00;
		}

		$current_balance = $this->get_balance($patient_id);
		$actual_deducted = min($current_balance, $amount);
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
			'amount' => $actual_deducted,
			'note' => $note,
		));

		if (!$inserted) {
			return FALSE;
		}

		return $actual_deducted;
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

		$this->schema_ready = TRUE;
	}
}
