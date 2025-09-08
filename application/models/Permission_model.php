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


	public function get_permissions_with_categories()
	{
		// Fetch category categories with their permissions
		$this->db->select('pc.id as category_id, pc.category_name, p.id as permission_id, p.permission_name');
		$this->db->from('permission_categories pc');
		$this->db->join('permissions p', 'p.category_id = pc.id', 'left');
		$this->db->order_by('pc.id, p.id'); // Sort by category and category IDs
		$query = $this->db->get();

		$result = $query->result_array();

		// Organize the results into a nested array
		$permissions = [];
		foreach ($result as $row) {
			$category_id = $row['category_id'];
			$category_name = $row['category_name'];

			// Initialize category if not already added
			if (!isset($permissions[$category_id])) {
				$permissions[$category_id] = [
					'category_id' => $category_id,
					'category_name' => $category_name,
					'permissions' => []
				];
			}

			// Add the category to the category
			if (!empty($row['permission_id'])) {
				$permissions[$category_id]['permissions'][] = [
					'permission_id' => $row['permission_id'],
					'permission_name' => $row['permission_name']
				];
			}
		}

		// Reset array indexing
		return array_values($permissions);
	}

}
