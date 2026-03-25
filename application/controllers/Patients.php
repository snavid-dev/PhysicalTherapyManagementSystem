<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patients extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Patient_model');
		$this->load->model('Turn_model');
		$this->load->model('Wallet_model');
		$this->load->model('Debt_model');
		$this->load->model('Payment_model');
		$this->load->model('Section_model');
		$this->load->model('Discount_model');
	}

	public function index()
	{
		$this->require_permission('manage_patients');

		$this->render('patients/index', array(
			'title' => t('Patients'),
			'current_section' => 'patients',
			'patients' => $this->Patient_model->get_all(),
		));
	}

	public function create()
	{
		$this->require_permission('manage_patients');
		$this->form(NULL, 'patients/store', array());
	}

	public function store()
	{
		$this->require_permission('manage_patients');
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form(NULL, 'patients/store', $this->diagnosis_ids_from_post());
		}

		$new_id = $this->Patient_model->create($this->patient_payload());
		$this->Patient_model->save_diagnoses($new_id, $this->diagnosis_ids_from_post());
		$this->session->set_flashdata('success', t('Patient created successfully.'));
		redirect('patients');
	}

	public function show($id)
	{
		$this->require_permission('manage_patients');
		$patient = $this->Patient_model->get_by_id($id);
		$patient_diagnoses = $this->Patient_model->get_diagnoses_for_patient($id);
		$turns = $this->Turn_model->get_turns_for_patient($id);
		$payments = $this->Patient_model->payment_history($id);
		$wallet_balance = $this->Wallet_model->get_balance($id);
		$wallet_transactions = $this->Wallet_model->get_transactions($id);
		$open_debts = $this->Debt_model->get_open_debts($id);
		$total_open_debt = $this->Debt_model->get_total_open_debt($id);
		show_404_if_empty($patient);

		$this->render('patients/show', array(
			'title' => t('Patient Profile'),
			'current_section' => 'patients',
			'patient' => $patient,
			'patient_diagnoses' => $patient_diagnoses,
			'turns' => $turns,
			'payments' => $payments,
			'wallet_balance' => $wallet_balance,
			'wallet_transactions' => $wallet_transactions,
			'open_debts' => $open_debts,
			'total_open_debt' => $total_open_debt,
			'financial_summary' => $this->build_financial_summary($wallet_transactions, $turns, $payments, $wallet_balance, $total_open_debt),
			'financial_timeline' => $this->build_financial_timeline($wallet_transactions, $turns, $payments),
			'discounts' => $this->discount_rows_payload($id),
			'all_sections' => $this->Section_model->get_all(),
		));
	}

	public function add_discount($patient_id)
	{
		$this->require_permission('manage_patients');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$patient = $this->Patient_model->get_by_id($patient_id);
		if (!$patient) {
			return $this->json_error(t('Invalid patient selected.'), 404);
		}

		$section_id = (int) $this->input->post('section_id');
		$section = $this->Section_model->get_by_id($section_id);
		if (!$section) {
			return $this->json_error(t('Invalid section selected.'));
		}

		$discount_percent_raw = trim((string) $this->input->post('discount_percent', TRUE));
		if ($discount_percent_raw === '' || !is_numeric($discount_percent_raw) || !$this->valid_discount_percent($discount_percent_raw)) {
			return $this->json_error(t('discount_invalid'));
		}

		$discount_id = $this->Discount_model->create(
			$patient_id,
			$section_id,
			$discount_percent_raw,
			$this->input->post('note', TRUE),
			$this->auth->user_id()
		);

		if (!$discount_id) {
			return $this->json_error(t('unable_to_save_discount'));
		}

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => TRUE,
				'message' => t('discount_saved'),
				'discounts' => $this->discount_rows_payload($patient_id),
			)));
	}

	public function delete_discount($patient_id, $discount_id)
	{
		$this->require_permission('manage_patients');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$patient = $this->Patient_model->get_by_id($patient_id);
		if (!$patient) {
			return $this->json_error(t('Invalid patient selected.'), 404);
		}

		if (!$this->Discount_model->delete($discount_id, $patient_id)) {
			return $this->json_error(t('discount_not_found'), 404);
		}

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => TRUE,
				'message' => t('discount_deleted'),
				'discounts' => $this->discount_rows_payload($patient_id),
			)));
	}

	public function wallet_topup($id)
	{
		$this->require_permission('manage_patients');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$patient = $this->Patient_model->get_by_id($id);
		show_404_if_empty($patient);
		$wants_json = $this->wants_json_response();

		$amount = round((float) $this->input->post('amount'), 2);
		$note = $this->null_if_empty($this->input->post('note', TRUE));

		if ($amount <= 0) {
			return $this->respond_wallet_topup_error($id, t('Invalid wallet amount.'), 422, $wants_json);
		}

		$new_balance = $this->Wallet_model->top_up($id, $amount, NULL, $note);

		if ($new_balance === FALSE) {
			$db_error = $this->db->error();
			$message = t('Unable to update wallet right now.');
			if (ENVIRONMENT !== 'production' && !empty($db_error['message'])) {
				$message .= ' ' . $db_error['message'];
			}
			return $this->respond_wallet_topup_error($id, $message, 500, $wants_json);
		}

		if (!$wants_json) {
			$this->session->set_flashdata('success', t('Wallet updated successfully.'));
			redirect('patients/' . $id);
		}

		$financial_payload = $this->financial_profile_payload($id);

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array_merge($financial_payload, array(
				'success' => TRUE,
				'message' => t('Wallet updated successfully.'),
				'wallet_balance' => (float) $new_balance,
			))));
	}

	public function wallet_deduct($id)
	{
		$this->require_permission('manage_patients');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$patient = $this->Patient_model->get_by_id($id);
		show_404_if_empty($patient);
		$wants_json = $this->wants_json_response();

		$amount = round((float) $this->input->post('amount'), 2);
		$note = $this->null_if_empty($this->input->post('note', TRUE));

		if ($amount <= 0) {
			return $this->respond_wallet_topup_error($id, t('Invalid wallet amount.'), 422, $wants_json);
		}

		$actual_deducted = $this->Wallet_model->deduct($id, $amount, NULL, $note);

		if ($actual_deducted === FALSE) {
			$db_error = $this->db->error();
			$message = t('Unable to update wallet right now.');
			if (ENVIRONMENT !== 'production' && !empty($db_error['message'])) {
				$message .= ' ' . $db_error['message'];
			}
			return $this->respond_wallet_topup_error($id, $message, 500, $wants_json);
		}

		if ((float) $actual_deducted <= 0) {
			return $this->respond_wallet_topup_error($id, t('No wallet balance available to deduct.'), 422, $wants_json);
		}

		if (!$wants_json) {
			$this->session->set_flashdata('success', t('Wallet deducted successfully.'));
			redirect('patients/' . $id);
		}

		$financial_payload = $this->financial_profile_payload($id);

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array_merge($financial_payload, array(
				'success' => TRUE,
				'message' => t('Wallet deducted successfully.'),
				'actual_deducted' => (float) $actual_deducted,
			))));
	}

	public function debt_payment($id)
	{
		$this->require_permission('manage_patients');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$patient = $this->Patient_model->get_by_id($id);
		show_404_if_empty($patient);
		$wants_json = $this->wants_json_response();

		$amount = round((float) $this->input->post('amount'), 2);
		$note = $this->null_if_empty($this->input->post('note', TRUE));
		$payment_method = $this->input->post('payment_method', TRUE) ?: 'cash';
		$allowed_methods = array('cash', 'card', 'transfer');

		if ($amount <= 0) {
			return $this->respond_wallet_topup_error($id, t('Invalid debt payment amount.'), 422, $wants_json);
		}

		if (!in_array($payment_method, $allowed_methods, TRUE)) {
			return $this->respond_wallet_topup_error($id, t('Invalid payment method selected.'), 422, $wants_json);
		}

		$total_open_debt = $this->Debt_model->get_total_open_debt($id);

		if ($total_open_debt <= 0) {
			return $this->respond_wallet_topup_error($id, t('No open debt available to clear.'), 422, $wants_json);
		}

		$this->db->trans_begin();
		$remaining_amount = $this->Debt_model->clear_debts($id, $amount, NULL);
		$applied_amount = round($amount - $remaining_amount, 2);

		if ($applied_amount <= 0) {
			$this->db->trans_rollback();
			return $this->respond_wallet_topup_error($id, t('No open debt available to clear.'), 422, $wants_json);
		}

		$payment_note = trim(t('Debt payment from patient profile') . ($note ? ' - ' . $note : ''));
		$this->Payment_model->create(array(
			'patient_id' => (int) $id,
			'payment_date' => date('Y-m-d'),
			'amount' => $applied_amount,
			'payment_method' => $payment_method,
			'reference_number' => NULL,
			'notes' => $payment_note,
		));

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$db_error = $this->db->error();
			$message = t('Unable to record debt payment right now.');
			if (ENVIRONMENT !== 'production' && !empty($db_error['message'])) {
				$message .= ' ' . $db_error['message'];
			}
			return $this->respond_wallet_topup_error($id, $message, 500, $wants_json);
		}

		$this->db->trans_commit();

		if (!$wants_json) {
			$this->session->set_flashdata('success', t('Debt payment recorded successfully.'));
			redirect('patients/' . $id);
		}

		$financial_payload = $this->financial_profile_payload($id);

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array_merge($financial_payload, array(
				'success' => TRUE,
				'message' => t('Debt payment recorded successfully.'),
				'applied_amount' => (float) $applied_amount,
				'ignored_amount' => (float) $remaining_amount,
			))));
	}

	public function edit($id)
	{
		$this->require_permission('manage_patients');
		$patient = $this->Patient_model->get_by_id($id);
		show_404_if_empty($patient);

		$selected_diagnosis_ids = array_map('intval', array_column($this->Patient_model->get_diagnoses_for_patient($id), 'id'));
		$this->form($patient, 'patients/' . $id . '/update', $selected_diagnosis_ids);
	}

	public function update($id)
	{
		$this->require_permission('manage_patients');
		$patient = $this->Patient_model->get_by_id($id);
		show_404_if_empty($patient);
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form($patient, 'patients/' . $id . '/update', $this->diagnosis_ids_from_post());
		}

		$this->Patient_model->update($id, $this->patient_payload());
		$this->Patient_model->save_diagnoses($id, $this->diagnosis_ids_from_post());
		$this->session->set_flashdata('success', t('Patient updated successfully.'));
		redirect('patients/' . $id);
	}

	public function delete($id)
	{
		$this->require_permission('manage_patients');
		$patient = $this->Patient_model->get_by_id($id);
		show_404_if_empty($patient);

		$this->Patient_model->delete($id);
		$this->session->set_flashdata('success', t('Patient deleted successfully.'));
		redirect('patients');
	}

	protected function form($patient, $action, $selected_diagnosis_ids)
	{
		$this->render('patients/form', array(
			'title' => $patient ? t('Edit Patient') : t('Create Patient'),
			'current_section' => 'patients',
			'patient' => $patient,
			'action' => $action,
			'diagnoses' => $this->Patient_model->get_all_diagnoses(),
			'reference_doctors' => $this->Patient_model->get_active_reference_doctors(),
			'selected_diagnosis_ids' => $selected_diagnosis_ids,
		));
	}

	protected function validate_form()
	{
		$this->form_validation->set_rules('first_name', 'First name', 'required|trim');
		$this->form_validation->set_rules('last_name', 'Last name', 'trim');
		$this->form_validation->set_rules('gender', 'Gender', 'required|in_list[Male,Female]');
		$this->form_validation->set_rules('age', 'Age', 'trim|integer|greater_than_equal_to[0]|less_than_equal_to[120]');
		$this->form_validation->set_rules('phone', 'Phone 1', 'trim');
		$this->form_validation->set_rules('phone2', 'Phone 2', 'trim');
	}

	protected function patient_payload()
	{
		$referred_by = $this->input->post('referred_by', TRUE);
		$age = $this->input->post('age', TRUE);

		return array(
			'first_name' => $this->input->post('first_name', TRUE),
			'last_name' => $this->null_if_empty($this->input->post('last_name', TRUE)),
			'father_name' => $this->null_if_empty($this->input->post('father_name', TRUE)),
			'gender' => $this->input->post('gender', TRUE),
			'age' => $age === '' || $age === NULL ? NULL : (int) $age,
			'phone' => $this->null_if_empty($this->input->post('phone', TRUE)),
			'phone2' => $this->null_if_empty($this->input->post('phone2', TRUE)),
			'address' => $this->null_if_empty($this->input->post('address', TRUE)),
			'medical_notes' => $this->null_if_empty($this->input->post('medical_notes', TRUE)),
			'referred_by' => $referred_by === '' || $referred_by === NULL ? NULL : (int) $referred_by,
		);
	}

	protected function diagnosis_ids_from_post()
	{
		$diagnosis_ids = $this->input->post('diagnosis_ids');

		if (!is_array($diagnosis_ids)) {
			return array();
		}

		$diagnosis_ids = array_map('intval', $diagnosis_ids);
		$diagnosis_ids = array_filter($diagnosis_ids, static function ($id) {
			return $id > 0;
		});

		return array_values(array_unique($diagnosis_ids));
	}

	protected function null_if_empty($value)
	{
		$value = trim((string) $value);
		return $value === '' ? NULL : $value;
	}

	protected function valid_discount_percent($discount_percent)
	{
		$discount_percent = (float) $discount_percent;
		return $discount_percent >= 0.01 && $discount_percent <= 100;
	}

	protected function json_error($message, $status = 422)
	{
		return $this->output
			->set_status_header($status)
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => FALSE,
				'message' => $message,
			)));
	}

	protected function wants_json_response()
	{
		$accept = (string) $this->input->server('HTTP_ACCEPT');
		return stripos($accept, 'application/json') !== FALSE;
	}

	protected function respond_wallet_topup_error($patient_id, $message, $status, $wants_json)
	{
		if ($wants_json) {
			return $this->json_error($message, $status);
		}

		$this->session->set_flashdata('error', $message);
		redirect('patients/' . (int) $patient_id);
	}

	protected function normalized_wallet_transactions($patient_id)
	{
		return $this->normalize_wallet_transactions_rows($this->Wallet_model->get_transactions($patient_id));
	}

	protected function normalize_wallet_transactions_rows(array $transactions)
	{
		return array_map(static function ($transaction) {
			return array(
				'id' => (int) $transaction['id'],
				'patient_id' => (int) $transaction['patient_id'],
				'turn_id' => $transaction['turn_id'] === NULL ? NULL : (int) $transaction['turn_id'],
				'type' => (string) $transaction['type'],
				'amount' => (float) $transaction['amount'],
				'note' => $transaction['note'],
				'created_at' => (string) $transaction['created_at'],
			);
		}, $transactions);
	}

	protected function normalized_open_debts($patient_id)
	{
		return $this->normalize_open_debts_rows($this->Debt_model->get_open_debts($patient_id));
	}

	protected function normalize_open_debts_rows(array $debts)
	{
		return array_map(static function ($debt) {
			return array(
				'id' => (int) $debt['id'],
				'turn_id' => (int) $debt['turn_id'],
				'amount' => (float) $debt['amount'],
				'debt_date' => (string) $debt['debt_date'],
				'section_name' => !empty($debt['section_name']) ? t($debt['section_name']) : '',
			);
		}, $debts);
	}

	protected function normalize_payments_rows(array $payments)
	{
		return array_map(static function ($payment) {
			return array(
				'id' => (int) $payment['id'],
				'payment_date' => (string) $payment['payment_date'],
				'amount' => (float) $payment['amount'],
				'payment_method' => ucfirst((string) ($payment['payment_method'] ?? 'cash')),
				'reference_number' => (string) ($payment['reference_number'] ?? ''),
				'notes' => (string) ($payment['notes'] ?? ''),
			);
		}, $payments);
	}

	protected function discount_rows_payload($patient_id)
	{
		$discounts = $this->Discount_model->get_all_for_patient($patient_id);
		$active_ids = array();

		foreach ($discounts as $discount) {
			$section_id = (int) $discount['section_id'];
			$discount_id = (int) $discount['id'];

			if (!isset($active_ids[$section_id]) || $discount_id > $active_ids[$section_id]) {
				$active_ids[$section_id] = $discount_id;
			}
		}

		return array_map(static function ($discount) use ($active_ids) {
			$section_id = (int) $discount['section_id'];
			$discount_id = (int) $discount['id'];

			return array(
				'id' => $discount_id,
				'patient_id' => (int) $discount['patient_id'],
				'section_id' => $section_id,
				'section_name' => (string) ($discount['section_name'] ?? ''),
				'section_label' => !empty($discount['section_name']) ? t($discount['section_name']) : '',
				'discount_percent' => (float) $discount['discount_percent'],
				'note' => (string) ($discount['note'] ?? ''),
				'created_at' => substr((string) $discount['created_at'], 0, 16),
				'is_active' => isset($active_ids[$section_id]) && $active_ids[$section_id] === $discount_id,
			);
		}, $discounts);
	}

	protected function financial_profile_payload($patient_id)
	{
		$wallet_transactions = $this->Wallet_model->get_transactions($patient_id);
		$turns = $this->Turn_model->get_turns_for_patient($patient_id);
		$payments = $this->Patient_model->payment_history($patient_id);
		$wallet_balance = (float) $this->Wallet_model->get_balance($patient_id);
		$open_debts = $this->Debt_model->get_open_debts($patient_id);
		$total_open_debt = (float) $this->Debt_model->get_total_open_debt($patient_id);

		return array(
			'wallet_balance' => $wallet_balance,
			'wallet_transactions' => $this->normalize_wallet_transactions_rows($wallet_transactions),
			'open_debts' => $this->normalize_open_debts_rows($open_debts),
			'payments' => $this->normalize_payments_rows($payments),
			'total_open_debt' => $total_open_debt,
			'financial_summary' => $this->build_financial_summary($wallet_transactions, $turns, $payments, $wallet_balance, $total_open_debt),
			'financial_timeline' => $this->build_financial_timeline($wallet_transactions, $turns, $payments),
		);
	}

	protected function build_financial_summary(array $wallet_transactions, array $turns, array $payments, $wallet_balance, $total_open_debt)
	{
		$wallet_topups = 0.00;
		$wallet_deductions = 0.00;
		$turn_cash_total = 0.00;
		$turn_debt_total = 0.00;
		$payments_total = 0.00;

		foreach ($wallet_transactions as $transaction) {
			if (($transaction['type'] ?? '') === 'topup') {
				$wallet_topups += (float) $transaction['amount'];
				continue;
			}

			if (($transaction['type'] ?? '') === 'deduction') {
				$wallet_deductions += (float) $transaction['amount'];
			}
		}

		foreach ($turns as $turn) {
			$turn_cash_total += (float) ($turn['cash_collected'] ?? 0);
			$turn_debt_total += max(0, (float) ($turn['fee'] ?? 0) - (float) ($turn['wallet_deducted'] ?? 0) - (float) ($turn['cash_collected'] ?? 0));
		}

		foreach ($payments as $payment) {
			$payments_total += (float) ($payment['amount'] ?? 0);
		}

		return array(
			'wallet_balance' => (float) $wallet_balance,
			'total_open_debt' => (float) $total_open_debt,
			'wallet_topups' => $wallet_topups,
			'wallet_deductions' => $wallet_deductions,
			'direct_payments' => $payments_total,
			'turn_cash_total' => $turn_cash_total,
			'turn_debt_total' => $turn_debt_total,
		);
	}

	protected function build_financial_timeline(array $wallet_transactions, array $turns, array $payments)
	{
		$timeline = array();

		foreach ($wallet_transactions as $transaction) {
			$timeline[] = array(
				'occurred_at' => (string) $transaction['created_at'],
				'source' => 'wallet',
				'badge' => ($transaction['type'] ?? '') === 'topup' ? 'success' : 'warning',
				'label' => t($transaction['type'] ?? 'wallet'),
				'amount' => (float) ($transaction['amount'] ?? 0),
				'detail' => !empty($transaction['note']) ? $transaction['note'] : (!empty($transaction['turn_id']) ? '#' . (int) $transaction['turn_id'] : t('wallet_balance')),
			);
		}

		foreach ($turns as $turn) {
			$fee = (float) ($turn['fee'] ?? 0);
			$wallet_deducted = (float) ($turn['wallet_deducted'] ?? 0);
			$cash_collected = (float) ($turn['cash_collected'] ?? 0);
			$debt_created = max(0, $fee - $wallet_deducted - $cash_collected);

			if ($fee <= 0 && $wallet_deducted <= 0 && $cash_collected <= 0 && $debt_created <= 0) {
				continue;
			}

			$details = array();
			if (!empty($turn['section_name'])) {
				$details[] = t($turn['section_name']);
			}
			$details[] = t('payment_type') . ': ' . t($turn['payment_type'] ?? 'cash');
			if ($cash_collected > 0) {
				$details[] = t('cash_collected') . ': ' . format_amount($cash_collected);
			}
			if ($wallet_deducted > 0) {
				$details[] = t('wallet_deducted') . ': ' . format_amount($wallet_deducted);
			}
			if ($debt_created > 0) {
				$details[] = t('amount_becoming_debt') . ': ' . format_amount($debt_created);
			}

			$timeline[] = array(
				'occurred_at' => trim((string) $turn['turn_date'] . ' ' . ((string) ($turn['turn_time'] ?? '') === '00:00:00' ? '00:00' : substr((string) ($turn['turn_time'] ?? ''), 0, 5))),
				'source' => 'turn',
				'badge' => 'secondary',
				'label' => t('turn_financial_entry'),
				'amount' => $fee,
				'detail' => implode(' | ', $details),
			);
		}

		foreach ($payments as $payment) {
			$payment_method = ucfirst((string) ($payment['payment_method'] ?? 'cash'));
			$detail_parts = array(t('Payment Method') . ': ' . t($payment_method));

			if (!empty($payment['reference_number'])) {
				$detail_parts[] = t('Reference Number') . ': ' . $payment['reference_number'];
			}

			if (!empty($payment['notes'])) {
				$detail_parts[] = $payment['notes'];
			}

			$timeline[] = array(
				'occurred_at' => (string) $payment['payment_date'] . ' 00:00',
				'source' => 'payment',
				'badge' => 'primary',
				'label' => t('direct_payment_entry'),
				'amount' => (float) ($payment['amount'] ?? 0),
				'detail' => implode(' | ', $detail_parts),
			);
		}

		usort($timeline, static function ($left, $right) {
			return strcmp((string) $right['occurred_at'], (string) $left['occurred_at']);
		});

		return $timeline;
	}
}
