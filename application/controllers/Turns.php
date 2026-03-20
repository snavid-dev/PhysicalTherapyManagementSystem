<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Turns extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Turn_model');
		$this->load->model('Patient_model');
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
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form(NULL, 'turns/store');
		}

		$this->Turn_model->create($this->turn_payload());
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
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form($turn, 'turns/' . $id . '/update');
		}

		$this->Turn_model->update($id, $this->turn_payload());
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

	protected function form($turn, $action)
	{
		$this->render('turns/form', array(
			'title' => $turn ? t('Edit Turn') : t('Create Turn'),
			'current_section' => 'turns',
			'turn' => $turn,
			'action' => $action,
			'patients' => $this->Patient_model->all(),
			'therapists' => $this->User_model->therapists(),
		));
	}

	protected function validate_form()
	{
		$this->form_validation->set_rules('patient_id', 'Patient', 'required|integer');
		$this->form_validation->set_rules('doctor_id', 'Therapist', 'required|integer');
		$this->form_validation->set_rules('turn_date', 'Turn date', 'required');
		$this->form_validation->set_rules('turn_time', 'Turn time', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
	}

	protected function turn_payload()
	{
		return array(
			'patient_id' => (int) $this->input->post('patient_id'),
			'doctor_id' => (int) $this->input->post('doctor_id'),
			'turn_date' => $this->input->post('turn_date', TRUE),
			'turn_time' => $this->input->post('turn_time', TRUE),
			'status' => $this->input->post('status', TRUE),
			'notes' => $this->input->post('notes', TRUE),
		);
	}
}
