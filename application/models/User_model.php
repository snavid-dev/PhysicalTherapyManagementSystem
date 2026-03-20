<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
	public function all()
	{
		return $this->db
			->select('users.*, roles.name AS role_name')
			->from('users')
			->join('roles', 'roles.id = users.role_id', 'left')
			->order_by('users.first_name', 'asc')
			->order_by('users.last_name', 'asc')
			->get()
			->result_array();
	}

	public function find($id)
	{
		return $this->db
			->select('users.*, roles.name AS role_name')
			->from('users')
			->join('roles', 'roles.id = users.role_id', 'left')
			->where('users.id', (int) $id)
			->get()
			->row_array();
	}

	public function find_by_username($username)
	{
		return $this->db->get_where('users', array('username' => $username))->row_array();
	}

	public function therapists()
	{
		return $this->db
			->select('users.id, users.first_name, users.last_name')
			->from('users')
			->join('roles', 'roles.id = users.role_id', 'left')
			->where('users.is_active', 1)
			->group_start()
				->where('roles.slug', 'therapist')
				->or_where('roles.slug', 'doctor')
			->group_end()
			->order_by('users.first_name', 'asc')
			->get()
			->result_array();
	}

	public function create($data)
	{
		$this->db->insert('users', $data);
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		return $this->db->where('id', (int) $id)->update('users', $data);
	}

	public function delete($id)
	{
		return $this->db->where('id', (int) $id)->delete('users');
	}

	public function has_permission($user_id, $permission_name)
	{
		return $this->db
			->select('permissions.id')
			->from('permissions')
			->join('role_permissions', 'role_permissions.permission_id = permissions.id')
			->join('users', 'users.role_id = role_permissions.role_id')
			->where('users.id', (int) $user_id)
			->where('permissions.name', $permission_name)
			->limit(1)
			->get()
			->num_rows() > 0;
	}
}
