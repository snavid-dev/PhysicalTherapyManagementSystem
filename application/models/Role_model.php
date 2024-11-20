<?php

class Role_model extends CI_Model
{

	public function create_role($role_name)
	{
		$data = ['role_name' => $role_name];
		return $this->db->insert('roles', $data);
	}

	public function get_all_roles()
	{
		return $this->db->get('roles')->result();
	}

	public function get_role($id)
	{
		return $this->db->get_where('roles', array('id' => $id))->result();
	}

	public function assign_permission($role_id, $permission_id)
	{
		return $this->db->insert('role_permissions', [
			'role_id' => $role_id,
			'permission_id' => $permission_id
		]);
	}

	public function get_permissions_for_role($role_id)
	{
		$this->db->select('permissions.*');
		$this->db->from('permissions');
		$this->db->join('role_permissions', 'permissions.id = role_permissions.permission_id');
		$this->db->where('role_permissions.role_id', $role_id);
		return $this->db->get()->result();
	}
}
