<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Dashboard_model');
		$this->load->model('Safe_model');
	}

	public function index()
	{
		$safe_balance = NULL;
		$turns_by_section = NULL;
		$open_debt_summary = NULL;
		$staff_on_leave = NULL;
		$unpaid_salary_count = NULL;
		$expenses_this_month = NULL;
		$new_patients_this_month = NULL;

		if ($this->auth->has_permission('view_safe')) {
			$safe_balance = $this->Dashboard_model->get_safe_balance();
		}

		if ($this->auth->has_permission('manage_turns')) {
			$turns_by_section = $this->Dashboard_model->turns_by_section_today();
		}

		if ($this->auth->has_permission('manage_patients')) {
			$open_debt_summary = $this->Dashboard_model->open_debt_summary();
			$new_patients_this_month = $this->Dashboard_model->new_patients_this_month();
		}

		if ($this->auth->has_permission('manage_leaves')) {
			$staff_on_leave = $this->Dashboard_model->staff_on_leave_today();
		}

		if ($this->auth->has_permission('manage_salaries')) {
			$unpaid_salary_count = $this->Dashboard_model->unpaid_salary_count_this_month();
		}

		if ($this->auth->has_permission('manage_expenses')) {
			$expenses_this_month = $this->Dashboard_model->expenses_this_month();
		}

		$this->render('dashboard/index', array(
			'title' => t('Dashboard'),
			'current_section' => 'dashboard',
			'stats' => $this->Dashboard_model->stats(),
			'safe_balance' => $safe_balance,
			'turns_by_section' => $turns_by_section,
			'open_debt_summary' => $open_debt_summary,
			'staff_on_leave' => $staff_on_leave,
			'unpaid_salary_count' => $unpaid_salary_count,
			'expenses_this_month' => $expenses_this_month,
			'new_patients_this_month' => $new_patients_this_month,
		));
	}
}
