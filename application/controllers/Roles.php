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
			echo json_encode(['status' => 'success', 'message' => 'Role and permissions inserted successfully.']);
		} else {
			echo json_encode(['status' => 'error', 'message' => $result['message']]);
		}
	}
}
