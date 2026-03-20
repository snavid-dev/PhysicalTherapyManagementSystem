<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Role_model');
	}

	public function index()
	{
		$this->require_permission('manage_roles');

		$this->render('roles/index', array(
			'title' => t('Roles'),
			'current_section' => 'roles',
			'roles' => $this->Role_model->all(),
		));
	}

	public function create()
	{
		$this->require_permission('manage_roles');
		$this->form(NULL, $this->Role_model->permissions(), array(), 'roles/store');
	}

	public function store()
	{
		$this->require_permission('manage_roles');
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form(NULL, $this->Role_model->permissions(), $this->input->post('permissions') ?: array(), 'roles/store');
		}

		$this->Role_model->create($this->role_payload(), $this->input->post('permissions') ?: array());
		$this->session->set_flashdata('success', t('Role created successfully.'));
		redirect('roles');
	}

	public function edit($id)
	{
		$this->require_permission('manage_roles');
		$role = $this->Role_model->find($id);
		show_404_if_empty($role);

		$this->form($role, $this->Role_model->permissions(), $this->Role_model->role_permission_ids($id), 'roles/' . $id . '/update');
	}

	public function update($id)
	{
		$this->require_permission('manage_roles');
		$role = $this->Role_model->find($id);
		show_404_if_empty($role);
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form($role, $this->Role_model->permissions(), $this->input->post('permissions') ?: array(), 'roles/' . $id . '/update');
		}

		$this->Role_model->update($id, $this->role_payload(), $this->input->post('permissions') ?: array());
		$this->session->set_flashdata('success', t('Role updated successfully.'));
		redirect('roles');
	}

	public function delete($id)
	{
		$this->require_permission('manage_roles');
		$role = $this->Role_model->find($id);
		show_404_if_empty($role);

		if ((int) $role['id'] === 1) {
			$this->session->set_flashdata('error', t('The administrator role cannot be deleted.'));
			redirect('roles');
		}

		if (!$this->Role_model->delete($id)) {
			$this->session->set_flashdata('error', t('This role is assigned to existing users and cannot be deleted.'));
			redirect('roles');
		}

		$this->session->set_flashdata('success', t('Role deleted successfully.'));
		redirect('roles');
	}

	protected function form($role, $permissions, $selected_permissions, $action)
	{
		$this->render('roles/form', array(
			'title' => $role ? t('Edit Role') : t('Create Role'),
			'current_section' => 'roles',
			'role' => $role,
			'permissions' => $permissions,
			'selected_permissions' => array_map('intval', $selected_permissions),
			'action' => $action,
		));
	}

	protected function validate_form()
	{
		$this->form_validation->set_rules('name', 'Role name', 'required|trim');
		$this->form_validation->set_rules('slug', 'Role slug', 'required|trim|alpha_dash');
	}

	protected function role_payload()
	{
		return array(
			'name' => $this->input->post('name', TRUE),
			'slug' => $this->input->post('slug', TRUE),
		);
	}
}
