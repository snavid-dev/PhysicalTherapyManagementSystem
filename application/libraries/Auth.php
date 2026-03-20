<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth
{
	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('User_model');
	}

	public function check()
	{
		return (bool) $this->CI->session->userdata('logged_in');
	}

	public function user_id()
	{
		return (int) $this->CI->session->userdata('user_id');
	}

	public function user()
	{
		if (!$this->check()) {
			return null;
		}

		return $this->CI->User_model->find($this->user_id());
	}

	public function login(array $user)
	{
		$this->CI->session->set_userdata(array(
			'logged_in' => TRUE,
			'user_id' => (int) $user['id'],
			'user_name' => trim($user['first_name'] . ' ' . $user['last_name']),
			'role_id' => (int) $user['role_id'],
			'role_name' => $user['role_name'],
		));
	}

	public function logout()
	{
		$this->CI->session->unset_userdata(array(
			'logged_in',
			'user_id',
			'user_name',
			'role_id',
			'role_name',
		));
	}

	public function has_permission($permission_name)
	{
		if (!$this->check()) {
			return FALSE;
		}

		return $this->CI->User_model->has_permission($this->user_id(), $permission_name);
	}

	public function require_permission($permission_name)
	{
		if (!$this->has_permission($permission_name)) {
			show_error('Access denied.', 403);
		}
	}
}
