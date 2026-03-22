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
		show_404_if_empty($patient);

		$this->render('patients/show', array(
			'title' => t('Patient Profile'),
			'current_section' => 'patients',
			'patient' => $patient,
			'patient_diagnoses' => $patient_diagnoses,
			'turns' => $this->Turn_model->get_turns_for_patient($id),
			'payments' => $this->Patient_model->payment_history($id),
			'wallet_balance' => $this->Wallet_model->get_balance($id),
			'wallet_transactions' => $this->Wallet_model->get_transactions($id),
			'open_debts' => $this->Debt_model->get_open_debts($id),
			'total_open_debt' => $this->Debt_model->get_total_open_debt($id),
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

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => TRUE,
				'message' => t('Wallet updated successfully.'),
				'wallet_balance' => (float) $new_balance,
				'wallet_transactions' => $this->normalized_wallet_transactions($id),
			)));
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

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => TRUE,
				'message' => t('Wallet deducted successfully.'),
				'wallet_balance' => (float) $this->Wallet_model->get_balance($id),
				'wallet_transactions' => $this->normalized_wallet_transactions($id),
				'actual_deducted' => (float) $actual_deducted,
			)));
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

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => TRUE,
				'message' => t('Debt payment recorded successfully.'),
				'applied_amount' => (float) $applied_amount,
				'ignored_amount' => (float) $remaining_amount,
				'open_debts' => $this->normalized_open_debts($id),
				'total_open_debt' => (float) $this->Debt_model->get_total_open_debt($id),
			)));
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
		$this->form_validation->set_rules('last_name', 'Last name', 'required|trim');
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
			'last_name' => $this->input->post('last_name', TRUE),
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
		}, $this->Wallet_model->get_transactions($patient_id));
	}

	protected function normalized_open_debts($patient_id)
	{
		return array_map(static function ($debt) {
			return array(
				'id' => (int) $debt['id'],
				'turn_id' => (int) $debt['turn_id'],
				'amount' => (float) $debt['amount'],
				'debt_date' => (string) $debt['debt_date'],
				'section_name' => !empty($debt['section_name']) ? t($debt['section_name']) : '',
			);
		}, $this->Debt_model->get_open_debts($patient_id));
	}
}
