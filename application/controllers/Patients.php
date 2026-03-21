<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patients extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Patient_model');
	}

	public function index()
	{
		$this->require_permission('manage_patients');

		$this->render('patients/index', array(
			'title' => t('Patients'),
			'current_section' => 'patients',
			'patients' => $this->Patient_model->all(),
		));
	}

	public function create()
	{
		$this->require_permission('manage_patients');
		$this->form(NULL, 'patients/store');
	}

	public function store()
	{
		$this->require_permission('manage_patients');
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form(NULL, 'patients/store');
		}

		$this->Patient_model->create($this->patient_payload());
		$this->session->set_flashdata('success', t('Patient created successfully.'));
		redirect('patients');
	}

	public function show($id)
	{
		$this->require_permission('manage_patients');
		$patient = $this->Patient_model->find($id);
		show_404_if_empty($patient);

		$this->render('patients/show', array(
			'title' => t('Patient Profile'),
			'current_section' => 'patients',
			'patient' => $patient,
			'turns' => $this->Patient_model->turn_history($id),
			'payments' => $this->Patient_model->payment_history($id),
		));
	}

	public function edit($id)
	{
		$this->require_permission('manage_patients');
		$patient = $this->Patient_model->find($id);
		show_404_if_empty($patient);
		$this->form($patient, 'patients/' . $id . '/update');
	}

	public function update($id)
	{
		$this->require_permission('manage_patients');
		$patient = $this->Patient_model->find($id);
		show_404_if_empty($patient);
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form($patient, 'patients/' . $id . '/update');
		}

		$this->Patient_model->update($id, $this->patient_payload());
		$this->session->set_flashdata('success', t('Patient updated successfully.'));
		redirect('patients/' . $id);
	}

	public function delete($id)
	{
		$this->require_permission('manage_patients');
		$patient = $this->Patient_model->find($id);
		show_404_if_empty($patient);

		$this->Patient_model->delete($id);
		$this->session->set_flashdata('success', t('Patient deleted successfully.'));
		redirect('patients');
	}

	protected function form($patient, $action)
	{
		$this->render('patients/form', array(
			'title' => $patient ? t('Edit Patient') : t('Create Patient'),
			'current_section' => 'patients',
			'patient' => $patient,
			'action' => $action,
			'reference_doctors' => $this->Patient_model->get_active_reference_doctors(),
		));
	}

	protected function validate_form()
	{
		$this->form_validation->set_rules('first_name', 'First name', 'required|trim');
		$this->form_validation->set_rules('last_name', 'Last name', 'required|trim');
		$this->form_validation->set_rules('phone', 'Phone', 'trim');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
	}

	protected function patient_payload()
	{
		$referred_by = $this->input->post('referred_by', TRUE);

		return array(
			'first_name' => $this->input->post('first_name', TRUE),
			'last_name' => $this->input->post('last_name', TRUE),
			'gender' => $this->input->post('gender', TRUE),
			'date_of_birth' => $this->input->post('date_of_birth', TRUE) ?: NULL,
			'phone' => $this->input->post('phone', TRUE),
			'email' => $this->input->post('email', TRUE),
			'address' => $this->input->post('address', TRUE),
			'medical_notes' => $this->input->post('medical_notes', TRUE),
			'referred_by' => $referred_by === '' || $referred_by === NULL ? NULL : (int) $referred_by,
		);
	}
}
