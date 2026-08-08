<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Safe_model');
	}

	public function stats()
	{
		return array(
			'patients' => (int) $this->db->count_all('patients'),
			'users' => (int) $this->db->count_all('users'),
			'today_turns' => (int) $this->db->where('turn_date', date('Y-m-d'))->count_all_results('turns'),
		);
	}

	public function get_safe_balance()
	{
		return $this->Safe_model->get_current_balance();
	}

	public function turns_by_section_today()
	{
		return $this->db
			->select('sections.name AS section_name, COUNT(turns.id) AS turn_count', FALSE)
			->from('turns')
			->join('sections', 'sections.id = turns.section_id', 'left')
			->where('turns.turn_date', date('Y-m-d'))
			->group_by('turns.section_id')
			->order_by('turn_count', 'desc')
			->get()
			->result_array();
	}

	public function open_debt_summary()
	{
		$row = $this->db
			->select('COUNT(DISTINCT patient_id) AS patient_count, COALESCE(SUM(amount), 0) AS total_amount', FALSE)
			->from('patient_debts')
			->where('status', 'open')
			->get()
			->row_array();

		return array(
			'patient_count' => (int) ($row['patient_count'] ?? 0),
			'total_amount' => (float) ($row['total_amount'] ?? 0),
		);
	}

	public function staff_on_leave_today()
	{
		$today = date('Y-m-d');

		return $this->db
			->select('staff.id, staff.first_name, staff.last_name')
			->from('doctor_leaves')
			->join('staff', 'staff.id = doctor_leaves.staff_id')
			->where('doctor_leaves.status', 'approved')
			->where('doctor_leaves.start_date <=', $today)
			->where('doctor_leaves.end_date >=', $today)
			->get()
			->result_array();
	}

	public function unpaid_salary_count_this_month()
	{
		return (int) $this->db
			->where('month', date('Y-m'))
			->where_in('status', array('unpaid', 'partial'))
			->count_all_results('staff_salary_records');
	}

	public function expenses_this_month()
	{
		$amount = $this->db
			->select_sum('amount')
			->where('expense_date >=', date('Y-m-01'))
			->where('expense_date <=', date('Y-m-t'))
			->get('expenses')
			->row('amount');

		return (float) $amount;
	}

	public function new_patients_this_month()
	{
		return (int) $this->db
			->where('created_at >=', date('Y-m-01 00:00:00'))
			->count_all_results('patients');
	}
}
