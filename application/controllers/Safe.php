<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Safe extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Safe_model');
		$this->require_permission('view_safe');
	}

	public function index()
	{
		$filters = $this->safe_filters();
		$today = date('Y-m-d');
		$month_start = date('Y-m-01');

		$this->render('safe/index', array(
			'title' => t('safe'),
			'current_section' => 'safe',
			'current_balance' => $this->Safe_model->get_current_balance(),
			'latest_transaction' => $this->Safe_model->get_latest_transaction(),
			'today_summary' => $this->Safe_model->get_summary($today, $today),
			'month_summary' => $this->Safe_model->get_summary($month_start, $today),
			'ledger' => $this->Safe_model->get_ledger($filters),
			'filters' => $filters,
		));
	}

	public function add_income()
	{
		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$amount = round((float) $this->input->post('amount'), 2);
		$note = trim((string) $this->input->post('note', TRUE));
		$income_date = trim((string) $this->input->post('income_date', TRUE));

		if ($amount <= 0 || $note === '' || !$this->is_valid_date($income_date)) {
			$this->session->set_flashdata('error', t('Please provide valid income details.'));
			return redirect('safe');
		}

		$result = $this->Safe_model->log_transaction(
			'in',
			'other_income',
			$amount,
			NULL,
			NULL,
			$note,
			$this->session->userdata('user_id'),
			$income_date . ' ' . date('H:i:s')
		);

		if ($result === FALSE) {
			$this->session->set_flashdata('error', t('Unable to record income right now.'));
			return redirect('safe');
		}

		$this->session->set_flashdata('success', t('Income recorded successfully.'));
		redirect('safe');
	}

	public function adjust()
	{
		$this->require_permission('manage_safe');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$new_balance = round((float) $this->input->post('new_balance'), 2);
		$reason = trim((string) $this->input->post('reason', TRUE));

		if ($new_balance < 0 || $reason === '') {
			$this->session->set_flashdata('error', t('Please provide a valid balance adjustment.'));
			return redirect('safe');
		}

		$result = $this->Safe_model->adjust_balance(
			$new_balance,
			$reason,
			$this->session->userdata('user_id')
		);

		if ($result === FALSE) {
			$this->session->set_flashdata('error', t('Unable to adjust balance right now.'));
			return redirect('safe');
		}

		$this->session->set_flashdata('success', t('Safe balance adjusted successfully.'));
		redirect('safe');
	}

	protected function safe_filters()
	{
		return array(
			'type' => trim((string) $this->input->get('type', TRUE)),
			'source' => trim((string) $this->input->get('source', TRUE)),
			'date_from' => trim((string) $this->input->get('date_from', TRUE)),
			'date_to' => trim((string) $this->input->get('date_to', TRUE)),
		);
	}

	protected function is_valid_date($value)
	{
		$date = DateTime::createFromFormat('Y-m-d', (string) $value);
		return $date && $date->format('Y-m-d') === $value;
	}
}
