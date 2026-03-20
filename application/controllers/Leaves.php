<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leaves extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Leave_model');
		$this->load->model('User_model');
	}

	public function index()
	{
		$this->require_permission('manage_leaves');

		$this->render('leaves/index', array(
			'title' => t('Doctor Leaves'),
			'current_section' => 'leaves',
			'leaves' => $this->Leave_model->all(),
		));
	}

	public function create()
	{
		$this->require_permission('manage_leaves');
		$this->form(NULL, 'leaves/store');
	}

	public function store()
	{
		$this->require_permission('manage_leaves');
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form(NULL, 'leaves/store');
		}

		$this->Leave_model->create($this->leave_payload());
		$this->session->set_flashdata('success', t('Leave saved successfully.'));
		redirect('leaves');
	}

	public function edit($id)
	{
		$this->require_permission('manage_leaves');
		$leave = $this->Leave_model->find($id);
		show_404_if_empty($leave);
		$this->form($leave, 'leaves/' . $id . '/update');
	}

	public function update($id)
	{
		$this->require_permission('manage_leaves');
		$leave = $this->Leave_model->find($id);
		show_404_if_empty($leave);
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form($leave, 'leaves/' . $id . '/update');
		}

		$this->Leave_model->update($id, $this->leave_payload());
		$this->session->set_flashdata('success', t('Leave updated successfully.'));
		redirect('leaves');
	}

	public function delete($id)
	{
		$this->require_permission('manage_leaves');
		$leave = $this->Leave_model->find($id);
		show_404_if_empty($leave);

		$this->Leave_model->delete($id);
		$this->session->set_flashdata('success', t('Leave deleted successfully.'));
		redirect('leaves');
	}

	protected function form($leave, $action)
	{
		$this->render('leaves/form', array(
			'title' => $leave ? t('Edit Leave') : t('Create Leave'),
			'current_section' => 'leaves',
			'leave' => $leave,
			'action' => $action,
			'therapists' => $this->User_model->therapists(),
		));
	}

	protected function validate_form()
	{
		$this->form_validation->set_rules('doctor_id', 'Therapist', 'required|integer');
		$this->form_validation->set_rules('start_date', 'Start date', 'required');
		$this->form_validation->set_rules('end_date', 'End date', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
	}

	protected function leave_payload()
	{
		return array(
			'doctor_id' => (int) $this->input->post('doctor_id'),
			'start_date' => $this->input->post('start_date', TRUE),
			'end_date' => $this->input->post('end_date', TRUE),
			'status' => $this->input->post('status', TRUE),
			'reason' => $this->input->post('reason', TRUE),
		);
	}
}
