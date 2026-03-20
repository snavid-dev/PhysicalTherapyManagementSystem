<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sections extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Section_model');
	}

	public function index()
	{
		$this->require_permission('manage_sections');

		$this->render('sections/index', array(
			'title' => t('sections'),
			'current_section' => 'sections',
			'sections' => $this->Section_model->get_all(),
		));
	}

	public function create()
	{
		$this->require_permission('manage_sections');
		$this->form(NULL, 'sections/store');
	}

	public function show($id)
	{
		$this->require_permission('manage_sections');
		$section = $this->Section_model->get_by_id($id);
		show_404_if_empty($section);

		$this->render('sections/show', array(
			'title' => t('Section Staff Chart'),
			'current_section' => 'sections',
			'section' => $section,
			'staff_members' => $this->Section_model->get_staff_by_section($id),
			'staff_type_counts' => $this->Section_model->get_staff_type_counts($id),
		));
	}

	public function store()
	{
		$this->require_permission('manage_sections');
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form(NULL, 'sections/store');
		}

		$this->Section_model->create($this->section_payload());
		$this->session->set_flashdata('success', t('Section created successfully.'));
		redirect('sections');
	}

	public function edit($id)
	{
		$this->require_permission('manage_sections');
		$section = $this->Section_model->get_by_id($id);
		show_404_if_empty($section);
		$this->form($section, 'sections/' . $id . '/update');
	}

	public function update($id)
	{
		$this->require_permission('manage_sections');
		$section = $this->Section_model->get_by_id($id);
		show_404_if_empty($section);
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form($section, 'sections/' . $id . '/update');
		}

		$this->Section_model->update($id, $this->section_payload());
		$this->session->set_flashdata('success', t('Section updated successfully.'));
		redirect('sections');
	}

	public function delete($id)
	{
		$this->require_permission('manage_sections');
		$section = $this->Section_model->get_by_id($id);
		show_404_if_empty($section);

		$this->Section_model->delete($id);
		$this->session->set_flashdata('success', t('Section deleted successfully.'));
		redirect('sections');
	}

	protected function form($section, $action)
	{
		$this->render('sections/form', array(
			'title' => $section ? t('Edit Section') : t('Create Section'),
			'current_section' => 'sections',
			'section' => $section,
			'action' => $action,
		));
	}

	protected function validate_form()
	{
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('default_fee', 'Default Fee', 'required|numeric|greater_than_equal_to[0]');
	}

	protected function section_payload()
	{
		return array(
			'name' => $this->input->post('name', TRUE),
			'default_fee' => round((float) $this->input->post('default_fee'), 2),
		);
	}
}
