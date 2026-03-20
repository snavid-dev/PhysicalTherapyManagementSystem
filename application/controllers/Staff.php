<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Staff_model');
		$this->load->model('User_model');
	}

	public function index()
	{
		$this->require_permission('manage_staff');

		$this->render('staff/index', array(
			'title' => t('staff'),
			'current_section' => 'staff',
			'staff_members' => $this->Staff_model->get_all(),
		));
	}

	public function create()
	{
		$this->require_permission('manage_staff');
		$this->form(NULL, 'staff/store');
	}

	public function store()
	{
		$this->require_permission('manage_staff');
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form(NULL, 'staff/store');
		}

		$this->Staff_model->create($this->staff_payload(NULL));
		$this->session->set_flashdata('success', t('Staff created successfully.'));
		redirect('staff');
	}

	public function edit($id)
	{
		$this->require_permission('manage_staff');
		$staff = $this->Staff_model->get_by_id($id);
		show_404_if_empty($staff);
		$this->form($staff, 'staff/update/' . $id);
	}

	public function update($id)
	{
		$this->require_permission('manage_staff');
		$staff = $this->Staff_model->get_by_id($id);
		show_404_if_empty($staff);
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form($staff, 'staff/update/' . $id);
		}

		$this->Staff_model->update($id, $this->staff_payload($staff));
		$this->session->set_flashdata('success', t('Staff updated successfully.'));
		redirect('staff');
	}

	public function delete($id)
	{
		$this->require_permission('manage_staff');
		$staff = $this->Staff_model->get_by_id($id);
		show_404_if_empty($staff);

		$this->Staff_model->set_inactive($id);
		$this->session->set_flashdata('success', t('Staff deactivated successfully.'));
		redirect('staff');
	}

	public function profile($id)
	{
		$this->require_permission('manage_staff');
		$staff = $this->Staff_model->get_by_id($id);
		show_404_if_empty($staff);

		$this->render('staff/profile', array(
			'title' => t('staff_profile'),
			'current_section' => 'staff',
			'staff' => $staff,
			'patients_last_month' => $this->Staff_model->count_patients_last_month($id),
			'show_section' => $this->requires_section($staff['staff_type_name']),
		));
	}

	public function calculate_salary($id)
	{
		$this->require_permission('manage_staff');

		if (strtolower($this->input->method()) !== 'post') {
			show_error('Method Not Allowed', 405);
		}

		$staff = $this->Staff_model->get_by_id($id);
		show_404_if_empty($staff);

		$from_date = $this->input->post('from_date', TRUE) ?: date('Y-m-01');
		$to_date = $this->input->post('to_date', TRUE) ?: date('Y-m-t');

		if (!$this->is_valid_date($from_date) || !$this->is_valid_date($to_date) || $from_date > $to_date) {
			return $this->output
				->set_status_header(422)
				->set_content_type('application/json')
				->set_output(json_encode(array(
					'error' => t('Please choose a valid date range.'),
				)));
		}

		$approved_leaves = $this->Staff_model->get_approved_leaves_in_range($id, $from_date, $to_date);
		$monthly_leave_quota = (int) $staff['monthly_leave_quota'];
		$paid_leaves = min($approved_leaves, $monthly_leave_quota);
		$excess_leaves = max(0, $approved_leaves - $monthly_leave_quota);

		$response = array(
			'from_date' => $from_date,
			'to_date' => $to_date,
			'base_salary' => (float) $staff['salary'],
			'monthly_leave_quota' => $monthly_leave_quota,
			'approved_leaves' => $approved_leaves,
			'paid_leaves' => $paid_leaves,
			'excess_leaves' => $excess_leaves,
			'deduction' => 0,
			'final_salary' => NULL,
		);

		$daily_rate = ((float) $staff['salary']) / 30;
		$deduction = round($excess_leaves * $daily_rate, 2);
		$final_salary = round(((float) $staff['salary']) - $deduction, 2);

		$response['deduction'] = $deduction;
		$response['final_salary'] = $final_salary;

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}

	protected function form($staff, $action)
	{
		$this->render('staff/form', array(
			'title' => $staff ? t('Edit Staff') : t('Create Staff'),
			'current_section' => 'staff',
			'staff' => $staff,
			'action' => $action,
			'staff_types' => $this->Staff_model->get_staff_types(),
			'users' => $this->User_model->all(),
		));
	}

	protected function validate_form()
	{
		$this->form_validation->set_rules('first_name', 'First name', 'required|trim');
		$this->form_validation->set_rules('last_name', 'Last name', 'required|trim');
		$this->form_validation->set_rules('gender', 'Gender', 'required|in_list[male,female]');
		$this->form_validation->set_rules('staff_type_id', 'Staff type', 'required|integer|callback__valid_staff_type');
		$this->form_validation->set_rules('section', 'Section', 'callback__valid_section');
		$this->form_validation->set_rules('monthly_leave_quota', 'Monthly leave quota', 'required|integer|greater_than_equal_to[0]');
		$this->form_validation->set_rules('salary', 'Salary', 'required|numeric|greater_than_equal_to[0]');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');
		$this->form_validation->set_rules('user_id', 'Linked user', 'callback__valid_user_id');
	}

	public function _valid_staff_type($value)
	{
		if ($this->find_staff_type($value)) {
			return TRUE;
		}

		$this->form_validation->set_message('_valid_staff_type', t('Invalid staff type selected.'));
		return FALSE;
	}

	public function _valid_section($value)
	{
		$staff_type = $this->find_staff_type($this->input->post('staff_type_id'));

		if (!$staff_type) {
			return TRUE;
		}

		if (!$this->requires_section($staff_type['name'])) {
			return TRUE;
		}

		if (in_array($value, array('male', 'female', 'both'), TRUE)) {
			return TRUE;
		}

		$this->form_validation->set_message('_valid_section', t('Section is required for the selected staff type.'));
		return FALSE;
	}

	public function _valid_user_id($value)
	{
		if ($value === '' || $value === NULL) {
			return TRUE;
		}

		if ($this->User_model->find((int) $value)) {
			return TRUE;
		}

		$this->form_validation->set_message('_valid_user_id', t('Invalid linked user selected.'));
		return FALSE;
	}

	protected function staff_payload($existing_staff = NULL)
	{
		$staff_type = $this->find_staff_type($this->input->post('staff_type_id'));
		$requires_section = $staff_type ? $this->requires_section($staff_type['name']) : FALSE;
		$user_id = $this->resolve_linked_user_id($existing_staff);

		return array(
			'user_id' => $user_id,
			'staff_type_id' => (int) $this->input->post('staff_type_id'),
			'first_name' => $this->input->post('first_name', TRUE),
			'last_name' => $this->input->post('last_name', TRUE),
			'gender' => $this->input->post('gender', TRUE),
			'section' => $requires_section ? $this->input->post('section', TRUE) : 'na',
			'monthly_leave_quota' => (int) $this->input->post('monthly_leave_quota'),
			'salary' => round((float) $this->input->post('salary'), 2),
			'salary_type' => 'fixed',
			'status' => $this->input->post('status', TRUE),
		);
	}

	protected function find_staff_type($id)
	{
		foreach ($this->Staff_model->get_staff_types() as $staff_type) {
			if ((int) $staff_type['id'] === (int) $id) {
				return $staff_type;
			}
		}

		return NULL;
	}

	protected function requires_section($staff_type_name)
	{
		$staff_type_name = strtolower(trim((string) $staff_type_name));
		return in_array($staff_type_name, array('doctor', 'physiotherapist', 'helper', 'intern', 'cleaner'), TRUE);
	}

	protected function is_valid_date($value)
	{
		$date = DateTime::createFromFormat('Y-m-d', (string) $value);
		return $date && $date->format('Y-m-d') === $value;
	}

	protected function resolve_linked_user_id($existing_staff = NULL)
	{
		$selected_user_id = (int) $this->input->post('user_id');

		if ($selected_user_id > 0) {
			return $selected_user_id;
		}

		return (int) $this->User_model->create($this->generated_user_payload());
	}

	protected function generated_user_payload()
	{
		return array(
			'first_name' => $this->input->post('first_name', TRUE),
			'last_name' => $this->input->post('last_name', TRUE),
			'username' => $this->generate_username(
				$this->input->post('first_name', TRUE),
				$this->input->post('last_name', TRUE)
			),
			'email' => NULL,
			'phone' => NULL,
			'role_id' => $this->User_model->default_staff_role_id(),
			'is_active' => 0,
			'password' => password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT),
		);
	}

	protected function generate_username($first_name, $last_name)
	{
		$base = strtolower(trim($first_name . '.' . $last_name));
		$base = preg_replace('/[^a-z0-9]+/', '.', $base);
		$base = trim(preg_replace('/\.+/', '.', $base), '.');

		if ($base === '') {
			$base = 'staff.user';
		}

		$username = $base;
		$counter = 1;

		while ($this->User_model->find_by_username($username)) {
			$username = $base . '.' . $counter;
			$counter++;
		}

		return $username;
	}
}
