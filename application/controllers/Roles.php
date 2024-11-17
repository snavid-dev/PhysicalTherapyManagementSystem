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
		// Read raw input stream
		$rawData = file_get_contents("php://input");

		// Decode JSON data into an associative array
		$data = json_decode($rawData, true);
		// Validate data
		if (empty($data['role_name']) || empty($data['categories'])) {
			echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
			return;
		}

		// Get data from AJAX request
		$roleName = $data['role_name'];
		$categories = $data['categories'];


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
