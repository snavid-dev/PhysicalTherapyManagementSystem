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
		return $this->db->query("SELECT r.*, COUNT(u.id) AS user_count FROM roles r LEFT JOIN users u ON r.id = u.role_id GROUP BY r.id, r.role_name ORDER BY user_count DESC")->result();
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
		$this->db->select('permissions.*, permission_categories.category_name'); // Include category name
		$this->db->from('permissions');
		$this->db->join('role_permissions', 'permissions.id = role_permissions.permission_id');
		$this->db->join('permission_categories', 'permissions.category_id = permission_categories.id'); // Join with categories
		$this->db->where('role_permissions.role_id', $role_id);
		return $this->db->get()->result();
	}
		public function get_assigned_permissions($role_id)
	{
		// Select permissions and category name
		$this->db->select('permissions.*');
		$this->db->from('permissions');
		$this->db->join('role_permissions', 'permissions.id = role_permissions.permission_id');
		$this->db->where('role_permissions.role_id', $role_id);

		// Execute and return the result
		return $this->db->get()->result();
	}
}
