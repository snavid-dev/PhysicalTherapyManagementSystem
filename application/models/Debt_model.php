<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debt_model extends CI_Model
{
	const DEBT_TYPE_AUTO_SETTLEABLE = 'auto_settleable';
	const DEBT_TYPE_MANUAL_ONLY = 'manual_only';

	protected $schema_ready = FALSE;
	protected $last_reversal_skipped_cleared_count = 0;
	protected $allowed_debt_types = array(self::DEBT_TYPE_AUTO_SETTLEABLE, self::DEBT_TYPE_MANUAL_ONLY);

	public function create($patient_id, $turn_id, $amount, $note = NULL, $debt_type = self::DEBT_TYPE_AUTO_SETTLEABLE)
	{
		$this->ensure_schema();

		$debt_type = in_array($debt_type, $this->allowed_debt_types, TRUE) ? $debt_type : self::DEBT_TYPE_AUTO_SETTLEABLE;

		// A turn must never carry more than one open debt at a time — a new debt for this
		// turn supersedes any stale open one (e.g. left behind by a reversal race). No cash
		// changed hands against the stale row, so it's closed out rather than double-counted.
		if ((int) $turn_id > 0) {
			$this->db
				->where('turn_id', (int) $turn_id)
				->where('status', 'open')
				->update('patient_debts', array(
					'status' => 'cleared',
					'cleared_at' => date('Y-m-d H:i:s'),
					'note' => 'Superseded by a newer debt for this turn (auto-closed)',
				));
		}

		$data = array(
			'patient_id' => (int) $patient_id,
			'turn_id' => (int) $turn_id,
			'amount' => round((float) $amount, 2),
			'status' => 'open',
			'debt_type' => $debt_type,
			'note' => $note,
		);

		$this->db->insert('patient_debts', $data);
		return $this->db->insert_id();
	}

	public function get_auto_settleable_open_debts($patient_id)
	{
		$this->ensure_schema();

		return $this->base_debt_query()
			->where('patient_debts.patient_id', (int) $patient_id)
			->where('patient_debts.status', 'open')
			->where('patient_debts.debt_type', self::DEBT_TYPE_AUTO_SETTLEABLE)
			->order_by('patient_debts.created_at', 'asc')
			->order_by('patient_debts.id', 'asc')
			->get()
			->result_array();
	}

	public function get_open_debts($patient_id)
	{
		$this->ensure_schema();

		return $this->base_debt_query()
			->where('patient_debts.patient_id', (int) $patient_id)
			->where('patient_debts.status', 'open')
			->order_by('patient_debts.created_at', 'asc')
			->order_by('patient_debts.id', 'asc')
			->get()
			->result_array();
	}

	public function get_total_open_debt($patient_id)
	{
		$this->ensure_schema();

		$row = $this->db
			->select('COALESCE(SUM(amount), 0) AS total_open_debt', FALSE)
			->from('patient_debts')
			->where('patient_id', (int) $patient_id)
			->where('status', 'open')
			->get()
			->row_array();

		return $row ? (float) $row['total_open_debt'] : 0.00;
	}

	public function clear_debts($patient_id, $cash_available, $clearing_turn_id, $payment_id = NULL)
	{
		$this->ensure_schema();

		$cash_available = round((float) $cash_available, 2);
		$debts = $this->get_open_debts($patient_id);
		$payment_id = $payment_id === NULL ? NULL : (int) $payment_id;

		foreach ($debts as $debt) {
			if ($cash_available <= 0) {
				break;
			}

			$debt_amount = round((float) $debt['amount'], 2);

			if ($cash_available >= $debt_amount) {
				$this->record_payment_application($payment_id, (int) $debt['id'], $debt_amount);

				$this->db
					->where('id', (int) $debt['id'])
					->update('patient_debts', array(
						'amount' => $debt_amount,
						'status' => 'cleared',
						'cleared_at' => date('Y-m-d H:i:s'),
						'cleared_by_turn_id' => $clearing_turn_id === NULL ? NULL : (int) $clearing_turn_id,
					));

				$cash_available = round($cash_available - $debt_amount, 2);
				continue;
			}

			if ($cash_available > 0) {
				$this->record_payment_application($payment_id, (int) $debt['id'], $debt_amount);

				$this->db
					->where('id', (int) $debt['id'])
					->update('patient_debts', array(
						'amount' => round($debt_amount - $cash_available, 2),
					));

				$cash_available = 0.00;
				break;
			}
		}

		return $cash_available;
	}

	protected function record_payment_application($payment_id, $debt_id, $amount_before)
	{
		if ($payment_id === NULL) {
			return;
		}

		$this->db->insert('debt_payment_applications', array(
			'payment_id' => (int) $payment_id,
			'debt_id' => (int) $debt_id,
			'amount_before' => round((float) $amount_before, 2),
		));
	}

	/**
	 * A payment can only be safely reopened by reopen_debts_for_payment() if every
	 * dollar it moved is accounted for: either as a debt_payment_applications row,
	 * or as wallet overflow. Payments made before debt_payment_applications existed
	 * (2026-08-02) have no application rows even though they may have legitimately
	 * cleared or reduced a real debt — reopen_debts_for_payment would then silently
	 * restore nothing, permanently understating what the patient owes. Refuse
	 * deletion of those instead of guessing.
	 */
	public function payment_is_reconcilable($payment_id, $payment_amount)
	{
		$this->ensure_schema();

		$payment_id = (int) $payment_id;

		$has_applications = $this->db
			->where('payment_id', $payment_id)
			->count_all_results('debt_payment_applications') > 0;

		if ($has_applications) {
			return TRUE;
		}

		$overflow = (float) $this->db
			->select_sum('amount', 'total')
			->where('payment_id', $payment_id)
			->where('type', 'topup')
			->get('patient_wallet_transactions')
			->row('total');

		// No application rows — only safe to reopen if the WHOLE amount is proven
		// to have gone to wallet overflow (i.e. nothing was silently applied to a debt).
		return $overflow >= round((float) $payment_amount, 2) - 0.01;
	}

	/**
	 * Undo exactly the debts a standalone payment (Patients::record_standalone_debt_payment)
	 * applied cash to, restoring each debt to its pre-payment amount/open status. Used when
	 * staff delete a mistaken debt payment entry.
	 */
	public function reopen_debts_for_payment($payment_id)
	{
		$this->ensure_schema();

		$payment_id = (int) $payment_id;

		if ($payment_id <= 0) {
			return 0;
		}

		$applications = $this->db
			->select('debt_id, amount_before')
			->from('debt_payment_applications')
			->where('payment_id', $payment_id)
			->get()
			->result_array();

		foreach ($applications as $application) {
			$this->db
				->where('id', (int) $application['debt_id'])
				->update('patient_debts', array(
					'status' => 'open',
					'cleared_at' => NULL,
					'cleared_by_turn_id' => NULL,
					'amount' => round((float) $application['amount_before'], 2),
				));
		}

		$this->db->where('payment_id', $payment_id)->delete('debt_payment_applications');

		return count($applications);
	}

	public function get_all_debts_for_patient($patient_id)
	{
		$this->ensure_schema();

		return $this->base_debt_query()
			->where('patient_debts.patient_id', (int) $patient_id)
			->order_by('patient_debts.created_at', 'desc')
			->order_by('patient_debts.id', 'desc')
			->get()
			->result_array();
	}

	public function reverse_turn_debts($turn_id)
	{
		$this->ensure_schema();

		$turn_id = (int) $turn_id;
		$this->last_reversal_skipped_cleared_count = 0;

		if ($turn_id <= 0) {
			return 0;
		}

		$this->last_reversal_skipped_cleared_count = (int) $this->db
			->where('turn_id', $turn_id)
			->where('status', 'cleared')
			->count_all_results('patient_debts');

		$this->db
			->where('turn_id', $turn_id)
			->where('status', 'open')
			->delete('patient_debts');

		return (int) $this->db->affected_rows();
	}

	public function has_cleared_debts_for_turn($turn_id)
	{
		$this->ensure_schema();

		return $this->db
			->where('turn_id', (int) $turn_id)
			->where('status', 'cleared')
			->count_all_results('patient_debts') > 0;
	}

	public function get_last_reversal_skipped_cleared_count()
	{
		return (int) $this->last_reversal_skipped_cleared_count;
	}

	protected function base_debt_query()
	{
		return $this->db
			->select("patient_debts.*, turns.turn_date, turns.turn_time, turns.turn_number, sections.name AS section_name, DATE(patient_debts.created_at) AS debt_date", FALSE)
			->from('patient_debts')
			->join('turns', 'turns.id = patient_debts.turn_id', 'left')
			->join('sections', 'sections.id = turns.section_id', 'left');
	}

	protected function ensure_schema()
	{
		if ($this->schema_ready) {
			return;
		}

		$this->ensure_turn_columns();

		if (!$this->db->table_exists('patient_debts')) {
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `patient_debts` (
					`id` int unsigned NOT NULL AUTO_INCREMENT,
					`patient_id` int unsigned NOT NULL,
					`turn_id` int unsigned NOT NULL,
					`amount` decimal(12,2) NOT NULL,
					`status` enum('open','cleared') NOT NULL DEFAULT 'open',
					`debt_type` enum('auto_settleable','manual_only') NOT NULL DEFAULT 'auto_settleable',
					`cleared_at` timestamp NULL DEFAULT NULL,
					`cleared_by_turn_id` int unsigned DEFAULT NULL,
					`note` varchar(255) DEFAULT NULL,
					`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`),
					KEY `patient_debts_patient_id_index` (`patient_id`),
					KEY `patient_debts_turn_id_index` (`turn_id`),
					KEY `patient_debts_cleared_by_turn_id_index` (`cleared_by_turn_id`),
					CONSTRAINT `patient_debts_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
					CONSTRAINT `patient_debts_turn_fk` FOREIGN KEY (`turn_id`) REFERENCES `turns` (`id`) ON DELETE CASCADE,
					CONSTRAINT `patient_debts_cleared_turn_fk` FOREIGN KEY (`cleared_by_turn_id`) REFERENCES `turns` (`id`) ON DELETE SET NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
			");
		}

		// NOTE: The debt_type column ALTER + backfill + unwind used to live here, but
		// MySQL DDL implicitly commits the caller's transaction. We hoisted that work
		// to Debt_model::bootstrap_debt_type_migration(), which MUST be invoked once
		// per process BEFORE any controller transaction starts (see MY_Controller).

		if (!$this->db->table_exists('debt_payment_applications')) {
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `debt_payment_applications` (
					`id` int unsigned NOT NULL AUTO_INCREMENT,
					`payment_id` int unsigned NOT NULL,
					`debt_id` int unsigned NOT NULL,
					`amount_before` decimal(12,2) NOT NULL,
					`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`),
					KEY `debt_payment_applications_payment_id_index` (`payment_id`),
					CONSTRAINT `debt_payment_applications_debt_fk` FOREIGN KEY (`debt_id`) REFERENCES `patient_debts` (`id`) ON DELETE CASCADE
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
			");
		}

		if ($this->db->table_exists('payments')) {
			$this->add_column_if_missing('payments', 'section_id', "ALTER TABLE `payments` ADD COLUMN `section_id` int unsigned DEFAULT NULL AFTER `patient_id`");
			$this->add_column_if_missing('payments', 'staff_id', "ALTER TABLE `payments` ADD COLUMN `staff_id` int unsigned DEFAULT NULL AFTER `section_id`");
		}

		$this->close_orphaned_cash_free_debts();

		$this->schema_ready = TRUE;
	}

	/**
	 * A 'cash' or 'free' turn is always fully settled the moment it's created or
	 * edited — Turns.php's payment_type switch never calls create() for either
	 * branch — so it can never legitimately carry an open debt. reverse_turn_debts()
	 * is supposed to void any pre-existing debt whenever a turn is edited into one
	 * of these types, but 3 real patients (2026-08 audit) ended up with a debt that
	 * survived anyway, still showing as owed real money for a turn now recorded as
	 * fully paid — silently double-billing them. The exact trigger wasn't pinned
	 * down (rare — 3 of ~190 cash edits), so this runs every request rather than
	 * once, self-healing any survivor the same way create()'s own supersede logic
	 * closes a stale debt: preserve the historical amount, mark it cleared. Cheap
	 * once caught up — the join only matches rows that are actually still open.
	 */
	protected function close_orphaned_cash_free_debts()
	{
		if (!$this->db->table_exists('patient_debts') || !$this->db->table_exists('turns')) {
			return;
		}

		$this->db->query("
			UPDATE `patient_debts` pd
			INNER JOIN `turns` t ON t.id = pd.turn_id
			SET pd.status = 'cleared',
				pd.cleared_at = t.updated_at,
				pd.note = 'Superseded — turn reprocessed as cash/free (auto-closed)'
			WHERE pd.status = 'open' AND t.payment_type IN ('cash', 'free')
		");
	}

	/**
	 * One-time migration runner. Safe to call repeatedly; gated by field_exists.
	 * MUST be invoked outside any open DB transaction (the ALTER is DDL).
	 * Returns TRUE if migration ran or was unnecessary, FALSE on failure.
	 */
	public function bootstrap_debt_type_migration()
	{
		if (!$this->db->table_exists('patient_debts')) {
			return TRUE;
		}

		if ($this->db->field_exists('debt_type', 'patient_debts')) {
			return TRUE;
		}

		// Acquire a MySQL named lock so two concurrent PHP-FPM workers don't both run
		// the migration. Timeout=0 means non-blocking; the loser returns and the
		// winner does the work. Either way, subsequent code will see the column.
		$lock_row = $this->db->query("SELECT GET_LOCK('canin_debt_type_migration', 0) AS got_lock")->row_array();

		if (empty($lock_row) || (int) $lock_row['got_lock'] !== 1) {
			// Another worker is running the migration; wait briefly for it to finish.
			$this->db->query("SELECT GET_LOCK('canin_debt_type_migration', 10) AS got_lock");
			$this->db->query("SELECT RELEASE_LOCK('canin_debt_type_migration')");
			return $this->db->field_exists('debt_type', 'patient_debts');
		}

		try {
			// Re-check inside the lock to avoid a race window.
			if (!$this->db->field_exists('debt_type', 'patient_debts')) {
				$this->db->query("ALTER TABLE `patient_debts` ADD COLUMN `debt_type` enum('auto_settleable','manual_only') NOT NULL DEFAULT 'auto_settleable' AFTER `status`");
				$this->backfill_debt_types();
				$this->unwind_incorrect_auto_settlements();
			}
		} finally {
			$this->db->query("SELECT RELEASE_LOCK('canin_debt_type_migration')");
		}

		return TRUE;
	}

	protected function backfill_debt_types()
	{
		// Existing deferred-turn debts are user-elected "manual only" debts that must not auto-settle.
		// Existing prepaid-remaining debts default to 'auto_settleable' which matches the historical behaviour.
		$this->db->query("
			UPDATE `patient_debts` pd
			INNER JOIN `turns` t ON t.id = pd.turn_id
			SET pd.debt_type = 'manual_only'
			WHERE t.payment_type = 'deferred'
		");
	}

	/**
	 * One-time data fix: re-open patient_debts rows that were auto-settled against a wallet credit
	 * even though the source turn was 'deferred' (these are manual_only debts that should never have
	 * been auto-cleared). Deletes the matching auto_debt_settlement wallet transactions so the
	 * subsequent Wallet_model::recalculate_for_patient call rebuilds the balance correctly.
	 */
	protected function unwind_incorrect_auto_settlements()
	{
		if (!$this->db->table_exists('patient_wallet_transactions')) {
			return;
		}

		$this->db->trans_begin();

		$rows = $this->db->query("
			SELECT pwt.id AS tx_id,
				pwt.patient_id,
				pwt.amount,
				pwt.note,
				CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(pwt.note, '#', -1), ' ', 1) AS UNSIGNED) AS debt_id
			FROM `patient_wallet_transactions` pwt
			WHERE pwt.type = 'auto_debt_settlement'
				AND pwt.note LIKE 'Auto debt settlement against debt #%'
		")->result_array();

		if (empty($rows)) {
			$this->db->trans_commit();
			return;
		}

		$affected_patient_ids = array();

		foreach ($rows as $row) {
			$debt_id = (int) ($row['debt_id'] ?? 0);

			if ($debt_id <= 0) {
				continue;
			}

			$debt = $this->db
				->select('patient_debts.id, patient_debts.status, patient_debts.amount, patient_debts.turn_id, patient_debts.debt_type, patient_debts.patient_id, turns.payment_type')
				->from('patient_debts')
				->join('turns', 'turns.id = patient_debts.turn_id')
				->where('patient_debts.id', $debt_id)
				->limit(1)
				->get()
				->row_array();

			if (!$debt) {
				continue;
			}

			// Cross-check: the parsed debt must actually belong to the same patient as
			// the wallet transaction. If not, the note was customized/corrupt — skip.
			if ((int) $debt['patient_id'] !== (int) $row['patient_id']) {
				continue;
			}

			if ((string) $debt['payment_type'] !== 'deferred') {
				continue;
			}

			$settlement_amount = round((float) $row['amount'], 2);

			if ((string) $debt['status'] === 'cleared') {
				$this->db->where('id', $debt_id)->update('patient_debts', array(
					'status' => 'open',
					'cleared_at' => NULL,
					'amount' => $settlement_amount,
					'debt_type' => self::DEBT_TYPE_MANUAL_ONLY,
				));
			} else {
				$this->db->where('id', $debt_id)->update('patient_debts', array(
					'amount' => round((float) $debt['amount'] + $settlement_amount, 2),
					'debt_type' => self::DEBT_TYPE_MANUAL_ONLY,
				));
			}

			$this->db->where('id', (int) $row['tx_id'])->delete('patient_wallet_transactions');

			$affected_patient_ids[(int) $row['patient_id']] = TRUE;
		}

		if (empty($affected_patient_ids)) {
			// CRITICAL: must commit even when no rows survived the cross-checks,
			// otherwise the transaction leaks into the caller's request and every
			// subsequent query runs inside it, auto-rolled-back at shutdown.
			$this->db->trans_commit();
			return;
		}

		// Recompute the persisted wallet balance from the raw transaction log for every affected patient.
		foreach (array_keys($affected_patient_ids) as $patient_id) {
			$net_row = $this->db->query("
				SELECT COALESCE(SUM(CASE WHEN type = 'topup' THEN amount ELSE -amount END), 0) AS net
				FROM `patient_wallet_transactions`
				WHERE patient_id = ?
			", array((int) $patient_id))->row_array();

			$net_balance = round(max(0, (float) $net_row['net']), 2);

			$this->db->where('patient_id', (int) $patient_id)->update('patient_wallet', array('balance' => $net_balance));
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'Debt_model::unwind_incorrect_auto_settlements rolled back due to DB error');
			return;
		}

		$this->db->trans_commit();
	}

	protected function ensure_turn_columns()
	{
		if (!$this->db->table_exists('turns')) {
			return;
		}

		$this->add_column_if_missing('turns', 'section_id', "ALTER TABLE `turns` ADD COLUMN `section_id` int unsigned DEFAULT NULL AFTER `doctor_id`");
		$this->add_column_if_missing('turns', 'turn_number', "ALTER TABLE `turns` ADD COLUMN `turn_number` tinyint unsigned DEFAULT NULL AFTER `section_id`");
	}

	protected function add_column_if_missing($table, $column, $sql)
	{
		if (!$this->db->field_exists($column, $table)) {
			$this->db->query($sql);
		}
	}
}
