<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expenses extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Expense_model');
		$this->load->model('Expense_category_model');
		$this->load->model('Safe_model');
		$this->load->model('Staff_model');
	}

	public function index()
	{
		$this->require_permission('manage_expenses');

		$filters = $this->expense_filters();
		$expenses = $this->Expense_model->get_all($this->expense_query_filters($filters));
		$total_amount = 0;

		foreach ($expenses as $expense) {
			$total_amount += (float) $expense['amount'];
		}

		$this->render('expenses/index', array(
			'title' => t('expenses'),
			'current_section' => 'expenses',
			'expenses' => $expenses,
			'categories' => $this->Expense_category_model->get_all(),
			'staff_members' => $this->Staff_model->get_active(),
			'filters' => $filters,
			'total_amount' => $total_amount,
		));
	}

	public function create()
	{
		$this->require_permission('manage_expenses');
		$this->form(NULL, 'expenses/store');
	}

	public function store()
	{
		$this->require_permission('manage_expenses');
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form(NULL, 'expenses/store');
		}

		$category = $this->Expense_category_model->get_by_id($this->input->post('category_id'));
		if (!$category) {
			$this->session->set_flashdata('error', t('Invalid expense category selected.'));
			return redirect('expenses/create');
		}

		if ($this->is_salary_category($category)) {
			$this->session->set_flashdata('error', t('salary_payment_blocked'));
			return redirect('expenses/create');
		}

		$payload = $this->expense_payload();
		$expense_id = $this->Expense_model->create($payload);

		if ($expense_id) {
			$this->Safe_model->log_transaction(
				'out',
				'expense',
				$payload['amount'],
				$expense_id,
				'expenses',
				$payload['description'],
				$this->session->userdata('user_id')
			);
		}

		$this->session->set_flashdata('success', t('Expense created successfully.'));
		redirect('expenses');
	}

	public function edit($id)
	{
		$this->require_permission('manage_expenses');
		$expense = $this->Expense_model->get_by_id($id);
		show_404_if_empty($expense);

		$this->form($expense, 'expenses/update/' . $id, $this->Expense_model->is_linked_to_salary_payment($id));
	}

	public function update($id)
	{
		$this->require_permission('manage_expenses');
		$expense = $this->Expense_model->get_by_id($id);
		show_404_if_empty($expense);

		if ($this->Expense_model->is_linked_to_salary_payment($id)) {
			$this->session->set_flashdata('error', t('salary_linked_expense_read_only'));
			return redirect('expenses/edit/' . $id);
		}

		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form($expense, 'expenses/update/' . $id);
		}

		$category = $this->Expense_category_model->get_by_id($this->input->post('category_id'));
		if (!$category) {
			$this->session->set_flashdata('error', t('Invalid expense category selected.'));
			return redirect('expenses/edit/' . $id);
		}

		if ($this->is_salary_category($category)) {
			$this->session->set_flashdata('error', t('salary_payment_blocked'));
			return redirect('expenses/edit/' . $id);
		}

		if (!$this->Expense_model->update($id, $this->expense_payload())) {
			$this->session->set_flashdata('error', t('Unable to update expense right now.'));
			return redirect('expenses/edit/' . $id);
		}

		$this->session->set_flashdata('success', t('Expense updated successfully.'));
		redirect('expenses');
	}

	public function delete($id)
	{
		$this->require_permission('manage_expenses');
		$expense = $this->Expense_model->get_by_id($id);
		show_404_if_empty($expense);

		if ($this->Expense_model->is_linked_to_salary_payment($id)) {
			$this->session->set_flashdata('error', t('expense_linked_salary'));
			return redirect('expenses');
		}

		if (!$this->Expense_model->delete($id)) {
			$this->session->set_flashdata('error', t('Unable to delete record right now.'));
			return redirect('expenses');
		}

		$this->session->set_flashdata('success', t('Expense deleted successfully.'));
		redirect('expenses');
	}

	protected function form($expense, $action, $read_only = FALSE)
	{
		$this->render('expenses/form', array(
			'title' => $expense ? t('Edit Expense') : t('add_expense'),
			'current_section' => 'expenses',
			'expense' => $expense,
			'action' => $action,
			'categories' => $this->Expense_category_model->get_all(),
			'staff_members' => $this->Staff_model->get_active(),
			'read_only' => $read_only,
		));
	}

	protected function validate_form()
	{
		$this->form_validation->set_rules('category_id', 'Expense Category', 'required|integer');
		$this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('expense_date', 'Expense Date', 'required|callback__valid_expense_date');
	}

	public function _valid_expense_date($value)
	{
		if ($this->is_valid_shamsi_date_input($value)) {
			return TRUE;
		}

		$this->form_validation->set_message('_valid_expense_date', t('Please choose a valid expense date.'));
		return FALSE;
	}

	protected function expense_payload()
	{
		$category = $this->Expense_category_model->get_by_id($this->input->post('category_id'));
		$staff_id = NULL;

		if ($category && $this->is_salary_category($category)) {
			$staff_id = $this->input->post('staff_id') ? (int) $this->input->post('staff_id') : NULL;
		}

		return array(
			'category_id' => (int) $this->input->post('category_id'),
			'staff_id' => $staff_id,
			'amount' => round((float) $this->input->post('amount'), 2),
			'expense_date' => $this->gregorian_date_from_shamsi($this->input->post('expense_date', TRUE)),
			'description' => $this->null_if_empty($this->input->post('description', TRUE)),
		);
	}

	protected function expense_filters()
	{
		return array(
			'category_id' => (int) $this->input->get('category_id'),
			'staff_id' => (int) $this->input->get('staff_id'),
			'date_from' => trim((string) $this->input->get('date_from', TRUE)),
			'date_to' => trim((string) $this->input->get('date_to', TRUE)),
		);
	}

	protected function expense_query_filters($filters)
	{
		$query_filters = $filters;
		$query_filters['date_from'] = $filters['date_from'] !== '' ? $this->gregorian_date_from_shamsi($filters['date_from']) : '';
		$query_filters['date_to'] = $filters['date_to'] !== '' ? $this->gregorian_date_from_shamsi($filters['date_to']) : '';
		return $query_filters;
	}

	protected function is_salary_category($category)
	{
		return isset($category['name']) && $category['name'] === 'Staff Salary Payment';
	}

	protected function null_if_empty($value)
	{
		$value = trim((string) $value);
		return $value === '' ? NULL : $value;
	}
}
