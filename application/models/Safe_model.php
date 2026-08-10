<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Safe_model extends CI_Model
{
	protected $schema_ready = FALSE;
	protected $historical_sync_checked = FALSE;

	public function delete_transaction_by_reference($reference_table, $reference_id, $source = NULL)
	{
		$this->ensure_schema(FALSE);
		$deleted = $this->delete_transactions_by_reference($reference_table, array($reference_id), $source, FALSE);

		if ($deleted === FALSE) {
			return FALSE;
		}

		if ($deleted > 0) {
			$this->recalculate_balances();
		}

		return TRUE;
	}

	public function delete_transactions_for_patient($patient_id)
	{
		$this->ensure_schema(FALSE);

		$patient_id = (int) $patient_id;

		if ($patient_id <= 0) {
			return FALSE;
		}

		$deleted = 0;

		$turn_ids = array_map('intval', array_column(
			$this->db->select('id')->from('turns')->where('patient_id', $patient_id)->get()->result_array(),
			'id'
		));
		$payment_ids = array_map('intval', array_column(
			$this->db->select('id')->from('payments')->where('patient_id', $patient_id)->get()->result_array(),
			'id'
		));
		$wallet_transaction_ids = array_map('intval', array_column(
			$this->db->select('id')->from('patient_wallet_transactions')->where('patient_id', $patient_id)->get()->result_array(),
			'id'
		));

		$delete_groups = array(
			array('table' => 'turns', 'ids' => $turn_ids, 'source' => NULL),
			array('table' => 'payments', 'ids' => $payment_ids, 'source' => NULL),
			array('table' => 'patient_wallet_transactions', 'ids' => $wallet_transaction_ids, 'source' => NULL),
			array('table' => 'patients', 'ids' => array($patient_id), 'source' => NULL),
		);

		foreach ($delete_groups as $group) {
			$result = $this->delete_transactions_by_reference($group['table'], $group['ids'], $group['source'], FALSE);

			if ($result === FALSE) {
				return FALSE;
			}

			$deleted += $result;
		}

		if ($deleted > 0) {
			$this->recalculate_balances();
		}

		return TRUE;
	}

	public function get_current_balance($with_historical_sync = TRUE)
	{
		$this->ensure_schema($with_historical_sync);

		$row = $this->db
			->select('balance_after')
			->from('safe_transactions')
			->order_by('created_at', 'desc')
			->order_by('id', 'desc')
			->limit(1)
			->get()
			->row_array();

		return $row ? round((float) $row['balance_after'], 2) : 0.00;
	}

	public function get_latest_transaction()
	{
		$this->ensure_schema();

		return $this->db
			->select("safe_transactions.*, CONCAT(users.first_name, ' ', users.last_name) AS created_by_name", FALSE)
			->from('safe_transactions')
			->join('users', 'users.id = safe_transactions.created_by', 'left')
			->order_by('safe_transactions.created_at', 'desc')
			->order_by('safe_transactions.id', 'desc')
			->limit(1)
			->get()
			->row_array();
	}

	public function log_transaction($type, $source, $amount, $reference_id = NULL, $reference_table = NULL, $note = NULL, $created_by = NULL, $created_at = NULL, $options = array())
	{
		$this->ensure_schema(FALSE);

		$result = $this->insert_transaction(array(
			'type' => trim((string) $type),
			'source' => trim((string) $source),
			'amount' => round((float) $amount, 2),
			'reference_id' => $reference_id ? (int) $reference_id : NULL,
			'reference_table' => $reference_table ? (string) $reference_table : NULL,
			'note' => $this->null_if_empty($note),
			'created_by' => $created_by ? (int) $created_by : NULL,
			'created_at' => $created_at,
			'skip_duplicate_check' => !empty($options['skip_duplicate_check']),
			'section_id' => !empty($options['section_id']) ? (int) $options['section_id'] : NULL,
			'staff_id' => !empty($options['staff_id']) ? (int) $options['staff_id'] : NULL,
		));

		return $result;
	}

	public function sync_reference_transaction($type, $source, $amount, $reference_id, $reference_table, $note = NULL, $created_by = NULL, $created_at = NULL)
	{
		$this->ensure_schema(FALSE);

		$reference_id = (int) $reference_id;
		$reference_table = trim((string) $reference_table);
		$source = trim((string) $source);

		if ($reference_id <= 0 || $reference_table === '' || $source === '') {
			return FALSE;
		}

		$payload = array(
			'type' => trim((string) $type),
			'source' => $source,
			'amount' => round((float) $amount, 2),
			'reference_id' => $reference_id,
			'reference_table' => $reference_table,
			'note' => $this->null_if_empty($note),
			'created_by' => $created_by ? (int) $created_by : NULL,
		);

		if ($created_at && $this->is_valid_datetime($created_at)) {
			$payload['created_at'] = $created_at;
		}

		$rows = $this->db
			->select('id')
			->from('safe_transactions')
			->where('source', $source)
			->where('reference_id', $reference_id)
			->where('reference_table', $reference_table)
			->order_by('id', 'asc')
			->get()
			->result_array();

		$this->db->trans_begin();

		if (empty($rows)) {
			$result = $this->insert_transaction($payload);

			if ($result === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			}
		} else {
			$primary_id = (int) $rows[0]['id'];
			$update = array(
				'type' => $payload['type'],
				'source' => $payload['source'],
				'amount' => $payload['amount'],
				'reference_id' => $payload['reference_id'],
				'reference_table' => $payload['reference_table'],
				'note' => $payload['note'],
				'created_by' => $payload['created_by'],
			);

			if (array_key_exists('created_at', $payload)) {
				$update['created_at'] = $payload['created_at'];
			}

			$this->db
				->where('id', $primary_id)
				->update('safe_transactions', $update);

			if (count($rows) > 1) {
				$duplicate_ids = array_map('intval', array_column(array_slice($rows, 1), 'id'));

				if (!empty($duplicate_ids)) {
					$this->db
						->where_in('id', $duplicate_ids)
						->delete('safe_transactions');
				}
			}
		}

		$this->recalculate_balances();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();

		return TRUE;
	}

	/**
	 * Sync the safe ledger rows that mirror a turn's collected cash and wallet top-up.
	 * Existing rows for this turn are updated in place; missing rows are inserted;
	 * obsolete rows (amount becomes zero) are deleted. Running `balance_after` is then
	 * recomputed across the whole ledger so chronology stays consistent.
	 *
	 * Use this on Turns::update (in-place adjustment); do NOT use on Turns::delete
	 * (use reverse_turn_transactions or full deletion there).
	 */
	public function sync_turn_safe_entries($turn_id, $cash_collected, $topup_amount, $user_id = NULL, $cash_note = NULL, $topup_note = NULL, $in_outer_transaction = FALSE)
	{
		$this->ensure_schema(FALSE);

		$turn_id = (int) $turn_id;

		if ($turn_id <= 0) {
			return FALSE;
		}

		$cash_collected = round((float) $cash_collected, 2);
		$topup_amount = round((float) $topup_amount, 2);
		$user_id = $user_id ? (int) $user_id : NULL;

		if (!$in_outer_transaction) {
			$this->db->trans_begin();
		}

		$this->upsert_turn_safe_row($turn_id, 'turn_cash', $cash_collected, $user_id, $cash_note);
		$this->upsert_turn_safe_row($turn_id, 'wallet_topup', $topup_amount, $user_id, $topup_note);

		$this->recalculate_balances();

		if (!$in_outer_transaction) {
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			}
			$this->db->trans_commit();
		}

		return TRUE;
	}

	/**
	 * Adjust the net safe contribution of a turn's source (turn_cash or wallet_topup) to
	 * $target_amount by appending COMPENSATING rows instead of mutating history.
	 *
	 * Fixes:
	 *  - A5 (retroactive balance_after rewrite): we no longer modify the existing row's
	 *    amount, so balance_after for rows up to the original timestamp stays stable.
	 *  - B6 (audit-trail loss): we attribute each adjustment to its actual editor via
	 *    created_by and use NOW() so audit timestamps reflect when the edit happened.
	 *  - B7 (history erased on cash→prepaid switch): instead of deleting rows when the
	 *    new amount is 0, we insert an offsetting 'out' row that brings the net to 0.
	 *
	 * Existing pre-M2 duplicate rows (created via skip_duplicate_check) are still
	 * consolidated by net-sum logic rather than DELETE-on-zero.
	 */
	protected function upsert_turn_safe_row($turn_id, $source, $amount, $user_id, $note)
	{
		$existing = $this->db
			->select("id, type, amount")
			->from('safe_transactions')
			->where('source', $source)
			->where('reference_id', (int) $turn_id)
			->where('reference_table', 'turns')
			->order_by('id', 'asc')
			->get()
			->result_array();

		// Compute the current net contribution of this turn+source to the safe.
		$current_net = 0.0;
		foreach ($existing as $row) {
			$row_amount = round((float) $row['amount'], 2);
			if (($row['type'] ?? '') === 'in') {
				$current_net += $row_amount;
			} elseif (($row['type'] ?? '') === 'out') {
				$current_net -= $row_amount;
			}
		}
		$current_net = round($current_net, 2);

		$target = round((float) $amount, 2);
		$delta = round($target - $current_net, 2);

		if (abs($delta) < 0.005) {
			return;
		}

		if (empty($existing) && $target > 0) {
			// Original creation path: no history yet, just insert the first row at NOW.
			$this->db->insert('safe_transactions', array(
				'type' => 'in',
				'source' => $source,
				'amount' => $target,
				'balance_after' => 0.00,
				'reference_id' => (int) $turn_id,
				'reference_table' => 'turns',
				'note' => $this->null_if_empty($note),
				'created_by' => $user_id,
			));
			return;
		}

		// History exists. Append a compensating row to bring the net to $target.
		$row = array(
			'type' => $delta > 0 ? 'in' : 'out',
			'source' => $source,
			'amount' => abs($delta),
			'balance_after' => 0.00,
			'reference_id' => (int) $turn_id,
			'reference_table' => 'turns',
			'note' => $this->null_if_empty($note),
			'created_by' => $user_id,
		);

		$this->db->insert('safe_transactions', $row);
	}

	public function reverse_turn_transactions($turn, $created_by = NULL)
	{
		$this->ensure_schema(FALSE);

		if (!is_array($turn) || empty($turn['id'])) {
			return 0;
		}

		$turn_id = (int) $turn['id'];
		$created_by = $created_by ? (int) $created_by : NULL;

		if ($turn_id <= 0) {
			return 0;
		}

		$reversed = 0;

		if ((float) ($turn['cash_collected'] ?? 0) > 0) {
			$result = $this->log_transaction(
				'out',
				'turn_cash',
				(float) $turn['cash_collected'],
				$turn_id,
				'turns',
				safe_turn_cash_reversal_note($turn_id),
				$created_by,
				NULL,
				array('skip_duplicate_check' => TRUE)
			);

			if ($result === FALSE) {
				return FALSE;
			}

			$reversed++;
		}

		if ((float) ($turn['topup_amount'] ?? 0) > 0) {
			$result = $this->log_transaction(
				'out',
				'wallet_topup',
				(float) $turn['topup_amount'],
				$turn_id,
				'turns',
				safe_turn_wallet_topup_reversal_note($turn_id),
				$created_by,
				NULL,
				array('skip_duplicate_check' => TRUE)
			);

			if ($result === FALSE) {
				return FALSE;
			}

			$reversed++;
		}

		return $reversed;
	}

	public function adjust_balance($new_balance, $reason, $created_by)
	{
		$this->ensure_schema();

		$new_balance = round((float) $new_balance, 2);
		$created_by = $created_by ? (int) $created_by : NULL;
		$reason = trim((string) $reason);

		$this->db->trans_begin();

		$previous_balance = $this->get_current_balance();
		$logged_balance = $this->log_transaction(
			'adjustment',
			'adjustment',
			$new_balance,
			NULL,
			NULL,
			$reason,
			$created_by
		);

		if ($logged_balance === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}

		$safe_transaction_id = (int) $this->db->insert_id();
		$adjustment_amount = round($new_balance - $previous_balance, 2);

		$this->db->insert('safe_adjustments', array(
			'safe_transaction_id' => $safe_transaction_id,
			'previous_balance' => $previous_balance,
			'adjustment_amount' => $adjustment_amount,
			'new_balance' => $new_balance,
			'reason' => $reason,
			'created_by' => $created_by,
		));

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();

		return $new_balance;
	}

	public function get_ledger($filters = array())
	{
		$this->ensure_schema();

		$this->db
			->select("
				safe_transactions.*,
				CONCAT(users.first_name, ' ', users.last_name) AS created_by_name,
				turn_refs.patient_id AS turn_patient_id,
				CONCAT_WS(' ', turn_patient_refs.first_name, COALESCE(NULLIF(turn_patient_refs.last_name, ''), turn_patient_refs.father_name)) AS turn_patient_name,
				payment_refs.patient_id AS payment_patient_id,
				CONCAT_WS(' ', payment_patient_refs.first_name, COALESCE(NULLIF(payment_patient_refs.last_name, ''), payment_patient_refs.father_name)) AS payment_patient_name,
				wallet_refs.patient_id AS wallet_patient_id,
				CONCAT_WS(' ', wallet_patient_refs.first_name, COALESCE(NULLIF(wallet_patient_refs.last_name, ''), wallet_patient_refs.father_name)) AS wallet_patient_name,
				salary_payment_refs.staff_id AS salary_staff_id,
				CONCAT_WS(' ', salary_staff_refs.first_name, salary_staff_refs.last_name) AS salary_staff_name,
				salary_record_refs.month AS salary_month
			", FALSE)
			->from('safe_transactions')
			->join('users', 'users.id = safe_transactions.created_by', 'left')
			->join('turns AS turn_refs', "turn_refs.id = safe_transactions.reference_id AND safe_transactions.reference_table = 'turns'", 'left', FALSE)
			->join('patients AS turn_patient_refs', 'turn_patient_refs.id = turn_refs.patient_id', 'left')
			->join('payments AS payment_refs', "payment_refs.id = safe_transactions.reference_id AND safe_transactions.reference_table = 'payments'", 'left', FALSE)
			->join('patients AS payment_patient_refs', 'payment_patient_refs.id = payment_refs.patient_id', 'left')
			->join('patient_wallet_transactions AS wallet_refs', "wallet_refs.id = safe_transactions.reference_id AND safe_transactions.reference_table = 'patient_wallet_transactions'", 'left', FALSE)
			->join('patients AS wallet_patient_refs', 'wallet_patient_refs.id = wallet_refs.patient_id', 'left')
			->join('staff_salary_payments AS salary_payment_refs', "salary_payment_refs.id = safe_transactions.reference_id AND safe_transactions.reference_table = 'staff_salary_payments'", 'left', FALSE)
			->join('staff AS salary_staff_refs', 'salary_staff_refs.id = salary_payment_refs.staff_id', 'left')
			->join('staff_salary_records AS salary_record_refs', 'salary_record_refs.id = salary_payment_refs.salary_record_id', 'left');

		$this->apply_filters($filters);
		$this->db->order_by('safe_transactions.created_at', 'desc');
		$this->db->order_by('safe_transactions.id', 'desc');

		if (!empty($filters['limit'])) {
			$this->db->limit((int) $filters['limit']);
		}

		return $this->db->get()->result_array();
	}

	public function get_balance_at_date($date)
	{
		$this->ensure_schema();

		$cutoff = $this->normalize_end_of_day($date);

		if ($cutoff === NULL) {
			return 0.00;
		}

		$row = $this->db
			->select('balance_after')
			->from('safe_transactions')
			->where('created_at <=', $cutoff)
			->order_by('created_at', 'desc')
			->order_by('id', 'desc')
			->limit(1)
			->get()
			->row_array();

		return $row ? round((float) $row['balance_after'], 2) : 0.00;
	}

	public function get_summary($date_from, $date_to)
	{
		$this->ensure_schema();

		$range_start = $this->normalize_start_of_day($date_from);
		$range_end = $this->normalize_end_of_day($date_to);
		$row = array(
			'total_in' => 0,
			'total_out' => 0,
		);

		if ($range_start !== NULL && $range_end !== NULL) {
			$row = $this->db
				->select("
					SUM(CASE WHEN type = 'in' THEN amount ELSE 0 END) AS total_in,
					SUM(CASE WHEN type = 'out' THEN amount ELSE 0 END) AS total_out
				", FALSE)
				->from('safe_transactions')
				->where('created_at >=', $range_start)
				->where('created_at <=', $range_end)
				->get()
				->row_array();
		}

		$total_in = round((float) ($row['total_in'] ?? 0), 2);
		$total_out = round((float) ($row['total_out'] ?? 0), 2);

		return array(
			'total_in' => $total_in,
			'total_out' => $total_out,
			'net' => round($total_in - $total_out, 2),
			'opening_balance' => $this->get_balance_before_date($date_from),
			'closing_balance' => $this->get_balance_at_date($date_to),
		);
	}

	protected function get_balance_before_date($date)
	{
		$cutoff = $this->normalize_start_of_day($date);

		if ($cutoff === NULL) {
			return 0.00;
		}

		$row = $this->db
			->select('balance_after')
			->from('safe_transactions')
			->where('created_at <', $cutoff)
			->order_by('created_at', 'desc')
			->order_by('id', 'desc')
			->limit(1)
			->get()
			->row_array();

		return $row ? round((float) $row['balance_after'], 2) : 0.00;
	}

	protected function apply_filters($filters)
	{
		$allowed_types = array('in', 'out', 'adjustment');
		$allowed_sources = array(
			'turn_cash',
			'wallet_topup',
			'patient_payment',
			'patient_debt_payment',
			'patient_refund',
			'other_income',
			'expense',
			'salary_payment',
			'wallet_refund',
			'store_sale',
			'store_refund',
			'adjustment',
		);

		if (!empty($filters['type']) && in_array($filters['type'], $allowed_types, TRUE)) {
			$this->db->where('safe_transactions.type', $filters['type']);
		}

		if (!empty($filters['source']) && in_array($filters['source'], $allowed_sources, TRUE)) {
			$this->db->where('safe_transactions.source', $filters['source']);
		}

		if (!empty($filters['date_from']) && $this->is_valid_date($filters['date_from'])) {
			$this->db->where('DATE(safe_transactions.created_at) >=', $filters['date_from']);
		}

		if (!empty($filters['date_to']) && $this->is_valid_date($filters['date_to'])) {
			$this->db->where('DATE(safe_transactions.created_at) <=', $filters['date_to']);
		}
	}

	protected function ensure_schema($with_historical_sync = TRUE)
	{
		if (!$this->schema_ready) {
			if (!$this->db->table_exists('safe_transactions')) {
				$this->db->query("
					CREATE TABLE IF NOT EXISTS `safe_transactions` (
						`id` int unsigned NOT NULL AUTO_INCREMENT,
						`type` enum('in','out','adjustment') NOT NULL,
						`source` enum('turn_cash','wallet_topup','patient_payment','patient_debt_payment','patient_refund','other_income','expense','salary_payment','wallet_refund','adjustment') NOT NULL,
						`amount` decimal(12,2) NOT NULL,
						`balance_after` decimal(12,2) NOT NULL,
						`reference_id` int unsigned DEFAULT NULL,
						`reference_table` varchar(50) DEFAULT NULL,
						`note` varchar(255) DEFAULT NULL,
						`created_by` int unsigned DEFAULT NULL,
						`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
						PRIMARY KEY (`id`),
						KEY `safe_transactions_type_index` (`type`),
						KEY `safe_transactions_source_index` (`source`),
						KEY `safe_transactions_created_by_index` (`created_by`),
						KEY `safe_transactions_created_at_index` (`created_at`),
						KEY `safe_transactions_source_ref_index` (`source`, `reference_table`, `reference_id`),
						CONSTRAINT `safe_transactions_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
				");
			}

			if (!$this->db->table_exists('safe_adjustments')) {
				$this->db->query("
					CREATE TABLE IF NOT EXISTS `safe_adjustments` (
						`id` int unsigned NOT NULL AUTO_INCREMENT,
						`safe_transaction_id` int unsigned NOT NULL,
						`previous_balance` decimal(12,2) NOT NULL,
						`adjustment_amount` decimal(12,2) NOT NULL,
						`new_balance` decimal(12,2) NOT NULL,
						`reason` text NOT NULL,
						`created_by` int unsigned DEFAULT NULL,
						`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
						PRIMARY KEY (`id`),
						KEY `safe_adjustments_transaction_id_index` (`safe_transaction_id`),
						KEY `safe_adjustments_created_by_index` (`created_by`),
						CONSTRAINT `safe_adjustments_transaction_fk` FOREIGN KEY (`safe_transaction_id`) REFERENCES `safe_transactions` (`id`) ON DELETE CASCADE,
						CONSTRAINT `safe_adjustments_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
				");
			}

			$this->ensure_source_enum();
			$this->ensure_reference_index();

			if (!$this->db->field_exists('section_id', 'safe_transactions')) {
				$this->db->query("ALTER TABLE `safe_transactions` ADD COLUMN `section_id` int unsigned DEFAULT NULL AFTER `reference_table`");
			}

			if (!$this->db->field_exists('staff_id', 'safe_transactions')) {
				$this->db->query("ALTER TABLE `safe_transactions` ADD COLUMN `staff_id` int unsigned DEFAULT NULL AFTER `section_id`");
			}

			$this->schema_ready = TRUE;
		}

		if ($with_historical_sync && !$this->historical_sync_checked) {
			$this->historical_sync_checked = TRUE;
			$this->sync_historical_transactions();
			$this->cleanup_deleted_reference_transactions();
		}
	}

	protected function ensure_source_enum()
	{
		$column = $this->column_definition('safe_transactions', 'source');

		if (!$column) {
			return;
		}

		$expected = "enum('turn_cash','wallet_topup','patient_payment','patient_debt_payment','patient_refund','other_income','expense','salary_payment','wallet_refund','adjustment','store_sale','store_refund')";

		if (strtolower((string) $column['Type']) === $expected) {
			return;
		}

		$this->db->query("
			ALTER TABLE `safe_transactions`
			MODIFY COLUMN `source` ENUM('turn_cash','wallet_topup','patient_payment','patient_debt_payment','patient_refund','other_income','expense','salary_payment','wallet_refund','adjustment','store_sale','store_refund') NOT NULL
		");
	}

	// transaction_exists()/count_safe_records()/delete_orphaned_transactions_for_table()
	// all filter by (source, reference_table, reference_id) — without this index
	// each of those is a full table scan, and sync_historical_transactions() calls
	// transaction_exists() once per legacy event (thousands on a mature ledger).
	protected function ensure_reference_index()
	{
		$exists = $this->db
			->query("
				SELECT 1 FROM information_schema.STATISTICS
				WHERE TABLE_SCHEMA = DATABASE()
				AND TABLE_NAME = 'safe_transactions'
				AND INDEX_NAME = 'safe_transactions_source_ref_index'
				LIMIT 1
			")
			->num_rows() > 0;

		if ($exists) {
			return;
		}

		$this->db->query("
			ALTER TABLE `safe_transactions`
			ADD INDEX `safe_transactions_source_ref_index` (`source`, `reference_table`, `reference_id`)
		");
	}

	protected function sync_historical_transactions()
	{
		if (!$this->needs_historical_sync()) {
			return;
		}

		$events = array_merge(
			$this->legacy_turn_cash_events(),
			$this->legacy_turn_wallet_topup_events(),
			$this->legacy_manual_wallet_topup_events(),
			$this->legacy_payment_events(),
			$this->legacy_expense_events(),
			$this->legacy_salary_events()
		);

		if (empty($events)) {
			return;
		}

		usort($events, static function ($left, $right) {
			$date_compare = strcmp($left['created_at'], $right['created_at']);

			if ($date_compare !== 0) {
				return $date_compare;
			}

			$priority_compare = ((int) $left['priority']) <=> ((int) $right['priority']);

			if ($priority_compare !== 0) {
				return $priority_compare;
			}

			return ((int) $left['reference_id']) <=> ((int) $right['reference_id']);
		});

		foreach ($events as $event) {
			$this->insert_transaction($event);
		}

		$this->recalculate_balances();
	}

	protected function needs_historical_sync()
	{
		// Standalone debt payments use source='patient_debt_payment'; legacy/cash payments
		// use source='patient_payment'. Count BOTH so the sync doesn't think payments are
		// missing and duplicate-insert them on next deploy.
		$payment_safe_count = (int) $this->db
			->where_in('source', array('patient_payment', 'patient_debt_payment'))
			->where('reference_table', 'payments')
			->count_all_results('safe_transactions');

		return $this->count_turn_cash_records() > $this->count_safe_records('turn_cash', 'turns')
			|| $this->count_turn_wallet_topup_records() > $this->count_safe_records('wallet_topup', 'turns')
			|| $this->count_manual_wallet_topup_records() > $this->count_safe_records('wallet_topup', 'patient_wallet_transactions')
			|| $this->count_payment_records() > $payment_safe_count
			|| $this->count_expense_records() > $this->count_safe_records('expense', 'expenses')
			|| $this->count_salary_payment_records() > $this->count_safe_records('salary_payment', 'staff_salary_payments');
	}

	protected function count_turn_cash_records()
	{
		return (int) $this->db
			->where('cash_collected >', 0)
			->count_all_results('turns');
	}

	protected function count_turn_wallet_topup_records()
	{
		return (int) $this->db
			->where('topup_amount >', 0)
			->count_all_results('turns');
	}

	protected function count_payment_records()
	{
		return (int) $this->db
			->where('amount >', 0)
			->count_all_results('payments');
	}

	protected function count_manual_wallet_topup_records()
	{
		$query = $this->db
			->select('COUNT(DISTINCT patient_wallet_transactions.id) AS total')
			->from('patient_wallet_transactions')
			->join(
				'turns',
				"turns.patient_id = patient_wallet_transactions.patient_id
				AND turns.topup_amount = patient_wallet_transactions.amount
				AND turns.topup_amount > 0
				AND DATE(turns.created_at) = DATE(patient_wallet_transactions.created_at)",
				'left',
				FALSE
			)
			->where('patient_wallet_transactions.type', 'topup')
			->where('patient_wallet_transactions.amount >', 0)
			->where('patient_wallet_transactions.turn_id IS NULL', NULL, FALSE)
			// A reversal (Wallet_model::reversal_note(), always prefixed 'REVERSAL:')
			// is internal bookkeeping, never real incoming cash. When its turn is
			// deleted, the FK (ON DELETE SET NULL) nulls turn_id on it too, which
			// would otherwise satisfy this "manual topup" shape and get backfilled
			// into the safe ledger as phantom income (found: 11 patients, $1,990).
			->where("(patient_wallet_transactions.note NOT LIKE 'REVERSAL:%' OR patient_wallet_transactions.note IS NULL)", NULL, FALSE)
			->group_start()
				->where("patient_wallet_transactions.note IS NOT NULL AND patient_wallet_transactions.note != ''", NULL, FALSE)
				->or_where('turns.id IS NULL', NULL, FALSE)
			->group_end();

		if ($this->wallet_fund_type_supported()) {
			$query->where('patient_wallet_transactions.fund_type', 'cash_topup');
		}

		$row = $query->get()->row_array();

		return (int) ($row['total'] ?? 0);
	}

	protected function count_expense_records()
	{
		$row = $this->db
			->select('COUNT(expenses.id) AS total')
			->from('expenses')
			->join('staff_salary_payments', 'staff_salary_payments.expense_id = expenses.id', 'left')
			->where('expenses.amount >', 0)
			->where('staff_salary_payments.id IS NULL', NULL, FALSE)
			->get()
			->row_array();

		return (int) ($row['total'] ?? 0);
	}

	protected function count_salary_payment_records()
	{
		return (int) $this->db
			->where('amount >', 0)
			->count_all_results('staff_salary_payments');
	}

	protected function count_safe_records($source, $reference_table)
	{
		return (int) $this->db
			->where('source', $source)
			->where('reference_table', $reference_table)
			->count_all_results('safe_transactions');
	}

	protected function cleanup_deleted_reference_transactions()
	{
		$deleted = 0;
		$reference_tables = array(
			'turns',
			'payments',
			'patient_wallet_transactions',
			'expenses',
			'staff_salary_payments',
			'patients',
			'store_sales',
		);

		foreach ($reference_tables as $reference_table) {
			$result = $this->delete_orphaned_transactions_for_table($reference_table);

			if ($result === FALSE) {
				return FALSE;
			}

			$deleted += $result;
		}

		if ($deleted > 0) {
			$this->recalculate_balances();
		}

		return TRUE;
	}

	protected function legacy_turn_cash_events()
	{
		$rows = $this->db
			->select('id, cash_collected, turn_date, turn_time, created_at')
			->from('turns')
			->where('cash_collected >', 0)
			->order_by('turn_date', 'asc')
			->order_by('turn_time', 'asc')
			->order_by('id', 'asc')
			->get()
			->result_array();

		return array_map(function ($row) {
			return array(
				'type' => 'in',
				'source' => 'turn_cash',
				'amount' => (float) $row['cash_collected'],
				'reference_id' => (int) $row['id'],
				'reference_table' => 'turns',
				'note' => safe_turn_cash_note($row['id']),
				'created_by' => NULL,
				'created_at' => !empty($row['created_at']) ? $row['created_at'] : $this->datetime_from_date_and_time($row['turn_date'], $row['turn_time']),
				'priority' => 10,
			);
		}, $rows);
	}

	protected function legacy_turn_wallet_topup_events()
	{
		$rows = $this->db
			->select('id, topup_amount, turn_date, turn_time, created_at')
			->from('turns')
			->where('topup_amount >', 0)
			->order_by('turn_date', 'asc')
			->order_by('turn_time', 'asc')
			->order_by('id', 'asc')
			->get()
			->result_array();

		return array_map(function ($row) {
			return array(
				'type' => 'in',
				'source' => 'wallet_topup',
				'amount' => (float) $row['topup_amount'],
				'reference_id' => (int) $row['id'],
				'reference_table' => 'turns',
				'note' => safe_turn_wallet_topup_note($row['id']),
				'created_by' => NULL,
				'created_at' => !empty($row['created_at']) ? $row['created_at'] : $this->datetime_from_date_and_time($row['turn_date'], $row['turn_time']),
				'priority' => 20,
			);
		}, $rows);
	}

	protected function legacy_payment_events()
	{
		// Exclude payments that already have a safe row under EITHER source
		// (patient_payment OR patient_debt_payment) — the standard transaction_exists
		// dedupe in insert_transaction only checks one source at a time, which would
		// re-insert standalone debt payments as duplicates.
		$existing_payment_ids = array_map('intval', array_column(
			$this->db
				->distinct()
				->select('reference_id')
				->from('safe_transactions')
				->where_in('source', array('patient_payment', 'patient_debt_payment'))
				->where('reference_table', 'payments')
				->get()
				->result_array(),
			'reference_id'
		));

		$query = $this->db
			->select('id, patient_id, amount, payment_date, notes')
			->from('payments')
			->where('amount >', 0);

		if (!empty($existing_payment_ids)) {
			$query->where_not_in('id', $existing_payment_ids);
		}

		$rows = $query
			->order_by('payment_date', 'asc')
			->order_by('id', 'asc')
			->get()
			->result_array();

		return array_map(function ($row) {
			$note = $this->null_if_empty($row['notes']);

			if ($note === NULL) {
				$note = safe_patient_payment_note($row['id']);
			}

			return array(
				'type' => 'in',
				'source' => 'patient_payment',
				'amount' => (float) $row['amount'],
				'reference_id' => (int) $row['id'],
				'reference_table' => 'payments',
				'note' => $note,
				'created_by' => NULL,
				'created_at' => $this->datetime_from_date($row['payment_date']),
				'priority' => 30,
			);
		}, $rows);
	}

	protected function legacy_manual_wallet_topup_events()
	{
		$query = $this->db
			->select('patient_wallet_transactions.id, patient_wallet_transactions.patient_id, patient_wallet_transactions.amount, patient_wallet_transactions.note, patient_wallet_transactions.created_at')
			->from('patient_wallet_transactions')
			->join(
				'turns',
				"turns.patient_id = patient_wallet_transactions.patient_id
				AND turns.topup_amount = patient_wallet_transactions.amount
				AND turns.topup_amount > 0
				AND DATE(turns.created_at) = DATE(patient_wallet_transactions.created_at)",
				'left',
				FALSE
			)
			->where('patient_wallet_transactions.type', 'topup')
			->where('patient_wallet_transactions.amount >', 0)
			->where('patient_wallet_transactions.turn_id IS NULL', NULL, FALSE)
			// See the matching guard in count_manual_wallet_topup_records() — a
			// reversal must never be backfilled into the safe ledger as income.
			->where("(patient_wallet_transactions.note NOT LIKE 'REVERSAL:%' OR patient_wallet_transactions.note IS NULL)", NULL, FALSE)
			->group_start()
				->where("patient_wallet_transactions.note IS NOT NULL AND patient_wallet_transactions.note != ''", NULL, FALSE)
				->or_where('turns.id IS NULL', NULL, FALSE)
			->group_end()
			->group_by('patient_wallet_transactions.id')
			->order_by('patient_wallet_transactions.created_at', 'asc')
			->order_by('patient_wallet_transactions.id', 'asc');

		if ($this->wallet_fund_type_supported()) {
			$query->where('patient_wallet_transactions.fund_type', 'cash_topup');
		}

		$rows = $query->get()->result_array();

		return array_map(function ($row) {
			$note = $this->null_if_empty($row['note']);

			if ($note === NULL) {
				$note = safe_patient_wallet_topup_note($row['patient_id']);
			}

			return array(
				'type' => 'in',
				'source' => 'wallet_topup',
				'amount' => (float) $row['amount'],
				'reference_id' => (int) $row['id'],
				'reference_table' => 'patient_wallet_transactions',
				'note' => $note,
				'created_by' => NULL,
				'created_at' => $row['created_at'],
				'priority' => 25,
			);
		}, $rows);
	}

	protected function legacy_expense_events()
	{
		$rows = $this->db
			->select('expenses.id, expenses.amount, expenses.expense_date, expenses.description, expenses.created_by')
			->from('expenses')
			->join('staff_salary_payments', 'staff_salary_payments.expense_id = expenses.id', 'left')
			->where('expenses.amount >', 0)
			->where('staff_salary_payments.id IS NULL', NULL, FALSE)
			->order_by('expenses.expense_date', 'asc')
			->order_by('expenses.id', 'asc')
			->get()
			->result_array();

		return array_map(function ($row) {
			return array(
				'type' => 'out',
				'source' => 'expense',
				'amount' => (float) $row['amount'],
				'reference_id' => (int) $row['id'],
				'reference_table' => 'expenses',
				'note' => $this->null_if_empty($row['description']),
				'created_by' => !empty($row['created_by']) ? (int) $row['created_by'] : NULL,
				'created_at' => $this->datetime_from_date($row['expense_date']),
				'priority' => 40,
			);
		}, $rows);
	}

	protected function legacy_salary_events()
	{
		$rows = $this->db
			->select('staff_salary_payments.id, staff_salary_payments.staff_id, staff_salary_payments.amount, staff_salary_payments.payment_date, staff_salary_payments.note, staff_salary_payments.created_by, staff_salary_records.month')
			->from('staff_salary_payments')
			->join('staff_salary_records', 'staff_salary_records.id = staff_salary_payments.salary_record_id', 'left')
			->where('staff_salary_payments.amount >', 0)
			->order_by('staff_salary_payments.payment_date', 'asc')
			->order_by('staff_salary_payments.id', 'asc')
			->get()
			->result_array();

		return array_map(function ($row) {
			$note = $this->null_if_empty($row['note']);

			if ($note === NULL) {
				$note = safe_salary_payment_note($row['staff_id'], $row['month'] ?? NULL);
			}

			return array(
				'type' => 'out',
				'source' => 'salary_payment',
				'amount' => (float) $row['amount'],
				'reference_id' => (int) $row['id'],
				'reference_table' => 'staff_salary_payments',
				'note' => $note,
				'created_by' => !empty($row['created_by']) ? (int) $row['created_by'] : NULL,
				'created_at' => $this->datetime_from_date($row['payment_date']),
				'priority' => 50,
			);
		}, $rows);
	}

	protected function insert_transaction($data)
	{
		$type = trim((string) ($data['type'] ?? ''));
		$source = trim((string) ($data['source'] ?? ''));
		$amount = round((float) ($data['amount'] ?? 0), 2);

		$skip_duplicate_check = !empty($data['skip_duplicate_check']);

		if (!$skip_duplicate_check && !empty($data['reference_id']) && !empty($data['reference_table']) && $this->transaction_exists($source, (int) $data['reference_id'], (string) $data['reference_table'])) {
			return $this->get_current_balance(FALSE);
		}

		$has_explicit_created_at = !empty($data['created_at']) && $this->is_valid_datetime($data['created_at']);
		$effective_created_at = $has_explicit_created_at ? $data['created_at'] : date('Y-m-d H:i:s');

		// The incremental shortcut below (anchor to whatever row is currently
		// "latest") is only correct when this row is ALSO chronologically latest.
		// A legacy-synced row (date-only source, defaulted to noon) or any other
		// backdated entry can land with a created_at that isn't actually the
		// latest — and once that happens, every ordinary NOW()-timestamped insert
		// keeps anchoring to that same stale row instead of the true running
		// total, silently discarding every real contribution in between. Detect
		// that condition from the table itself, not from whether the caller
		// happened to pass an explicit timestamp.
		$max_existing = $this->db->select_max('created_at', 'max_created_at')->from('safe_transactions')->get()->row_array();
		$max_existing_created_at = $max_existing['max_created_at'] ?? NULL;
		$is_out_of_order = $max_existing_created_at !== NULL && $effective_created_at < $max_existing_created_at;

		$current_balance = $this->get_current_balance(FALSE);
		$new_balance = $current_balance;

		if ($type === 'in') {
			$new_balance = round($current_balance + $amount, 2);
		} elseif ($type === 'out') {
			$new_balance = round($current_balance - $amount, 2);
		} elseif ($type === 'adjustment') {
			$new_balance = $amount;
		}

		$insert = array(
			'type' => $type,
			'source' => $source,
			'amount' => $amount,
			'balance_after' => $new_balance,
			'reference_id' => !empty($data['reference_id']) ? (int) $data['reference_id'] : NULL,
			'reference_table' => !empty($data['reference_table']) ? (string) $data['reference_table'] : NULL,
			'note' => $this->null_if_empty($data['note'] ?? NULL),
			'created_by' => !empty($data['created_by']) ? (int) $data['created_by'] : NULL,
			'section_id' => !empty($data['section_id']) ? (int) $data['section_id'] : NULL,
			'staff_id' => !empty($data['staff_id']) ? (int) $data['staff_id'] : NULL,
		);

		if ($has_explicit_created_at) {
			$insert['created_at'] = $data['created_at'];
		}

		if (!$this->db->insert('safe_transactions', $insert)) {
			return FALSE;
		}

		if ($is_out_of_order) {
			$this->recalculate_balances();
			return $this->get_current_balance(FALSE);
		}

		return $new_balance;
	}

	protected function delete_transactions_by_reference($reference_table, array $reference_ids, $source = NULL, $recalculate = TRUE)
	{
		$this->ensure_schema(FALSE);

		$reference_table = trim((string) $reference_table);
		$reference_ids = array_values(array_unique(array_filter(array_map('intval', $reference_ids))));

		if ($reference_table === '' || empty($reference_ids)) {
			return 0;
		}

		$this->db->where('reference_table', $reference_table);
		$this->db->where_in('reference_id', $reference_ids);

		if ($source !== NULL && $source !== '') {
			$this->db->where('source', $source);
		}

		if (!$this->db->delete('safe_transactions')) {
			return FALSE;
		}

		$deleted = (int) $this->db->affected_rows();

		if ($deleted > 0 && $recalculate) {
			$this->recalculate_balances();
		}

		return $deleted;
	}

	protected function delete_orphaned_transactions_for_table($reference_table)
	{
		$reference_table = trim((string) $reference_table);

		if ($reference_table === '' || !$this->db->table_exists($reference_table)) {
			return 0;
		}

		$rows = $this->db
			->select('safe_transactions.id')
			->from('safe_transactions')
			->join($reference_table, $reference_table . '.id = safe_transactions.reference_id', 'left', FALSE)
			->where('safe_transactions.reference_table', $reference_table)
			->where($reference_table . '.id IS NULL', NULL, FALSE)
			->get()
			->result_array();

		$transaction_ids = array_values(array_unique(array_filter(array_map('intval', array_column($rows, 'id')))));

		if (empty($transaction_ids)) {
			return 0;
		}

		$this->db->where_in('id', $transaction_ids);

		if (!$this->db->delete('safe_transactions')) {
			return FALSE;
		}

		return (int) $this->db->affected_rows();
	}

	protected function transaction_exists($source, $reference_id, $reference_table)
	{
		return $this->db
			->where('source', $source)
			->where('reference_id', (int) $reference_id)
			->where('reference_table', $reference_table)
			->count_all_results('safe_transactions') > 0;
	}

	protected function recalculate_balances()
	{
		$rows = $this->db
			->select('id, type, amount')
			->from('safe_transactions')
			->order_by('created_at', 'asc')
			->order_by('id', 'asc')
			->get()
			->result_array();

		if (empty($rows)) {
			return;
		}

		$running_balance = 0.00;
		$balances = array();
		$adjustments = array();

		foreach ($rows as $row) {
			$amount = round((float) $row['amount'], 2);
			$previous_balance = $running_balance;

			if ($row['type'] === 'in') {
				$running_balance = round($running_balance + $amount, 2);
			} elseif ($row['type'] === 'out') {
				$running_balance = round($running_balance - $amount, 2);
			} elseif ($row['type'] === 'adjustment') {
				$running_balance = $amount;
			}

			$balances[] = array('id' => (int) $row['id'], 'balance_after' => $running_balance);

			if ($row['type'] === 'adjustment') {
				$adjustments[] = array(
					'safe_transaction_id' => (int) $row['id'],
					'previous_balance' => $previous_balance,
					'adjustment_amount' => round($running_balance - $previous_balance, 2),
					'new_balance' => $running_balance,
				);
			}
		}

		// Bulk-apply balance_after via a temp table + JOIN UPDATE instead of one
		// UPDATE per row. With a large ledger, one round trip per row can run
		// past PHP-FPM's request_terminate_timeout and get killed mid-loop,
		// leaving balance_after inconsistent for whatever wasn't reached yet.
		$this->db->query('DROP TEMPORARY TABLE IF EXISTS tmp_safe_balances');
		$this->db->query('CREATE TEMPORARY TABLE tmp_safe_balances (id INT UNSIGNED PRIMARY KEY, balance_after DECIMAL(12,2))');
		$this->db->insert_batch('tmp_safe_balances', $balances, TRUE, 1000);
		$this->db->query('
			UPDATE safe_transactions st
			JOIN tmp_safe_balances t ON t.id = st.id
			SET st.balance_after = t.balance_after
		');
		$this->db->query('DROP TEMPORARY TABLE IF EXISTS tmp_safe_balances');

		// Adjustment rows are rare (manual balance corrections), so per-row
		// updates here are not a scale concern the way the loop above was.
		foreach ($adjustments as $adjustment) {
			$this->db
				->where('safe_transaction_id', $adjustment['safe_transaction_id'])
				->update('safe_adjustments', array(
					'previous_balance' => $adjustment['previous_balance'],
					'adjustment_amount' => $adjustment['adjustment_amount'],
					'new_balance' => $adjustment['new_balance'],
				));
		}
	}

	protected function datetime_from_date($date)
	{
		if (!$this->is_valid_date($date)) {
			return date('Y-m-d H:i:s');
		}

		return $date . ' 12:00:00';
	}

	protected function datetime_from_date_and_time($date, $time = NULL)
	{
		if (!$this->is_valid_date($date)) {
			return date('Y-m-d H:i:s');
		}

		$time = trim((string) $time);

		if ($time === '') {
			return $date . ' 12:00:00';
		}

		$parts = explode(':', $time);

		if (count($parts) === 2) {
			$time .= ':00';
		}

		if (!$this->is_valid_datetime($date . ' ' . $time)) {
			return $date . ' 12:00:00';
		}

		return $date . ' ' . $time;
	}

	protected function column_definition($table, $column)
	{
		$query = $this->db->query("SHOW COLUMNS FROM `{$table}` LIKE " . $this->db->escape($column));
		return $query ? $query->row_array() : NULL;
	}

	protected function wallet_fund_type_supported()
	{
		return $this->db->table_exists('patient_wallet_transactions')
			&& $this->db->field_exists('fund_type', 'patient_wallet_transactions');
	}

	protected function normalize_start_of_day($date)
	{
		if (!$this->is_valid_date($date)) {
			return NULL;
		}

		return $date . ' 00:00:00';
	}

	protected function normalize_end_of_day($date)
	{
		if (!$this->is_valid_date($date)) {
			return NULL;
		}

		return $date . ' 23:59:59';
	}

	protected function is_valid_date($value)
	{
		$date = DateTime::createFromFormat('Y-m-d', (string) $value);
		return $date && $date->format('Y-m-d') === $value;
	}

	protected function is_valid_datetime($value)
	{
		$date = DateTime::createFromFormat('Y-m-d H:i:s', (string) $value);
		return $date && $date->format('Y-m-d H:i:s') === $value;
	}

	protected function null_if_empty($value)
	{
		$value = trim((string) $value);
		return $value === '' ? NULL : $value;
	}

	protected function turn_edit_reversal_note($turn_id, $transaction_id)
	{
		return sprintf(
			t('turn_edit_reversal_note'),
			(int) $turn_id,
			(int) $transaction_id
		);
	}
}
