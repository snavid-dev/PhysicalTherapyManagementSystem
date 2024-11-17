<?php

class Roles_model extends CI_Model
{

	public function insert_role_with_permissions($roleName, $categories)
	{
		$this->db->trans_start(); // Start transaction

		try {
			// Step 1: Insert the new role
			$roleData = [
				'role_name' => $roleName,
				'created_at' => date('Y-m-d H:i:s')
			];
			$this->db->insert('roles', $roleData);
			$roleId = $this->db->insert_id(); // Get inserted role ID

			// Step 2: Insert permissions for the role
			foreach ($categories as $category) {
				if (isset($category['permissions']) && is_array($category['permissions'])) {
					foreach ($category['permissions'] as $permissionId) {
						$rolePermissionData = [
							'role_id' => $roleId,
							'permission_id' => $permissionId
						];
						$this->db->insert('role_permissions', $rolePermissionData);
					}
				}
			}

			$this->db->trans_complete(); // Commit transaction

			if ($this->db->trans_status() === FALSE) {
				throw new Exception("Transaction failed.");
			}

			return ['status' => 'success'];
		} catch (Exception $e) {
			$this->db->trans_rollback(); // Rollback transaction
			return ['status' => 'error', 'message' => $e->getMessage()];
		}
	}
}
