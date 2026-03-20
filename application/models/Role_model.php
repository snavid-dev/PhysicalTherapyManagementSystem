<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends CI_Model
{
	protected $system_permissions = array(
		array('name' => 'manage_patients', 'module_key' => 'patients'),
		array('name' => 'manage_users', 'module_key' => 'users'),
		array('name' => 'manage_roles', 'module_key' => 'roles'),
		array('name' => 'manage_turns', 'module_key' => 'turns'),
		array('name' => 'manage_payments', 'module_key' => 'payments'),
		array('name' => 'view_reports', 'module_key' => 'reports'),
		array('name' => 'manage_leaves', 'module_key' => 'leaves'),
		array('name' => 'manage_staff', 'module_key' => 'staff'),
	);

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
		$this->ensure_system_permissions();

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

	protected function ensure_system_permissions()
	{
		$existing = $this->db
			->select('name')
			->get('permissions')
			->result_array();

		$existing_names = array_column($existing, 'name');

		foreach ($this->system_permissions as $permission) {
			if (in_array($permission['name'], $existing_names, TRUE)) {
				continue;
			}

			$this->db->insert('permissions', $permission);
			$existing_names[] = $permission['name'];
		}
	}
}
