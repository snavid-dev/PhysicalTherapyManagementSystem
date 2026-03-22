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
		$this->load->model('User_model');
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
		$section = $this->Section_model->get_by_id($section_id);

		if (!$section) {
			return $this->json_error(t('Invalid section selected.'));
		}

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'fee' => (float) $this->Turn_model->get_section_fee($section_id),
				'staff' => $this->Turn_model->get_staff_by_section($section_id),
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
						'created_at' => substr((string) $debt['created_at'], 0, 10),
					);
				}, $open_debts),
			)));
	}

	public function bulk_create()
	{
		$this->require_permission('manage_turns');
		$this->render('turns/bulk_form', array(
			'title' => t('Bulk Turn Entry'),
			'current_section' => 'turns',
			'patients' => $this->Patient_model->all(),
			'therapists' => $this->User_model->therapists(),
		));
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
		$this->session->set_flashdata('success', t('Turn created successfully.'));
		redirect('turns');
	}

	public function bulk_store()
	{
		$this->require_permission('manage_turns');

		$turn_date = $this->input->post('turn_date', TRUE);
		$doctor_id = (int) $this->input->post('doctor_id');
		$default_status = $this->input->post('default_status', TRUE) ?: 'scheduled';
		$patient_ids = (array) $this->input->post('patient_id');
		$turn_times = (array) $this->input->post('turn_time');
		$statuses = (array) $this->input->post('status');
		$notes = (array) $this->input->post('notes');
		$rows = array();

		foreach ($patient_ids as $index => $patient_id) {
			$patient_id = (int) $patient_id;
			$time = isset($turn_times[$index]) ? trim($turn_times[$index]) : '';
			if (!$patient_id && !$time) {
				continue;
			}
			if (!$patient_id || !$time) {
				continue;
			}

			$rows[] = array(
				'patient_id' => $patient_id,
				'doctor_id' => $doctor_id,
				'turn_date' => $turn_date,
				'turn_time' => $time,
				'status' => !empty($statuses[$index]) ? $statuses[$index] : $default_status,
				'notes' => isset($notes[$index]) ? $notes[$index] : '',
			);
		}

		if (!$turn_date || !$doctor_id || empty($rows)) {
			$this->session->set_flashdata('error', t('Please add at least one turn row.'));
			redirect('turns/bulk-create');
		}

		$this->Turn_model->create_many($rows);
		$this->session->set_flashdata('success', t('Turns created successfully.'));
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
		$doctor_id = (int) $staff['user_id'];

		$this->Turn_model->update($id, $this->turn_update_payload($doctor_id));
		$this->session->set_flashdata('success', t('Turn updated successfully.'));
		redirect('turns');
	}

	public function delete($id)
	{
		$this->require_permission('manage_turns');
		$turn = $this->Turn_model->find($id);
		show_404_if_empty($turn);

		$this->Turn_model->delete($id);
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

	protected function validate_store_form()
	{
		$this->form_validation->set_rules('patient_id', 'Patient', 'required|integer|callback__valid_patient_id');
		$this->form_validation->set_rules('section_id', 'Section', 'required|integer|callback__valid_section_id');
		$this->form_validation->set_rules('staff_id', 'Staff member', 'required|integer|callback__valid_staff_id');
		$this->form_validation->set_rules('turn_number', 'Turn number', 'trim|callback__valid_turn_number');
		$this->form_validation->set_rules('fee', 'Fee', 'required|numeric|greater_than_equal_to[0]');
		$this->form_validation->set_rules('payment_type', 'Payment type', 'required|in_list[prepaid,cash,deferred,free]');
		$this->form_validation->set_rules('topup_amount', 'Top up amount', 'trim|numeric|greater_than_equal_to[0]');
		$this->form_validation->set_rules('turn_date', 'Date', 'required');
		$this->form_validation->set_rules('turn_time', 'Time', 'trim');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[scheduled,completed,cancelled]');
	}

	protected function validate_update_form()
	{
		$this->form_validation->set_rules('section_id', 'Section', 'required|integer|callback__valid_section_id');
		$this->form_validation->set_rules('staff_id', 'Staff member', 'required|integer|callback__valid_staff_id');
		$this->form_validation->set_rules('turn_number', 'Turn number', 'trim|callback__valid_turn_number');
		$this->form_validation->set_rules('turn_date', 'Date', 'required');
		$this->form_validation->set_rules('turn_time', 'Time', 'trim');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[scheduled,completed,cancelled]');
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
			'payment_type' => $this->input->post('payment_type', TRUE) ?: 'cash',
			'wallet_deducted' => isset($overrides['wallet_deducted']) ? round((float) $overrides['wallet_deducted'], 2) : 0.00,
			'cash_collected' => isset($overrides['cash_collected']) ? round((float) $overrides['cash_collected'], 2) : 0.00,
			'topup_amount' => isset($overrides['topup_amount']) ? round((float) $overrides['topup_amount'], 2) : 0.00,
			'turn_date' => $this->input->post('turn_date', TRUE),
			'turn_time' => $this->normalize_time($this->input->post('turn_time', TRUE)),
			'status' => $this->input->post('status', TRUE),
			'notes' => $this->null_if_empty($this->input->post('notes', TRUE)),
		);
	}

	protected function turn_update_payload($doctor_id)
	{
		return array(
			'doctor_id' => (int) $doctor_id,
			'section_id' => (int) $this->input->post('section_id'),
			'staff_id' => (int) $this->input->post('staff_id'),
			'turn_number' => $this->nullable_int($this->input->post('turn_number', TRUE)),
			'turn_date' => $this->input->post('turn_date', TRUE),
			'turn_time' => $this->normalize_time($this->input->post('turn_time', TRUE)),
			'status' => $this->input->post('status', TRUE),
			'notes' => $this->null_if_empty($this->input->post('notes', TRUE)),
		);
	}

	protected function decimal_value($value)
	{
		return round((float) $value, 2);
	}

	protected function nullable_int($value)
	{
		$value = trim((string) $value);
		return $value === '' ? NULL : (int) $value;
	}

	protected function normalize_time($value)
	{
		$value = trim((string) $value);
		return $value === '' ? '00:00:00' : $value;
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
