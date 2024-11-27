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

	public function update_role_with_permissions($roleId, $roleName, $categories)
	{
		// Start transaction
		$this->db->trans_start();

		// Update the role name in the roles table
		$this->db->where('id', $roleId);
		$this->db->update('roles', ['role_name' => $roleName]);

		// Delete all existing permissions for the role
		$this->db->where('role_id', $roleId);
		$this->db->delete('role_permissions');

//		print_r($categories);
//		exit();

		// Insert the new permissions for the role
		foreach ($categories as $category) {
			$categoryId = $category['category_id'];
			if (isset($category['permissions'])) {
				$permissions = $category['permissions'];
				if (is_array($permissions) && !empty($permissions)) {
					foreach ($permissions as $permissionId) {
						$this->db->insert('role_permissions', [
							'role_id' => $roleId,
							'permission_id' => $permissionId
						]);
					}
				}
			}

		}

		// Complete the transaction
		$this->db->trans_complete();

		// Check if the transaction was successful
		if ($this->db->trans_status() === false) {
			return ['status' => 'error', 'message' => 'Failed to update role and permissions.'];
		}

		return ['status' => 'success'];
	}

}
