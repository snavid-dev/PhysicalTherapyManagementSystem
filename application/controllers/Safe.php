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
		$query_filters = $this->safe_query_filters($filters);
		$today = date('Y-m-d');
		$month_start = date('Y-m-01');

		// "Today"/"This Month" are fixed reference points, but the ledger below
		// is filterable by date — without this, filtering to e.g. yesterday only
		// changes the list, not the totals above it, so a same-day-only figure
		// like a refund reads as "not counted" even though it always summed
		// correctly; the reader was just looking at the wrong period's card.
		$filtered_summary = $this->Safe_model->get_summary(
			$query_filters['date_from'] !== '' ? $query_filters['date_from'] : $month_start,
			$query_filters['date_to'] !== '' ? $query_filters['date_to'] : $today
		);

		$this->render('safe/index', array(
			'title' => t('safe'),
			'current_section' => 'safe',
			'current_balance' => $this->Safe_model->get_current_balance(),
			'latest_transaction' => $this->Safe_model->get_latest_transaction(),
			'today_summary' => $this->Safe_model->get_summary($today, $today),
			'month_summary' => $this->Safe_model->get_summary($month_start, $today),
			'filtered_summary' => $filtered_summary,
			'ledger' => $this->Safe_model->get_ledger($query_filters),
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
		$income_date_input = trim((string) $this->input->post('income_date', TRUE));
		$income_date = $this->gregorian_date_from_shamsi($income_date_input);

		if ($amount <= 0 || $note === '' || $income_date === '') {
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
		$date_from = trim((string) $this->input->get('date_from', TRUE));

		// Ledger grows without bound; default to the current month so an
		// unfiltered visit doesn't join/render the entire history every time.
		// Users can still widen the range via the date_from field.
		if ($date_from === '') {
			$date_from = to_shamsi(date('Y-m-01'));
		}

		return array(
			'type' => trim((string) $this->input->get('type', TRUE)),
			'source' => trim((string) $this->input->get('source', TRUE)),
			'date_from' => $date_from,
			'date_to' => trim((string) $this->input->get('date_to', TRUE)),
		);
	}

	protected function safe_query_filters($filters)
	{
		$query_filters = $filters;
		$query_filters['date_from'] = $filters['date_from'] !== '' ? $this->gregorian_date_from_shamsi($filters['date_from']) : '';
		$query_filters['date_to'] = $filters['date_to'] !== '' ? $this->gregorian_date_from_shamsi($filters['date_to']) : '';
		return $query_filters;
	}
}
