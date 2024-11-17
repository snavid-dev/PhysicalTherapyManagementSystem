<?php

class Permission_model extends CI_Model
{

	public function create_category($category_name)
	{
		return $this->db->insert('permission_categories', ['category_name' => $category_name]);
	}

	public function create_permission($permission_name, $category_id)
	{
		return $this->db->insert('permissions', [
			'permission_name' => $permission_name,
			'category_id' => $category_id
		]);
	}

	public function get_permissions_by_category($category_id)
	{
		return $this->db->get_where('permissions', ['category_id' => $category_id])->result();
	}

	public function get_all_categories()
	{
		return $this->db->get('permission_categories')->result();
	}
}
