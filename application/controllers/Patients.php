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
		$this->load->model('Safe_model');
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
		$wants_json = $this->wants_json_response();
		$this->validate_form();

		if (!$this->form_validation->run()) {
			if ($wants_json) {
				return $this->json_validation_error($this->patient_validation_errors());
			}

			return $this->form(NULL, 'patients/store', $this->diagnosis_ids_from_post());
		}

		$payload = $this->patient_payload();
		$duplicate_patient = $this->Patient_model->find_duplicate_identity($payload);

		if ($duplicate_patient) {
			if ($wants_json) {
				return $this->output
					->set_status_header(409)
					->set_content_type('application/json')
					->set_output(json_encode(array(
						'success' => FALSE,
						'message' => t('Duplicate patient found.'),
						'duplicate_patient' => array(
							'id' => (int) $duplicate_patient['id'],
							'profile_url' => base_url('patients/' . (int) $duplicate_patient['id']),
						),
					)));
			}

			return $this->form(NULL, 'patients/store', $this->diagnosis_ids_from_post(), $duplicate_patient);
		}

		$new_id = $this->Patient_model->create($payload);
		$this->Patient_model->save_diagnoses($new_id, $this->diagnosis_ids_from_post());

		if ($wants_json) {
			$patient = $this->Patient_model->get_by_id($new_id);

			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode(array(
					'success' => TRUE,
					'message' => t('Patient created successfully.'),
					'patient' => $this->patient_option_payload($patient),
				)));
		}

		$this->session->set_flashdata('success', t('Patient created successfully.'));
		redirect('patients');
	}

	public function show($id)
	{
		$this->require_permission('manage_patients');
		$patient = $this->Patient_model->get_by_id($id);
		$patient_diagnoses = $this->Patient_model->get_diagnoses_for_patient($id);
		$turns = $this->Turn_model->get_turns_for_patient($id);
		$wallet_balance = $this->Wallet_model->get_balance($id);
		$wallet_breakdown = $this->Wallet_model->get_balance_breakdown($id);
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
			'wallet_balance' => $wallet_balance,
			'wallet_breakdown' => $wallet_breakdown,
			'wallet_transactions' => $wallet_transactions,
			'open_debts' => $open_debts,
			'total_open_debt' => $total_open_debt,
			'financial_summary' => $this->build_financial_summary($wallet_transactions, $turns, $wallet_balance, $total_open_debt, $wallet_breakdown),
			'financial_timeline' => $this->build_financial_timeline($wallet_transactions, $turns),
		));
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

		$new_balance = $this->Wallet_model->top_up_cash($id, $amount, NULL, $note);

		if ($new_balance === FALSE) {
			$db_error = $this->db->error();
			$message = t('Unable to update wallet right now.');
			if (ENVIRONMENT !== 'production' && !empty($db_error['message'])) {
				$message .= ' ' . $db_error['message'];
			}
			return $this->respond_wallet_topup_error($id, $message, 500, $wants_json);
		}

		$latest_wallet_transaction = $this->Wallet_model->get_transactions($id, 1);
		$wallet_reference = !empty($latest_wallet_transaction[0]['id']) ? (int) $latest_wallet_transaction[0]['id'] : NULL;

		$this->Safe_model->log_transaction(
			'in',
			'wallet_topup',
			$amount,
			$wallet_reference ?: (int) $id,
			$wallet_reference ? 'patient_wallet_transactions' : 'patients',
			$note ?: safe_patient_wallet_topup_note($id),
			$this->session->userdata('user_id')
		);

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

	public function wallet_historical_credit($id)
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

		$new_balance = $this->Wallet_model->top_up_historical($id, $amount, NULL, $note);

		if ($new_balance === FALSE) {
			$db_error = $this->db->error();
			$message = t('Unable to update wallet right now.');
			if (ENVIRONMENT !== 'production' && !empty($db_error['message'])) {
				$message .= ' ' . $db_error['message'];
			}
			return $this->respond_wallet_topup_error($id, $message, 500, $wants_json);
		}

		if (!$wants_json) {
			$this->session->set_flashdata('success', t('Historical wallet credit recorded successfully.'));
			redirect('patients/' . $id);
		}

		$financial_payload = $this->financial_profile_payload($id);

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array_merge($financial_payload, array(
				'success' => TRUE,
				'message' => t('Historical wallet credit recorded successfully.'),
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

		$deduction = $this->Wallet_model->deduct_prioritized($id, $amount, NULL, $note);

		if ($deduction === FALSE) {
			$db_error = $this->db->error();
			$message = t('Unable to update wallet right now.');
			if (ENVIRONMENT !== 'production' && !empty($db_error['message'])) {
				$message .= ' ' . $db_error['message'];
			}
			return $this->respond_wallet_topup_error($id, $message, 500, $wants_json);
		}

		$actual_deducted = round((float) ($deduction['deducted_amount'] ?? 0), 2);

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

		if (!$this->record_debt_payment($id, $applied_amount, $payment_method, $payment_note)) {
			$this->db->trans_rollback();
			$db_error = $this->db->error();
			$message = t('Unable to record debt payment right now.');
			if (ENVIRONMENT !== 'production' && !empty($db_error['message'])) {
				$message .= ' ' . $db_error['message'];
			}
			return $this->respond_wallet_topup_error($id, $message, 500, $wants_json);
		}

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

		$payload = $this->patient_payload();
		$duplicate_patient = $this->Patient_model->find_duplicate_identity($payload, $id);

		if ($duplicate_patient) {
			return $this->form($patient, 'patients/' . $id . '/update', $this->diagnosis_ids_from_post(), $duplicate_patient);
		}

		$this->Patient_model->update($id, $payload);
		$this->Patient_model->save_diagnoses($id, $this->diagnosis_ids_from_post());
		$this->session->set_flashdata('success', t('Patient updated successfully.'));
		redirect('patients/' . $id);
	}

	public function delete($id)
	{
		$this->require_permission('manage_patients');
		$patient = $this->Patient_model->get_by_id($id);
		show_404_if_empty($patient);

		if (!$this->Patient_model->delete($id)) {
			$this->session->set_flashdata('error', t('Unable to delete record right now.'));
			return redirect('patients');
		}

		$this->session->set_flashdata('success', t('Patient deleted successfully.'));
		redirect('patients');
	}

	protected function form($patient, $action, $selected_diagnosis_ids, $duplicate_patient = NULL)
	{
		$this->render('patients/form', array(
			'title' => $patient ? t('Edit Patient') : t('Create Patient'),
			'current_section' => 'patients',
			'patient' => $patient,
			'action' => $action,
			'diagnoses' => $this->Patient_model->get_all_diagnoses(),
			'reference_doctors' => $this->Patient_model->get_active_reference_doctors(),
			'selected_diagnosis_ids' => $selected_diagnosis_ids,
			'duplicate_patient' => $duplicate_patient,
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

	protected function patient_option_payload($patient)
	{
		$patient = is_array($patient) ? $patient : array();
		$first_name = trim((string) ($patient['first_name'] ?? ''));
		$last_name = trim((string) ($patient['last_name'] ?? ''));
		$father_name = trim((string) ($patient['father_name'] ?? ''));
		$name = $first_name;

		if ($last_name !== '') {
			$name = trim($first_name . ' ' . $last_name);
		} elseif ($father_name !== '') {
			$name = trim($first_name . ' ' . $father_name);
		}

		return array(
			'id' => (int) ($patient['id'] ?? 0),
			'name' => $name,
			'first_name' => $first_name,
			'last_name' => $last_name,
			'father_name' => $father_name,
			'phone' => (string) ($patient['phone'] ?? ''),
		);
	}

	protected function patient_validation_errors()
	{
		$fields = array('first_name', 'last_name', 'gender', 'age', 'phone', 'phone2');
		$errors = array();

		foreach ($fields as $field) {
			$error = trim(strip_tags(form_error($field)));

			if ($error === '') {
				continue;
			}

			$errors[$field] = $error;
		}

		return $errors;
	}

	protected function json_validation_error(array $field_errors, $status = 422)
	{
		$message = '';

		foreach ($field_errors as $field_error) {
			$message = trim((string) $field_error);

			if ($message !== '') {
				break;
			}
		}

		if ($message === '') {
			$message = t('Unable to save record right now.');
		}

		return $this->output
			->set_status_header($status)
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => FALSE,
				'message' => $message,
				'field_errors' => $field_errors,
			)));
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
				'fund_type' => (string) ($transaction['fund_type'] ?? 'cash_topup'),
				'amount' => (float) $transaction['amount'],
				'note' => $transaction['note'],
				'created_at' => to_shamsi((string) $transaction['created_at'], 'Y/m/d H:i'),
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
				'debt_date' => to_shamsi((string) $debt['debt_date']),
				'section_name' => !empty($debt['section_name']) ? t($debt['section_name']) : '',
			);
		}, $debts);
	}

	protected function financial_profile_payload($patient_id)
	{
		$wallet_transactions = $this->Wallet_model->get_transactions($patient_id);
		$turns = $this->Turn_model->get_turns_for_patient($patient_id);
		$wallet_balance = (float) $this->Wallet_model->get_balance($patient_id);
		$wallet_breakdown = $this->Wallet_model->get_balance_breakdown($patient_id);
		$open_debts = $this->Debt_model->get_open_debts($patient_id);
		$total_open_debt = (float) $this->Debt_model->get_total_open_debt($patient_id);

		return array(
			'wallet_balance' => $wallet_balance,
			'wallet_breakdown' => $wallet_breakdown,
			'wallet_transactions' => $this->normalize_wallet_transactions_rows($wallet_transactions),
			'open_debts' => $this->normalize_open_debts_rows($open_debts),
			'total_open_debt' => $total_open_debt,
			'financial_summary' => $this->build_financial_summary($wallet_transactions, $turns, $wallet_balance, $total_open_debt, $wallet_breakdown),
			'financial_timeline' => $this->build_financial_timeline($wallet_transactions, $turns),
		);
	}

	protected function build_financial_summary(array $wallet_transactions, array $turns, $wallet_balance, $total_open_debt, array $wallet_breakdown = array())
	{
		$wallet_topups = 0.00;
		$cash_wallet_topups = 0.00;
		$historical_wallet_credits = 0.00;
		$wallet_deductions = 0.00;
		$turn_cash_total = 0.00;
		$turn_debt_total = 0.00;

		foreach ($wallet_transactions as $transaction) {
			if (($transaction['type'] ?? '') === 'topup') {
				$wallet_topups += (float) $transaction['amount'];
				if (($transaction['fund_type'] ?? 'cash_topup') === 'historical_credit') {
					$historical_wallet_credits += (float) $transaction['amount'];
				} else {
					$cash_wallet_topups += (float) $transaction['amount'];
				}
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

		return array(
			'wallet_balance' => (float) $wallet_balance,
			'cash_wallet_balance' => (float) ($wallet_breakdown['cash_topup'] ?? 0),
			'historical_wallet_balance' => (float) ($wallet_breakdown['historical_credit'] ?? 0),
			'total_open_debt' => (float) $total_open_debt,
			'wallet_topups' => $wallet_topups,
			'cash_wallet_topups' => $cash_wallet_topups,
			'historical_wallet_credits' => $historical_wallet_credits,
			'wallet_deductions' => $wallet_deductions,
			'turn_cash_total' => $turn_cash_total,
			'turn_debt_total' => $turn_debt_total,
		);
	}

	protected function build_financial_timeline(array $wallet_transactions, array $turns)
	{
		$timeline = array();

		foreach ($wallet_transactions as $transaction) {
			$fund_type = (string) ($transaction['fund_type'] ?? 'cash_topup');
			$is_topup = ($transaction['type'] ?? '') === 'topup';
			$label_key = $is_topup
				? ($fund_type === 'historical_credit' ? 'historical_wallet_credit' : 'cash_wallet_topup')
				: ($fund_type === 'historical_credit' ? 'historical_wallet_deduction' : 'cash_wallet_deduction');

			$timeline[] = array(
				'occurred_at' => to_shamsi((string) $transaction['created_at'], 'Y/m/d H:i'),
				'source' => 'wallet',
				'badge' => ($transaction['type'] ?? '') === 'topup' ? 'success' : 'warning',
				'label' => t($label_key),
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
				'occurred_at' => to_shamsi(
					trim((string) $turn['turn_date'] . ' ' . ((string) ($turn['turn_time'] ?? '') === '00:00:00' ? '00:00:00' : substr((string) ($turn['turn_time'] ?? ''), 0, 5) . ':00')),
					'Y/m/d H:i'
				),
				'source' => 'turn',
				'badge' => 'secondary',
				'label' => t('turn_financial_entry'),
				'amount' => $fee,
				'detail' => implode(' | ', $details),
			);
		}

		usort($timeline, static function ($left, $right) {
			return strcmp((string) $right['occurred_at'], (string) $left['occurred_at']);
		});

		return $timeline;
	}

	protected function record_debt_payment($patient_id, $amount, $payment_method, $note)
	{
		$payment_date = date('Y-m-d');
		$payment_data = array(
			'patient_id' => (int) $patient_id,
			'payment_date' => $payment_date,
			'amount' => round((float) $amount, 2),
			'payment_method' => (string) $payment_method,
			'reference_number' => NULL,
			'notes' => $note,
		);

		$this->db->insert('payments', $payment_data);
		$payment_id = (int) $this->db->insert_id();

		if ($payment_id <= 0) {
			return FALSE;
		}

		$safe_note = trim((string) $note);
		if ($safe_note === '') {
			$safe_note = safe_patient_payment_note($payment_id);
		}

		return $this->Safe_model->log_transaction(
			'in',
			'patient_payment',
			$payment_data['amount'],
			$payment_id,
			'payments',
			$safe_note,
			$this->session->userdata('user_id'),
			$this->payment_datetime_from_date($payment_date)
		) !== FALSE;
	}

	protected function payment_datetime_from_date($date)
	{
		$date = trim((string) $date);
		$parsed = DateTime::createFromFormat('Y-m-d', $date);

		if ($parsed && $parsed->format('Y-m-d') === $date) {
			return $date . ' 12:00:00';
		}

		return NULL;
	}
}
