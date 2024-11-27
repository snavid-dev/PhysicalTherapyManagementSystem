<?php

class Roles extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Roles_model');
	}

	public function insert_role()
	{
		// Get data from AJAX request
		$roleName = $this->input->post('role_name');
		$categories = $this->input->post('categories'); // This is expected to be an array

		// Validate inputs
		if (empty($roleName) || empty($categories)) {
			echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
			return;
		}

		// Insert role and permissions
		$result = $this->Roles_model->insert_role_with_permissions($roleName, $categories);

		// Send response
		if ($result['status'] === 'success') {
			echo json_encode(['status' => 'success', 'message' => 'Hello Navid', 'title' => $this->language->languages('success', $_COOKIE['language'])]);
		} else {
			echo json_encode(['status' => 'error', 'message' => $result['message'], 'title' => $this->language->languages('error', $_COOKIE['language'])]);
		}
	}

	public function update_role()
	{
		// Get data from AJAX request
		$roleId = $this->input->post('role_id'); // Role ID to be updated
		$roleName = $this->input->post('role_name'); // Updated role name
		$categories = $this->input->post('categories'); // Updated permissions categories (array)

		// Validate inputs
		if (empty($roleId) || empty($roleName) || empty($categories)) {
			echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
			return;
		}

		// Update role and permissions
		$result = $this->Roles_model->update_role_with_permissions($roleId, $roleName, $categories);

		// Send response
		if ($result['status'] === 'success') {
			echo json_encode(['status' => 'success', 'message' => 'Role updated successfully.', 'title' => $this->language->languages('success', $_COOKIE['language'])]);
		} else {
			echo json_encode(['status' => 'error', 'message' => $result['message'], 'title' => $this->language->languages('error', $_COOKIE['language'])]);
		}
	}

}
