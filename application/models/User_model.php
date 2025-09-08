<?php
class User_model extends CI_Model {

	public function assign_role($user_id, $role_id) {
		$this->db->where('id', $user_id);
		return $this->db->update('users', ['role_id' => $role_id]);
	}

	public function has_permission($user_id, $permission_name) {
		$this->db->select('permissions.id');
		$this->db->from('permissions');
		$this->db->join('role_permissions', 'permissions.id = role_permissions.permission_id');
		$this->db->join('users', 'users.role_id = role_permissions.role_id');
		$this->db->where('users.id', $user_id);
		$this->db->where('permissions.permission_name', $permission_name);
		$query = $this->db->get();
		return $query->num_rows() > 0;
	}
}
