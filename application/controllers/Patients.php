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
			'turns' => $this->Patient_model->turn_history($id),
			'payments' => $this->Patient_model->payment_history($id),
		));
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
}
