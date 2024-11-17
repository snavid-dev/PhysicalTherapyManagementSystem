<?php

class Auth
{

	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('User_model');
	}

	public function has_permission($permission_name)
	{
		$user_id = $this->CI->session->userdata($this->CI->mylibrary->hash_session('u_id'));
		return $this->CI->User_model->has_permission($user_id, $permission_name);
	}

	public function require_permission($permission_name)
	{
		if (!$this->has_permission($permission_name)) {
			show_error('Access Denied: You do not have the required permission.', 403);
		}
	}
}
