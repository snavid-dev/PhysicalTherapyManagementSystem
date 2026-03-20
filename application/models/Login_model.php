<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model
{
	public function find_by_username($username)
	{
		return $this->db
			->select('users.*, roles.name AS role_name')
			->from('users')
			->join('roles', 'roles.id = users.role_id', 'left')
			->where('users.username', $username)
			->where('users.is_active', 1)
			->get()
			->row_array();
	}
}
