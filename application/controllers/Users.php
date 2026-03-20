<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Role_model');
	}

	public function index()
	{
		$this->require_permission('manage_users');

		$this->render('users/index', array(
			'title' => t('Users'),
			'current_section' => 'users',
			'users' => $this->User_model->all(),
		));
	}

	public function create()
	{
		$this->require_permission('manage_users');
		$this->form(NULL, 'users/store');
	}

	public function store()
	{
		$this->require_permission('manage_users');
		$this->validate_form(TRUE);

		if (!$this->form_validation->run()) {
			return $this->form(NULL, 'users/store');
		}

		$data = $this->user_payload();
		$data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);

		$this->User_model->create($data);
		$this->session->set_flashdata('success', t('User created successfully.'));
		redirect('users');
	}

	public function edit($id)
	{
		$this->require_permission('manage_users');
		$user = $this->User_model->find($id);
		show_404_if_empty($user);
		$this->form($user, 'users/' . $id . '/update');
	}

	public function update($id)
	{
		$this->require_permission('manage_users');
		$user = $this->User_model->find($id);
		show_404_if_empty($user);
		$this->validate_form(FALSE, $id);

		if (!$this->form_validation->run()) {
			return $this->form($user, 'users/' . $id . '/update');
		}

		$data = $this->user_payload();
		if ($this->input->post('password')) {
			$data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
		}

		$this->User_model->update($id, $data);
		$this->session->set_flashdata('success', t('User updated successfully.'));
		redirect('users');
	}

	public function delete($id)
	{
		$this->require_permission('manage_users');
		$user = $this->User_model->find($id);
		show_404_if_empty($user);

		if ((int) $this->auth->user_id() === (int) $id) {
			$this->session->set_flashdata('error', t('You cannot delete the current signed-in user.'));
			redirect('users');
		}

		$this->User_model->delete($id);
		$this->session->set_flashdata('success', t('User deleted successfully.'));
		redirect('users');
	}

	protected function form($user, $action)
	{
		$this->render('users/form', array(
			'title' => $user ? t('Edit User') : t('Create User'),
			'current_section' => 'users',
			'user' => $user,
			'roles' => $this->Role_model->all(),
			'action' => $action,
		));
	}

	protected function validate_form($is_create, $id = NULL)
	{
		$username = $this->input->post('username', TRUE);
		$existing = $username ? $this->User_model->find_by_username($username) : NULL;

		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('first_name', 'First name', 'required|trim');
		$this->form_validation->set_rules('last_name', 'Last name', 'required|trim');
		$this->form_validation->set_rules('role_id', 'Role', 'required|integer');

		if (($is_create && $existing) || (!$is_create && $existing && (int) $existing['id'] !== (int) $id)) {
			$this->form_validation->set_rules('username', 'Username', 'callback__username_taken');
		}

		if ($is_create) {
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
		} else {
			$this->form_validation->set_rules('password', 'Password', 'min_length[6]');
		}
	}

	public function _username_taken()
	{
		$this->form_validation->set_message('_username_taken', 'This username is already in use.');
		return FALSE;
	}

	protected function user_payload()
	{
		return array(
			'first_name' => $this->input->post('first_name', TRUE),
			'last_name' => $this->input->post('last_name', TRUE),
			'username' => $this->input->post('username', TRUE),
			'email' => $this->input->post('email', TRUE),
			'phone' => $this->input->post('phone', TRUE),
			'role_id' => (int) $this->input->post('role_id'),
			'is_active' => $this->input->post('is_active') ? 1 : 0,
		);
	}
}
