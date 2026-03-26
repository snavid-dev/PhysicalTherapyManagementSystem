<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Safe_model');
	}

	public function get_all($filters = array())
	{
		$this->ensure_schema();

		$this->db
			->select('expenses.*, expense_categories.name AS category_name, expense_categories.name_fa AS category_name_fa, staff.first_name AS staff_first_name, staff.last_name AS staff_last_name, staff_salary_payments.id AS salary_payment_id')
			->from('expenses')
			->join('expense_categories', 'expense_categories.id = expenses.category_id')
			->join('staff', 'staff.id = expenses.staff_id', 'left')
			->join('staff_salary_payments', 'staff_salary_payments.expense_id = expenses.id', 'left');

		if (!empty($filters['category_id'])) {
			$this->db->where('expenses.category_id', (int) $filters['category_id']);
		}

		if (!empty($filters['staff_id'])) {
			$this->db->where('expenses.staff_id', (int) $filters['staff_id']);
		}

		if (!empty($filters['date_from'])) {
			$this->db->where('expenses.expense_date >=', $filters['date_from']);
		}

		if (!empty($filters['date_to'])) {
			$this->db->where('expenses.expense_date <=', $filters['date_to']);
		}

		return $this->db
			->order_by('expenses.expense_date', 'desc')
			->order_by('expenses.id', 'desc')
			->get()
			->result_array();
	}

	public function get_by_id($id)
	{
		$this->ensure_schema();

		return $this->db
			->select('expenses.*, expense_categories.name AS category_name, expense_categories.name_fa AS category_name_fa, CONCAT_WS(" ", staff.first_name, staff.last_name) AS staff_full_name, staff_salary_payments.id AS salary_payment_id')
			->from('expenses')
			->join('expense_categories', 'expense_categories.id = expenses.category_id')
			->join('staff', 'staff.id = expenses.staff_id', 'left')
			->join('staff_salary_payments', 'staff_salary_payments.expense_id = expenses.id', 'left')
			->where('expenses.id', (int) $id)
			->get()
			->row_array();
	}

	public function create($data)
	{
		$this->ensure_schema();

		$data['created_by'] = (int) $this->session->userdata('user_id') ?: NULL;
		$this->db->insert('expenses', $data);
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		$this->ensure_schema();

		return $this->db
			->where('id', (int) $id)
			->update('expenses', $data);
	}

	public function delete($id)
	{
		$this->ensure_schema();
		$id = (int) $id;

		if ($this->is_linked_to_salary_payment($id)) {
			return FALSE;
		}

		$this->db->trans_begin();

		$safe_deleted = $this->Safe_model->delete_transaction_by_reference('expenses', $id, 'expense');
		$deleted = $this->db
			->where('id', $id)
			->delete('expenses');

		if ($safe_deleted === FALSE || !$deleted || $this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();

		return TRUE;
	}

	public function is_linked_to_salary_payment($id)
	{
		$this->ensure_schema();

		return $this->db
			->where('expense_id', (int) $id)
			->count_all_results('staff_salary_payments') > 0;
	}

	public function get_total_by_category($date_from, $date_to)
	{
		$this->ensure_schema();

		return $this->db
			->select('expense_categories.id, expense_categories.name, expense_categories.name_fa, SUM(expenses.amount) AS total_amount')
			->from('expenses')
			->join('expense_categories', 'expense_categories.id = expenses.category_id')
			->where('expenses.expense_date >=', $date_from)
			->where('expenses.expense_date <=', $date_to)
			->group_by('expense_categories.id')
			->order_by('expense_categories.name', 'asc')
			->get()
			->result_array();
	}

	public function get_monthly_summary($year_month)
	{
		$this->ensure_schema();

		$month_start = $year_month . '-01';
		$month_end = date('Y-m-t', strtotime($month_start));

		$row = $this->db
			->select_sum('amount')
			->from('expenses')
			->where('expense_date >=', $month_start)
			->where('expense_date <=', $month_end)
			->get()
			->row_array();

		return (float) ($row['amount'] ?: 0);
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

		$defaults = array(
			array('name' => 'Staff Salary Payment', 'name_fa' => 'پرداخت معاش کارمند'),
			array('name' => 'Rent / Utilities', 'name_fa' => 'کرایه و خدمات'),
			array('name' => 'Other', 'name_fa' => 'سایر'),
		);

		foreach ($defaults as $default) {
			$exists = $this->db
				->where('name', $default['name'])
				->limit(1)
				->get('expense_categories')
				->row_array();

			if (!$exists) {
				$this->db->insert('expense_categories', $default);
			}
		}
	}
}
