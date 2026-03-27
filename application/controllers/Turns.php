<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Turns extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Turn_model');
		$this->load->model('Patient_model');
		$this->load->model('Section_model');
		$this->load->model('Staff_model');
		$this->load->model('Wallet_model');
		$this->load->model('Debt_model');
		$this->load->model('Safe_model');
		$this->load->model('User_model');
		$this->load->model('Discount_model');
	}

	public function index()
	{
		$this->require_permission('manage_turns');

		$this->render('turns/index', array(
			'title' => t('Turns'),
			'current_section' => 'turns',
			'turns' => $this->Turn_model->all(),
		));
	}

	public function create()
	{
		$this->require_permission('manage_turns');
		$this->form(NULL, 'turns/store');
	}

	public function get_section_data()
	{
		$this->require_permission('manage_turns');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$section_id = (int) $this->input->post('section_id');
		$patient_id = (int) $this->input->post('patient_id');
		$section = $this->Section_model->get_by_id($section_id);

		if (!$section) {
			return $this->json_error(t('Invalid section selected.'));
		}

		$fee = (float) $this->Turn_model->get_section_fee($section_id);
		$discount_response = array(
			'has_discount' => FALSE,
			'discount_percent' => 0,
			'discount_amount' => 0,
			'final_fee' => $fee,
		);

		if ($patient_id > 0) {
			$active_discount = $this->Discount_model->get_active_discount($patient_id, $section_id);

			if ($active_discount) {
				$calculation = $this->Discount_model->calculate_discounted_fee($fee, $active_discount['discount_percent']);
				$discount_response = array(
					'has_discount' => TRUE,
					'discount_percent' => (float) $calculation['discount_percent'],
					'discount_amount' => (float) $calculation['discount_amount'],
					'final_fee' => (float) $calculation['final_fee'],
				);
			}
		}

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'fee' => $fee,
				'staff' => $this->Turn_model->get_staff_by_section($section_id),
				'discount' => $discount_response,
			)));
	}

	public function get_patient_financial()
	{
		$this->require_permission('manage_turns');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$patient_id = (int) $this->input->post('patient_id');
		$patient = $this->Patient_model->get_by_id($patient_id);

		if (!$patient) {
			return $this->json_error(t('Invalid patient selected.'));
		}

		$open_debts = $this->Debt_model->get_open_debts($patient_id);

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'wallet_balance' => (float) $this->Wallet_model->get_balance($patient_id),
				'total_open_debt' => (float) $this->Debt_model->get_total_open_debt($patient_id),
				'open_debts' => array_map(static function ($debt) {
					return array(
						'id' => (int) $debt['id'],
						'amount' => (float) $debt['amount'],
						'created_at' => to_shamsi(substr((string) $debt['created_at'], 0, 10)),
					);
				}, $open_debts),
			)));
	}

	public function get_session_number()
	{
		$this->require_permission('manage_turns');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$patient_id = (int) $this->input->post('patient_id');
		$section_id = (int) $this->input->post('section_id');

		if ($patient_id <= 0 || $section_id <= 0) {
			return $this->json_error('patient and section required');
		}

		$patient = $this->Patient_model->get_by_id($patient_id);
		$section = $this->Section_model->get_by_id($section_id);

		if (!$patient) {
			return $this->json_error(t('Invalid patient selected.'));
		}

		if (!$section) {
			return $this->json_error(t('Invalid section selected.'));
		}

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => TRUE,
				'session_number' => $this->Turn_model->get_next_session_number($patient_id, $section_id),
			)));
	}

	public function bulk_create()
	{
		$this->require_permission('manage_turns');
		$this->render_bulk_form();
	}

	public function store()
	{
		$this->require_permission('manage_turns');
		$this->validate_store_form();

		if (!$this->form_validation->run()) {
			return $this->form(NULL, 'turns/store');
		}

		$patient_id = (int) $this->input->post('patient_id');
		$staff = $this->Staff_model->get_by_id((int) $this->input->post('staff_id'));
		$fee = $this->decimal_value($this->input->post('fee'));
		$payment_type = $this->input->post('payment_type', TRUE);
		$topup_amount = $this->decimal_value($this->input->post('topup_amount'));
		$doctor_id = (int) $staff['user_id'];
		$wallet_deducted = 0.00;
		$cash_collected = 0.00;
		$remaining_fee = 0.00;

		$this->db->trans_begin();

		if ($topup_amount > 0) {
			$this->Wallet_model->top_up($patient_id, $topup_amount);
		}

		switch ($payment_type) {
			case 'free':
				break;

			case 'cash':
				$cash_collected = $fee;
				break;

			case 'deferred':
				break;

			case 'prepaid':
				$wallet_deducted = $this->Wallet_model->deduct($patient_id, $fee);
				$remaining_fee = round($fee - $wallet_deducted, 2);
				break;

			default:
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', t('Invalid payment type selected.'));
				return redirect('turns/create');
		}

		$turn_id = $this->Turn_model->create($this->turn_payload(array(
			'doctor_id' => $doctor_id,
			'wallet_deducted' => $wallet_deducted,
			'cash_collected' => $cash_collected,
			'topup_amount' => $topup_amount,
		)));

		if (!$turn_id) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', t('Unable to save turn right now.'));
			return redirect('turns/create');
		}

		if ($payment_type === 'cash') {
			$this->Debt_model->clear_debts($patient_id, $cash_collected, $turn_id);
		}

		if ($payment_type === 'deferred') {
			$this->Debt_model->create($patient_id, $turn_id, $fee);
		}

		if ($payment_type === 'prepaid' && $remaining_fee > 0) {
			$this->Debt_model->create($patient_id, $turn_id, $remaining_fee, 'Partial wallet - remaining after deduction');
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', t('Unable to save turn right now.'));
			return redirect('turns/create');
		}

		$this->db->trans_commit();

		if ($payment_type === 'cash' && $cash_collected > 0) {
			$this->Safe_model->log_transaction(
				'in',
				'turn_cash',
				$cash_collected,
				$turn_id,
				'turns',
				safe_turn_cash_note($turn_id),
				$this->session->userdata('user_id')
			);
		}

		if ($topup_amount > 0) {
			$this->Safe_model->log_transaction(
				'in',
				'wallet_topup',
				$topup_amount,
				$turn_id,
				'turns',
				safe_turn_wallet_topup_note($turn_id),
				$this->session->userdata('user_id')
			);
		}

		$this->session->set_flashdata('success', t('Turn created successfully.'));
		redirect('turns');
	}

	public function bulk_store()
	{
		$this->require_permission('manage_turns');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$shared_input = $this->bulk_shared_input();
		$submitted_turns = $this->bulk_posted_turns();
		$validation = $this->validate_bulk_submission($shared_input, $submitted_turns);

		if (!empty($validation['shared_errors']) || !empty($validation['row_errors'])) {
			return $this->render_bulk_form($shared_input, $submitted_turns, $validation['shared_errors'], $validation['row_errors']);
		}

		$validated_rows = $validation['rows'];
		$safe_entries = array();
		$this->db->trans_begin();

		foreach ($validated_rows as $index => $row) {
			$staff = $this->Staff_model->get_by_id($row['staff_id']);

			if (!$staff || empty($staff['user_id'])) {
				$this->db->trans_rollback();
				return $this->render_bulk_form(
					$shared_input,
					$submitted_turns,
					array($this->bulk_row_error_message($index, t('Invalid staff member selected.'))),
					array($index => array(t('Invalid staff member selected.')))
				);
			}

			$wallet_deducted = 0.00;
			$cash_collected = 0.00;
			$remaining_fee = 0.00;

			if ($row['topup_amount'] > 0) {
				$new_balance = $this->Wallet_model->top_up($row['patient_id'], $row['topup_amount']);

				if ($new_balance === FALSE) {
					$this->db->trans_rollback();
					return $this->render_bulk_form(
						$shared_input,
						$submitted_turns,
						array($this->bulk_row_error_message($index, t('Unable to save turn right now.'))),
						array($index => array(t('Unable to save turn right now.')))
					);
				}
			}

			switch ($row['payment_type']) {
				case 'free':
					break;

				case 'cash':
					$cash_collected = $row['fee'];
					break;

				case 'deferred':
					break;

				case 'prepaid':
					$wallet_deducted = $this->Wallet_model->deduct($row['patient_id'], $row['fee']);

					if ($wallet_deducted === FALSE) {
						$this->db->trans_rollback();
						return $this->render_bulk_form(
							$shared_input,
							$submitted_turns,
							array($this->bulk_row_error_message($index, t('Unable to save turn right now.'))),
							array($index => array(t('Unable to save turn right now.')))
						);
					}

					$remaining_fee = round($row['fee'] - $wallet_deducted, 2);
					break;

				default:
					$this->db->trans_rollback();
					return $this->render_bulk_form(
						$shared_input,
						$submitted_turns,
						array($this->bulk_row_error_message($index, t('Invalid payment type selected.'))),
						array($index => array(t('Invalid payment type selected.')))
					);
			}

			$turn_id = $this->Turn_model->create($this->bulk_turn_payload($shared_input, $row, array(
				'doctor_id' => (int) $staff['user_id'],
				'wallet_deducted' => $wallet_deducted,
				'cash_collected' => $cash_collected,
			)));

			if (!$turn_id) {
				$this->db->trans_rollback();
				return $this->render_bulk_form(
					$shared_input,
					$submitted_turns,
					array($this->bulk_row_error_message($index, t('Unable to save turn right now.'))),
					array($index => array(t('Unable to save turn right now.')))
				);
			}

			if ($row['payment_type'] === 'cash') {
				$this->Debt_model->clear_debts($row['patient_id'], $cash_collected, $turn_id);
			}

			if ($row['payment_type'] === 'deferred') {
				$debt_id = $this->Debt_model->create($row['patient_id'], $turn_id, $row['fee']);

				if (!$debt_id) {
					$this->db->trans_rollback();
					return $this->render_bulk_form(
						$shared_input,
						$submitted_turns,
						array($this->bulk_row_error_message($index, t('Unable to save turn right now.'))),
						array($index => array(t('Unable to save turn right now.')))
					);
				}
			}

			if ($row['payment_type'] === 'prepaid' && $remaining_fee > 0) {
				$debt_id = $this->Debt_model->create($row['patient_id'], $turn_id, $remaining_fee, 'Partial wallet - remaining after deduction');

				if (!$debt_id) {
					$this->db->trans_rollback();
					return $this->render_bulk_form(
						$shared_input,
						$submitted_turns,
						array($this->bulk_row_error_message($index, t('Unable to save turn right now.'))),
						array($index => array(t('Unable to save turn right now.')))
					);
				}
			}

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return $this->render_bulk_form(
					$shared_input,
					$submitted_turns,
					array($this->bulk_row_error_message($index, t('Unable to save turn right now.'))),
					array($index => array(t('Unable to save turn right now.')))
				);
			}

			if ($row['payment_type'] === 'cash' && $cash_collected > 0) {
				$safe_entries[] = array(
					'type' => 'in',
					'source' => 'turn_cash',
					'amount' => $cash_collected,
					'reference_id' => $turn_id,
					'reference_table' => 'turns',
					'note' => safe_turn_cash_note($turn_id),
				);
			}

			if ($row['topup_amount'] > 0) {
				$safe_entries[] = array(
					'type' => 'in',
					'source' => 'wallet_topup',
					'amount' => $row['topup_amount'],
					'reference_id' => $turn_id,
					'reference_table' => 'turns',
					'note' => safe_turn_wallet_topup_note($turn_id),
				);
			}
		}

		$this->db->trans_commit();

		foreach ($safe_entries as $entry) {
			$this->Safe_model->log_transaction(
				$entry['type'],
				$entry['source'],
				$entry['amount'],
				$entry['reference_id'],
				$entry['reference_table'],
				$entry['note'],
				$this->session->userdata('user_id')
			);
		}

		$this->session->set_flashdata('success', count($validated_rows) . ' ' . t('bulk_success'));
		redirect('turns');
	}

	public function edit($id)
	{
		$this->require_permission('manage_turns');
		$turn = $this->Turn_model->find($id);
		show_404_if_empty($turn);
		$this->form($turn, 'turns/' . $id . '/update');
	}

	public function update($id)
	{
		$this->require_permission('manage_turns');
		$turn = $this->Turn_model->find($id);
		show_404_if_empty($turn);
		$this->validate_update_form();

		if (!$this->form_validation->run()) {
			return $this->form($turn, 'turns/' . $id . '/update');
		}

		$staff = $this->Staff_model->get_by_id((int) $this->input->post('staff_id'));
		if (!$staff || empty($staff['user_id'])) {
			$this->session->set_flashdata('error', t('Invalid staff member selected.'));
			return redirect('turns/' . $id . '/edit');
		}

		$patient_id = (int) $turn['patient_id'];
		$fee = $this->decimal_value($this->input->post('fee'));
		$payment_type = $this->input->post('payment_type', TRUE);
		$topup_amount = $this->decimal_value($this->input->post('topup_amount'));
		$discount_percent = $this->decimal_value($this->input->post('discount_percent'));
		$discount_amount = $this->decimal_value($this->input->post('discount_amount'));
		$doctor_id = (int) $staff['user_id'];
		$user_id = (int) $this->session->userdata('user_id');
		$financials_changed = $this->turn_financials_changed($turn, array(
			'fee' => $fee,
			'payment_type' => $payment_type,
			'topup_amount' => $topup_amount,
			'discount_percent' => $discount_percent,
			'discount_amount' => $discount_amount,
		));

		if (!$financials_changed) {
			$updated = $this->Turn_model->update($id, $this->turn_update_payload($doctor_id, array(
				'fee' => (float) $turn['fee'],
				'payment_type' => (string) $turn['payment_type'],
				'wallet_deducted' => (float) $turn['wallet_deducted'],
				'cash_collected' => (float) $turn['cash_collected'],
				'topup_amount' => (float) $turn['topup_amount'],
				'discount_percent' => (float) $turn['discount_percent'],
				'discount_amount' => (float) $turn['discount_amount'],
			)));

			if (!$updated) {
				$this->session->set_flashdata('error', t('Unable to save turn right now.'));
				return redirect('turns/' . $id . '/edit');
			}

			$this->session->set_flashdata('success', t('Turn updated successfully.'));
			return redirect('turns');
		}

		$wallet_deducted = 0.00;
		$cash_collected = 0.00;

		$this->db->trans_begin();

		$reversed = $this->reverse_turn_financials($turn);

		if ($reversed === FALSE) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', t('Unable to save turn right now.'));
			return redirect('turns/' . $id . '/edit');
		}

		if ($topup_amount > 0) {
			$new_balance = $this->Wallet_model->top_up($patient_id, $topup_amount, $id, 'Top-up on edit of turn #' . $id);

			if ($new_balance === FALSE) {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', t('Unable to save turn right now.'));
				return redirect('turns/' . $id . '/edit');
			}
		}

		switch ($payment_type) {
			case 'free':
				break;

			case 'cash':
				$cash_collected = $fee;
				$remaining_cash = $this->Debt_model->clear_debts($patient_id, $cash_collected, $id);

				if ($remaining_cash === FALSE) {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', t('Unable to save turn right now.'));
					return redirect('turns/' . $id . '/edit');
				}
				break;

			case 'deferred':
				$debt_id = $this->Debt_model->create($patient_id, $id, $fee);

				if (!$debt_id) {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', t('Unable to save turn right now.'));
					return redirect('turns/' . $id . '/edit');
				}
				break;

			case 'prepaid':
				$actual_deducted = $this->Wallet_model->deduct($patient_id, $fee, $id, 'Deduction on edit of turn #' . $id);

				if ($actual_deducted === FALSE) {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', t('Unable to save turn right now.'));
					return redirect('turns/' . $id . '/edit');
				}

				$wallet_deducted = $actual_deducted;
				$remaining = round($fee - $actual_deducted, 2);

				if ($remaining > 0) {
					$debt_id = $this->Debt_model->create($patient_id, $id, $remaining, 'Partial wallet on edit - remaining after deduction');

					if (!$debt_id) {
						$this->db->trans_rollback();
						$this->session->set_flashdata('error', t('Unable to save turn right now.'));
						return redirect('turns/' . $id . '/edit');
					}
				}
				break;

			default:
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', t('Invalid payment type selected.'));
				return redirect('turns/' . $id . '/edit');
		}

		$updated = $this->Turn_model->update($id, $this->turn_update_payload($doctor_id, array(
			'fee' => $fee,
			'payment_type' => $payment_type,
			'wallet_deducted' => $wallet_deducted,
			'cash_collected' => $cash_collected,
			'topup_amount' => $topup_amount,
			'discount_percent' => $discount_percent,
			'discount_amount' => $discount_amount,
		)));

		if (!$updated) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', t('Unable to save turn right now.'));
			return redirect('turns/' . $id . '/edit');
		}

		if ($payment_type === 'cash' && $cash_collected > 0) {
			$logged = $this->Safe_model->log_transaction(
				'in',
				'turn_cash',
				$cash_collected,
				$id,
				'turns',
				'Cash on edit of turn #' . $id,
				$user_id,
				NULL,
				array('skip_duplicate_check' => TRUE)
			);

			if ($logged === FALSE) {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', t('Unable to save turn right now.'));
				return redirect('turns/' . $id . '/edit');
			}
		}

		if ($topup_amount > 0) {
			$logged = $this->Safe_model->log_transaction(
				'in',
				'wallet_topup',
				$topup_amount,
				$id,
				'turns',
				'Top-up on edit of turn #' . $id,
				$user_id,
				NULL,
				array('skip_duplicate_check' => TRUE)
			);

			if ($logged === FALSE) {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', t('Unable to save turn right now.'));
				return redirect('turns/' . $id . '/edit');
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', t('Unable to save turn right now.'));
			return redirect('turns/' . $id . '/edit');
		}

		$this->db->trans_commit();
		$this->session->set_flashdata('success', t('Turn updated successfully.'));
		redirect('turns');
	}

	public function delete($id)
	{
		$this->require_permission('manage_turns');
		$turn = $this->Turn_model->find($id);
		show_404_if_empty($turn);

		if (!$this->Turn_model->delete($id)) {
			$this->session->set_flashdata('error', t('Unable to delete record right now.'));
			return redirect('turns');
		}

		$this->session->set_flashdata('success', t('Turn deleted successfully.'));
		redirect('turns');
	}

	public function _valid_patient_id($value)
	{
		if ($this->Patient_model->get_by_id((int) $value)) {
			return TRUE;
		}

		$this->form_validation->set_message('_valid_patient_id', t('Invalid patient selected.'));
		return FALSE;
	}

	public function _valid_section_id($value)
	{
		if ($this->Section_model->get_by_id((int) $value)) {
			return TRUE;
		}

		$this->form_validation->set_message('_valid_section_id', t('Invalid section selected.'));
		return FALSE;
	}

	public function _valid_staff_id($value)
	{
		$staff_id = (int) $value;
		$section_id = (int) $this->input->post('section_id');

		if ($staff_id <= 0 || $section_id <= 0) {
			$this->form_validation->set_message('_valid_staff_id', t('Invalid staff member selected.'));
			return FALSE;
		}

		$available_staff = $this->Turn_model->get_staff_by_section($section_id);
		$available_ids = array_map('intval', array_column($available_staff, 'id'));

		if (in_array($staff_id, $available_ids, TRUE)) {
			return TRUE;
		}

		$this->form_validation->set_message('_valid_staff_id', t('Invalid staff member selected.'));
		return FALSE;
	}

	public function _valid_turn_number($value)
	{
		$value = trim((string) $value);

		if ($value === '') {
			return TRUE;
		}

		if (ctype_digit($value) && (int) $value > 0) {
			return TRUE;
		}

		$this->form_validation->set_message('_valid_turn_number', t('Invalid session number.'));
		return FALSE;
	}

	protected function form($turn, $action)
	{
		$selected_patient_id = (int) set_value('patient_id', $turn['patient_id'] ?? 0);
		$selected_section_id = (int) set_value('section_id', $turn['section_id'] ?? 0);

		$this->render('turns/form', array(
			'title' => $turn ? t('Edit Turn') : t('Create Turn'),
			'current_section' => 'turns',
			'turn' => $turn,
			'action' => $action,
			'is_edit' => (bool) $turn,
			'patients' => $this->Patient_model->all(),
			'sections' => $this->Section_model->get_all(),
			'staff_members' => $selected_section_id > 0 ? $this->Turn_model->get_staff_by_section($selected_section_id) : array(),
			'wallet_balance' => $selected_patient_id > 0 ? $this->Wallet_model->get_balance($selected_patient_id) : 0,
			'open_debts' => $selected_patient_id > 0 ? $this->Debt_model->get_open_debts($selected_patient_id) : array(),
			'total_open_debt' => $selected_patient_id > 0 ? $this->Debt_model->get_total_open_debt($selected_patient_id) : 0,
			'default_section_fee' => $selected_section_id > 0 ? $this->Turn_model->get_section_fee($selected_section_id) : 0,
		));
	}

	protected function render_bulk_form($shared_input = array(), $submitted_turns = array(), $shared_errors = array(), $row_errors = array())
	{
		$shared_input = array_merge(array(
			'section_id' => 0,
			'date' => shamsi_today(),
			'status' => 'accepted',
		), $shared_input);

		$this->render('turns/bulk_form', array(
			'title' => t('bulk_turns'),
			'current_section' => 'turns',
			'patients' => $this->Patient_model->all(),
			'sections' => $this->Section_model->get_all(),
			'shared_input' => $shared_input,
			'submitted_turns' => array_values($submitted_turns),
			'shared_errors' => array_values($shared_errors),
			'row_errors' => $row_errors,
		));
	}

	protected function validate_store_form()
	{
		$this->form_validation->set_rules('patient_id', 'Patient', 'required|integer|callback__valid_patient_id');
		$this->form_validation->set_rules('section_id', 'Section', 'required|integer|callback__valid_section_id');
		$this->form_validation->set_rules('staff_id', 'Staff member', 'required|integer|callback__valid_staff_id');
		$this->form_validation->set_rules('turn_number', 'Turn number', 'trim|callback__valid_turn_number');
		$this->form_validation->set_rules('fee', 'Fee', 'required|numeric|greater_than_equal_to[0]');
		$this->form_validation->set_rules('payment_type', 'Payment type', 'required|in_list[prepaid,cash,deferred,free]');
		$this->form_validation->set_rules('topup_amount', 'Top up amount', 'trim|numeric|greater_than_equal_to[0]');
		$this->form_validation->set_rules('turn_date', 'Date', 'required|callback__valid_turn_date');
		$this->form_validation->set_rules('turn_time', 'Time', 'trim');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[accepted,scheduled,completed,cancelled]');
	}

	protected function validate_update_form()
	{
		$this->form_validation->set_rules('patient_id', 'Patient', 'required|integer|callback__valid_patient_id');
		$this->form_validation->set_rules('section_id', 'Section', 'required|integer|callback__valid_section_id');
		$this->form_validation->set_rules('staff_id', 'Staff member', 'required|integer|callback__valid_staff_id');
		$this->form_validation->set_rules('turn_number', 'Turn number', 'trim|callback__valid_turn_number');
		$this->form_validation->set_rules('fee', 'Fee', 'required|numeric|greater_than_equal_to[0]');
		$this->form_validation->set_rules('payment_type', 'Payment type', 'required|in_list[prepaid,cash,deferred,free]');
		$this->form_validation->set_rules('topup_amount', 'Top up amount', 'trim|numeric|greater_than_equal_to[0]');
		$this->form_validation->set_rules('discount_percent', 'Discount percent', 'trim|numeric|greater_than_equal_to[0]');
		$this->form_validation->set_rules('discount_amount', 'Discount amount', 'trim|numeric|greater_than_equal_to[0]');
		$this->form_validation->set_rules('turn_date', 'Date', 'required|callback__valid_turn_date');
		$this->form_validation->set_rules('turn_time', 'Time', 'trim');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[accepted,scheduled,completed,cancelled]');
	}

	public function _valid_turn_date($value)
	{
		if ($this->is_valid_shamsi_date_input($value)) {
			return TRUE;
		}

		$this->form_validation->set_message('_valid_turn_date', t('Please choose a valid date.'));
		return FALSE;
	}

	protected function turn_payload($overrides = array())
	{
		return array(
			'patient_id' => (int) $this->input->post('patient_id'),
			'doctor_id' => isset($overrides['doctor_id']) ? (int) $overrides['doctor_id'] : 0,
			'section_id' => (int) $this->input->post('section_id'),
			'staff_id' => (int) $this->input->post('staff_id'),
			'turn_number' => $this->nullable_int($this->input->post('turn_number', TRUE)),
			'fee' => $this->decimal_value($this->input->post('fee')),
			'discount_percent' => $this->decimal_value($this->input->post('discount_percent')),
			'discount_amount' => $this->decimal_value($this->input->post('discount_amount')),
			'payment_type' => $this->input->post('payment_type', TRUE) ?: 'cash',
			'wallet_deducted' => isset($overrides['wallet_deducted']) ? round((float) $overrides['wallet_deducted'], 2) : 0.00,
			'cash_collected' => isset($overrides['cash_collected']) ? round((float) $overrides['cash_collected'], 2) : 0.00,
			'topup_amount' => isset($overrides['topup_amount']) ? round((float) $overrides['topup_amount'], 2) : 0.00,
			'turn_date' => $this->gregorian_date_from_shamsi($this->input->post('turn_date', TRUE)),
			'turn_time' => $this->normalize_time($this->input->post('turn_time', TRUE)),
			'status' => $this->input->post('status', TRUE) ?: 'accepted',
			'notes' => $this->null_if_empty($this->input->post('notes', TRUE)),
		);
	}

	protected function turn_update_payload($doctor_id, $overrides = array())
	{
		return array(
			'doctor_id' => (int) $doctor_id,
			'section_id' => (int) $this->input->post('section_id'),
			'staff_id' => (int) $this->input->post('staff_id'),
			'turn_number' => $this->nullable_int($this->input->post('turn_number', TRUE)),
			'fee' => isset($overrides['fee']) ? round((float) $overrides['fee'], 2) : $this->decimal_value($this->input->post('fee')),
			'discount_percent' => isset($overrides['discount_percent']) ? round((float) $overrides['discount_percent'], 2) : $this->decimal_value($this->input->post('discount_percent')),
			'discount_amount' => isset($overrides['discount_amount']) ? round((float) $overrides['discount_amount'], 2) : $this->decimal_value($this->input->post('discount_amount')),
			'payment_type' => isset($overrides['payment_type']) ? (string) $overrides['payment_type'] : ($this->input->post('payment_type', TRUE) ?: 'cash'),
			'wallet_deducted' => isset($overrides['wallet_deducted']) ? round((float) $overrides['wallet_deducted'], 2) : 0.00,
			'cash_collected' => isset($overrides['cash_collected']) ? round((float) $overrides['cash_collected'], 2) : 0.00,
			'topup_amount' => isset($overrides['topup_amount']) ? round((float) $overrides['topup_amount'], 2) : $this->decimal_value($this->input->post('topup_amount')),
			'turn_date' => $this->gregorian_date_from_shamsi($this->input->post('turn_date', TRUE)),
			'turn_time' => $this->normalize_time($this->input->post('turn_time', TRUE)),
			'status' => $this->input->post('status', TRUE) ?: 'accepted',
			'notes' => $this->null_if_empty($this->input->post('notes', TRUE)),
		);
	}

	private function reverse_turn_financials($original_turn)
	{
		$patient_id = (int) $original_turn['patient_id'];
		$turn_id = (int) $original_turn['id'];

		if ((float) $original_turn['topup_amount'] > 0) {
			$reversed = $this->Wallet_model->reverse_topup(
				$patient_id,
				(float) $original_turn['topup_amount'],
				$turn_id,
				'Reversal of top-up for turn #' . $turn_id
			);

			if ($reversed === FALSE) {
				return FALSE;
			}
		}

		if ((float) $original_turn['wallet_deducted'] > 0) {
			$reversed = $this->Wallet_model->reverse_deduction(
				$patient_id,
				(float) $original_turn['wallet_deducted'],
				$turn_id,
				'Reversal of deduction for turn #' . $turn_id
			);

			if ($reversed === FALSE) {
				return FALSE;
			}
		}

		$debt_reversed = $this->Debt_model->reverse_turn_debts($turn_id);

		if ($debt_reversed === FALSE) {
			return FALSE;
		}

		$safe_reversed = $this->Safe_model->reverse_turn_transactions($turn_id);

		if ($safe_reversed === FALSE) {
			return FALSE;
		}

		return TRUE;
	}

	private function turn_financials_changed($original_turn, $new_values)
	{
		return round((float) $original_turn['fee'], 2) !== round((float) $new_values['fee'], 2)
			|| (string) $original_turn['payment_type'] !== (string) $new_values['payment_type']
			|| round((float) $original_turn['topup_amount'], 2) !== round((float) $new_values['topup_amount'], 2)
			|| round((float) $original_turn['discount_percent'], 2) !== round((float) $new_values['discount_percent'], 2)
			|| round((float) $original_turn['discount_amount'], 2) !== round((float) $new_values['discount_amount'], 2);
	}

	protected function decimal_value($value)
	{
		return round((float) $value, 2);
	}

	protected function bulk_shared_input()
	{
		return array(
			'section_id' => (int) $this->input->post('section_id'),
			'date' => trim((string) $this->input->post('date', TRUE)),
			'status' => 'accepted',
		);
	}

	protected function bulk_posted_turns()
	{
		$turns = $this->input->post('turns');

		if (!is_array($turns)) {
			return array();
		}

		$normalized = array();

		foreach ($turns as $turn) {
			if (!is_array($turn)) {
				continue;
			}

			$normalized[] = array(
				'patient_id' => trim((string) ($turn['patient_id'] ?? '')),
				'staff_id' => trim((string) ($turn['staff_id'] ?? '')),
				'turn_number' => trim((string) ($turn['turn_number'] ?? '')),
				'fee' => trim((string) ($turn['fee'] ?? '')),
				'discount_percent' => trim((string) ($turn['discount_percent'] ?? '0')),
				'discount_amount' => trim((string) ($turn['discount_amount'] ?? '0')),
				'payment_type' => trim((string) ($turn['payment_type'] ?? '')),
				'topup_amount' => trim((string) ($turn['topup_amount'] ?? '0')),
				'notes' => trim((string) ($turn['notes'] ?? '')),
			);
		}

		return $normalized;
	}

	protected function validate_bulk_submission($shared_input, $submitted_turns)
	{
		$shared_errors = array();
		$row_errors = array();
		$validated_rows = array();
		$allowed_payment_types = array('prepaid', 'cash', 'deferred', 'free');
		$patient_occurrences = array();
		$section_id = (int) $shared_input['section_id'];

		if ($section_id <= 0) {
			$shared_errors[] = t('Invalid section selected.');
		} elseif (!$this->Section_model->get_by_id($section_id)) {
			$shared_errors[] = t('Invalid section selected.');
		}

		if ($shared_input['date'] === '') {
			$shared_errors[] = t('Date') . ' ' . t('is required.');
		} elseif (!$this->is_valid_shamsi_date_input($shared_input['date'])) {
			$shared_errors[] = t('Please choose a valid date.');
		}

		if (empty($submitted_turns)) {
			$shared_errors[] = t('no_rows');
		}

		$valid_staff_ids = array();

		if ($section_id > 0 && empty($shared_errors)) {
			$valid_staff_ids = array_map('intval', array_column($this->Turn_model->get_staff_by_section($section_id), 'id'));
		}

		foreach ($submitted_turns as $index => $row) {
			$errors = array();
			$patient_id = (int) $row['patient_id'];
			$staff_id = (int) $row['staff_id'];
			$payment_type = $row['payment_type'];
			$fee_raw = $row['fee'];
			$topup_raw = $row['topup_amount'];

			if ($patient_id <= 0) {
				$errors[] = t('Patient') . ' ' . t('is required.');
			} elseif (!$this->Patient_model->get_by_id($patient_id)) {
				$errors[] = t('Invalid patient selected.');
			} else {
				$patient_occurrences[$patient_id][] = $index;
			}

			if ($staff_id <= 0) {
				$errors[] = t('staff_member') . ' ' . t('is required.');
			} elseif ($section_id > 0 && !in_array($staff_id, $valid_staff_ids, TRUE)) {
				$errors[] = t('Invalid staff member selected.');
			}

			if ($fee_raw === '') {
				$errors[] = t('fee') . ' ' . t('is required.');
			} elseif (!is_numeric($fee_raw) || (float) $fee_raw < 0) {
				$errors[] = t('fee') . ' ' . t('must be a valid number.');
			}

			if ($payment_type === '') {
				$errors[] = t('payment_type') . ' ' . t('is required.');
			} elseif (!in_array($payment_type, $allowed_payment_types, TRUE)) {
				$errors[] = t('Invalid payment type selected.');
			}

			if ($topup_raw !== '' && (!is_numeric($topup_raw) || (float) $topup_raw < 0)) {
				$errors[] = t('top_up_amount') . ' ' . t('must be a valid number.');
			}

			if ($row['turn_number'] !== '' && (!ctype_digit($row['turn_number']) || (int) $row['turn_number'] <= 0)) {
				$errors[] = t('Invalid session number.');
			}

			if ($errors) {
				$row_errors[$index] = $errors;
				continue;
			}

			$validated_rows[$index] = array(
				'patient_id' => $patient_id,
				'staff_id' => $staff_id,
				'turn_number' => $this->nullable_int($row['turn_number']),
				'fee' => round((float) $fee_raw, 2),
				'discount_percent' => round((float) ($row['discount_percent'] === '' ? 0 : $row['discount_percent']), 2),
				'discount_amount' => round((float) ($row['discount_amount'] === '' ? 0 : $row['discount_amount']), 2),
				'payment_type' => $payment_type,
				'topup_amount' => $topup_raw === '' ? 0.00 : round((float) $topup_raw, 2),
				'notes' => $this->null_if_empty($row['notes']),
			);
		}

		foreach ($patient_occurrences as $indexes) {
			if (count($indexes) < 2) {
				continue;
			}

			foreach ($indexes as $index) {
				$row_errors[$index][] = t('duplicate_patient');
			}
		}

		if (!empty($row_errors)) {
			$shared_errors = array_merge($shared_errors, $this->flatten_bulk_row_errors($row_errors));
		}

		ksort($validated_rows);

		return array(
			'shared_errors' => array_values(array_unique($shared_errors)),
			'row_errors' => $row_errors,
			'rows' => array_values($validated_rows),
		);
	}

	protected function flatten_bulk_row_errors($row_errors)
	{
		$messages = array();

		foreach ($row_errors as $index => $errors) {
			foreach (array_unique($errors) as $error) {
				$messages[] = $this->bulk_row_error_message($index, $error);
			}
		}

		return $messages;
	}

	protected function bulk_row_error_message($index, $message)
	{
		return t('bulk_row_error') . ' ' . ($index + 1) . ': ' . $message;
	}

	protected function bulk_turn_payload($shared_input, $row, $overrides = array())
	{
		return array(
			'patient_id' => (int) $row['patient_id'],
			'doctor_id' => isset($overrides['doctor_id']) ? (int) $overrides['doctor_id'] : 0,
			'section_id' => (int) $shared_input['section_id'],
			'staff_id' => (int) $row['staff_id'],
			'turn_number' => $row['turn_number'],
			'fee' => round((float) $row['fee'], 2),
			'discount_percent' => round((float) ($row['discount_percent'] ?? 0), 2),
			'discount_amount' => round((float) ($row['discount_amount'] ?? 0), 2),
			'payment_type' => $row['payment_type'],
			'wallet_deducted' => isset($overrides['wallet_deducted']) ? round((float) $overrides['wallet_deducted'], 2) : 0.00,
			'cash_collected' => isset($overrides['cash_collected']) ? round((float) $overrides['cash_collected'], 2) : 0.00,
			'topup_amount' => round((float) $row['topup_amount'], 2),
			'turn_date' => $this->gregorian_date_from_shamsi($shared_input['date']),
			'turn_time' => NULL,
			'status' => 'accepted',
			'notes' => $row['notes'],
		);
	}

	protected function nullable_int($value)
	{
		$value = trim((string) $value);
		return $value === '' ? NULL : (int) $value;
	}

	protected function normalize_time($value)
	{
		$value = trim((string) $value);
		return $value === '' ? NULL : $value;
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
}
