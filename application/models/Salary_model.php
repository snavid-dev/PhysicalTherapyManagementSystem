<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salary_model extends CI_Model
{
	protected $schema_ready = FALSE;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Staff_model');
		$this->load->model('Leave_model');
	}

	public function get_or_create_record($staff_id, $month)
	{
		$this->ensure_schema();

		$record = $this->db
			->get_where('staff_salary_records', array(
				'staff_id' => (int) $staff_id,
				'month' => $month,
			))
			->row_array();

		if ($record) {
			return $record;
		}

		$this->db->insert('staff_salary_records', array(
			'staff_id' => (int) $staff_id,
			'month' => $month,
		));

		return $this->get_record_by_id($this->db->insert_id());
	}

	public function calculate_salary($staff_id, $month)
	{
		$this->ensure_schema();
		// The leave table is migrated from user-based to staff-based by Leave_model;
		// make sure that has run before we query it by staff_id here.
		$this->Leave_model->ensure_schema();

		$staff = $this->Staff_model->get_by_id($staff_id);

		if (!$staff) {
			return array();
		}

		$month_start = $month . '-01';
		$month_end = date('Y-m-t', strtotime($month_start));
		$leave_detail = $this->approved_leave_days_detail($staff, $month_start, $month_end);
		$approved_leaves = (int) $leave_detail['count'];
		$leave_quota = (int) $staff['monthly_leave_quota'];
		// Every approved leave day reduces the salary at the daily rate; there is
		// no free quota, so all approved days are "excess" (deductible).
		$paid_leaves = 0;
		$excess_leaves = $approved_leaves;
		$base_salary = round((float) $staff['salary'], 2);
		$salary_type = (string) $staff['salary_type'];
		$days_in_month = (int) date('t', strtotime($month_start));
		$deduction = 0.00;
		$final_salary = $base_salary;
		$daily_rate = 0.00;

		// Fix B8: if a record already exists with stored daily_rate, REUSE it so
		// already-paid months don't drift when the denominator formula changes.
		$existing_record = $this->db
			->get_where('staff_salary_records', array(
				'staff_id' => (int) $staff_id,
				'month' => $month,
			))
			->row_array();

		$stored_daily_rate = $existing_record && isset($existing_record['daily_rate'])
			? round((float) $existing_record['daily_rate'], 4)
			: 0.0;

		if ($salary_type === 'fixed' && $days_in_month > 0) {
			if ($stored_daily_rate > 0) {
				$daily_rate = $stored_daily_rate;
			} else {
				$daily_rate = round($base_salary / $days_in_month, 4);
			}
			$deduction = round($excess_leaves * $daily_rate, 2);
			$final_salary = round($base_salary - $deduction, 2);
		}

		return array(
			'month' => $month,
			'base_salary' => $base_salary,
			'salary_type' => $salary_type,
			'leave_quota' => $leave_quota,
			'monthly_leave_quota' => $leave_quota,
			'approved_leaves' => $approved_leaves,
			'leave_dates' => $leave_detail['dates'],
			'paid_leaves' => $paid_leaves,
			'excess_leaves' => $excess_leaves,
			'days_in_month' => $days_in_month,
			'daily_rate' => $daily_rate,
			'deduction' => $deduction,
			'final_salary' => $final_salary,
		);
	}

	public function record_payment($staff_id, $month, $amount, $payment_date, $note, $created_by)
	{
		$this->ensure_schema();

		$this->db->trans_begin();

		// Lock this staff+month's record before reading total_paid — otherwise two
		// concurrent payment submissions can both read the same stale total and
		// the second UPDATE silently overwrites the first payment's contribution
		// (same race pattern already found and fixed on the patient wallet balance).
		$this->db->query(
			'SELECT id FROM staff_salary_records WHERE staff_id = ? AND month = ? FOR UPDATE',
			array((int) $staff_id, $month)
		);

		$record = $this->get_or_create_record($staff_id, $month);
		$record = $this->sync_record_if_empty($record, $month);

		$amount = round((float) $amount, 2);

		// Flexible salary: the calculated final_salary is only a suggestion. The
		// manager may pay more (bonus / correction) or less than the suggested
		// amount, so the payment is no longer capped at the remaining balance.
		// Only a non-positive amount is rejected.
		if ($amount <= 0) {
			$this->db->trans_rollback();
			return FALSE;
		}

		$new_total_paid = round((float) $record['total_paid'] + $amount, 2);
		$status = $this->derive_status($new_total_paid, $record['final_salary'], isset($record['settled']) ? $record['settled'] : 0);

		$this->db
			->where('id', (int) $record['id'])
			->update('staff_salary_records', array(
				'total_paid' => $new_total_paid,
				'status' => $status,
			));

		$expense_id = $this->create_salary_expense($staff_id, $month, $amount, $payment_date, $note, $created_by);

		$this->db->insert('staff_salary_payments', array(
			'salary_record_id' => (int) $record['id'],
			'staff_id' => (int) $staff_id,
			'expense_id' => $expense_id,
			'amount' => $amount,
			'payment_date' => $payment_date,
			'note' => $this->null_if_empty($note),
			'created_by' => (int) $created_by ?: NULL,
		));

		$payment_id = $this->db->insert_id();

		$this->db
			->where('id', (int) $payment_id)
			->update('staff_salary_payments', array('expense_id' => $expense_id));

		$this->load->model('Safe_model');
		$this->Safe_model->log_transaction(
			'out',
			'salary_payment',
			$amount,
			$payment_id,
			'staff_salary_payments',
			safe_salary_payment_note($staff_id, $month),
			$created_by
		);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return $this->get_record_by_id($record['id']);
	}

	/**
	 * Delete a recorded salary payment and unwind everything it created: the safe
	 * outflow (with balance recompute), the mirrored expense row, the payment row
	 * itself, and the record's running total/status. Lets a manager correct a
	 * mistaken payment (re-add the right one afterwards).
	 */
	public function delete_payment($payment_id)
	{
		$this->ensure_schema();

		$payment_id = (int) $payment_id;

		$payment = $this->db
			->get_where('staff_salary_payments', array('id' => $payment_id))
			->row_array();

		if (!$payment) {
			return FALSE;
		}

		$this->db->trans_begin();

		// Same lock rationale as record_payment(): serialize concurrent deletes
		// against this record so the total_paid recompute below can't read stale data.
		$this->db->query(
			'SELECT id FROM staff_salary_records WHERE id = ? FOR UPDATE',
			array((int) $payment['salary_record_id'])
		);

		$record = $this->get_record_by_id($payment['salary_record_id']);

		// Remove the safe outflow for this payment and recompute the running balance.
		$this->load->model('Safe_model');
		$safe_ok = $this->Safe_model->delete_transaction_by_reference('staff_salary_payments', $payment_id, 'salary_payment');

		// Remove the mirrored expense row.
		if (!empty($payment['expense_id'])) {
			$this->db->where('id', (int) $payment['expense_id'])->delete('expenses');
		}

		// Remove the payment row.
		$this->db->where('id', $payment_id)->delete('staff_salary_payments');

		// Recompute the record's total_paid + status from the remaining payments.
		if ($record) {
			$sum_row = $this->db
				->select('COALESCE(SUM(amount), 0) AS total', FALSE)
				->from('staff_salary_payments')
				->where('salary_record_id', (int) $record['id'])
				->get()
				->row_array();

			$total_paid = round((float) $sum_row['total'], 2);
			$status = $this->derive_status($total_paid, $record['final_salary'], isset($record['settled']) ? $record['settled'] : 0);

			$this->db
				->where('id', (int) $record['id'])
				->update('staff_salary_records', array(
					'total_paid' => $total_paid,
					'status' => $status,
				));
		}

		if ($safe_ok === FALSE || $this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();

		return TRUE;
	}

	/**
	 * Remaining suggested amount still due for a record. A settled month (closed
	 * by the manager) and any fully/over-paid month both return 0.
	 */
	public function remaining_for($record)
	{
		if (!$record) {
			return 0.0;
		}

		if ((int) (isset($record['settled']) ? $record['settled'] : 0) === 1) {
			return 0.0;
		}

		return max(0, round((float) $record['final_salary'] - (float) $record['total_paid'], 2));
	}

	/**
	 * Close a salary month even if it was paid for less (or more) than the
	 * suggested amount. The suggestion is never a hard limit.
	 */
	public function settle_record($staff_id, $month)
	{
		$this->ensure_schema();

		$record = $this->get_or_create_record($staff_id, $month);
		$record = $this->sync_record_if_empty($record, $month);

		$this->db
			->where('id', (int) $record['id'])
			->update('staff_salary_records', array(
				'settled' => 1,
				'status' => 'paid',
			));

		return $this->get_record_by_id($record['id']);
	}

	/**
	 * Re-open a settled month so it accepts more payments and shows its real
	 * paid/partial/unpaid status again.
	 */
	public function reopen_record($staff_id, $month)
	{
		$this->ensure_schema();

		$record = $this->get_or_create_record($staff_id, $month);
		$status = $this->derive_status($record['total_paid'], $record['final_salary'], 0);

		$this->db
			->where('id', (int) $record['id'])
			->update('staff_salary_records', array(
				'settled' => 0,
				'status' => $status,
			));

		return $this->get_record_by_id($record['id']);
	}

	protected function derive_status($total_paid, $final_salary, $settled = 0)
	{
		if ((int) $settled === 1) {
			return 'paid';
		}

		if ((float) $total_paid <= 0) {
			return 'unpaid';
		}

		if ((float) $total_paid >= (float) $final_salary) {
			return 'paid';
		}

		return 'partial';
	}

	public function get_payments_for_record($salary_record_id)
	{
		$this->ensure_schema();

		return $this->db
			->select('staff_salary_payments.*, users.first_name AS created_by_first_name, users.last_name AS created_by_last_name')
			->from('staff_salary_payments')
			->join('users', 'users.id = staff_salary_payments.created_by', 'left')
			->where('staff_salary_payments.salary_record_id', (int) $salary_record_id)
			->order_by('staff_salary_payments.payment_date', 'asc')
			->order_by('staff_salary_payments.id', 'asc')
			->get()
			->result_array();
	}

	public function get_all_salary_records($filters = array())
	{
		$this->ensure_schema();

		$this->db
			->select('staff_salary_records.*, staff.first_name, staff.last_name, staff.salary_type')
			->from('staff_salary_records')
			->join('staff', 'staff.id = staff_salary_records.staff_id');

		if (!empty($filters['month'])) {
			$this->db->where('staff_salary_records.month', $filters['month']);
		}

		if (!empty($filters['status'])) {
			$this->db->where('staff_salary_records.status', $filters['status']);
		}

		if (!empty($filters['staff_id'])) {
			$this->db->where('staff_salary_records.staff_id', (int) $filters['staff_id']);
		}

		return $this->db
			->order_by('staff_salary_records.month', 'desc')
			->order_by('staff.first_name', 'asc')
			->order_by('staff.last_name', 'asc')
			->get()
			->result_array();
	}

	public function sync_month_records($month, $staff_id = NULL)
	{
		$this->ensure_schema();

		$staff_members = $staff_id
			? array_filter(array($this->Staff_model->get_by_id($staff_id)))
			: $this->Staff_model->get_active();

		foreach ($staff_members as $staff) {
			$record = $this->get_or_create_record($staff['id'], $month);
			$this->sync_record_if_empty($record, $month);
		}
	}

	/**
	 * Keep an existing salary record's calculated_deduction/final_salary in sync
	 * when leave is created, edited, or deleted for a month that already has a
	 * record — including a paid/settled one. record_needs_initial_calculation()
	 * only refreshes a record while it's untouched (unpaid, unsettled, nothing
	 * paid yet), so leave approved *after* that point previously never fed back
	 * into the record at all (confirmed: staff #18's June 2026 record stayed at
	 * a $0 deduction after 2 leave days were approved post-settlement).
	 *
	 * Deliberately narrow: only calculated_deduction/final_salary are touched.
	 * daily_rate stays whatever calculate_salary() already has pinned for this
	 * record (Fix B8 — the rate itself must never drift), and total_paid/status/
	 * settled are never touched here, so no already-recorded payment is altered.
	 * If the record doesn't exist yet, there's nothing to reconcile — a fresh
	 * calculate_salary() will pick up the leave correctly whenever the record is
	 * first created.
	 */
	public function reconcile_leave_impact($staff_id, $start_date, $end_date)
	{
		$this->ensure_schema();

		$staff_id = (int) $staff_id;

		if ($staff_id <= 0 || !$this->is_valid_date($start_date) || !$this->is_valid_date($end_date)) {
			return;
		}

		$cursor = date('Y-m', strtotime($start_date));
		$last_month = date('Y-m', strtotime($end_date));

		while ($cursor <= $last_month) {
			$record = $this->db
				->get_where('staff_salary_records', array('staff_id' => $staff_id, 'month' => $cursor))
				->row_array();

			if ($record) {
				$calculation = $this->calculate_salary($staff_id, $cursor);

				$this->db
					->where('id', (int) $record['id'])
					->update('staff_salary_records', array(
						'calculated_deduction' => $calculation['deduction'],
						'final_salary' => $calculation['final_salary'],
					));
			}

			$cursor = date('Y-m', strtotime($cursor . '-01 +1 month'));
		}
	}

	protected function is_valid_date($date)
	{
		$date = (string) $date;
		$parsed = DateTime::createFromFormat('Y-m-d', $date);
		return $parsed && $parsed->format('Y-m-d') === $date;
	}

	public function get_record_by_id($id)
	{
		$this->ensure_schema();

		return $this->db
			->get_where('staff_salary_records', array('id' => (int) $id))
			->row_array();
	}

	protected function sync_record_if_empty($record, $month)
	{
		if (!$record) {
			return NULL;
		}

		if (!$this->record_needs_initial_calculation($record)) {
			return $record;
		}

		$calculation = $this->calculate_salary($record['staff_id'], $month);

		$this->db
			->where('id', (int) $record['id'])
			->update('staff_salary_records', array(
				'base_salary' => $calculation['base_salary'],
				'calculated_deduction' => $calculation['deduction'],
				'final_salary' => $calculation['final_salary'],
				'daily_rate' => $calculation['daily_rate'],
			));

		return $this->get_record_by_id($record['id']);
	}

	protected function record_needs_initial_calculation($record)
	{
		// Keep the stored suggestion in sync with the staff salary and approved
		// leaves while the month is still unpaid and nothing has been paid yet.
		// Once a payment lands (or the month is settled) the figures are pinned.
		return $record['status'] === 'unpaid'
			&& (int) (isset($record['settled']) ? $record['settled'] : 0) === 0
			&& (float) $record['total_paid'] == 0.0;
	}

	protected function count_approved_leave_days($staff, $from_date, $to_date)
	{
		$detail = $this->approved_leave_days_detail($staff, $from_date, $to_date);
		return (int) $detail['count'];
	}

	/**
	 * Returns the deduplicated set of dates a staff member was on approved leave
	 * within [from, to]. Overlapping leave rows are merged so a single calendar day
	 * is never counted twice.
	 */
	protected function approved_leave_days_detail($staff, $from_date, $to_date)
	{
		$empty = array('count' => 0, 'dates' => array());

		if (empty($staff['id'])) {
			return $empty;
		}

		$rows = $this->db
			->select('start_date, end_date')
			->from('doctor_leaves')
			->where('staff_id', (int) $staff['id'])
			->where('status', 'approved')
			->where('start_date <=', $to_date)
			->where('end_date >=', $from_date)
			->get()
			->result_array();

		$date_set = array();

		foreach ($rows as $row) {
			$effective_start = max($row['start_date'], $from_date);
			$effective_end = min($row['end_date'], $to_date);

			if ($effective_start > $effective_end) {
				continue;
			}

			$period = new DatePeriod(
				new DateTime($effective_start),
				new DateInterval('P1D'),
				(new DateTime($effective_end))->modify('+1 day')
			);

			foreach ($period as $date) {
				$date_set[$date->format('Y-m-d')] = TRUE;
			}
		}

		$dates = array_keys($date_set);
		sort($dates);

		return array(
			'count' => count($dates),
			'dates' => $dates,
		);
	}

	protected function create_salary_expense($staff_id, $month, $amount, $payment_date, $note, $created_by)
	{
		$category_id = $this->ensure_salary_category();

		$this->db->insert('expenses', array(
			'category_id' => $category_id,
			'staff_id' => (int) $staff_id,
			'amount' => $amount,
			'expense_date' => $payment_date,
			'description' => 'Salary payment for ' . $month,
			'created_by' => (int) $created_by ?: NULL,
		));

		return $this->db->insert_id();
	}

	protected function ensure_salary_category()
	{
		$category = $this->db
			->get_where('expense_categories', array('name' => 'Staff Salary Payment'))
			->row_array();

		if ($category) {
			return (int) $category['id'];
		}

		$this->db->insert('expense_categories', array(
			'name' => 'Staff Salary Payment',
			'name_fa' => 'پرداخت معاش کارمند',
		));

		return (int) $this->db->insert_id();
	}

	protected function null_if_empty($value)
	{
		$value = trim((string) $value);
		return $value === '' ? NULL : $value;
	}

	protected function ensure_schema()
	{
		if ($this->schema_ready) {
			return;
		}
		$this->schema_ready = TRUE;

		if (!$this->db->table_exists('expense_categories')) {
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `expense_categories` (
					`id` int unsigned NOT NULL AUTO_INCREMENT,
					`name` varchar(150) NOT NULL,
					`name_fa` varchar(150) DEFAULT NULL,
					`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
			");
		}

		if (!$this->db->table_exists('expenses')) {
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `expenses` (
					`id` int unsigned NOT NULL AUTO_INCREMENT,
					`category_id` int unsigned NOT NULL,
					`staff_id` int unsigned DEFAULT NULL,
					`amount` decimal(12,2) NOT NULL,
					`expense_date` date NOT NULL,
					`description` text DEFAULT NULL,
					`created_by` int unsigned DEFAULT NULL,
					`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`),
					KEY `expenses_category_id_index` (`category_id`),
					KEY `expenses_staff_id_index` (`staff_id`),
					KEY `expenses_created_by_index` (`created_by`),
					CONSTRAINT `expenses_category_fk` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`),
					CONSTRAINT `expenses_staff_fk` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL,
					CONSTRAINT `expenses_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
			");
		}

		if (!$this->db->table_exists('staff_salary_records')) {
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `staff_salary_records` (
					`id` int unsigned NOT NULL AUTO_INCREMENT,
					`staff_id` int unsigned NOT NULL,
					`month` varchar(7) NOT NULL,
					`base_salary` decimal(12,2) NOT NULL DEFAULT 0.00,
					`calculated_deduction` decimal(12,2) NOT NULL DEFAULT 0.00,
					`final_salary` decimal(12,2) NOT NULL DEFAULT 0.00,
					`daily_rate` decimal(12,4) NOT NULL DEFAULT 0.0000,
					`total_paid` decimal(12,2) NOT NULL DEFAULT 0.00,
					`status` enum('unpaid','partial','paid') NOT NULL DEFAULT 'unpaid',
					`settled` tinyint(1) NOT NULL DEFAULT 0,
					`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`),
					UNIQUE KEY `uq_staff_month` (`staff_id`, `month`),
					CONSTRAINT `staff_salary_records_staff_fk` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
			");
		} else {
			if (!$this->db->field_exists('daily_rate', 'staff_salary_records')) {
				// One-time migration to add daily_rate so already-stored records pin their rate.
				$this->db->query("ALTER TABLE `staff_salary_records` ADD COLUMN `daily_rate` decimal(12,4) NOT NULL DEFAULT 0.0000 AFTER `final_salary`");
			}

			if (!$this->db->field_exists('settled', 'staff_salary_records')) {
				// One-time migration to add the manager-controlled "settled" flag.
				$this->db->query("ALTER TABLE `staff_salary_records` ADD COLUMN `settled` tinyint(1) NOT NULL DEFAULT 0 AFTER `status`");
			}
		}

		if (!$this->db->table_exists('staff_salary_payments')) {
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `staff_salary_payments` (
					`id` int unsigned NOT NULL AUTO_INCREMENT,
					`salary_record_id` int unsigned NOT NULL,
					`staff_id` int unsigned NOT NULL,
					`expense_id` int unsigned DEFAULT NULL,
					`amount` decimal(12,2) NOT NULL,
					`payment_date` date NOT NULL,
					`note` varchar(255) DEFAULT NULL,
					`created_by` int unsigned DEFAULT NULL,
					`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`),
					KEY `staff_salary_payments_record_id_index` (`salary_record_id`),
					KEY `staff_salary_payments_staff_id_index` (`staff_id`),
					KEY `staff_salary_payments_expense_id_index` (`expense_id`),
					KEY `staff_salary_payments_created_by_index` (`created_by`),
					CONSTRAINT `staff_salary_payments_record_fk` FOREIGN KEY (`salary_record_id`) REFERENCES `staff_salary_records` (`id`) ON DELETE CASCADE,
					CONSTRAINT `staff_salary_payments_staff_fk` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE,
					CONSTRAINT `staff_salary_payments_expense_fk` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE SET NULL,
					CONSTRAINT `staff_salary_payments_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
			");
		}

		$this->ensure_salary_category();
	}
}
