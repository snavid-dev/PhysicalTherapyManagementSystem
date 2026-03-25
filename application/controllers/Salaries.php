<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salaries extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Salary_model');
		$this->load->model('Staff_model');
	}

	public function index()
	{
		$this->require_permission('manage_salaries');

		$filters = array(
			'month' => $this->input->get('month', TRUE) ?: date('Y-m'),
			'status' => trim((string) $this->input->get('status', TRUE)),
			'staff_id' => (int) $this->input->get('staff_id'),
		);

		if (!$this->is_valid_month($filters['month'])) {
			$filters['month'] = date('Y-m');
		}

		$this->Salary_model->sync_month_records($filters['month'], $filters['staff_id'] ?: NULL);
		$records = $this->Salary_model->get_all_salary_records($filters);

		$this->render('salaries/index', array(
			'title' => t('salaries'),
			'current_section' => 'salaries',
			'records' => $records,
			'filters' => $filters,
			'staff_members' => $this->Staff_model->get_active(),
		));
	}

	public function pay($staff_id)
	{
		$this->require_permission('manage_salaries');
		$staff = $this->Staff_model->get_by_id($staff_id);
		show_404_if_empty($staff);

		$month = $this->input->get('month', TRUE) ?: date('Y-m');
		if (!$this->is_valid_month($month)) {
			$month = date('Y-m');
		}

		$calculation = $this->Salary_model->calculate_salary($staff_id, $month);
		$record = $this->Salary_model->get_or_create_record($staff_id, $month);
		$this->Salary_model->sync_month_records($month, $staff_id);
		$record = $this->Salary_model->get_or_create_record($staff_id, $month);
		$payments = $this->Salary_model->get_payments_for_record($record['id']);

		$this->render('salaries/pay', array(
			'title' => t('salary_payment'),
			'current_section' => 'salaries',
			'staff' => $staff,
			'month' => $month,
			'calculation' => $calculation,
			'record' => $record,
			'payments' => $payments,
			'remaining_amount' => max(0, round((float) $record['final_salary'] - (float) $record['total_paid'], 2)),
		));
	}

	public function store_payment()
	{
		$this->require_permission('manage_salaries');

		$staff_id = (int) $this->input->post('staff_id');
		$month = trim((string) $this->input->post('month', TRUE));
		$amount = round((float) $this->input->post('amount'), 2);
		$payment_date = trim((string) $this->input->post('payment_date', TRUE));
		$note = trim((string) $this->input->post('note', TRUE));

		$staff = $this->Staff_model->get_by_id($staff_id);
		show_404_if_empty($staff);

		if (!$this->is_valid_month($month) || !$this->is_valid_date($payment_date)) {
			$this->session->set_flashdata('error', t('Please choose valid salary payment details.'));
			return redirect('salaries/pay/' . $staff_id . '?month=' . rawurlencode($month));
		}

		$this->Salary_model->sync_month_records($month, $staff_id);
		$record = $this->Salary_model->get_or_create_record($staff_id, $month);
		$remaining = max(0, round((float) $record['final_salary'] - (float) $record['total_paid'], 2));

		if ($amount <= 0 || $amount > $remaining) {
			$this->session->set_flashdata('error', t('Salary payment amount exceeds the remaining unpaid amount.'));
			return redirect('salaries/pay/' . $staff_id . '?month=' . rawurlencode($month));
		}

		$result = $this->Salary_model->record_payment(
			$staff_id,
			$month,
			$amount,
			$payment_date,
			$note,
			$this->auth->user_id()
		);

		if (!$result) {
			$this->session->set_flashdata('error', t('Unable to record salary payment right now.'));
			return redirect('salaries/pay/' . $staff_id . '?month=' . rawurlencode($month));
		}

		$this->session->set_flashdata('success', t('Salary payment recorded successfully.'));
		redirect('salaries/pay/' . $staff_id . '?month=' . rawurlencode($month));
	}

	public function get_calculation()
	{
		$this->require_permission('manage_salaries');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$staff_id = (int) $this->input->post('staff_id');
		$month = trim((string) $this->input->post('month', TRUE));
		$staff = $this->Staff_model->get_by_id($staff_id);

		if (!$staff || !$this->is_valid_month($month)) {
			return $this->output
				->set_status_header(422)
				->set_content_type('application/json')
				->set_output(json_encode(array(
					'error' => t('Please choose a valid month.'),
				)));
		}

		$this->Salary_model->sync_month_records($month, $staff_id);
		$record = $this->Salary_model->get_or_create_record($staff_id, $month);
		$payments = $this->Salary_model->get_payments_for_record($record['id']);
		$calculation = $this->Salary_model->calculate_salary($staff_id, $month);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'calculation' => $calculation,
				'record' => $record,
				'payments' => $payments,
				'remaining_amount' => max(0, round((float) $record['final_salary'] - (float) $record['total_paid'], 2)),
			)));
	}

	protected function is_valid_month($value)
	{
		$date = DateTime::createFromFormat('Y-m', (string) $value);
		return $date && $date->format('Y-m') === $value;
	}

	protected function is_valid_date($value)
	{
		$date = DateTime::createFromFormat('Y-m-d', (string) $value);
		return $date && $date->format('Y-m-d') === $value;
	}
}
