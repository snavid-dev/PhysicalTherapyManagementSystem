<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salary_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Staff_model');
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

		$staff = $this->Staff_model->get_by_id($staff_id);

		if (!$staff) {
			return array();
		}

		$month_start = $month . '-01';
		$month_end = date('Y-m-t', strtotime($month_start));
		$approved_leaves = $this->count_approved_leave_days($staff, $month_start, $month_end);
		$leave_quota = (int) $staff['monthly_leave_quota'];
		$paid_leaves = min($approved_leaves, $leave_quota);
		$excess_leaves = max(0, $approved_leaves - $leave_quota);
		$base_salary = round((float) $staff['salary'], 2);
		$salary_type = (string) $staff['salary_type'];
		$deduction = 0.00;
		$final_salary = $base_salary;

		if ($salary_type === 'fixed') {
			$daily_rate = $base_salary / 30;
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
			'paid_leaves' => $paid_leaves,
			'excess_leaves' => $excess_leaves,
			'deduction' => $deduction,
			'final_salary' => $final_salary,
		);
	}

	public function record_payment($staff_id, $month, $amount, $payment_date, $note, $created_by)
	{
		$this->ensure_schema();

		$this->db->trans_begin();

		$record = $this->get_or_create_record($staff_id, $month);
		$record = $this->sync_record_if_empty($record, $month);

		$amount = round((float) $amount, 2);
		$remaining = max(0, round((float) $record['final_salary'] - (float) $record['total_paid'], 2));

		if ($amount <= 0 || $amount > $remaining) {
			$this->db->trans_rollback();
			return FALSE;
		}

		$new_total_paid = round((float) $record['total_paid'] + $amount, 2);
		$status = 'unpaid';

		if ($new_total_paid >= (float) $record['final_salary']) {
			$status = 'paid';
		} elseif ($new_total_paid > 0) {
			$status = 'partial';
		}

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

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return $this->get_record_by_id($record['id']);
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
			));

		return $this->get_record_by_id($record['id']);
	}

	protected function record_needs_initial_calculation($record)
	{
		return $record['status'] === 'unpaid'
			&& (float) $record['base_salary'] == 0.0
			&& (float) $record['calculated_deduction'] == 0.0
			&& (float) $record['final_salary'] == 0.0
			&& (float) $record['total_paid'] == 0.0;
	}

	protected function count_approved_leave_days($staff, $from_date, $to_date)
	{
		if (empty($staff['user_id'])) {
			return 0;
		}

		$rows = $this->db
			->select('start_date, end_date')
			->from('doctor_leaves')
			->where('doctor_id', (int) $staff['user_id'])
			->where('status', 'approved')
			->where('start_date <=', $to_date)
			->where('end_date >=', $from_date)
			->get()
			->result_array();

		$total_days = 0;

		foreach ($rows as $row) {
			$effective_start = max($row['start_date'], $from_date);
			$effective_end = min($row['end_date'], $to_date);

			if ($effective_start > $effective_end) {
				continue;
			}

			$start = new DateTime($effective_start);
			$end = new DateTime($effective_end);
			$total_days += ((int) $start->diff($end)->days) + 1;
		}

		return $total_days;
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
					`total_paid` decimal(12,2) NOT NULL DEFAULT 0.00,
					`status` enum('unpaid','partial','paid') NOT NULL DEFAULT 'unpaid',
					`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`),
					UNIQUE KEY `uq_staff_month` (`staff_id`, `month`),
					CONSTRAINT `staff_salary_records_staff_fk` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
			");
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
