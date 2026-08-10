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
		$this->load->model('Discount_model');
		$this->load->model('Section_model');
		$this->load->model('Staff_model');
	}

	public function index()
	{
		$this->require_permission('manage_patients');

		// Rows are loaded on demand via the server-side DataTables endpoint
		// (patients/datatable) so the page no longer ships every patient up front.
		$this->render('patients/index', array(
			'title' => t('Patients'),
			'current_section' => 'patients',
			'datatable_url' => base_url('patients/datatable'),
		));
	}

	public function datatable()
	{
		$this->require_permission('manage_patients');

		$draw = (int) $this->input->get('draw');
		$start = (int) $this->input->get('start');
		$length = (int) $this->input->get('length');

		if ($length <= 0) {
			$length = 25;
		}

		$search = $this->input->get('search');
		$search_value = is_array($search) ? trim((string) ($search['value'] ?? '')) : '';

		$order = $this->input->get('order');
		$order_col = 0;
		$order_dir = 'asc';

		if (is_array($order) && isset($order[0]) && is_array($order[0])) {
			$order_col = (int) ($order[0]['column'] ?? 0);
			$order_dir = (string) ($order[0]['dir'] ?? 'asc');
		}

		$result = $this->Patient_model->get_datatable(array(
			'start' => $start,
			'length' => $length,
			'search' => $search_value,
			'order_col' => $order_col,
			'order_dir' => $order_dir,
		));

		$rows = array();
		foreach ($result['data'] as $patient) {
			$rows[] = $this->patient_row_for_datatable($patient);
		}

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'draw' => $draw,
				'recordsTotal' => (int) $result['records_total'],
				'recordsFiltered' => (int) $result['records_filtered'],
				'data' => $rows,
			)));
	}

	protected function patient_row_for_datatable($patient)
	{
		$id = (int) $patient['id'];
		$full_name = trim((string) $patient['first_name'] . ' ' . (string) ($patient['last_name'] ?? ''));
		$father_name = $patient['father_name'] ?? NULL;
		$gender = $patient['gender'] ?? NULL;
		$age = $patient['age'] ?? NULL;
		$phone = $patient['phone'] ?? NULL;

		$actions = '<div class="d-flex gap-2 justify-content-end flex-wrap">'
			. '<a href="' . base_url('patients/' . $id) . '" class="btn btn-sm btn-outline-dark btn-icon"><i class="bi bi-person-vcard" aria-hidden="true"></i> ' . html_escape(t('Profile')) . '</a>'
			. '<a href="' . base_url('patients/' . $id . '/edit') . '" class="btn btn-sm btn-outline-secondary btn-icon"><i class="bi bi-pencil" aria-hidden="true"></i> ' . html_escape(t('Edit')) . '</a>'
			. '<a href="' . base_url('patients/' . $id . '/delete') . '" class="btn btn-sm btn-outline-danger btn-icon" onclick="return confirm(\'' . html_escape(t('Delete this patient?')) . '\')"><i class="bi bi-trash" aria-hidden="true"></i> ' . html_escape(t('Delete')) . '</a>'
			. '</div>';

		return array(
			html_escape($full_name),
			$father_name ? html_escape($father_name) : '&mdash;',
			$gender ? html_escape($gender) : '&mdash;',
			$age !== NULL ? format_number($age) : '&mdash;',
			$phone ? html_escape($phone) : '&mdash;',
			'<span class="badge text-bg-success">' . html_escape(t('Active')) . '</span>',
			$actions,
		);
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

		if ($this->input->post('submit_action', TRUE) === 'save_and_open') {
			redirect('patients/' . (int) $new_id);
		}

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
		// Full history, not the default recent-20 page — financial_summary/timeline
		// below need every transaction to total correctly.
		$wallet_transactions = $this->Wallet_model->get_transactions($id, 0);
		$open_debts = $this->Debt_model->get_all_debts_for_patient($id);
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
			'discounts' => $this->normalized_discounts($id),
			'all_sections' => $this->Section_model->get_all(),
			'all_staff' => $this->Staff_model->get_active(),
			'standalone_payments' => $this->normalized_standalone_payments($id),
			'financial_summary' => $this->build_financial_summary($wallet_transactions, $turns, $wallet_balance, $total_open_debt, $wallet_breakdown),
			'financial_timeline' => $this->build_financial_timeline($wallet_transactions, $turns),
		));
	}

	public function add_discount($id)
	{
		$this->require_permission('manage_patients');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$patient = $this->Patient_model->get_by_id($id);
		show_404_if_empty($patient);
		$wants_json = $this->wants_json_response();

		$section_id = (int) $this->input->post('section_id');
		$discount_percent = max(round((float) $this->input->post('discount_percent'), 2), 0);
		$discount_amount = max(round((float) $this->input->post('discount_amount'), 2), 0);
		$note = $this->null_if_empty($this->input->post('note', TRUE));

		if ($section_id <= 0 || !$this->Section_model->get_by_id($section_id)) {
			return $this->respond_discount_error($id, t('section') . ' ' . t('is required.'), 422, $wants_json);
		}

		if (($discount_percent < 0.01 || $discount_percent > 100) && $discount_amount <= 0) {
			return $this->respond_discount_error($id, t('discount_invalid'), 422, $wants_json);
		}

		$created_id = $this->Discount_model->create(
			$id,
			$section_id,
			$discount_percent,
			$discount_amount,
			$note,
			$this->session->userdata('user_id')
		);

		if (!$created_id) {
			$db_error = $this->db->error();
			$message = t('unable_to_save_discount');
			if (ENVIRONMENT !== 'production' && !empty($db_error['message'])) {
				$message .= ' ' . $db_error['message'];
			}
			return $this->respond_discount_error($id, $message, 500, $wants_json);
		}

		if (!$wants_json) {
			$this->session->set_flashdata('success', t('discount_saved'));
			redirect('patients/' . (int) $id);
		}

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => TRUE,
				'message' => t('discount_saved'),
				'discounts' => $this->normalized_discounts($id),
			)));
	}

	public function delete_discount($patient_id, $discount_id)
	{
		$this->require_permission('manage_patients');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$patient = $this->Patient_model->get_by_id($patient_id);
		show_404_if_empty($patient);
		$wants_json = $this->wants_json_response();

		if (!$this->Discount_model->delete($discount_id, $patient_id)) {
			return $this->respond_discount_error($patient_id, t('discount_not_found'), 404, $wants_json);
		}

		if (!$wants_json) {
			$this->session->set_flashdata('success', t('discount_deleted'));
			redirect('patients/' . (int) $patient_id);
		}

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => TRUE,
				'message' => t('discount_deleted'),
				'discounts' => $this->normalized_discounts($patient_id),
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

		$this->db->trans_begin();
		$this->Wallet_model->ensure_wallet_exists($id);
		$this->db->query('SELECT id FROM patient_wallet WHERE patient_id = ? FOR UPDATE', array((int) $id));

		$new_balance = $this->Wallet_model->top_up_cash($id, $amount, NULL, $note);

		if ($new_balance === FALSE) {
			$this->db->trans_rollback();
			$db_error = $this->db->error();
			$message = t('Unable to update wallet right now.');
			if (ENVIRONMENT !== 'production' && !empty($db_error['message'])) {
				$message .= ' ' . $db_error['message'];
			}
			return $this->respond_wallet_topup_error($id, $message, 500, $wants_json);
		}

		$latest_wallet_transaction = $this->Wallet_model->get_transactions($id, 1);
		$wallet_reference = !empty($latest_wallet_transaction[0]['id']) ? (int) $latest_wallet_transaction[0]['id'] : NULL;

		$safe_logged = $this->Safe_model->log_transaction(
			'in',
			'wallet_topup',
			$amount,
			$wallet_reference ?: (int) $id,
			$wallet_reference ? 'patient_wallet_transactions' : 'patients',
			$note ?: safe_patient_wallet_topup_note($id),
			$this->session->userdata('user_id')
		);

		if ($safe_logged === FALSE || $this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return $this->respond_wallet_topup_error($id, t('Unable to update wallet right now.'), 500, $wants_json);
		}

		$this->db->trans_commit();

		$this->Wallet_model->recalculate_for_patient($id);

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
				'wallet_balance' => (float) $financial_payload['wallet_balance'],
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

		$this->Wallet_model->recalculate_for_patient($id);

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
				'wallet_balance' => (float) $financial_payload['wallet_balance'],
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

		$this->Wallet_model->recalculate_for_patient($id);

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
		$this->require_permission('manage_turns');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$patient = $this->Patient_model->get_by_id($id);
		show_404_if_empty($patient);
		$wants_json = $this->wants_json_response();

		$amount = round((float) $this->input->post('amount'), 2);
		$note = $this->null_if_empty($this->input->post('note', TRUE));
		$payment_date_input = trim((string) $this->input->post('payment_date', TRUE));
		$payment_date = $payment_date_input === '' ? date('Y-m-d') : $this->gregorian_date_from_shamsi($payment_date_input);
		list($section_id, $staff_id, $dimension_error) = $this->payment_dimension_from_post();

		if ($amount <= 0) {
			return $this->respond_wallet_topup_error($id, t('Invalid debt payment amount.'), 422, $wants_json);
		}

		if ($payment_date_input !== '' && $payment_date === '') {
			return $this->respond_wallet_topup_error($id, t('Please choose a valid date.'), 422, $wants_json);
		}

		if ($dimension_error !== NULL) {
			return $this->respond_wallet_topup_error($id, $dimension_error, 422, $wants_json);
		}

		$payment_note = trim(t('Debt payment from patient profile') . ($note ? ' - ' . $note : ''));

		$this->db->trans_begin();

		// Lock the patient's wallet row first so concurrent debt-payment submits for
		// the same patient serialize. THEN re-read total_open_debt under the lock so
		// we don't apply against a stale snapshot.
		$this->Wallet_model->ensure_wallet_exists($id);
		$this->db->query('SELECT id FROM patient_wallet WHERE patient_id = ? FOR UPDATE', array((int) $id));

		// A patient can pay money even with no open debt: prepayment (no treatment
		// yet). With an open debt, record_standalone_debt_payment applies the amount
		// oldest-first and routes any remainder to the wallet.
		$total_open_debt = (float) $this->Debt_model->get_total_open_debt($id);

		if ($total_open_debt <= 0) {
			// No open debt — record the money as wallet credit (prepayment). It still
			// appears in the daily report as income, and unlike a standalone payment
			// row it does not desync the safe ledger's payment reconciliation (which
			// expects every payments row to have a patient_(debt_)payment safe entry).
			$payment_datetime = $this->payment_datetime_from_date($payment_date);
			$dimension_options = array('section_id' => $section_id, 'staff_id' => $staff_id);
			$topped = $this->Wallet_model->top_up_cash($id, $amount, NULL, $payment_note, $payment_datetime, $dimension_options);

			if ($topped === FALSE) {
				$this->db->trans_rollback();
				return $this->respond_wallet_topup_error($id, t('Unable to record debt payment right now.'), 500, $wants_json);
			}

			$latest_wallet_tx = $this->Wallet_model->get_transactions($id, 1);
			$wallet_ref = !empty($latest_wallet_tx[0]['id']) ? (int) $latest_wallet_tx[0]['id'] : (int) $id;
			$wallet_ref_table = !empty($latest_wallet_tx[0]['id']) ? 'patient_wallet_transactions' : 'patients';

			$safe_logged = $this->Safe_model->log_transaction(
				'in',
				'wallet_topup',
				$amount,
				$wallet_ref,
				$wallet_ref_table,
				$payment_note,
				$this->session->userdata('user_id'),
				$payment_datetime,
				$dimension_options
			);

			if ($safe_logged === FALSE || $this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return $this->respond_wallet_topup_error($id, t('Unable to record debt payment right now.'), 500, $wants_json);
			}

			$this->db->trans_commit();
			$this->Wallet_model->recalculate_for_patient($id);

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
					'applied_amount' => 0.0,
					'overflow_amount' => (float) $amount,
				))));
		}

		$payment_id = $this->record_standalone_debt_payment($id, $amount, $payment_date, $payment_note, $section_id, $staff_id);

		if (!$payment_id) {
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

		$this->Wallet_model->recalculate_for_patient($id);

		if (!$wants_json) {
			$this->session->set_flashdata('success', t('Debt payment recorded successfully.'));
			redirect('patients/' . $id);
		}

		$financial_payload = $this->financial_profile_payload($id);
		$applied_amount = round(min($amount, $total_open_debt), 2);
		$overflow_amount = round(max(0, $amount - $total_open_debt), 2);

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array_merge($financial_payload, array(
				'success' => TRUE,
				'message' => t('Debt payment recorded successfully.'),
				'applied_amount' => (float) $applied_amount,
				'overflow_amount' => (float) $overflow_amount,
			))));
	}

	public function refund($id)
	{
		$this->require_permission('manage_turns');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$patient = $this->Patient_model->get_by_id($id);
		show_404_if_empty($patient);
		$wants_json = $this->wants_json_response();

		$amount = round((float) $this->input->post('amount'), 2);
		$note = $this->null_if_empty($this->input->post('note', TRUE));
		$refund_date_input = trim((string) $this->input->post('refund_date', TRUE));
		$refund_date = $refund_date_input === '' ? date('Y-m-d') : $this->gregorian_date_from_shamsi($refund_date_input);
		list($section_id, $staff_id, $dimension_error) = $this->payment_dimension_from_post();

		if ($amount <= 0) {
			return $this->respond_wallet_topup_error($id, t('Invalid refund amount.'), 422, $wants_json);
		}

		if ($refund_date_input !== '' && $refund_date === '') {
			return $this->respond_wallet_topup_error($id, t('Please choose a valid date.'), 422, $wants_json);
		}

		if ($dimension_error !== NULL) {
			return $this->respond_wallet_topup_error($id, $dimension_error, 422, $wants_json);
		}

		// Only the cash_topup bucket is refundable — historical_credit represents
		// non-cash credit (carryover from older bookkeeping) and cannot be returned
		// as cash. (Fix A2.)
		$wallet_breakdown = $this->Wallet_model->get_balance_breakdown($id);
		$refundable_balance = round((float) ($wallet_breakdown['cash_topup'] ?? 0), 2);

		if ($refundable_balance <= 0) {
			return $this->respond_wallet_topup_error($id, t('No wallet balance available to refund.'), 422, $wants_json);
		}

		if ($amount > $refundable_balance + 0.001) {
			return $this->respond_wallet_topup_error($id, t('Refund amount exceeds wallet balance.'), 422, $wants_json);
		}

		$this->db->trans_begin();

		// Serialize concurrent refunds for the same patient — without the lock,
		// two duplicate-click submits both read balance=X and both decrement
		// without seeing each other.
		$this->Wallet_model->ensure_wallet_exists($id);
		$this->db->query('SELECT id FROM patient_wallet WHERE patient_id = ? FOR UPDATE', array((int) $id));

		$refund_datetime = $refund_date . ' 12:00:00';
		$dimension_options = array('section_id' => $section_id, 'staff_id' => $staff_id);
		$refund_transaction_id = $this->Wallet_model->record_refund($id, $amount, $note, $refund_datetime, $dimension_options);

		if (!$refund_transaction_id) {
			$this->db->trans_rollback();
			$db_error = $this->db->error();
			$message = t('Unable to record refund right now.');
			if (ENVIRONMENT !== 'production' && !empty($db_error['message'])) {
				$message .= ' ' . $db_error['message'];
			}
			return $this->respond_wallet_topup_error($id, $message, 500, $wants_json);
		}

		$safe_note = $note ?: t('Refund issued from patient profile');
		$safe_logged = $this->Safe_model->log_transaction(
			'out',
			'patient_refund',
			$amount,
			$refund_transaction_id,
			'patient_wallet_transactions',
			$safe_note,
			$this->session->userdata('user_id'),
			$refund_datetime,
			$dimension_options
		);

		if ($safe_logged === FALSE) {
			$this->db->trans_rollback();
			$db_error = $this->db->error();
			$message = t('Unable to record refund right now.');
			if (ENVIRONMENT !== 'production' && !empty($db_error['message'])) {
				$message .= ' ' . $db_error['message'];
			}
			return $this->respond_wallet_topup_error($id, $message, 500, $wants_json);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$db_error = $this->db->error();
			$message = t('Unable to record refund right now.');
			if (ENVIRONMENT !== 'production' && !empty($db_error['message'])) {
				$message .= ' ' . $db_error['message'];
			}
			return $this->respond_wallet_topup_error($id, $message, 500, $wants_json);
		}

		$this->db->trans_commit();

		$this->Wallet_model->recalculate_for_patient($id);

		if (!$wants_json) {
			$this->session->set_flashdata('success', t('Refund recorded successfully.'));
			redirect('patients/' . $id);
		}

		$financial_payload = $this->financial_profile_payload($id);

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array_merge($financial_payload, array(
				'success' => TRUE,
				'message' => t('Refund recorded successfully.'),
				'refunded_amount' => (float) $amount,
			))));
	}

	public function edit_debt_payment($patient_id, $payment_id)
	{
		$this->require_permission('manage_turns');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$patient = $this->Patient_model->get_by_id($patient_id);
		show_404_if_empty($patient);
		$wants_json = $this->wants_json_response();

		$payment = $this->db
			->where('id', (int) $payment_id)
			->where('patient_id', (int) $patient_id)
			->get('payments')
			->row_array();

		if (!$payment) {
			return $this->respond_wallet_topup_error($patient_id, t('Payment not found.'), 404, $wants_json);
		}

		list($section_id, $staff_id, $dimension_error) = $this->payment_dimension_from_post();

		if ($dimension_error !== NULL) {
			return $this->respond_wallet_topup_error($patient_id, $dimension_error, 422, $wants_json);
		}

		$note = $this->null_if_empty($this->input->post('note', TRUE));

		$this->db->where('id', (int) $payment_id)->update('payments', array(
			'notes' => $note,
			'section_id' => $section_id,
			'staff_id' => $staff_id,
		));

		$this->sync_standalone_payment_dimension((int) $payment_id, $section_id, $staff_id);

		if (!$wants_json) {
			$this->session->set_flashdata('success', t('Payment updated successfully.'));
			redirect('patients/' . $patient_id);
		}

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array_merge($this->financial_profile_payload($patient_id), array(
				'success' => TRUE,
				'message' => t('Payment updated successfully.'),
				'standalone_payments' => $this->normalized_standalone_payments($patient_id),
			))));
	}

	public function delete_debt_payment($patient_id, $payment_id)
	{
		$this->require_permission('manage_turns');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$patient = $this->Patient_model->get_by_id($patient_id);
		show_404_if_empty($patient);
		$wants_json = $this->wants_json_response();

		$payment = $this->db
			->where('id', (int) $payment_id)
			->where('patient_id', (int) $patient_id)
			->get('payments')
			->row_array();

		if (!$payment) {
			return $this->respond_wallet_topup_error($patient_id, t('Payment not found.'), 404, $wants_json);
		}

		if (!$this->Debt_model->payment_is_reconcilable((int) $payment_id, (float) $payment['amount'])) {
			return $this->respond_wallet_topup_error($patient_id, t('This payment predates automatic debt-application tracking and cannot be safely auto-reversed. Adjust the affected debt manually before deleting this payment.'), 422, $wants_json);
		}

		$this->db->trans_begin();

		$this->Wallet_model->ensure_wallet_exists($patient_id);
		$this->db->query('SELECT id FROM patient_wallet WHERE patient_id = ? FOR UPDATE', array((int) $patient_id));

		// Undo exactly the debts this payment settled, restoring each to its pre-payment
		// amount/open status (Debt_model::reopen_debts_for_payment reads the application
		// ledger, so partial applications are restored precisely, not guessed at).
		$this->Debt_model->reopen_debts_for_payment((int) $payment_id);

		// Any overflow beyond the open debt was routed to the wallet as a top-up — reverse that too.
		$overflow = $this->db
			->where('payment_id', (int) $payment_id)
			->where('type', 'topup')
			->get('patient_wallet_transactions')
			->row_array();

		if ($overflow) {
			$this->Safe_model->delete_transaction_by_reference('patient_wallet_transactions', (int) $overflow['id'], 'wallet_topup');
			$this->db->where('id', (int) $overflow['id'])->delete('patient_wallet_transactions');
		}

		$this->Safe_model->delete_transaction_by_reference('payments', (int) $payment_id, 'patient_debt_payment');
		$this->db->where('id', (int) $payment_id)->delete('payments');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return $this->respond_wallet_topup_error($patient_id, t('Unable to delete payment right now.'), 500, $wants_json);
		}

		$this->db->trans_commit();
		$this->Wallet_model->recalculate_for_patient($patient_id);

		if (!$wants_json) {
			$this->session->set_flashdata('success', t('Payment deleted successfully.'));
			redirect('patients/' . $patient_id);
		}

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array_merge($this->financial_profile_payload($patient_id), array(
				'success' => TRUE,
				'message' => t('Payment deleted successfully.'),
				'standalone_payments' => $this->normalized_standalone_payments($patient_id),
			))));
	}

	public function edit_refund($patient_id, $wallet_transaction_id)
	{
		$this->require_permission('manage_turns');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$patient = $this->Patient_model->get_by_id($patient_id);
		show_404_if_empty($patient);
		$wants_json = $this->wants_json_response();

		$refund = $this->db
			->where('id', (int) $wallet_transaction_id)
			->where('patient_id', (int) $patient_id)
			->where('type', 'refund')
			->get('patient_wallet_transactions')
			->row_array();

		if (!$refund) {
			return $this->respond_wallet_topup_error($patient_id, t('Refund not found.'), 404, $wants_json);
		}

		list($section_id, $staff_id, $dimension_error) = $this->payment_dimension_from_post();

		if ($dimension_error !== NULL) {
			return $this->respond_wallet_topup_error($patient_id, $dimension_error, 422, $wants_json);
		}

		$note = $this->null_if_empty($this->input->post('note', TRUE));

		$this->db->where('id', (int) $wallet_transaction_id)->update('patient_wallet_transactions', array(
			'note' => $note,
			'section_id' => $section_id,
			'staff_id' => $staff_id,
		));

		$this->db
			->where('reference_table', 'patient_wallet_transactions')
			->where('reference_id', (int) $wallet_transaction_id)
			->where('source', 'patient_refund')
			->update('safe_transactions', array('section_id' => $section_id, 'staff_id' => $staff_id));

		if (!$wants_json) {
			$this->session->set_flashdata('success', t('Refund updated successfully.'));
			redirect('patients/' . $patient_id);
		}

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array_merge($this->financial_profile_payload($patient_id), array(
				'success' => TRUE,
				'message' => t('Refund updated successfully.'),
				'standalone_payments' => $this->normalized_standalone_payments($patient_id),
			))));
	}

	public function delete_refund($patient_id, $wallet_transaction_id)
	{
		$this->require_permission('manage_turns');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$patient = $this->Patient_model->get_by_id($patient_id);
		show_404_if_empty($patient);
		$wants_json = $this->wants_json_response();

		$refund = $this->db
			->where('id', (int) $wallet_transaction_id)
			->where('patient_id', (int) $patient_id)
			->where('type', 'refund')
			->get('patient_wallet_transactions')
			->row_array();

		if (!$refund) {
			return $this->respond_wallet_topup_error($patient_id, t('Refund not found.'), 404, $wants_json);
		}

		$this->db->trans_begin();

		$this->Wallet_model->ensure_wallet_exists($patient_id);
		$this->db->query('SELECT id FROM patient_wallet WHERE patient_id = ? FOR UPDATE', array((int) $patient_id));

		$this->Safe_model->delete_transaction_by_reference('patient_wallet_transactions', (int) $wallet_transaction_id, 'patient_refund');
		$this->db->where('id', (int) $wallet_transaction_id)->delete('patient_wallet_transactions');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return $this->respond_wallet_topup_error($patient_id, t('Unable to delete refund right now.'), 500, $wants_json);
		}

		$this->db->trans_commit();
		$this->Wallet_model->recalculate_for_patient($patient_id);

		if (!$wants_json) {
			$this->session->set_flashdata('success', t('Refund deleted successfully.'));
			redirect('patients/' . $patient_id);
		}

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array_merge($this->financial_profile_payload($patient_id), array(
				'success' => TRUE,
				'message' => t('Refund deleted successfully.'),
				'standalone_payments' => $this->normalized_standalone_payments($patient_id),
			))));
	}

	protected function sync_standalone_payment_dimension($payment_id, $section_id, $staff_id)
	{
		$this->db
			->where('reference_table', 'payments')
			->where('reference_id', (int) $payment_id)
			->where('source', 'patient_debt_payment')
			->update('safe_transactions', array('section_id' => $section_id, 'staff_id' => $staff_id));

		$overflow = $this->db
			->select('id')
			->where('payment_id', (int) $payment_id)
			->where('type', 'topup')
			->get('patient_wallet_transactions')
			->row_array();

		if (!$overflow) {
			return;
		}

		$overflow_id = (int) $overflow['id'];

		$this->db->where('id', $overflow_id)->update('patient_wallet_transactions', array(
			'section_id' => $section_id,
			'staff_id' => $staff_id,
		));

		$this->db
			->where('reference_table', 'patient_wallet_transactions')
			->where('reference_id', $overflow_id)
			->where('source', 'wallet_topup')
			->update('safe_transactions', array('section_id' => $section_id, 'staff_id' => $staff_id));
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
				'debt_type' => (string) ($debt['debt_type'] ?? 'auto_settleable'),
				'status' => (string) ($debt['status'] ?? 'open'),
			);
		}, $debts);
	}

	protected function financial_profile_payload($patient_id)
	{
		// Full history — see show() for why this can't be the default recent-20 page.
		$wallet_transactions = $this->Wallet_model->get_transactions($patient_id, 0);
		$turns = $this->Turn_model->get_turns_for_patient($patient_id);
		$wallet_balance = (float) $this->Wallet_model->get_balance($patient_id);
		$wallet_breakdown = $this->Wallet_model->get_balance_breakdown($patient_id);
		$open_debts = $this->Debt_model->get_all_debts_for_patient($patient_id);
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

	protected function normalized_discounts($patient_id)
	{
		$discounts = $this->Discount_model->get_all_for_patient($patient_id);
		$active_ids_by_section = array();

		foreach ($discounts as $discount) {
			$section_id = (int) ($discount['section_id'] ?? 0);
			if ($section_id > 0 && !isset($active_ids_by_section[$section_id])) {
				$active_ids_by_section[$section_id] = (int) $discount['id'];
			}
		}

		return array_map(static function ($discount) use ($active_ids_by_section) {
			$section_id = (int) ($discount['section_id'] ?? 0);
			$section_name = trim((string) ($discount['section_name'] ?? ''));

			return array(
				'id' => (int) ($discount['id'] ?? 0),
				'patient_id' => (int) ($discount['patient_id'] ?? 0),
				'section_id' => $section_id,
				'section_name' => $section_name,
				'section_label' => $section_name !== '' ? t($section_name) : '',
				'discount_percent' => round((float) ($discount['discount_percent'] ?? 0), 2),
				'discount_amount' => round((float) ($discount['discount_amount'] ?? 0), 2),
				'note' => $discount['note'] ?? NULL,
				'created_by' => isset($discount['created_by']) ? (int) $discount['created_by'] : NULL,
				'created_at' => (string) ($discount['created_at'] ?? ''),
				'is_active' => isset($active_ids_by_section[$section_id]) && $active_ids_by_section[$section_id] === (int) ($discount['id'] ?? 0),
			);
		}, $discounts);
	}

	protected function respond_discount_error($patient_id, $message, $status, $wants_json)
	{
		if (!$wants_json) {
			$this->session->set_flashdata('error', $message);
			redirect('patients/' . (int) $patient_id);
		}

		return $this->output
			->set_status_header((int) $status)
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => FALSE,
				'message' => $message,
				'discounts' => $this->normalized_discounts($patient_id),
			)));
	}

	protected function build_financial_summary(array $wallet_transactions, array $turns, $wallet_balance, $total_open_debt, array $wallet_breakdown = array())
	{
		$wallet_topups = 0.00;
		$cash_wallet_topups = 0.00;
		$historical_wallet_credits = 0.00;
		$wallet_deductions = 0.00;
		$turn_cash_total = 0.00;
		$paid_by_section = array();

		foreach ($wallet_transactions as $transaction) {
			$type = (string) ($transaction['type'] ?? '');

			if ($type === 'topup') {
				$wallet_topups += (float) $transaction['amount'];
				if (($transaction['fund_type'] ?? 'cash_topup') === 'historical_credit') {
					$historical_wallet_credits += (float) $transaction['amount'];
				} else {
					$cash_wallet_topups += (float) $transaction['amount'];
				}
				continue;
			}

			if (in_array($type, array('deduction', 'auto_debt_settlement', 'refund'), TRUE)) {
				$wallet_deductions += (float) $transaction['amount'];
			}
		}

		foreach ($turns as $turn) {
			$turn_cash_total += (float) ($turn['cash_collected'] ?? 0);

			$paid = round((float) ($turn['cash_collected'] ?? 0) + (float) ($turn['wallet_deducted'] ?? 0), 2);
			if ($paid <= 0) {
				continue;
			}

			$section_id = (int) ($turn['section_id'] ?? 0);
			if (!isset($paid_by_section[$section_id])) {
				$paid_by_section[$section_id] = array(
					'section_name' => !empty($turn['section_name']) ? $turn['section_name'] : NULL,
					'paid' => 0.00,
				);
			}
			$paid_by_section[$section_id]['paid'] = round($paid_by_section[$section_id]['paid'] + $paid, 2);
		}

		usort($paid_by_section, static function ($left, $right) {
			return $right['paid'] <=> $left['paid'];
		});

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
			'paid_by_section' => $paid_by_section,
		);
	}

	protected function build_financial_timeline(array $wallet_transactions, array $turns)
	{
		$timeline = array();

		foreach ($wallet_transactions as $transaction) {
			$type = (string) ($transaction['type'] ?? '');
			$fund_type = (string) ($transaction['fund_type'] ?? 'cash_topup');
			$is_topup = $type === 'topup';
			$note = (string) ($transaction['note'] ?? '');

			if (strpos($note, 'REVERSAL:') === 0) {
				// Internal bookkeeping (Wallet_model::reversal_note()), not money the
				// patient paid or was refunded — keep it visually distinct from a real
				// cash top-up so it can't be mistaken for a payment and double-counted.
				$label_key = 'wallet_correction';
				$badge = 'secondary';
			} elseif ($type === 'auto_debt_settlement') {
				$label_key = 'auto_debt_settlement';
				$badge = 'primary';
			} elseif ($type === 'refund') {
				$label_key = 'refund';
				$badge = 'danger';
			} else {
				$label_key = $is_topup
					? ($fund_type === 'historical_credit' ? 'historical_wallet_credit' : 'cash_wallet_topup')
					: ($fund_type === 'historical_credit' ? 'historical_wallet_deduction' : 'cash_wallet_deduction');
				$badge = $is_topup ? 'success' : 'warning';
			}

			$timeline[] = array(
				'occurred_at' => to_shamsi((string) $transaction['created_at'], 'Y/m/d H:i'),
				'source' => 'wallet',
				'badge' => $badge,
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

	protected function record_standalone_debt_payment($patient_id, $amount, $payment_date, $note, $section_id = NULL, $staff_id = NULL)
	{
		$amount = round((float) $amount, 2);

		$payment_data = array(
			'patient_id' => (int) $patient_id,
			'section_id' => $section_id ? (int) $section_id : NULL,
			'staff_id' => $staff_id ? (int) $staff_id : NULL,
			'payment_date' => (string) $payment_date,
			'amount' => $amount,
			'payment_method' => 'cash',
			'reference_number' => NULL,
			'notes' => $note,
		);

		$this->db->insert('payments', $payment_data);
		$payment_id = (int) $this->db->insert_id();

		if ($payment_id <= 0) {
			return FALSE;
		}

		$safe_options = array('section_id' => $section_id, 'staff_id' => $staff_id);
		// Also tags the overflow wallet top-up with the payment it came from, so deleting
		// the payment later can find and reverse that top-up too.
		$topup_options = $safe_options + array('payment_id' => $payment_id);

		// Explicit user-initiated payment must clear ALL open debts (manual_only AND auto_settleable),
		// oldest first. Any leftover after debts are paid becomes a wallet top-up.
		$leftover = $this->Debt_model->clear_debts((int) $patient_id, $amount, NULL, $payment_id);
		$leftover = round((float) $leftover, 2);
		$applied_to_debt = round($amount - $leftover, 2);

		$payment_datetime = $this->payment_datetime_from_date($payment_date);

		$wallet_topup_transaction_id = NULL;

		if ($leftover > 0) {
			$top_up_result = $this->Wallet_model->top_up_cash(
				$patient_id,
				$leftover,
				NULL,
				$note ?: ('Debt payment overflow #' . $payment_id),
				$payment_datetime,
				$topup_options
			);

			if ($top_up_result === FALSE) {
				return FALSE;
			}

			// Capture the row we just inserted directly — $payment_datetime can be
			// backdated (staff recording a past payment), so picking the patient's
			// "latest" transaction by created_at further down would grab whatever
			// unrelated, more-recent transaction that patient has instead of this one.
			$wallet_topup_transaction_id = (int) $this->db->insert_id();
		}

		$safe_note = trim((string) $note);
		if ($safe_note === '') {
			$safe_note = safe_patient_payment_note($payment_id);
		}
		$user_id = $this->session->userdata('user_id');

		// Split the safe ledger entries so the daily-register report doesn't double-count
		// the overflow portion (which is ALSO logged as a manual wallet topup by top_up_cash).
		// Applied-to-debt portion → patient_debt_payment.
		// Overflow portion → wallet_topup (matches the patient_wallet_transactions topup row).
		if ($applied_to_debt > 0) {
			$safe_logged = $this->Safe_model->log_transaction(
				'in',
				'patient_debt_payment',
				$applied_to_debt,
				$payment_id,
				'payments',
				$safe_note,
				$user_id,
				$payment_datetime,
				$safe_options
			);

			if ($safe_logged === FALSE) {
				return FALSE;
			}
		}

		if ($leftover > 0) {
			$wallet_ref = !empty($wallet_topup_transaction_id) ? $wallet_topup_transaction_id : $payment_id;
			$wallet_ref_table = !empty($wallet_topup_transaction_id) ? 'patient_wallet_transactions' : 'payments';

			$safe_logged = $this->Safe_model->log_transaction(
				'in',
				'wallet_topup',
				$leftover,
				$wallet_ref,
				$wallet_ref_table,
				$safe_note,
				$user_id,
				$payment_datetime,
				$safe_options
			);

			if ($safe_logged === FALSE) {
				return FALSE;
			}
		}

		return $payment_id;
	}

	/**
	 * Unified, editable list of the money movements staff record directly from this profile
	 * (not generated by a turn): standalone debt payments, refunds, and prepayments taken
	 * with no open debt. Each carries the section/doctor tag captured on the form.
	 */
	protected function normalized_standalone_payments($patient_id)
	{
		$patient_id = (int) $patient_id;
		$rows = array();

		$staff_name_expr = "TRIM(CONCAT(staff.first_name, ' ', COALESCE(staff.last_name, '')))";

		$payments = $this->db
			->select("payments.id, payments.amount, payments.notes, payments.section_id, payments.staff_id, payments.created_at,
				sections.name AS section_name, {$staff_name_expr} AS staff_name", FALSE)
			->from('payments')
			->join('sections', 'sections.id = payments.section_id', 'left')
			->join('staff', 'staff.id = payments.staff_id', 'left')
			->where('payments.patient_id', $patient_id)
			->order_by('payments.id', 'desc')
			->get()
			->result_array();

		foreach ($payments as $row) {
			$rows[] = array(
				'kind' => 'debt_payment',
				'edit_kind' => 'payment',
				'id' => (int) $row['id'],
				'amount' => (float) $row['amount'],
				'note' => $row['notes'],
				'section_id' => $row['section_id'] ? (int) $row['section_id'] : NULL,
				'section_name' => !empty($row['section_name']) ? t($row['section_name']) : '',
				'staff_id' => $row['staff_id'] ? (int) $row['staff_id'] : NULL,
				'staff_name' => trim((string) ($row['staff_name'] ?? '')),
				'occurred_at' => to_shamsi((string) $row['created_at'], 'Y/m/d H:i'),
				'label' => t('debt_payment'),
			);
		}

		$wallet_rows = $this->db
			->select("patient_wallet_transactions.id, patient_wallet_transactions.type, patient_wallet_transactions.amount, patient_wallet_transactions.note,
				patient_wallet_transactions.section_id, patient_wallet_transactions.staff_id, patient_wallet_transactions.created_at,
				sections.name AS section_name, {$staff_name_expr} AS staff_name", FALSE)
			->from('patient_wallet_transactions')
			->join('sections', 'sections.id = patient_wallet_transactions.section_id', 'left')
			->join('staff', 'staff.id = patient_wallet_transactions.staff_id', 'left')
			->where('patient_wallet_transactions.patient_id', $patient_id)
			// Reversal entries (Wallet_model::reversal_note(), always prefixed
			// 'REVERSAL:') are internal bookkeeping for a cancelled/edited turn, never
			// a real payment or refund. When the turn is deleted outright, its FK
			// (ON DELETE SET NULL) nulls out turn_id on the reversal row too, which
			// would otherwise satisfy the "topup with no turn" branch below and list
			// it here as a phantom standalone payment.
			->where("(patient_wallet_transactions.note NOT LIKE 'REVERSAL:%' OR patient_wallet_transactions.note IS NULL)", NULL, FALSE)
			->group_start()
				->where('patient_wallet_transactions.type', 'refund')
				->or_group_start()
					->where('patient_wallet_transactions.type', 'topup')
					->where('patient_wallet_transactions.turn_id IS NULL', NULL, FALSE)
					->where('patient_wallet_transactions.payment_id IS NULL', NULL, FALSE)
				->group_end()
			->group_end()
			->order_by('patient_wallet_transactions.id', 'desc')
			->get()
			->result_array();

		foreach ($wallet_rows as $row) {
			$is_refund = (string) $row['type'] === 'refund';

			$rows[] = array(
				'kind' => $is_refund ? 'refund' : 'no_turn_payment',
				'edit_kind' => 'wallet_transaction',
				'id' => (int) $row['id'],
				'amount' => (float) $row['amount'],
				'note' => $row['note'],
				'section_id' => $row['section_id'] ? (int) $row['section_id'] : NULL,
				'section_name' => !empty($row['section_name']) ? t($row['section_name']) : '',
				'staff_id' => $row['staff_id'] ? (int) $row['staff_id'] : NULL,
				'staff_name' => trim((string) ($row['staff_name'] ?? '')),
				'occurred_at' => to_shamsi((string) $row['created_at'], 'Y/m/d H:i'),
				'label' => $is_refund ? t('refund') : t('payment_without_turn'),
			);
		}

		usort($rows, static function ($left, $right) {
			return strcmp((string) $right['occurred_at'], (string) $left['occurred_at']);
		});

		return $rows;
	}

	/**
	 * Section + doctor are required on standalone payment/refund forms so a filtered daily
	 * report can attribute money paid or refunded outside a turn to the right department.
	 * Returns [section_id, staff_id, error_message_or_NULL].
	 */
	protected function payment_dimension_from_post()
	{
		$section_id = (int) $this->input->post('section_id');
		$staff_id = (int) $this->input->post('staff_id');

		if ($section_id <= 0 || !$this->Section_model->get_by_id($section_id)) {
			return array(NULL, NULL, t('Please choose a valid section.'));
		}

		if ($staff_id <= 0 || !$this->Staff_model->get_by_id($staff_id)) {
			return array(NULL, NULL, t('Please choose a valid doctor.'));
		}

		return array($section_id, $staff_id, NULL);
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
