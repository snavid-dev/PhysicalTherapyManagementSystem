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

		$month_input = trim((string) $this->input->get('month', TRUE));
		$month = $month_input !== '' ? $this->gregorian_month_from_shamsi($month_input) : date('Y-m');
		if ($month === '') {
			$month = date('Y-m');
		}

		$filters = array(
			'month' => $month_input !== '' ? $month_input : gregorian_month_to_shamsi($month),
			'status' => trim((string) $this->input->get('status', TRUE)),
			'staff_id' => (int) $this->input->get('staff_id'),
		);

		$query_filters = $filters;
		$query_filters['month'] = $month;
		$this->Salary_model->sync_month_records($month, $filters['staff_id'] ?: NULL);
		$records = $this->Salary_model->get_all_salary_records($query_filters);

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

		$month_input = trim((string) $this->input->get('month', TRUE));
		$month = $month_input !== '' ? $this->gregorian_month_from_shamsi($month_input) : date('Y-m');
		if ($month === '') {
			$month = date('Y-m');
		}
		$month_display = $month_input !== '' ? $month_input : gregorian_month_to_shamsi($month);

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
			'month_display' => $month_display,
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
		$month_input = trim((string) $this->input->post('month', TRUE));
		$month = $this->gregorian_month_from_shamsi($month_input);
		$amount = round((float) $this->input->post('amount'), 2);
		$payment_date_input = trim((string) $this->input->post('payment_date', TRUE));
		$payment_date = $this->gregorian_date_from_shamsi($payment_date_input);
		$note = trim((string) $this->input->post('note', TRUE));

		$staff = $this->Staff_model->get_by_id($staff_id);
		show_404_if_empty($staff);

		if ($month === '' || $payment_date === '') {
			$this->session->set_flashdata('error', t('Please choose valid salary payment details.'));
			return redirect('salaries/pay/' . $staff_id . '?month=' . rawurlencode($month_input));
		}

		$this->Salary_model->sync_month_records($month, $staff_id);
		$record = $this->Salary_model->get_or_create_record($staff_id, $month);
		$remaining = max(0, round((float) $record['final_salary'] - (float) $record['total_paid'], 2));

		if ($amount <= 0 || $amount > $remaining) {
			$this->session->set_flashdata('error', t('Salary payment amount exceeds the remaining unpaid amount.'));
			return redirect('salaries/pay/' . $staff_id . '?month=' . rawurlencode($month_input));
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
			return redirect('salaries/pay/' . $staff_id . '?month=' . rawurlencode($month_input));
		}

		$this->session->set_flashdata('success', t('Salary payment recorded successfully.'));
		redirect('salaries/pay/' . $staff_id . '?month=' . rawurlencode($month_input));
	}

	public function get_calculation()
	{
		$this->require_permission('manage_salaries');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$staff_id = (int) $this->input->post('staff_id');
		$month_input = trim((string) $this->input->post('month', TRUE));
		$month = $this->gregorian_month_from_shamsi($month_input);
		$staff = $this->Staff_model->get_by_id($staff_id);

		if (!$staff || $month === '') {
			return $this->output
				->set_status_header(422)
				->set_content_type('application/json')
				->set_output(json_encode(array(
					'error' => t('Please choose a valid month.'),
				)));
		}

		$this->Salary_model->sync_month_records($month, $staff_id);
		$record = $this->Salary_model->get_or_create_record($staff_id, $month);
		$payments = array_map(static function ($payment) {
			$payment['payment_date_shamsi'] = to_shamsi($payment['payment_date']);
			return $payment;
		}, $this->Salary_model->get_payments_for_record($record['id']));
		$calculation = $this->Salary_model->calculate_salary($staff_id, $month);
		$calculation['month_shamsi'] = $month_input !== '' ? $month_input : gregorian_month_to_shamsi($month);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'calculation' => $calculation,
				'record' => $record,
				'month' => $month_input !== '' ? $month_input : gregorian_month_to_shamsi($month),
				'month_gregorian' => $month,
				'payments' => $payments,
				'remaining_amount' => max(0, round((float) $record['final_salary'] - (float) $record['total_paid'], 2)),
			)));
	}
}
