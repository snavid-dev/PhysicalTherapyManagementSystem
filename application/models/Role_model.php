<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends CI_Model
{
	public function all()
	{
		return $this->db
			->select('roles.*, COUNT(users.id) AS user_count')
			->from('roles')
			->join('users', 'users.role_id = roles.id', 'left')
			->group_by('roles.id')
			->order_by('roles.name', 'asc')
			->get()
			->result_array();
	}

	public function find($id)
	{
		return $this->db->get_where('roles', array('id' => (int) $id))->row_array();
	}

	public function permissions()
	{
		return $this->db->order_by('module_key', 'asc')->order_by('name', 'asc')->get('permissions')->result_array();
	}

	public function role_permission_ids($role_id)
	{
		$rows = $this->db->select('permission_id')->get_where('role_permissions', array('role_id' => (int) $role_id))->result_array();
		return array_map('intval', array_column($rows, 'permission_id'));
	}

	public function create($data, $permission_ids)
	{
		$this->db->insert('roles', $data);
		$role_id = $this->db->insert_id();
		$this->sync_permissions($role_id, $permission_ids);
		return $role_id;
	}

	public function update($id, $data, $permission_ids)
	{
		$this->db->where('id', (int) $id)->update('roles', $data);
		$this->sync_permissions($id, $permission_ids);
		return TRUE;
	}

	public function delete($id)
	{
		$assigned_users = (int) $this->db->where('role_id', (int) $id)->count_all_results('users');
		if ($assigned_users > 0) {
			return FALSE;
		}

		$this->db->where('role_id', (int) $id)->delete('role_permissions');
		return $this->db->where('id', (int) $id)->delete('roles');
	}

	protected function sync_permissions($role_id, $permission_ids)
	{
		$this->db->where('role_id', (int) $role_id)->delete('role_permissions');

		foreach ((array) $permission_ids as $permission_id) {
			$this->db->insert('role_permissions', array(
				'role_id' => (int) $role_id,
				'permission_id' => (int) $permission_id,
			));
		}
	}
}
