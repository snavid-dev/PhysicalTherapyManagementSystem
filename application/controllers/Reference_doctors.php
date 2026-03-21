<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reference_doctors extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Reference_doctor_model');
	}

	public function index()
	{
		$this->require_permission('manage_reference_doctors');

		$this->render('reference_doctors/index', array(
			'title' => t('Reference Doctors'),
			'current_section' => 'reference_doctors',
			'reference_doctors' => $this->Reference_doctor_model->get_all(),
		));
	}

	public function create()
	{
		$this->require_permission('manage_reference_doctors');
		$this->form(NULL, 'reference_doctors/store');
	}

	public function store()
	{
		$this->require_permission('manage_reference_doctors');
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form(NULL, 'reference_doctors/store');
		}

		$this->Reference_doctor_model->create($this->doctor_payload());
		$this->session->set_flashdata('success', t('Reference doctor created successfully.'));
		redirect('reference_doctors');
	}

	public function edit($id)
	{
		$this->require_permission('manage_reference_doctors');
		$reference_doctor = $this->Reference_doctor_model->get_by_id($id);
		show_404_if_empty($reference_doctor);

		$this->form($reference_doctor, 'reference_doctors/update/' . $id);
	}

	public function update($id)
	{
		$this->require_permission('manage_reference_doctors');
		$reference_doctor = $this->Reference_doctor_model->get_by_id($id);
		show_404_if_empty($reference_doctor);
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form($reference_doctor, 'reference_doctors/update/' . $id);
		}

		$this->Reference_doctor_model->update($id, $this->doctor_payload());
		$this->session->set_flashdata('success', t('Reference doctor updated successfully.'));
		redirect('reference_doctors/profile/' . $id);
	}

	public function delete($id)
	{
		$this->require_permission('manage_reference_doctors');
		$reference_doctor = $this->Reference_doctor_model->get_by_id($id);
		show_404_if_empty($reference_doctor);

		$this->Reference_doctor_model->set_inactive($id);
		$this->session->set_flashdata('success', t('Reference doctor deactivated successfully.'));
		redirect('reference_doctors');
	}

	public function activate($id)
	{
		$this->require_permission('manage_reference_doctors');
		$reference_doctor = $this->Reference_doctor_model->get_by_id($id);
		show_404_if_empty($reference_doctor);

		$this->Reference_doctor_model->set_active($id);
		$this->session->set_flashdata('success', t('Reference doctor activated successfully.'));
		redirect('reference_doctors');
	}

	public function profile($id)
	{
		$this->require_permission('manage_reference_doctors');
		$reference_doctor = $this->Reference_doctor_model->get_by_id($id);
		show_404_if_empty($reference_doctor);

		$this->render('reference_doctors/profile', array(
			'title' => t('Reference Doctor Profile'),
			'current_section' => 'reference_doctors',
			'reference_doctor' => $reference_doctor,
			'all_patients' => $this->Reference_doctor_model->get_referred_patients($id, '1000-01-01 00:00:00', '9999-12-31 23:59:59'),
		));
	}

	public function patient_count($id)
	{
		$this->require_permission('manage_reference_doctors');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Access denied.', 403);
		}

		$reference_doctor = $this->Reference_doctor_model->get_by_id($id);
		show_404_if_empty($reference_doctor);

		$date_from = $this->input->post('date_from', TRUE);
		$date_to = $this->input->post('date_to', TRUE);

		if (!$date_from || !$date_to) {
			return $this->json_error(t('Both date fields are required.'), 422);
		}

		if (!$this->is_valid_date($date_from) || !$this->is_valid_date($date_to) || $date_from > $date_to) {
			return $this->json_error(t('Please choose a valid date range.'), 422);
		}

		$date_from_start = $date_from . ' 00:00:00';
		$date_to_end = $date_to . ' 23:59:59';
		$patients = $this->Reference_doctor_model->get_referred_patients($id, $date_from_start, $date_to_end);
		$count = $this->Reference_doctor_model->count_referred_patients($id, $date_from_start, $date_to_end);

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => TRUE,
				'count' => $count,
				'date_from' => $date_from,
				'date_to' => $date_to,
				'patients' => $patients,
			)));
	}

	protected function form($reference_doctor, $action)
	{
		$this->render('reference_doctors/form', array(
			'title' => $reference_doctor ? t('Edit Reference Doctor') : t('Create Reference Doctor'),
			'current_section' => 'reference_doctors',
			'reference_doctor' => $reference_doctor,
			'action' => $action,
		));
	}

	protected function validate_form()
	{
		$this->form_validation->set_rules('first_name', 'First name', 'required|trim');
		$this->form_validation->set_rules('last_name', 'Last name', 'required|trim');
		$this->form_validation->set_rules('phone', 'Phone', 'trim');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');
	}

	protected function doctor_payload()
	{
		return array(
			'first_name' => $this->input->post('first_name', TRUE),
			'last_name' => $this->input->post('last_name', TRUE),
			'specialty' => $this->null_if_empty($this->input->post('specialty', TRUE)),
			'phone' => $this->null_if_empty($this->input->post('phone', TRUE)),
			'clinic_name' => $this->null_if_empty($this->input->post('clinic_name', TRUE)),
			'address' => $this->null_if_empty($this->input->post('address', TRUE)),
			'notes' => $this->null_if_empty($this->input->post('notes', TRUE)),
			'status' => $this->input->post('status', TRUE) ?: 'active',
		);
	}

	protected function null_if_empty($value)
	{
		$value = trim((string) $value);
		return $value === '' ? NULL : $value;
	}

	protected function is_valid_date($value)
	{
		$date = DateTime::createFromFormat('Y-m-d', (string) $value);
		return $date && $date->format('Y-m-d') === $value;
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
