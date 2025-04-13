<?php

class Admin extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->language->startup('fa');
		$this->load->model('Admin_model');
		if (!$this->session->userdata($this->mylibrary->hash_session('logged_in'))) {
			$this->session->set_flashdata('er_msg', 'برای دسترسی به صفحه درخواست شده لطفا وارد شوید!');
			redirect(base_url());
		}
		$user = $this->Admin_model->single_user($this->session->userdata($this->mylibrary->hash_session('u_id')));


		if (count($user) == 0 || ucwords($user[0]['status']) == 'P') {
			$this->session->set_userdata($this->mylibrary->hash_session('logged_in'), false);
			$this->session->set_flashdata('er_msg', 'برای دسترسی به صفحه درخواست شده لطفا وارد شوید!');
			redirect(base_url());
			exit();
		}

		if ($user[0]['password'] != $this->session->userdata($this->mylibrary->hash_session('u_pass'))) {
			$this->session->set_userdata($this->mylibrary->hash_session('logged_in'), false);
			$this->session->set_flashdata('er_msg', 'برای دسترسی به صفحه درخواست شده لطفا وارد شوید!');
			redirect(base_url());
			exit();
		}
		date_default_timezone_set('Asia/Kabul');
	}


	public function switcher()
	{
		$data['title'] = $this->lang('switcher');
		$data['page'] = 'switcher';
		$this->load->view('header', $data);
		$this->load->view('switcher', $data);
		$this->load->view('footer', $data);
	}

	public function language($lang = 'en')
	{
		$this->language->change($lang);
	}

	public function lang($key)
	{
		return $this->language->languages($key, $_COOKIE['language']);
	}

	public function index()
	{
		$this->auth->has_permission('View Appointments');
		$data['title'] = $this->lang('home');;
		$data['page'] = "home";
		$data['temp_patients'] = $this->Admin_model->get_temp_patients_extra();
		$data['patients'] = $this->Admin_model->get_patients();
		$data['doctors'] = $this->Admin_model->get_doctors();
		$data['accounts'] = $this->Admin_model->get_accounts();
		$data['sum_income'] = $this->Admin_model->find_sum_price_tooth($this->mylibrary->getCurrentShamsiDate()['date'])[0]['sum_price'];
		$data['sum_paid'] = $this->Admin_model->find_sum_paid_turn($this->mylibrary->getCurrentShamsiDate()['date'])[0]['sum_cr'];
		$data['sum_expenses'] = $this->Admin_model->find_sum_dr_balance_sheet($this->mylibrary->getCurrentShamsiDate()['date'])[0]['sum_dr'];
		$data['script'] = $this->mylibrary->generateSelect2();
		$data['prescriptions'] = $this->Admin_model->list_prescription_samples('sample');
		$data['medicines'] = $this->Admin_model->get_medicines();
//		$data['script_single_patient_assets'] = ['assets/js/home.js'];
		$data['turns'] = $this->Admin_model->get_turns($this->mylibrary->getCurrentShamsiDate()['date']);
		$this->load->view('header', $data);
		$this->load->view('index', $data);
		$this->load->view('footer', $data);
	}

	public function users()
	{
		$this->check_permission_page();
		$this->load->model('Role_model');
		$data['title'] = $this->lang('users');
		$data['page'] = "users";
		$data['users'] = $this->Admin_model->get_users();
		$data['user_roles'] = $this->Role_model->get_all_roles();
		$data['script_single_patient_assets'] = ['assets/js/users.js'];

		$this->load->view('header', $data);
		$this->load->view('users/index', $data);
		$this->load->view('footer');
	}

	public function leave()
	{
		$this->check_permission_page();
		$data['title'] = $this->lang('doctors leave requests');
		$data['page'] = "leave";
		$data['leaves'] = ($this->Admin_model->get_leave_requests()) ? $this->Admin_model->get_leave_requests() : array();

		$data['doctors'] = $this->Admin_model->get_doctors();
		$data['script'] = $this->mylibrary->generateSelect2();
		$this->load->view('header', $data);
		$this->load->view('users/leave_requests', $data);
		$this->load->view('footer');
	}

	public function insert_leave()
	{
		$data = array('type' => 'form_error', 'messages' => array());

		// Set validation rules
		$this->form_validation->set_rules('doctor_id', 'Doctor', 'trim|required', array('required' => $this->lang('insert leave doctor error')));
		$this->form_validation->set_rules('leave_start_date', 'Leave Start Date', 'trim|required', array('required' => $this->lang('insert leave leave_start_date error')));
		$this->form_validation->set_rules('leave_end_date', 'Leave End Date', 'trim|required', array('required' => $this->lang('insert leave leave_end_date error')));
		$this->form_validation->set_rules('reason', 'Reason', 'trim');

		if ($this->form_validation->run()) {

			// Collect the leave data
			$datas = array(
				'doctor_id' => $this->input->post('doctor_id'),
				'leave_start_date' => $this->input->post('leave_start_date'),
				'leave_end_date' => $this->input->post('leave_end_date'),
				'reason' => $this->input->post('reason'),
				'status' => "a"  // Default to approved status for simplicity
			);

			// Insert the leave record into the database
			$insert = $this->Admin_model->insert_leave($datas);

			// Check if the insertion was successful
			if ($insert[0]) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('leave_inserted_success');
				$data['alert']['type'] = 'success';

				// Provide the leave ID for further processing or for the frontend
				$data['id'] = $insert[1];

				// Optional: If you want to display additional information such as buttons for edit and delete
				$btns = '';
				$btns .= $this->mylibrary->generateBtnUpdate('edit_leave', $data['id']);
				$btns .= $this->mylibrary->generateBtnDelete($insert[1], 'admin/delete_leave');

				// Collect the information for the row display, if required
				$leaveDetails = $this->Admin_model->get_leave_details($insert[1]);  // Assuming a function to get details of the inserted leave

				$data['tr'] = array(
					$leaveDetails['doctor_name'],
					$leaveDetails['leave_start_date'],
					$leaveDetails['leave_end_date'],
					$leaveDetails['reason'],
					$this->mylibrary->check_status($leaveDetails['status']),
					$this->mylibrary->btn_group($btns)
				);
			} else {
				// Failure case
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('error');
				$data['alert']['type'] = 'error';
			}
		} else {
			// Collect and return form validation errors
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}

		// Send the response back as JSON
		print_r(json_encode($data));
	}

	public function delete_leave()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$datas = array(
				'id' => $this->input->post('record')
			);

			if ($this->Admin_model->delete_leave($datas)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('delete leave');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	public function get_leave()
	{
		$leave_id = $this->input->post('leave_id');

		$leave = $this->Admin_model->get_leave_by_id($leave_id);

		if ($leave) {
			$data['type'] = 'success';
			$data['content'] = $leave;
		} else {
			$data['type'] = 'error';
			$data['alert']['text'] = $this->lang('leave_not_found');
		}

		echo json_encode($data);
	}

	public function update_leave()
	{
		$data = array('type' => 'form_error', 'messages' => array());

		// Form validation rules
		$this->form_validation->set_rules('leave_id', 'Leave ID', 'trim|required', array('required' => $this->lang('error')));
		$this->form_validation->set_rules('doctor_id', 'Doctor', 'trim|required', array('required' => $this->lang('insert leave doctor error')));
		$this->form_validation->set_rules('leave_start_date', 'Leave Start Date', 'trim|required', array('required' => $this->lang('insert leave leave_start_date error')));
		$this->form_validation->set_rules('leave_end_date', 'Leave End Date', 'trim|required', array('required' => $this->lang('insert leave leave_end_date error')));
		$this->form_validation->set_rules('reason', 'Reason', 'trim');

		if ($this->form_validation->run()) {
			// Collect form data
			$datas = array(
				'doctor_id' => $this->input->post('doctor_id'),
				'leave_start_date' => $this->input->post('leave_start_date'),
				'leave_end_date' => $this->input->post('leave_end_date'),
				'reason' => $this->input->post('reason'),
			);

			$id = $this->input->post('leave_id'); // Leave ID to update

			// Call model function to update the record
			$update = $this->Admin_model->update_leave_record($datas, $id);

			if ($update) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('update leave success');
				$data['alert']['type'] = 'success';

				$data['id'] = $id;

				// Generate action buttons for the updated leave record
				$btns = '';
				$btns .= $this->mylibrary->generateBtnUpdate('edit_leave', $data['id']);
				$btns .= $this->mylibrary->generateBtnDelete($data['id'], 'admin/delete_leave');

				// Fetch updated leave details
				$x = $this->Admin_model->get_leave_by_id($id);

				$data['tr'] = array(
					$this->mylibrary->user_name($x['fname'], $x['lname']),
					$x['leave_start_date'],
					$x['leave_end_date'],
					$x['reason'],
					$this->mylibrary->check_status($x['status']),
					$this->mylibrary->btn_group($btns),
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('error');
				$data['alert']['type'] = 'error';
			}
		} else {
			// Handle validation errors
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		// Return response as JSON
		print_r(json_encode($data));
	}


	public function user_role()
	{
		$data['title'] = $this->lang('users');
		$data['page'] = "users";


		$this->load->model('Permission_model');

		$data['permissions'] = $this->Permission_model->get_permissions_with_categories();


		$this->load->view('header', $data);
		$this->load->view('users/user_role', $data);
		$this->load->view('footer');
	}

	public function lock()
	{
		$data['title'] = $this->lang('users');
		$data['page'] = "lock";
		$data['users'] = $this->Admin_model->get_users();

		$this->load->view('lock_screen', $data);
	}

	// TODO finish this after Prayer
	function upload_image($image, $username)
	{
		if (isset($image)) {
			if (!is_null($image) && !empty($image)) {
				$data = $image;

				////data:image/png;base64,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

				$image_array_1 = explode(";", $data);

				//base64,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

				$image_array_2 = explode(",", $image_array_1[1]);

				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

				$data = base64_decode($image_array_2[1]);

				$imageName = $username . '.png';

				if (file_put_contents('user_images/' . $imageName, $data)) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function insert_user()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('uploadedImage', 'uploadedImage', 'trim');
		$this->form_validation->set_rules('name', 'name', 'trim|required', array('required' => $this->lang('insert user name error')));
		$this->form_validation->set_rules('lname', 'lname', 'trim|required', array('required' => $this->lang('insert user lname error')));
		$this->form_validation->set_rules('username', 'username', 'trim|required|is_unique[users.username]', array('required' => $this->lang('insert user username error'), 'is_unique' => $this->lang('insert user username unique error')));
		$this->form_validation->set_rules('role', 'role', 'trim|required', array('required' => $this->lang('insert user role error')));
		$this->form_validation->set_rules('user_role', 'user_role', 'trim|required', array('required' => $this->lang('insert user role error')));
		$this->form_validation->set_rules('status', 'status', 'trim|required', array('required' => $this->lang('insert user status error')));
		$this->form_validation->set_rules('password', 'password', 'trim|required', array('required' => $this->lang('insert user password error')));
		$this->form_validation->set_rules('confirm', 'confirm', 'trim|required|matches[password]', array('required' => $this->lang('insert user confirm error'), 'matches' => $this->lang('insert user confirm matches error')));

		$this->form_validation->set_rules('working_start_time', 'working_start_time', 'trim');
		$this->form_validation->set_rules('working_end_time', 'working_end_time', 'trim');

		if ($this->form_validation->run()) {
			$upload = $this->upload_image($this->input->post('uploadedImage'), $this->input->post('username'));
			$profile_image = null;
			if ($upload) {
				$profile_image = $this->input->post('username') . '.png';
			} else {
				$profile_image = 'default.png';
			}
			$datas = array(
				'fname' => $this->input->post('name'),
				'lname' => $this->input->post('lname'),
				'role' => $this->input->post('role'),
				'role_id' => $this->input->post('user_role'),
				'username' => $this->input->post('username'),
				'status' => $this->input->post('status'),
				'working_start_time' => $this->input->post('working_start_time'),
				'working_end_time' => $this->input->post('working_end_time'),
				'photo' => $profile_image,
				'password' => $this->mylibrary->hash($this->input->post('password')),
				'uniqid' => uniqid()
			);
			$insert = $this->Admin_model->insert_user($datas);
			if ($insert[0]) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('insert user success');
				$data['alert']['type'] = 'success';

				$data['id'] = $insert[1];

				$btns = '';
				if ($this->input->post('status') == 'P') {
					$btns .= $this->mylibrary->generateBtnUnlock($data['id'], 'admin/change_status_user');
				} elseif ($this->input->post('status') == 'A') {
					$btns .= $this->mylibrary->generateBtnBan($data['id'], 'admin/change_status_user');
				}
				$btns .= $this->mylibrary->generateBtnDelete($insert[1], 'admin/delete_user');
				$btns .= $this->mylibrary->generateBtnUpdate($insert[1], 'updateUser');

				$this->load->model('Role_model');
				$role_name = $this->Role_model->get_role($datas['role_id'])[0]->role_name;

				$data['tr'] = array(
					$datas['fname'],
					$datas['lname'],
					$datas['username'],
					$this->mylibrary->generateUserBadge($this->input->post('status')),
					$role_name,
					$this->lang($this->mylibrary->check_user_type($datas['role'])),
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['title'] = $this->lang('error');
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}


	public function update_user()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('name', 'name', 'trim|required', array('required' => $this->lang('insert user name error')));
		$this->form_validation->set_rules('lname', 'lname', 'trim|required', array('required' => $this->lang('insert user lname error')));
		$this->form_validation->set_rules('role', 'role', 'trim|required', array('required' => $this->lang('insert user role error')));
		$this->form_validation->set_rules('user_role', 'user_role', 'trim|required', array('required' => $this->lang('insert user role error')));
		$this->form_validation->set_rules('status', 'status', 'trim|required', array('required' => $this->lang('insert user status error')));
		if ($this->input->post('password') != '') {
			$this->form_validation->set_rules('password', 'password', 'trim|required', array('required' => $this->lang('insert user password error')));
			$this->form_validation->set_rules('confirm', 'confirm', 'trim|required|matches[password]', array('required' => $this->lang('insert user confirm error'), 'matches' => $this->lang('insert user confirm matches error')));
		}

		$this->form_validation->set_rules('working_start_time', 'working_start_time', 'trim');
		$this->form_validation->set_rules('working_end_time', 'working_end_time', 'trim');

		if ($this->form_validation->run()) {

			$datas = array(
				'fname' => $this->input->post('name'),
				'lname' => $this->input->post('lname'),
				'role' => $this->input->post('role'),
				'role_id' => $this->input->post('user_role'),
				'status' => $this->input->post('status'),
				'working_start_time' => $this->input->post('working_start_time'),
				'working_end_time' => $this->input->post('working_end_time'),
			);
			if ($this->input->post('password') != '') {
				$datas['password'] = $this->mylibrary->hash($this->input->post('password'));
			}
			$update = $this->Admin_model->update_user($datas, $this->input->post('id'));
			if ($update) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('insert user success');
				$data['alert']['type'] = 'success';

				$id = $this->input->post('id');

				$data['id'] = $id;

				$btns = '';
				if ($this->input->post('status') == 'P') {
					$btns .= $this->mylibrary->generateBtnUnlock($data['id'], 'admin/change_status_user');
				} elseif ($this->input->post('status') == 'A') {
					$btns .= $this->mylibrary->generateBtnBan($data['id'], 'admin/change_status_user');
				}
				$btns .= $this->mylibrary->generateBtnDelete($id, 'admin/delete_user');
				$btns .= $this->mylibrary->generateBtnUpdate($id, 'updateUser');
				$this->load->model('Role_model');
				$role_name = $this->Role_model->get_role($datas['role_id'])[0]->role_name;

				$data['tr'] = array(
					$datas['fname'],
					$datas['lname'],
					$this->input->post('username'),
					$this->mylibrary->generateUserBadge($this->input->post('status')),
					$role_name,
					$this->lang($this->mylibrary->check_user_type($datas['role'])),
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['title'] = $this->lang('error');
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	public function single_user()
	{
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));

		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('slug');
			$datas = array(
				'id' => $record
			);
			$receipt = $this->Admin_model->single_user_update($datas);


			if (count($receipt) > 0) {
				$data['type'] = 'success';

				$data['content'] = array(
					'slug' => $receipt[0]['id'],
					'fname' => $receipt[0]['fname'],
					'lname' => $receipt[0]['lname'],
					'role' => $receipt[0]['role'],
					'username' => $receipt[0]['username'],
					'status' => $receipt[0]['status'],
					'role_id' => $receipt[0]['role_id'],
					'working_start_time' => $receipt[0]['working_start_time'],
					'working_end_time' => $receipt[0]['working_end_time'],
				);

			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function change_status_user()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		$this->form_validation->set_rules('status', 'status', 'trim|required', array('required' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$datas = array(
				'id' => $this->input->post('record')
			);
			$record = $this->input->post('record');
			if ($record == $this->session->userdata($this->mylibrary->hash_session('u_id'))) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('user edit not allowed');
				$data['alert']['type'] = 'error';
				print_r(json_encode($data));
				exit;
			}
			$status = $this->input->post('status');
			if ($this->Admin_model->change_user_status($status, $datas)) {
				$user = $this->Admin_model->single_user($record)[0];
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('user status changed');
				$data['alert']['type'] = 'success';


				$data['id'] = $record;

				$btns = '';
				if (ucfirst($this->input->post('status')) == 'P') {
					$btns .= $this->mylibrary->generateBtnUnlock($data['id'], 'admin/change_status_user');
				} elseif (ucfirst($this->input->post('status')) == 'A') {
					$btns .= $this->mylibrary->generateBtnBan($data['id'], 'admin/change_status_user');
				}
				$btns .= $this->mylibrary->generateBtnDelete($record, 'admin/delete_user');

				$user = $this->Admin_model->single_user($record)[0];

				$data['tr'] = array(
					$user['fname'],
					$user['lname'],
					$user['username'],
					$this->mylibrary->generateUserBadge($this->input->post('status')),
					$user['role_name'],
					$this->lang($this->mylibrary->check_user_type($user['role'])),
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	public function pending_user()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$datas = array(
				'id' => $this->input->post('record')
			);
			$record = $this->input->post('record');
			if ($record == $this->session->userdata($this->mylibrary->hash_session('u_id'))) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = 'شما مجاز به ویرایش وضعیت حساب خود نیستید!!';
				$data['alert']['type'] = 'error';
				print_r(json_encode($data));
				exit;
			}
			$status = 'P';
			if ($this->Admin_model->change_user_status($status, $datas)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = 'وضعیت کاربر موفقانه تغییر یافت';
				$data['alert']['type'] = 'success';


				$data['id'] = $record;

				$btns = '';

				$btns .= $this->mylibrary->generateBtnDelete($record, 'admin/delete_user', 'mr-left-0 btn-danger btn-group-right', 'trash');
				if ($status == 'A') {
					$btns .= $this->mylibrary->generateBtnStatus($record, 'admin/pending_user', 'mr-left-0 btn-info btn-group-left', 'clock-o');
				} else {
					$btns .= $this->mylibrary->generateBtnStatus($record, 'admin/accept_user', 'mr-left-0 btn-info btn-group-left', 'check');
				}
				$user = $this->Admin_model->single_user($record)[0];

				$data['tr'] = array(
					$user['fname'],
					$user['lname'],
					$user['username'],
					$this->mylibrary->elsewise($user['status'], 'A', "<span class='bg-green'>پذیرفته شده</span>", "<span class='bg-red'>انتظار</span>"),
					$this->mylibrary->elsewise($user['role'], 'admin', 'مدیر', 'کاربر'),
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	function delete_user()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$id = $this->input->post('record');
			$turn = $this->Admin_model->table_by_doctor('turn', $id);
			$tooth = $this->Admin_model->table_by_user('tooth', $id);
			$patient = $this->Admin_model->table_by_user('patient', $id);
			$doctors = $this->Admin_model->table_by_doctor('patient', $id);
			$customers = $this->Admin_model->table_by_user('customers', $id);
			$balance_sheet = $this->Admin_model->table_by_user('balance_sheet', $id);
			if ((count($turn) !== 0) || (count($tooth) !== 0) || (count($patient) !== 0) || (count($doctors) !== 0) || (count($customers) !== 0) || (count($balance_sheet) !== 0)) {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('user delete not allowed');
				$data['alert']['type'] = 'error';
				print_r(json_encode($data));
				exit;
			}

			$datas = array(
				'id' => $id
			);

			if ($this->Admin_model->delete_user($datas)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('delete user');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}


	function update_password()
	{
		// print_r($_POST);
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('oldPassword', 'oldPassword', 'trim|required', array('required' => $this->lang('insert account name error')));
		$this->form_validation->set_rules('newPassword', 'newPassword', 'trim|required');
		$this->form_validation->set_rules('confirmPassword', 'confirmPassword', 'trim|matches[newPassword]|required');
		if ($this->form_validation->run()) {
			$user = $this->Admin_model->single_user($this->session->userdata($this->mylibrary->hash_session('u_id')));

			if (count($user) != 0) {
				if ($user[0]['password'] != $this->mylibrary->hash($this->input->post('oldPassword'))) {
					$data['type'] = 'error';
					$data['alert']['title'] = $this->lang('error');
					$data['alert']['text'] = 'your password is incorrect';
					// $data['alert']['text'] = $this->lang('your password is incorrect');
					$data['alert']['type'] = 'error';
				} else {
					$datas = array(
						'password' => $this->mylibrary->hash($this->input->post('newPassword')),
					);
					$id = $this->session->userdata($this->mylibrary->hash_session('u_id'));
					$update = $this->Admin_model->update_password($datas, $id);
					if ($update) {
						$this->session->set_userdata($this->mylibrary->hash_session('u_pass'), $this->mylibrary->hash($this->input->post('newPassword')));
						$data['type'] = 'success';
						$data['alert']['title'] = $this->lang('success');
						$data['alert']['text'] = $this->lang('update account success');
						$data['alert']['type'] = 'success';
					} else {
						$data['type'] = 'error';
						$data['alert']['title'] = $this->lang('error');
						$data['alert']['text'] = $this->lang('problem');
						$data['alert']['type'] = 'error';
					}
				}
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	// Start Primary information

	public function primary_info()
	{
		$this->check_permission_page();
		$data['title'] = $this->lang('primary information');
		$data['page'] = "primary_info";
		$data['services'] = $this->Admin_model->get_services();
		$data['script_single_patient_assets'] = ["assets/js/primary_info.js"];
		$data['medicines'] = $this->Admin_model->get_medicines();
		$data['prescriptions'] = $this->Admin_model->list_prescription_samples('sample');
		$data['diagnoses'] = $this->Admin_model->get_diagnoses();
		$data['categories'] = $this->Admin_model->get_categories();
		$data['categories_teeth'] = $this->Admin_model->categories_by_type('teeth');
		$data['restorative_basic_informations'] = $this->Admin_model->teeth_basic_information_list('restorative');
		$data['Endo_basic_informations'] = $this->Admin_model->teeth_basic_information_list('Endo');
		$data['Prosthodontics_basic_informations'] = $this->Admin_model->teeth_basic_information_list('Prosthodontics');
		$data['script'] = $this->mylibrary->generateSelect2();
		$this->load->view('header', $data);
		$this->load->view('primary_info', $data);
		$this->load->view('footer');
	}

	// End Primary information

	// Start Services
	public function check_service_price()
	{
		$arr = array();
		$price = 0;

		if (isset($_POST['service'])) {
			$services = $_POST['service'];

			foreach ($services as $service) {
				$result = $this->Admin_model->single_service(array('id' => $service));
				$price += $result[0]['price'];
			}
		}

		$arr[0] = $price;
		print_r(json_encode($arr));
	}

	public function services_name($serviceText)
	{

		if (isset($serviceText)) {
			$services = explode(',', $serviceText);
			$name = '';
			foreach ($services as $service) {
				$result = $this->Admin_model->single_service(array('id' => $service));
				$name .= $result[0]['name'];
				$name .= ',';
			}
			return substr($name, 0, -1);
		}
	}

	public function services_name_multiple($serviceTextArray = array())
	{
		$serviceText = '';
		if (count($serviceTextArray) > 0) {
			foreach ($serviceTextArray as $text) {
				if (!is_null($text)) {
					$serviceText .= $text;
					$serviceText .= ',';
				}
			}
		} else {
			return 'error';
		}

		if (isset($serviceText)) {
			$servicesTextNew = substr($serviceText, 0, -1);
			$services = explode(',', $servicesTextNew);
			$name = '';
			if (count($services) > 0) {
				foreach ($services as $service) {
					$result = $this->Admin_model->single_service(array('id' => $service));
					if (count($result) > 0) {
						$name .= $result[0]['name'];
						$name .= ',';
					} else {
						$name .= $this->lang('error');
						$name .= ',';
					}
				}
				return substr($name, 0, -1);
			} else {
				return 'error';
			}
		}
	}


	public function delete_service()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$datas = array(
				'services_id' => $this->input->post('record')
			);

			$endo_has_service = $this->Admin_model->get_endo_has_service($datas);
			$restorative_has_service = $this->Admin_model->get_restorative_has_service($datas);

			// print_r($restorative_has_service);
			// exit;

			if (count($endo_has_service) !== 0 || count($restorative_has_service) !== 0) {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('services delete not allowed');
				$data['alert']['type'] = 'error';
				print_r(json_encode($data));
				exit();
			}


			$datas_for_delete = array(
				'id' => $this->input->post('record')
			);

			if ($this->Admin_model->delete_service($datas_for_delete)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('delete service');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	public function insert_service()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('name', 'name', 'trim|required|is_unique[services.name]', array('required' => $this->lang('insert service name error'), 'is_unique' => $this->lang('insert service name error unique')));
		$this->form_validation->set_rules('department', 'department', 'trim|required', array('required' => $this->lang('insert service error department')));
		$this->form_validation->set_rules('price', 'price', 'trim|required', array('required' => $this->lang('insert service error price')));

		$names = $this->input->post('process_name');
		$percentages = $this->input->post('percentage');

		$hasProcessError = false;
		$totalPercentage = 0;

		if (empty($names) || !is_array($names) || count($names) < 1) {
			$data['messages'][] = $this->lang('at least one process is required');
			$hasProcessError = true;
		} else {
			foreach ($names as $index => $name) {
				$name = trim($name);
				$percentage = isset($percentages[$index]) ? floatval($percentages[$index]) : 0;

				if ($name === '') {
					$data['messages'][] = $this->lang('process name is required') . " (" . ($index + 1) . ")";
					$hasProcessError = true;
				}

				if ($percentage <= 0 || $percentage > 100) {
					$data['messages'][] = $this->lang('percentage must be between 1 and 100') . " (" . ($index + 1) . ")";
					$hasProcessError = true;
				}

				$totalPercentage += $percentage;
			}

			if ($totalPercentage != 100) {
				$data['messages'][] = $this->lang('total percentage must equal 100');
				$hasProcessError = true;
			}
		}

		if ($hasProcessError) {
			$data['type'] = 'form_error';
			$data['title'] = $this->lang('error');
			echo json_encode($data);
			return;
		}

		if ($this->form_validation->run()) {
			$datas = array(
				'name' => $this->input->post('name'),
				'department' => $this->input->post('department'),
				'price' => $this->input->post('price'),
			);

			$insert = $this->Admin_model->insert_service($datas);

			if ($insert[0]) {
				$service_id = $insert[1];

				foreach ($names as $index => $name) {
					$percentage = isset($percentages[$index]) ? $percentages[$index] : 0;
					$process_data = array(
						'name' => $name,
						'percentage' => $percentage,
						'number' => $index + 1,
						'services_id' => $service_id
					);
					$this->Admin_model->insert_process($process_data);
				}

				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('insert service success');
				$data['alert']['type'] = 'success';
				$data['id'] = $service_id;

				$btns = '';
				$btns .= $this->mylibrary->generateBtnUpdate('edit_service', $data['id']);
				$btns .= $this->mylibrary->generateBtnDeleteMultiDataTable($service_id, 'admin/delete_service', 'services_table');

				$data['tr'] = array(
					$datas['name'],
					$this->lang($datas['department']),
					$datas['price'],
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}

		echo json_encode($data);
	}

	public function single_service()
	{
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));

		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('slug');
			$datas = array('id' => $record);

			$service = $this->Admin_model->single_service($datas);

			if (count($service) > 0) {
				$processes = $this->Admin_model->get_processes_by_service_id($record);

				$data['type'] = 'success';
				$data['content'] = array(
					'slug' => $service[0]['id'],
					'name' => $service[0]['name'],
					'department' => $service[0]['department'],
					'price' => $service[0]['price'],
					'processes' => $processes
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function update_service()
	{
		$data = array('type' => 'form_error', 'messages' => array());

		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', [
			'required' => $this->lang('problem'),
			'is_natural_no_zero' => $this->lang('problem')
		]);

		if ($this->input->post('nameOld') !== $this->input->post('name')) {
			$this->form_validation->set_rules('name', 'name', 'trim|required|is_unique[services.name]', [
				'required' => $this->lang('insert service name error'),
				'is_unique' => $this->lang('insert service name error unique')
			]);
		}

		$this->form_validation->set_rules('price', 'price', 'trim|required', [
			'required' => $this->lang('insert service error price')
		]);
		$this->form_validation->set_rules('department', 'department', 'trim|required', [
			'required' => $this->lang('insert service error department')
		]);

		$names = $this->input->post('process_name');
		$percentages = $this->input->post('percentage');

		$hasProcessError = false;
		$totalPercentage = 0;

		if (empty($names) || !is_array($names) || count($names) < 1) {
			$data['messages'][] = $this->lang('at least one process is required');
			$hasProcessError = true;
		} else {
			foreach ($names as $index => $name) {
				$name = trim($name);
				$percentage = isset($percentages[$index]) ? floatval($percentages[$index]) : 0;

				if ($name === '') {
					$data['messages'][] = $this->lang('process name is required') . " (" . ($index + 1) . ")";
					$hasProcessError = true;
				}

				if ($percentage <= 0 || $percentage > 100) {
					$data['messages'][] = $this->lang('percentage must be between 1 and 100') . " (" . ($index + 1) . ")";
					$hasProcessError = true;
				}

				$totalPercentage += $percentage;
			}

			if ($totalPercentage != 100) {
				$data['messages'][] = $this->lang('total percentage must equal 100');
				$hasProcessError = true;
			}
		}

		if ($hasProcessError) {
			$data['type'] = 'form_error';
			$data['title'] = $this->lang('error');
			echo json_encode($data);
			return;
		}

		if ($this->form_validation->run()) {
			$id = $this->input->post('slug');

			$datas = [
				'name' => $this->input->post('name'),
				'department' => $this->input->post('department'),
				'price' => $this->input->post('price'),
			];

			$update = $this->Admin_model->update_service($datas, $id);

			if ($update) {
				// Fetch existing processes from DB
				$existingProcesses = $this->Admin_model->get_processes_by_service_id($id);
				$existingNames = array_column($existingProcesses, 'name');
				$submittedNames = array_map('trim', $names);

				// Determine which to keep, update, insert, delete
				foreach ($submittedNames as $index => $name) {
					$percentage = $percentages[$index];
					$number = $index + 1;

					if (in_array($name, $existingNames)) {
						// UPDATE existing process
						$this->Admin_model->update_process_by_name_and_service($id, $name, [
							'percentage' => $percentage,
							'number' => $number
						]);
					} else {
						// INSERT new process
						$this->Admin_model->insert_process([
							'name' => $name,
							'percentage' => $percentage,
							'number' => $number,
							'services_id' => $id
						]);
					}
				}

				// DELETE unused ones (only if not used in turns)
				foreach ($existingProcesses as $existing) {
					if (!in_array($existing['name'], $submittedNames)) {
//						TODO: Check if the process is in use or not!!
//						$inUse = $this->Admin_model->is_process_used_in_turns($existing['id']);
						$inUse = false;
						if (!$inUse) {
							$this->Admin_model->delete_process_by_id($existing['id']);
						}
					}
				}

				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('update service success');
				$data['alert']['type'] = 'success';
				$data['id'] = $id;

				$btns = '';
				$btns .= $this->mylibrary->generateBtnUpdate('edit_service', $id);
				$btns .= $this->mylibrary->generateBtnDeleteMultiDataTable($id, 'admin/delete_service', 'services_table');

				$service = $this->Admin_model->single_service(['id' => $id])[0];
				$data['tr'] = [
					$service['name'],
					$this->lang($service['department']),
					$service['price'],
					$this->mylibrary->btn_group($btns)
				];
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}

		echo json_encode($data);
	}

	// End Services


	// Start Categories

	public function insert_categories()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('name', 'name', 'trim|required|is_unique[categories.name]', array('required' => $this->lang('insert categories name error'), 'is_unique' => $this->lang('insert categories name error unique')));
		$this->form_validation->set_rules('type', 'type', 'trim|required', array('required' => $this->lang('insert categories error type')));
		if ($this->form_validation->run()) {
			$datas = array(
				'name' => $this->input->post('name'),
				'type' => $this->input->post('type'),
			);
			$insert = $this->Admin_model->insert_categories($datas);
			if ($insert[0]) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('insert categories success');
				$data['alert']['type'] = 'success';

				$data['id'] = $insert[1];

				$btns = '';
				$btns .= $this->mylibrary->generateBtnUpdate('edit_categories', $data['id']);

				$btns .= $this->mylibrary->generateBtnDeleteMultiDataTable($insert[1], 'admin/delete_categories', 'categories_table');

				$data['tr'] = array(
					$datas['name'],
					$this->lang($datas['type']),
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}

	public function delete_categories()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$id = $this->input->post('record');
			$basic_information_teeth = $this->Admin_model->table_by_category('basic_information_teeth', $id);
			$files = $this->Admin_model->table_by_category('files', $id);
			if ((count($files) !== 0) || (count($basic_information_teeth) !== 0)) {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('category delete not allowed');
				$data['alert']['type'] = 'error';
				print_r(json_encode($data));
				exit;
			}
			$datas = array(
				'id' => $this->input->post('record')
			);

			if ($this->Admin_model->delete_categories($datas)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('delete categories');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	public function single_categories()
	{
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('slug');
			$datas = array(
				'id' => $record
			);
			$category = $this->Admin_model->single_category($datas);


			if (count($category) > 0) {
				$data['type'] = 'success';

				$data['content'] = array(
					'slug' => $category[0]['id'],
					'name' => $category[0]['name'],
					'type' => $category[0]['type'],
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function update_categories()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->input->post('nameOld') !== $this->input->post('name')) {
			$this->form_validation->set_rules('name', 'name', 'trim|required|is_unique[categories.name]', array('required' => $this->lang('insert categories name error'), 'is_unique' => $this->lang('insert categories name error unique')));
		}
		$this->form_validation->set_rules('type', 'type', 'trim|required', array('required' => $this->lang('insert categories error type')));

		if ($this->form_validation->run()) {
			$datas = array(
				'name' => $this->input->post('name'),
				'type' => $this->input->post('type'),
			);
			$id = $this->input->post('slug');
			$update = $this->Admin_model->update_categories($datas, $id);
			if ($update) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('update categories success');
				$data['alert']['type'] = 'success';

				$data['id'] = $id;

				$btns = '';

				$btns .= $this->mylibrary->generateBtnUpdate('edit_categories', $data['id']);

				$btns .= $this->mylibrary->generateBtnDeleteMultiDataTable($data['id'], 'admin/delete_categories', 'categories_table');

				$category = $this->Admin_model->single_category(array('id' => $id))[0];
				$data['tr'] = array(
					$category['name'],
					$this->lang($category['type']),
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	// End Categories


	// Start teeth_basic_information

	public function insert_teeth_info()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('name', 'name', 'trim|required|is_unique[basic_information_teeth.name]', array('required' => $this->lang('insert basic_teeth_info name error'), 'is_unique' => $this->lang('insert basic_teeth_info name error unique')));
		$this->form_validation->set_rules('categories_id', 'categories_id', 'trim|required', array('required' => $this->lang('insert basic_teeth_info error categories_id')));
		$this->form_validation->set_rules('department', 'department', 'trim|required', array('required' => $this->lang('insert basic_teeth_info error department')));
		if ($this->form_validation->run()) {
			$datas = array(
				'name' => $this->input->post('name'),
				'categories_id' => $this->input->post('categories_id'),
				'department' => $this->input->post('department'),
			);
			$insert = $this->Admin_model->insert_basic_information_teeth($datas);
			if ($insert[0]) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('insert basic_teeth_info success');
				$data['alert']['type'] = 'success';

				$data['id'] = $insert[1];

				$btns = '';
				$btns .= $this->mylibrary->generateBtnUpdate('edit_basic_teeth_info', $data['id']);

				$btns .= $this->mylibrary->generateBtnDeleteMultiDataTable($insert[1], 'admin/delete_basic_teeth_info', $datas['department'] . '_table');


				$category = $this->Admin_model->single_category($datas['categories_id'])[0];

				$data['tr'] = array(
					$category['name'],
					$datas['name'],
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}


	public function single_teeth_basic_info()
	{
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('slug');
			$datas = array(
				'id' => $record
			);
			$single_info = $this->Admin_model->single_basic_teeth_info($datas);

			if (count($single_info) > 0) {
				$data['type'] = 'success';

				$data['content'] = array(
					'slug' => $single_info[0]['id'],
					'name' => $single_info[0]['name'],
					'categories_id' => $single_info[0]['categories_id'],
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function update_basic_information_teeth()
	{
		// echo '<pre>';
		// print_r($_POST);
		// echo '</pre>';
		// exit;
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->input->post('nameOld') !== $this->input->post('name')) {
			$this->form_validation->set_rules('name', 'name', 'trim|required|is_unique[basic_information_teeth.name]', array('required' => $this->lang('insert basic_teeth_info name error'), 'is_unique' => $this->lang('insert service name error unique')));
		}
		$this->form_validation->set_rules('categories_id', 'categories_id', 'trim|required', array('required' => $this->lang('insert basic_teeth_info error categories_id')));

		if ($this->form_validation->run()) {
			$datas = array(
				'name' => $this->input->post('name'),
				'categories_id' => $this->input->post('categories_id'),
			);
			$id = $this->input->post('slug');
			$update = $this->Admin_model->update_basic_information_teeth($datas, $id);
			if ($update) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('update basic_teeth_info success');
				$data['alert']['type'] = 'success';

				$data['id'] = $id;

				$single_info = $this->Admin_model->single_basic_teeth_info_with_category($id)[0];
				$btns = '';

				$btns .= $this->mylibrary->generateBtnUpdate('edit_' . $single_info['department'], $data['id']);

				$btns .= $this->mylibrary->generateBtnDeleteMultiDataTable($id, 'admin/delete_basic_teeth_info', $single_info['department'] . '_table');


				$data['tr'] = array(
					$single_info['category_name'],
					$single_info['name'],
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	public function delete_basic_teeth_info()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$datas = array(
				'id' => $this->input->post('record')
			);

			$endo_basic_info = $this->Admin_model->check_basic_info($datas['id']);
			$restorative_basic_info = $this->Admin_model->check_basic_info($datas['id'], 'restorative');

			if (count($endo_basic_info) > 0 || count($restorative_basic_info) > 0) {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('basic_teeth_info delete not allowed');
				$data['alert']['type'] = 'error';
				print_r(json_encode($data));
				exit;
			}

			if ($this->Admin_model->delete_basic_teeth_info($datas)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('delete basic_teeth_info');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}



	// End teeth_basic_information


	// Start Medicine

	public function medicines()
	{
		$data['title'] = $this->lang('medicines');
		$data['page'] = "medicine";
		$data['medicines'] = $this->Admin_model->get_medicines();
		$data['script'] = $this->mylibrary->generateSelect2();

		$this->load->view('header', $data);
		$this->load->view('medicines', $data);
		$this->load->view('footer');
	}

	public function single_medicine()
	{
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('slug');
			$datas = array(
				'id' => $record
			);
			$medicine = $this->Admin_model->single_medicine($datas);


			if (count($medicine) > 0) {
				$data['type'] = 'success';

				$data['content'] = array(
					'slug' => $medicine[0]['id'],
					'type' => $medicine[0]['type'],
					'name' => $medicine[0]['name'],
					'doze' => $medicine[0]['doze'],
					'day' => $medicine[0]['day'],
					'times' => $medicine[0]['times'],
					'amount' => $medicine[0]['amount'],
					'usageType' => $medicine[0]['usageType'],
					'unit' => $medicine[0]['unit'],
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function medicine_by_id($record)
	{
		$datas = array(
			'id' => $record
		);
		$tooth = $this->Admin_model->single_medicine($datas);


		if (count($tooth) > 0) {
			return $tooth[0];
		} else {
			return 'undefined';
		}
	}


	public function insert_medicine()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('type', 'type', 'trim|required', array('required' => $this->lang('insert medicine type error')));
		$this->form_validation->set_rules('name', 'name', 'trim|required', array('required' => $this->lang('insert medicine name error')));
		$this->form_validation->set_rules('unit', 'unit', 'trim');
		$this->form_validation->set_rules('doze', 'doze', 'trim');
		$this->form_validation->set_rules('usageType', 'usageType', 'trim');
		$this->form_validation->set_rules('day', 'day', 'trim');
		$this->form_validation->set_rules('times', 'times', 'trim');
		$this->form_validation->set_rules('amount', 'amount', 'trim');
		if ($this->form_validation->run()) {
			$datas = array(
				'type' => $this->input->post('type'),
				'name' => $this->input->post('name'),
				'unit' => $this->input->post('unit'),
				'doze' => $this->input->post('doze'),
				'usageType' => $this->input->post('usageType'),
				'day' => $this->input->post('day'),
				'times' => $this->input->post('times'),
				'amount' => $this->input->post('amount'),
			);
			$insert = $this->Admin_model->insert_medicine($datas);
			if ($insert[0]) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('insert medicine success');
				$data['alert']['type'] = 'success';

				$data['id'] = $insert[1];

				$btns = '';
				$btns .= $this->mylibrary->generateBtnUpdate('edit_medicine', $data['id']);

				$btns .= $this->mylibrary->generateBtnDeleteMultiDataTable($insert[1], 'admin/delete_medicine', 'medicine_table');

				$data['tr'] = array(
					$datas['type'],
					$datas['name'],
					$datas['doze'],
					$datas['unit'],
					$datas['usageType'],
					$datas['day'],
					$datas['times'],
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}


	public function update_medicine()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('type', 'type', 'trim|required', array('required' => $this->lang('insert medicine type error')));
		$this->form_validation->set_rules('slug', 'slug', 'trim|required', array('required' => $this->lang('insert medicine name error')));
		$this->form_validation->set_rules('name', 'name', 'trim|required', array('required' => $this->lang('insert medicine name error')));
		$this->form_validation->set_rules('unit', 'unit', 'trim');
		$this->form_validation->set_rules('doze', 'doze', 'trim');
		$this->form_validation->set_rules('usageType', 'usageType', 'trim');
		$this->form_validation->set_rules('day', 'day', 'trim');
		$this->form_validation->set_rules('times', 'times', 'trim');
		$this->form_validation->set_rules('amount', 'amount', 'trim');
		if ($this->form_validation->run()) {
			$datas = array(
				'type' => $this->input->post('type'),
				'name' => $this->input->post('name'),
				'unit' => $this->input->post('unit'),
				'doze' => $this->input->post('doze'),
				'usageType' => $this->input->post('usageType'),
				'day' => $this->input->post('day'),
				'times' => $this->input->post('times'),
				'amount' => $this->input->post('amount'),
			);
			$update = $this->Admin_model->update_medicine($datas, $this->input->post('slug'));
			if ($update) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('update medicine success');
				$data['alert']['type'] = 'success';

				$data['id'] = $this->input->post('slug');

				$btns = '';
				$btns .= $this->mylibrary->generateBtnUpdate('edit_medicine', $data['id']);

				$btns .= $this->mylibrary->generateBtnDeleteMultiDataTable($data['id'], 'admin/delete_medicine', 'medicine_table');

				$data['tr'] = array(
					$datas['type'],
					$datas['name'],
					$datas['doze'],
					$datas['unit'],
					$datas['usageType'],
					$datas['day'],
					$datas['times'],
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}


	function delete_medicine()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$id = $this->input->post('record');
			$prescription = $this->Admin_model->check_medicine_from_prescription($id);
			if ((count($prescription) !== 0)) {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('medicine delete not allowed');
				$data['alert']['type'] = 'error';
				print_r(json_encode($data));
				exit;
			}

			$datas = array(
				'id' => $id
			);

			if ($this->Admin_model->delete_medicine($datas)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('delete medicine');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	// End Medicine

	// Start Diagnoses

	function delete_diagnose()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$id = $this->input->post('record');
			$tooth_has_diagnose = $this->Admin_model->tooth_has_diagnose($id);
			if ((count($tooth_has_diagnose) !== 0)) {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('diagnose delete not allowed');
				$data['alert']['type'] = 'error';
				print_r(json_encode($data));
				exit;
			}

			$datas = array(
				'id' => $id
			);

			if ($this->Admin_model->delete_diagnose($datas)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('delete diagnose');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	public function insert_diagnose()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('name', 'name', 'trim|required|is_unique[diagnose.name]', array('required' => $this->lang('insert diagnose name error'), 'is_unique' => $this->lang('insert diagnose name error unique')));
		if ($this->form_validation->run()) {
			$datas = array(
				'name' => $this->input->post('name'),
			);
			$insert = $this->Admin_model->insert_diagnose($datas);
			if ($insert[0]) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('insert diagnose success');
				$data['alert']['type'] = 'success';

				$data['id'] = $insert[1];

				$btns = '';
				$btns .= $this->mylibrary->generateBtnUpdate('edit_diagnose', $data['id']);

				$btns .= $this->mylibrary->generateBtnDeleteMultiDataTable($insert[1], 'admin/delete_diagnose', 'diagnose_table');

				$data['tr'] = array(
					$datas['name'],
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}


	public function single_diagnose()
	{
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('slug');
			$datas = array(
				'id' => $record
			);
			$diagnose = $this->Admin_model->single_diagnose($datas);


			if (count($diagnose) > 0) {
				$data['type'] = 'success';

				$data['content'] = array(
					'slug' => $diagnose[0]['id'],
					'name' => $diagnose[0]['name'],
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function update_diagnose()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->input->post('nameOld') !== $this->input->post('name')) {
			$this->form_validation->set_rules('name', 'name', 'trim|required|is_unique[diagnose.name]', array('required' => $this->lang('insert service name error'), 'is_unique' => $this->lang('insert diagnose name error unique')));
		}

		if ($this->form_validation->run()) {
			$datas = array(
				'name' => $this->input->post('name'),
			);
			$id = $this->input->post('slug');
			$update = $this->Admin_model->update_diagnose($datas, $id);
			if ($update) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('update service success');
				$data['alert']['type'] = 'success';

				$data['id'] = $id;

				$btns = '';

				$btns .= $this->mylibrary->generateBtnUpdate('edit_diagnose', $data['id']);

				$btns .= $this->mylibrary->generateBtnDeleteMultiDataTable($data['id'], 'admin/delete_diagnose', 'diagnose_table');

				$service = $this->Admin_model->single_diagnose(array('id' => $id))[0];
				$data['tr'] = array(
					$service['name'],
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	// End Diagnoses


	//start prescription

	public function single_prescription()
	{
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('slug');
			$datas = array(
				'id' => $record
			);
			$prescription = $this->Admin_model->single_prescription($datas);

			$data['type'] = 'success';
			if (count($prescription) > 0) {
				$data['content'] = $prescription[0];
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function single_prescription_sample()
	{
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('slug');
			$datas = array(
				'id' => $record
			);
			$prescription = $this->Admin_model->single_prescription_sample($datas);

			$data['type'] = 'success';
			if (count($prescription) > 0) {
				$data['content'] = $prescription[0];
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}


	public function insert_prescription()
	{
		// print_r($_POST);
		// exit;
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('patient_id', 'patient_id', 'trim|required', array('required' => $this->lang('insert turn patient_id error')));

		// First Row that always exists
		$this->form_validation->set_rules('medicine_1', 'medicine_1', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
		$this->form_validation->set_rules('doze_1', 'doze_1', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
		$this->form_validation->set_rules('unit_1', 'unit_1', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
		$this->form_validation->set_rules('usageType_1', 'usageType_1', 'trim');
		$this->form_validation->set_rules('day_1', 'day_1', 'trim|required', array('required' => $this->lang('insert prescription day error')));
		$this->form_validation->set_rules('time_1', 'time_1', 'trim|required', array('required' => $this->lang('insert prescription time error')));
		$this->form_validation->set_rules('amount_1', 'amount_1', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		// End First Row

		// Second Row
		if ($this->input->post('medicine_2') != '') {
			$this->form_validation->set_rules('medicine_2', 'medicine_2', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
			$this->form_validation->set_rules('doze_2', 'doze_2', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
			$this->form_validation->set_rules('unit_2', 'unit_2', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
			$this->form_validation->set_rules('usageType_2', 'usageType_2', 'trim');
			$this->form_validation->set_rules('day_2', 'day_2', 'trim|required', array('required' => $this->lang('insert prescription day error')));
			$this->form_validation->set_rules('time_2', 'time_2', 'trim|required', array('required' => $this->lang('insert prescription time error')));
			$this->form_validation->set_rules('amount_2', 'amount_2', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		} else {
			$this->form_validation->set_rules('medicine_2', 'medicine_2', 'trim');
			$this->form_validation->set_rules('doze_2', 'doze_2', 'trim');
			$this->form_validation->set_rules('unit_2', 'unit_2', 'trim');
			$this->form_validation->set_rules('usageType_2', 'usageType_2', 'trim');
			$this->form_validation->set_rules('day_2', 'day_2', 'trim');
			$this->form_validation->set_rules('time_2', 'time_2', 'trim');
			$this->form_validation->set_rules('amount_2', 'amount_2', 'trim');
		}
		// end Second Row

		// third Row
		if ($this->input->post('medicine_3') != '') {
			$this->form_validation->set_rules('medicine_3', 'medicine_3', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
			$this->form_validation->set_rules('doze_3', 'doze_3', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
			$this->form_validation->set_rules('unit_3', 'unit_3', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
			$this->form_validation->set_rules('usageType_3', 'usageType_3', 'trim');
			$this->form_validation->set_rules('day_3', 'day_3', 'trim|required', array('required' => $this->lang('insert prescription day error')));
			$this->form_validation->set_rules('time_3', 'time_3', 'trim|required', array('required' => $this->lang('insert prescription time error')));
			$this->form_validation->set_rules('amount_3', 'amount_3', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		} else {
			$this->form_validation->set_rules('medicine_3', 'medicine_3', 'trim');
			$this->form_validation->set_rules('doze_3', 'doze_3', 'trim');
			$this->form_validation->set_rules('unit_3', 'unit_3', 'trim');
			$this->form_validation->set_rules('usageType_3', 'usageType_3', 'trim');
			$this->form_validation->set_rules('day_3', 'day_3', 'trim');
			$this->form_validation->set_rules('time_3', 'time_3', 'trim');
			$this->form_validation->set_rules('amount_3', 'amount_3', 'trim');
		}
		// end third Row

		// Fourth Row
		if ($this->input->post('medicine_4') != '') {
			$this->form_validation->set_rules('medicine_4', 'medicine_4', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
			$this->form_validation->set_rules('doze_4', 'doze_4', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
			$this->form_validation->set_rules('unit_4', 'unit_4', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
			$this->form_validation->set_rules('usageType_4', 'usageType_4', 'trim');
			$this->form_validation->set_rules('day_4', 'day_4', 'trim|required', array('required' => $this->lang('insert prescription day error')));
			$this->form_validation->set_rules('time_4', 'time_4', 'trim|required', array('required' => $this->lang('insert prescription time error')));
			$this->form_validation->set_rules('amount_4', 'amount_4', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		} else {
			$this->form_validation->set_rules('medicine_4', 'medicine_4', 'trim');
			$this->form_validation->set_rules('doze_4', 'doze_4', 'trim');
			$this->form_validation->set_rules('unit_4', 'unit_4', 'trim');
			$this->form_validation->set_rules('usageType_4', 'usageType_4', 'trim');
			$this->form_validation->set_rules('day_4', 'day_4', 'trim');
			$this->form_validation->set_rules('time_4', 'time_4', 'trim');
			$this->form_validation->set_rules('amount_4', 'amount_4', 'trim');
		}
		// end Fourth Row

		// Fifth Row
		if ($this->input->post('medicine_5') != '') {
			$this->form_validation->set_rules('medicine_5', 'medicine_5', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
			$this->form_validation->set_rules('doze_5', 'doze_5', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
			$this->form_validation->set_rules('unit_5', 'unit_5', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
			$this->form_validation->set_rules('usageType_5', 'usageType_5', 'trim');
			$this->form_validation->set_rules('day_5', 'day_5', 'trim|required', array('required' => $this->lang('insert prescription day error')));
			$this->form_validation->set_rules('time_5', 'time_5', 'trim|required', array('required' => $this->lang('insert prescription time error')));
			$this->form_validation->set_rules('amount_5', 'amount_5', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		} else {
			$this->form_validation->set_rules('medicine_5', 'medicine_5', 'trim');
			$this->form_validation->set_rules('doze_5', 'doze_5', 'trim');
			$this->form_validation->set_rules('unit_5', 'unit_5', 'trim');
			$this->form_validation->set_rules('usageType_5', 'usageType_5', 'trim');
			$this->form_validation->set_rules('day_5', 'day_5', 'trim');
			$this->form_validation->set_rules('time_5', 'time_5', 'trim');
			$this->form_validation->set_rules('amount_5', 'amount_5', 'trim');
		}
		// end Fifth Row

		// sixth Row
		if ($this->input->post('medicine_6') != '') {
			$this->form_validation->set_rules('medicine_6', 'medicine_6', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
			$this->form_validation->set_rules('doze_6', 'doze_6', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
			$this->form_validation->set_rules('unit_6', 'unit_6', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
			$this->form_validation->set_rules('usageType_6', 'usageType_6', 'trim');
			$this->form_validation->set_rules('day_6', 'day_6', 'trim|required', array('required' => $this->lang('insert prescription day error')));
			$this->form_validation->set_rules('time_6', 'time_6', 'trim|required', array('required' => $this->lang('insert prescription time error')));
			$this->form_validation->set_rules('amount_6', 'amount_6', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		} else {
			$this->form_validation->set_rules('medicine_6', 'medicine_6', 'trim');
			$this->form_validation->set_rules('doze_6', 'doze_6', 'trim');
			$this->form_validation->set_rules('unit_6', 'unit_6', 'trim');
			$this->form_validation->set_rules('usageType_6', 'usageType_6', 'trim');
			$this->form_validation->set_rules('day_6', 'day_6', 'trim');
			$this->form_validation->set_rules('time_6', 'time_6', 'trim');
			$this->form_validation->set_rules('amount_6', 'amount_6', 'trim');
		}
		// end sixth Row


		// seventh Row
		if ($this->input->post('medicine_7') != '') {
			$this->form_validation->set_rules('medicine_7', 'medicine_7', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
			$this->form_validation->set_rules('doze_7', 'doze_7', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
			$this->form_validation->set_rules('unit_7', 'unit_7', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
			$this->form_validation->set_rules('usageType_7', 'usageType_7', 'trim');
			$this->form_validation->set_rules('day_7', 'day_7', 'trim|required', array('required' => $this->lang('insert prescription day error')));
			$this->form_validation->set_rules('time_7', 'time_7', 'trim|required', array('required' => $this->lang('insert prescription time error')));
			$this->form_validation->set_rules('amount_7', 'amount_7', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		} else {
			$this->form_validation->set_rules('medicine_7', 'medicine_7', 'trim');
			$this->form_validation->set_rules('doze_7', 'doze_7', 'trim');
			$this->form_validation->set_rules('unit_7', 'unit_7', 'trim');
			$this->form_validation->set_rules('usageType_7', 'usageType_7', 'trim');
			$this->form_validation->set_rules('day_7', 'day_7', 'trim');
			$this->form_validation->set_rules('time_7', 'time_7', 'trim');
			$this->form_validation->set_rules('amount_7', 'amount_7', 'trim');
		}
		// end seventh Row


		// eightth Row
		if ($this->input->post('medicine_8') != '') {
			$this->form_validation->set_rules('medicine_8', 'medicine_8', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
			$this->form_validation->set_rules('doze_8', 'doze_8', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
			$this->form_validation->set_rules('unit_8', 'unit_8', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
			$this->form_validation->set_rules('usageType_8', 'usageType_8', 'trim');
			$this->form_validation->set_rules('day_8', 'day_8', 'trim|required', array('required' => $this->lang('insert prescription day error')));
			$this->form_validation->set_rules('time_8', 'time_8', 'trim|required', array('required' => $this->lang('insert prescription time error')));
			$this->form_validation->set_rules('amount_8', 'amount_8', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		} else {
			$this->form_validation->set_rules('medicine_8', 'medicine_8', 'trim');
			$this->form_validation->set_rules('doze_8', 'doze_8', 'trim');
			$this->form_validation->set_rules('unit_8', 'unit_8', 'trim');
			$this->form_validation->set_rules('usageType_8', 'usageType_8', 'trim');
			$this->form_validation->set_rules('day_8', 'day_8', 'trim');
			$this->form_validation->set_rules('time_8', 'time_8', 'trim');
			$this->form_validation->set_rules('amount_8', 'amount_8', 'trim');
		}
		// end eightth Row


		// nineth Row
		if ($this->input->post('medicine_9') != '') {
			$this->form_validation->set_rules('medicine_9', 'medicine_9', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
			$this->form_validation->set_rules('doze_9', 'doze_9', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
			$this->form_validation->set_rules('unit_9', 'unit_9', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
			$this->form_validation->set_rules('usageType_9', 'usageType_9', 'trim');
			$this->form_validation->set_rules('day_9', 'day_9', 'trim|required', array('required' => $this->lang('insert prescription day error')));
			$this->form_validation->set_rules('time_9', 'time_9', 'trim|required', array('required' => $this->lang('insert prescription time error')));
			$this->form_validation->set_rules('amount_9', 'amount_9', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		} else {
			$this->form_validation->set_rules('medicine_9', 'medicine_9', 'trim');
			$this->form_validation->set_rules('doze_9', 'doze_9', 'trim');
			$this->form_validation->set_rules('unit_9', 'unit_9', 'trim');
			$this->form_validation->set_rules('usageType_9', 'usageType_9', 'trim');
			$this->form_validation->set_rules('day_9', 'day_9', 'trim');
			$this->form_validation->set_rules('time_9', 'time_9', 'trim');
			$this->form_validation->set_rules('amount_9', 'amount_9', 'trim');
		}
		// end nineth Row

		// tenth Row
		if ($this->input->post('medicine_10') != '') {
			$this->form_validation->set_rules('medicine_10', 'medicine_10', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
			$this->form_validation->set_rules('doze_10', 'doze_10', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
			$this->form_validation->set_rules('unit_10', 'unit_10', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
			$this->form_validation->set_rules('usageType_10', 'usageType_10', 'trim');
			$this->form_validation->set_rules('day_10', 'day_10', 'trim|required', array('required' => $this->lang('insert prescription day error')));
			$this->form_validation->set_rules('time_10', 'time_10', 'trim|required', array('required' => $this->lang('insert prescription time error')));
			$this->form_validation->set_rules('amount_10', 'amount_10', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		} else {
			$this->form_validation->set_rules('medicine_10', 'medicine_10', 'trim');
			$this->form_validation->set_rules('doze_10', 'doze_10', 'trim');
			$this->form_validation->set_rules('unit_10', 'unit_10', 'trim');
			$this->form_validation->set_rules('usageType_10', 'usageType_10', 'trim');
			$this->form_validation->set_rules('day_10', 'day_10', 'trim');
			$this->form_validation->set_rules('time_10', 'time_10', 'trim');
			$this->form_validation->set_rules('amount_10', 'amount_10', 'trim');
		}
		// end tenth Row

		if ($this->form_validation->run()) {
			$date_time = $this->mylibrary->getCurrentShamsiDate();
			$datas = array(
				'patient_id' => $this->input->post('patient_id'),
				'users_id' => $this->session->userdata($this->mylibrary->hash_session('u_id')),
				'date_time' => $date_time['date'] . ' (' . $date_time['time'] . ')',


				'medicine_1' => $this->input->post('medicine_1'),
				'doze_1' => $this->input->post('doze_1'),
				'amount_1' => $this->input->post('amount_1'),
				'time_1' => $this->input->post('time_1'),
				'day_1' => $this->input->post('day_1'),
				'usageType_1' => $this->input->post('usageType_1'),
				'unit_1' => $this->input->post('unit_1'),


				'medicine_2' => $this->input->post('medicine_2'),
				'doze_2' => $this->input->post('doze_2'),
				'amount_2' => $this->input->post('amount_2'),
				'time_2' => $this->input->post('time_2'),
				'day_2' => $this->input->post('day_2'),
				'usageType_2' => $this->input->post('usageType_2'),
				'unit_2' => $this->input->post('unit_2'),


				'medicine_3' => $this->input->post('medicine_3'),
				'doze_3' => $this->input->post('doze_3'),
				'amount_3' => $this->input->post('amount_3'),
				'time_3' => $this->input->post('time_3'),
				'day_3' => $this->input->post('day_3'),
				'usageType_3' => $this->input->post('usageType_3'),
				'unit_3' => $this->input->post('unit_3'),

				'medicine_4' => $this->input->post('medicine_4'),
				'doze_4' => $this->input->post('doze_4'),
				'amount_4' => $this->input->post('amount_4'),
				'time_4' => $this->input->post('time_4'),
				'day_4' => $this->input->post('day_4'),
				'usageType_4' => $this->input->post('usageType_4'),
				'unit_4' => $this->input->post('unit_4'),


				'medicine_5' => $this->input->post('medicine_5'),
				'doze_5' => $this->input->post('doze_5'),
				'amount_5' => $this->input->post('amount_5'),
				'time_5' => $this->input->post('time_5'),
				'day_5' => $this->input->post('day_5'),
				'usageType_5' => $this->input->post('usageType_5'),
				'unit_5' => $this->input->post('unit_5'),

				'medicine_6' => $this->input->post('medicine_6'),
				'doze_6' => $this->input->post('doze_6'),
				'amount_6' => $this->input->post('amount_6'),
				'time_6' => $this->input->post('time_6'),
				'day_6' => $this->input->post('day_6'),
				'usageType_6' => $this->input->post('usageType_6'),
				'unit_6' => $this->input->post('unit_6'),


				'medicine_7' => $this->input->post('medicine_7'),
				'doze_7' => $this->input->post('doze_7'),
				'amount_7' => $this->input->post('amount_7'),
				'time_7' => $this->input->post('time_7'),
				'day_7' => $this->input->post('day_7'),
				'usageType_7' => $this->input->post('usageType_7'),
				'unit_7' => $this->input->post('unit_7'),


				'medicine_8' => $this->input->post('medicine_8'),
				'doze_8' => $this->input->post('doze_8'),
				'amount_8' => $this->input->post('amount_8'),
				'time_8' => $this->input->post('time_8'),
				'day_8' => $this->input->post('day_8'),
				'usageType_8' => $this->input->post('usageType_8'),
				'unit_8' => $this->input->post('unit_8'),


				'medicine_9' => $this->input->post('medicine_9'),
				'doze_9' => $this->input->post('doze_9'),
				'amount_9' => $this->input->post('amount_9'),
				'time_9' => $this->input->post('time_9'),
				'day_9' => $this->input->post('day_9'),
				'usageType_9' => $this->input->post('usageType_9'),
				'unit_9' => $this->input->post('unit_9'),


				'medicine_10' => $this->input->post('medicine_10'),
				'doze_10' => $this->input->post('doze_10'),
				'amount_10' => $this->input->post('amount_10'),
				'time_10' => $this->input->post('time_10'),
				'day_10' => $this->input->post('day_10'),
				'usageType_10' => $this->input->post('usageType_10'),
				'unit_10' => $this->input->post('unit_10'),
			);
			$insert = $this->Admin_model->insert_prescription($datas);
			if ($insert[0]) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('insert prescription success');
				$data['alert']['type'] = 'success';

				$data['id'] = $insert[1];

				$btns = '';
				$btns .= $this->mylibrary->generateBtnView('viewPrescriptionsMedicines', $data['id']);
				$btns .= $this->mylibrary->generateBtnPrint('print_prescription', $data['id']);

				$data['tr'] = array(
					$date_time['date'] . ' (' . $date_time['time'] . ')',
					$this->session->userdata($this->mylibrary->hash_session('u_fullname')),
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}


	public function insert_prescription_sample()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('name', 'name', 'trim|required|is_unique[prescription_samples.name]', array('required' => $this->lang('insert user name error'), 'is_unique' => $this->lang('insert user username unique error')));


		// First Row that always exists
		$this->form_validation->set_rules('medicine_1', 'medicine_1', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
		$this->form_validation->set_rules('doze_1', 'doze_1', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
		$this->form_validation->set_rules('unit_1', 'unit_1', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
		$this->form_validation->set_rules('usageType_1', 'usageType_1', 'trim');
		$this->form_validation->set_rules('day_1', 'day_1', 'trim|required', array('required' => $this->lang('insert prescription day error')));
		$this->form_validation->set_rules('time_1', 'time_1', 'trim|required', array('required' => $this->lang('insert prescription time error')));
		$this->form_validation->set_rules('amount_1', 'amount_1', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		// End First Row

		// Second Row
		if ($this->input->post('medicine_2') != '') {
			$this->form_validation->set_rules('medicine_2', 'medicine_2', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
			$this->form_validation->set_rules('doze_2', 'doze_2', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
			$this->form_validation->set_rules('unit_2', 'unit_2', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
			$this->form_validation->set_rules('usageType_2', 'usageType_2', 'trim');
			$this->form_validation->set_rules('day_2', 'day_2', 'trim|required', array('required' => $this->lang('insert prescription day error')));
			$this->form_validation->set_rules('time_2', 'time_2', 'trim|required', array('required' => $this->lang('insert prescription time error')));
			$this->form_validation->set_rules('amount_2', 'amount_2', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		} else {
			$this->form_validation->set_rules('medicine_2', 'medicine_2', 'trim');
			$this->form_validation->set_rules('doze_2', 'doze_2', 'trim');
			$this->form_validation->set_rules('unit_2', 'unit_2', 'trim');
			$this->form_validation->set_rules('usageType_2', 'usageType_2', 'trim');
			$this->form_validation->set_rules('day_2', 'day_2', 'trim');
			$this->form_validation->set_rules('time_2', 'time_2', 'trim');
			$this->form_validation->set_rules('amount_2', 'amount_2', 'trim');
		}
		// end Second Row

		// third Row
		if ($this->input->post('medicine_3') != '') {
			$this->form_validation->set_rules('medicine_3', 'medicine_3', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
			$this->form_validation->set_rules('doze_3', 'doze_3', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
			$this->form_validation->set_rules('unit_3', 'unit_3', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
			$this->form_validation->set_rules('usageType_3', 'usageType_3', 'trim');
			$this->form_validation->set_rules('day_3', 'day_3', 'trim|required', array('required' => $this->lang('insert prescription day error')));
			$this->form_validation->set_rules('time_3', 'time_3', 'trim|required', array('required' => $this->lang('insert prescription time error')));
			$this->form_validation->set_rules('amount_3', 'amount_3', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		} else {
			$this->form_validation->set_rules('medicine_3', 'medicine_3', 'trim');
			$this->form_validation->set_rules('doze_3', 'doze_3', 'trim');
			$this->form_validation->set_rules('unit_3', 'unit_3', 'trim');
			$this->form_validation->set_rules('usageType_3', 'usageType_3', 'trim');
			$this->form_validation->set_rules('day_3', 'day_3', 'trim');
			$this->form_validation->set_rules('time_3', 'time_3', 'trim');
			$this->form_validation->set_rules('amount_3', 'amount_3', 'trim');
		}
		// end third Row

		// Fourth Row
		if ($this->input->post('medicine_4') != '') {
			$this->form_validation->set_rules('medicine_4', 'medicine_4', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
			$this->form_validation->set_rules('doze_4', 'doze_4', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
			$this->form_validation->set_rules('unit_4', 'unit_4', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
			$this->form_validation->set_rules('usageType_4', 'usageType_4', 'trim');
			$this->form_validation->set_rules('day_4', 'day_4', 'trim|required', array('required' => $this->lang('insert prescription day error')));
			$this->form_validation->set_rules('time_4', 'time_4', 'trim|required', array('required' => $this->lang('insert prescription time error')));
			$this->form_validation->set_rules('amount_4', 'amount_4', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		} else {
			$this->form_validation->set_rules('medicine_4', 'medicine_4', 'trim');
			$this->form_validation->set_rules('doze_4', 'doze_4', 'trim');
			$this->form_validation->set_rules('unit_4', 'unit_4', 'trim');
			$this->form_validation->set_rules('usageType_4', 'usageType_4', 'trim');
			$this->form_validation->set_rules('day_4', 'day_4', 'trim');
			$this->form_validation->set_rules('time_4', 'time_4', 'trim');
			$this->form_validation->set_rules('amount_4', 'amount_4', 'trim');
		}
		// end Fourth Row

		// Fifth Row
		if ($this->input->post('medicine_5') != '') {
			$this->form_validation->set_rules('medicine_5', 'medicine_5', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
			$this->form_validation->set_rules('doze_5', 'doze_5', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
			$this->form_validation->set_rules('unit_5', 'unit_5', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
			$this->form_validation->set_rules('usageType_5', 'usageType_5', 'trim');
			$this->form_validation->set_rules('day_5', 'day_5', 'trim|required', array('required' => $this->lang('insert prescription day error')));
			$this->form_validation->set_rules('time_5', 'time_5', 'trim|required', array('required' => $this->lang('insert prescription time error')));
			$this->form_validation->set_rules('amount_5', 'amount_5', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		} else {
			$this->form_validation->set_rules('medicine_5', 'medicine_5', 'trim');
			$this->form_validation->set_rules('doze_5', 'doze_5', 'trim');
			$this->form_validation->set_rules('unit_5', 'unit_5', 'trim');
			$this->form_validation->set_rules('usageType_5', 'usageType_5', 'trim');
			$this->form_validation->set_rules('day_5', 'day_5', 'trim');
			$this->form_validation->set_rules('time_5', 'time_5', 'trim');
			$this->form_validation->set_rules('amount_5', 'amount_5', 'trim');
		}
		// end Fifth Row

		// sixth Row
		if ($this->input->post('medicine_6') != '') {
			$this->form_validation->set_rules('medicine_6', 'medicine_6', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
			$this->form_validation->set_rules('doze_6', 'doze_6', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
			$this->form_validation->set_rules('unit_6', 'unit_6', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
			$this->form_validation->set_rules('usageType_6', 'usageType_6', 'trim');
			$this->form_validation->set_rules('day_6', 'day_6', 'trim|required', array('required' => $this->lang('insert prescription day error')));
			$this->form_validation->set_rules('time_6', 'time_6', 'trim|required', array('required' => $this->lang('insert prescription time error')));
			$this->form_validation->set_rules('amount_6', 'amount_6', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		} else {
			$this->form_validation->set_rules('medicine_6', 'medicine_6', 'trim');
			$this->form_validation->set_rules('doze_6', 'doze_6', 'trim');
			$this->form_validation->set_rules('unit_6', 'unit_6', 'trim');
			$this->form_validation->set_rules('usageType_6', 'usageType_6', 'trim');
			$this->form_validation->set_rules('day_6', 'day_6', 'trim');
			$this->form_validation->set_rules('time_6', 'time_6', 'trim');
			$this->form_validation->set_rules('amount_6', 'amount_6', 'trim');
		}
		// end sixth Row


		// seventh Row
		if ($this->input->post('medicine_7') != '') {
			$this->form_validation->set_rules('medicine_7', 'medicine_7', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
			$this->form_validation->set_rules('doze_7', 'doze_7', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
			$this->form_validation->set_rules('unit_7', 'unit_7', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
			$this->form_validation->set_rules('usageType_7', 'usageType_7', 'trim');
			$this->form_validation->set_rules('day_7', 'day_7', 'trim|required', array('required' => $this->lang('insert prescription day error')));
			$this->form_validation->set_rules('time_7', 'time_7', 'trim|required', array('required' => $this->lang('insert prescription time error')));
			$this->form_validation->set_rules('amount_7', 'amount_7', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		} else {
			$this->form_validation->set_rules('medicine_7', 'medicine_7', 'trim');
			$this->form_validation->set_rules('doze_7', 'doze_7', 'trim');
			$this->form_validation->set_rules('unit_7', 'unit_7', 'trim');
			$this->form_validation->set_rules('usageType_7', 'usageType_7', 'trim');
			$this->form_validation->set_rules('day_7', 'day_7', 'trim');
			$this->form_validation->set_rules('time_7', 'time_7', 'trim');
			$this->form_validation->set_rules('amount_7', 'amount_7', 'trim');
		}
		// end seventh Row


		// eightth Row
		if ($this->input->post('medicine_8') != '') {
			$this->form_validation->set_rules('medicine_8', 'medicine_8', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
			$this->form_validation->set_rules('doze_8', 'doze_8', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
			$this->form_validation->set_rules('unit_8', 'unit_8', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
			$this->form_validation->set_rules('usageType_8', 'usageType_8', 'trim');
			$this->form_validation->set_rules('day_8', 'day_8', 'trim|required', array('required' => $this->lang('insert prescription day error')));
			$this->form_validation->set_rules('time_8', 'time_8', 'trim|required', array('required' => $this->lang('insert prescription time error')));
			$this->form_validation->set_rules('amount_8', 'amount_8', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		} else {
			$this->form_validation->set_rules('medicine_8', 'medicine_8', 'trim');
			$this->form_validation->set_rules('doze_8', 'doze_8', 'trim');
			$this->form_validation->set_rules('unit_8', 'unit_8', 'trim');
			$this->form_validation->set_rules('usageType_8', 'usageType_8', 'trim');
			$this->form_validation->set_rules('day_8', 'day_8', 'trim');
			$this->form_validation->set_rules('time_8', 'time_8', 'trim');
			$this->form_validation->set_rules('amount_8', 'amount_8', 'trim');
		}
		// end eightth Row


		// nineth Row
		if ($this->input->post('medicine_9') != '') {
			$this->form_validation->set_rules('medicine_9', 'medicine_9', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
			$this->form_validation->set_rules('doze_9', 'doze_9', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
			$this->form_validation->set_rules('unit_9', 'unit_9', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
			$this->form_validation->set_rules('usageType_9', 'usageType_9', 'trim');
			$this->form_validation->set_rules('day_9', 'day_9', 'trim|required', array('required' => $this->lang('insert prescription day error')));
			$this->form_validation->set_rules('time_9', 'time_9', 'trim|required', array('required' => $this->lang('insert prescription time error')));
			$this->form_validation->set_rules('amount_9', 'amount_9', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		} else {
			$this->form_validation->set_rules('medicine_9', 'medicine_9', 'trim');
			$this->form_validation->set_rules('doze_9', 'doze_9', 'trim');
			$this->form_validation->set_rules('unit_9', 'unit_9', 'trim');
			$this->form_validation->set_rules('usageType_9', 'usageType_9', 'trim');
			$this->form_validation->set_rules('day_9', 'day_9', 'trim');
			$this->form_validation->set_rules('time_9', 'time_9', 'trim');
			$this->form_validation->set_rules('amount_9', 'amount_9', 'trim');
		}
		// end nineth Row

		// tenth Row
		if ($this->input->post('medicine_10') != '') {
			$this->form_validation->set_rules('medicine_10', 'medicine_10', 'trim|required', array('required' => $this->lang('insert prescription medicine error')));
			$this->form_validation->set_rules('doze_10', 'doze_10', 'trim|required', array('required' => $this->lang('insert prescription doze error')));
			$this->form_validation->set_rules('unit_10', 'unit_10', 'trim|required', array('required' => $this->lang('insert prescription unit error')));
			$this->form_validation->set_rules('usageType_10', 'usageType_10', 'trim');
			$this->form_validation->set_rules('day_10', 'day_10', 'trim|required', array('required' => $this->lang('insert prescription day error')));
			$this->form_validation->set_rules('time_10', 'time_10', 'trim|required', array('required' => $this->lang('insert prescription time error')));
			$this->form_validation->set_rules('amount_10', 'amount_10', 'trim|required', array('required' => $this->lang('insert prescription amount error')));
		} else {
			$this->form_validation->set_rules('medicine_10', 'medicine_10', 'trim');
			$this->form_validation->set_rules('doze_10', 'doze_10', 'trim');
			$this->form_validation->set_rules('unit_10', 'unit_10', 'trim');
			$this->form_validation->set_rules('usageType_10', 'usageType_10', 'trim');
			$this->form_validation->set_rules('day_10', 'day_10', 'trim');
			$this->form_validation->set_rules('time_10', 'time_10', 'trim');
			$this->form_validation->set_rules('amount_10', 'amount_10', 'trim');
		}
		// end tenth Row

		if ($this->form_validation->run()) {
			$date_time = $this->mylibrary->getCurrentShamsiDate();
			$datas = array(
				'name' => $this->input->post('name'),
				'users_id' => $this->session->userdata($this->mylibrary->hash_session('u_id')),
				'date_time' => $date_time['date'] . ' (' . $date_time['time'] . ')',


				'medicine_1' => $this->input->post('medicine_1'),
				'doze_1' => $this->input->post('doze_1'),
				'amount_1' => $this->input->post('amount_1'),
				'time_1' => $this->input->post('time_1'),
				'day_1' => $this->input->post('day_1'),
				'usageType_1' => $this->input->post('usageType_1'),
				'unit_1' => $this->input->post('unit_1'),


				'medicine_2' => $this->input->post('medicine_2'),
				'doze_2' => $this->input->post('doze_2'),
				'amount_2' => $this->input->post('amount_2'),
				'time_2' => $this->input->post('time_2'),
				'day_2' => $this->input->post('day_2'),
				'usageType_2' => $this->input->post('usageType_2'),
				'unit_2' => $this->input->post('unit_2'),


				'medicine_3' => $this->input->post('medicine_3'),
				'doze_3' => $this->input->post('doze_3'),
				'amount_3' => $this->input->post('amount_3'),
				'time_3' => $this->input->post('time_3'),
				'day_3' => $this->input->post('day_3'),
				'usageType_3' => $this->input->post('usageType_3'),
				'unit_3' => $this->input->post('unit_3'),

				'medicine_4' => $this->input->post('medicine_4'),
				'doze_4' => $this->input->post('doze_4'),
				'amount_4' => $this->input->post('amount_4'),
				'time_4' => $this->input->post('time_4'),
				'day_4' => $this->input->post('day_4'),
				'usageType_4' => $this->input->post('usageType_4'),
				'unit_4' => $this->input->post('unit_4'),


				'medicine_5' => $this->input->post('medicine_5'),
				'doze_5' => $this->input->post('doze_5'),
				'amount_5' => $this->input->post('amount_5'),
				'time_5' => $this->input->post('time_5'),
				'day_5' => $this->input->post('day_5'),
				'usageType_5' => $this->input->post('usageType_5'),
				'unit_5' => $this->input->post('unit_5'),

				'medicine_6' => $this->input->post('medicine_6'),
				'doze_6' => $this->input->post('doze_6'),
				'amount_6' => $this->input->post('amount_6'),
				'time_6' => $this->input->post('time_6'),
				'day_6' => $this->input->post('day_6'),
				'usageType_6' => $this->input->post('usageType_6'),
				'unit_6' => $this->input->post('unit_6'),


				'medicine_7' => $this->input->post('medicine_7'),
				'doze_7' => $this->input->post('doze_7'),
				'amount_7' => $this->input->post('amount_7'),
				'time_7' => $this->input->post('time_7'),
				'day_7' => $this->input->post('day_7'),
				'usageType_7' => $this->input->post('usageType_7'),
				'unit_7' => $this->input->post('unit_7'),


				'medicine_8' => $this->input->post('medicine_8'),
				'doze_8' => $this->input->post('doze_8'),
				'amount_8' => $this->input->post('amount_8'),
				'time_8' => $this->input->post('time_8'),
				'day_8' => $this->input->post('day_8'),
				'usageType_8' => $this->input->post('usageType_8'),
				'unit_8' => $this->input->post('unit_8'),


				'medicine_9' => $this->input->post('medicine_9'),
				'doze_9' => $this->input->post('doze_9'),
				'amount_9' => $this->input->post('amount_9'),
				'time_9' => $this->input->post('time_9'),
				'day_9' => $this->input->post('day_9'),
				'usageType_9' => $this->input->post('usageType_9'),
				'unit_9' => $this->input->post('unit_9'),


				'medicine_10' => $this->input->post('medicine_10'),
				'doze_10' => $this->input->post('doze_10'),
				'amount_10' => $this->input->post('amount_10'),
				'time_10' => $this->input->post('time_10'),
				'day_10' => $this->input->post('day_10'),
				'usageType_10' => $this->input->post('usageType_10'),
				'unit_10' => $this->input->post('unit_10'),
			);
			$insert = $this->Admin_model->insert_prescription_sample($datas);
			if ($insert[0]) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('insert prescription success');
				$data['alert']['type'] = 'success';

				$data['id'] = $insert[1];

				$btns = '';
				$btns .= $this->mylibrary->generateBtnUpdate('editPrescription', $data['id']);
				$btns .= $this->mylibrary->generateBtnDeleteMultiDataTable($data['id'], 'admin/delete_prescription_sample', 'prescription_table');

				$data['tr'] = array(
					$datas['name'],
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}


	public function delete_prescription_sample()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$datas = array(
				'id' => $this->input->post('record')
			);

			if ($this->Admin_model->delete_prescription_sample($datas)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('delete service');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}


	public function print_prescription($id)
	{
		$data['title'] = $this->lang('prescription');
		$data['page'] = "prescription";
		if (is_null($id)) {
			redirect(base_url() . 'admin/patients/');
		}
		$prescription = $this->Admin_model->single_prescription_print($id);
		if (count($prescription) !== 1) {
			show_404();
		} else {
			$data['prescription'] = $prescription[0];
			$this->load->view("prints/prescription", $data);
		}
	}


	// End prescription


	// Start Files

	function insert_files()
	{

		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('title', 'title', 'trim|required', array('required' => $this->lang('insert file title error')));
		$this->form_validation->set_rules('patient_id', 'patient_id', 'trim|required', array('required' => $this->lang('insert file title error')));
		$this->form_validation->set_rules('categories_id', 'categories_id', 'trim|required', array('required' => $this->lang('insert file category error')));
		$this->form_validation->set_rules('desc', 'desc', 'trim');
		if ($this->form_validation->run()) {
			if (!empty($_FILES['to_upload']['name'])) {
				$config = array(
					'upload_path' => './patient_files/',
					'allowed_types' => 'pdf|doc|docx|jpg|png|jpeg|gif',
					'encrypt_name' => true,
					'max_size' => 10000,
					'remove_spaces' => true,
					'detect_mime' => true
				);
				$this->load->library('upload', $config);

				// Alternately you can set preferences by calling the ``initialize()`` method. Useful if you auto-load the class:
				$this->upload->initialize($config);

				if ($this->upload->do_upload('to_upload')) {
					$datas = array(
						'filename' => $this->upload->data('file_name'),
						'title' => $this->input->post('title'),
						'categories_id' => $this->input->post('categories_id'),
						'desc' => $this->input->post('desc'),
						'date' => $this->mylibrary->getCurrentShamsiDate()['date'] . ' ' . date('H:i:s'),
						'patient_id' => $this->input->post('patient_id'),
					);


					$insert = $this->Admin_model->insert_files($datas);
					if ($insert[0]) {
						$data['type'] = 'success';
						$data['alert']['title'] = $this->lang('success');
						$data['alert']['text'] = $this->lang('insert file success');
						$data['alert']['type'] = 'success';

						$data['id'] = $insert[1];

						$btns = '';
						$btns .= $this->mylibrary->generateBtnDownload($datas['filename']);
						$btns .= $this->mylibrary->generateBtnDelete($data['id'], 'admin/delete_files', 'filesTable');

						$data['tr'] = array(
							$datas['title'],
							$datas['date'],
							$datas['desc'],
							$this->mylibrary->btn_group($btns)
						);
					} else {
						$data['type'] = 'error';
						$data['alert']['title'] = $this->lang('error');
						$data['alert']['text'] = $this->lang('problem');
						$data['alert']['type'] = 'error';
					}
				} else {
					$upload_errors = array('error' => $this->upload->display_errors());
					foreach ($upload_errors as $upload_error) {
						$data['messages'][] = $upload_error;
						$data['title'] = $this->lang('error');
					}
				}
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('empty file error');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}

	public function delete_files()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$datas = array(
				'id' => $this->input->post('record')
			);
			$file = $this->Admin_model->file_by_id($this->input->post('record'))[0];
			if ($this->Admin_model->delete_file($datas)) {
				unlink('patient_files/' . $file['filename']);
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('delete turn');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	// End Files

	public function patients()
	{
		$this->check_permission_page('Read Patients');

		$data['title'] = $this->lang('patients');
		$data['page'] = "patients";
		$data['patients'] = $this->Admin_model->get_patients();
		$data['doctors'] = $this->Admin_model->get_doctors();
		$data['script'] = $this->mylibrary->generateSelect2();
		$data['script_single_patient_assets'] = ['assets/js/home.js'];

		//$data['script_date'] = $this->mylibrary->script_datepicker();


		$this->load->view('header', $data);
		$this->load->view('patients/index', $data);
		$this->load->view('footer');

	}

	function list_patient_json()
	{
		$this->form_validation->set_rules('status', 'status', 'trim|required', array('required' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$status = $this->input->post('status');
			if ($status != 't') {
				$patients = $this->Admin_model->get_patients($status);
			} else {
				$patients = $this->Admin_model->get_temp_patients_extra("temp_patient.status = 'a'");
			}


			if (count($patients) > 0) {
				$data['type'] = 'success';
				$i = 1;

				foreach ($patients as $patient) {
					if ($status == 't') {
						$patient['serial_id'] = 'none';
					}
					$data['content'][] = array(
						'number' => $i,
						'id' => $patient['id'],
						'serial_id' => $patient['serial_id'],
						'fullname' => $this->mylibrary->get_patient_name($patient['name'], $patient['lname'], '', $patient['gender']),
						'phone1' => $patient['phone1'],
						'doctor_name' => $patient['doctor_name'],
						'history' => $patient['pains'],
						'other_pains' => $patient['other_pains'],
						'remarks' => $patient['remarks'],
						'profile_access' => $this->auth->has_permission('Read Patient Profile'),
						'accept_access' => $this->auth->has_permission('Update Patient Acceptance'),
						'block_access' => $this->auth->has_permission('Update Blocked Patient'),
						'pending_access' => $this->auth->has_permission('Update Patient Pending'),
						'delete_access' => $this->auth->has_permission('Delete Patient'),
					);

					$i++;
				}
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	function delete_patient()
	{
		$this->check_permission_function('Delete Patient');
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$id = $this->input->post('record');
			$files = $this->Admin_model->table_by_patient('files', $id);
			$labs = $this->Admin_model->table_by_patient('labs', $id);
			$turns = $this->Admin_model->table_by_patient('turn', $id);
			$teeth = $this->Admin_model->table_by_patient('tooth', $id);
			if ((count($files) !== 0) || (count($labs) !== 0) || (count($turns) !== 0) || (count($teeth) !== 0)) {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('patient delete not allowed');
				$data['alert']['type'] = 'error';
				print_r(json_encode($data));
				exit;
			}

			$datas = array(
				'id' => $id
			);

			if ($this->Admin_model->delete_patient($datas)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('delete patient');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	function accept_patient()
	{
		$this->check_permission_function('Update Patient Acceptance');
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$id = $this->input->post('record');
			$check = $this->check_balance($id);
			if ($check['sum'] > 0) {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->language->languages('patient payment remain', null, $check['sum']);
				$data['alert']['type'] = 'error';
				print_r(json_encode($data));
				exit;
			}

			$datas = array(
				'status' => 'a'
			);

			if ($this->Admin_model->update_patient($datas, $id)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('accept patient');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	function block_patient()
	{
		$this->check_permission_function('Update Blocked Patient');
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$id = $this->input->post('record');

			$datas = array(
				'status' => 'b'
			);

			if ($this->Admin_model->update_patient($datas, $id)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('block patient');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}


	function pending_patient()
	{
		$this->check_permission_function('Update Patient Pending');
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$id = $this->input->post('record');

			$datas = array(
				'status' => 'p'
			);

			if ($this->Admin_model->update_patient($datas, $id)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('block patient');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	public function single_patient($id = null)
	{
		$this->check_permission_page('Read Patient Profile');
		if (!is_null($id)) {
			$profile = $this->Admin_model->profile_patient($id);

			if (count($profile) !== 0) {

				$data['title'] = $this->lang('patients');
				$data['page'] = "single_patient";

				// Start List of primary information for insert teeth

				// Start restorative
				$data['CariesDepthList'] = $this->Admin_model->list_insert_tooth_basic_information('عمق پوسیدگی');
				$data['BaseOrLinerMaterialList'] = $this->Admin_model->list_insert_tooth_basic_information('ماده بیس یا لاینر');
				$data['RestorativeMaterialList'] = $this->Admin_model->list_insert_tooth_basic_information('ماده ترمیم');
				$data['CompositeBrandList'] = $this->Admin_model->list_insert_tooth_basic_information('برند کامپوزیت');
				$data['AmalgamBrandList'] = $this->Admin_model->list_insert_tooth_basic_information('برند امالگام');
				$data['BondingBrandList'] = $this->Admin_model->list_insert_tooth_basic_information('برند باندینگ');
				$data['restorative_services'] = $this->Admin_model->get_services('restorative');
				// End restorative


				// Start Endo
				$data['typeOfAbturationList'] = $this->Admin_model->list_insert_tooth_basic_information('نوع آبجوریشن', 'endo');
				$data['typeOfSealerList'] = $this->Admin_model->list_insert_tooth_basic_information('نوع سیلر', 'endo');
				$data['typeOfIrregationList'] = $this->Admin_model->list_insert_tooth_basic_information('نوع اریگیشن', 'endo');
				$data['endo_services'] = $this->Admin_model->get_services('Endodantic');
				// End Endo


				// Start Prosthodontics
				$data['TypeOfRestorationList'] = $this->Admin_model->list_insert_tooth_basic_information('Type of restoration', 'Prosthodontics');
				$data['CoreMaterialList'] = $this->Admin_model->list_insert_tooth_basic_information('Core material', 'Prosthodontics');
				$data['PostList'] = $this->Admin_model->list_insert_tooth_basic_information('Post', 'Prosthodontics');
				$data['PrefabricatedPostList'] = $this->Admin_model->list_insert_tooth_basic_information('Prefabricated post', 'Prosthodontics');
				$data['CustomPostList'] = $this->Admin_model->list_insert_tooth_basic_information('Custom post', 'Prosthodontics');
				$data['MaterialOfCrownList'] = $this->Admin_model->list_insert_tooth_basic_information('Material of crown', 'Prosthodontics');
				$data['ColorList'] = $this->Admin_model->list_insert_tooth_basic_information('color', 'Prosthodontics');
				$data['PonticDesignList'] = $this->Admin_model->list_insert_tooth_basic_information('pontic design', 'Prosthodontics');
				$data['ImpressionTechniqueList'] = $this->Admin_model->list_insert_tooth_basic_information('Impression Technique', 'Prosthodontics');
				$data['ImpressionMaterialsList'] = $this->Admin_model->list_insert_tooth_basic_information('Impression materials', 'Prosthodontics');
				$data['CementList'] = $this->Admin_model->list_insert_tooth_basic_information('Cement', 'Prosthodontics');
				$data['CementMaterialList'] = $this->Admin_model->list_insert_tooth_basic_information('Cement Material', 'Prosthodontics');
				$data['Prosthodontics_services'] = $this->Admin_model->get_services('Prosthodontics');

				// End Prosthodontics


				// End List of primary information for insert teeth


				// Start List of primary information for files
				$data['categories_files'] = $this->Admin_model->categories_by_type('files');
				// End List of primary information for files

				$data['doctors'] = $this->Admin_model->get_doctors();
				$data['turns'] = $this->Admin_model->turns_by_patient_id($id);
				$data['teeth'] = $this->Admin_model->get_teeth_by_id_with_diagnose($id);
				$data['files'] = $this->Admin_model->get_files_by_patient($id);
				$data['lab_accounts'] = $this->Admin_model->get_lab_account();
				$data['labs'] = $this->Admin_model->labs_patient_id($id);
				$data['diagnoses'] = $this->Admin_model->get_diagnoses();
				$data['script_date'] = $this->mylibrary->script_datepicker();
				$data['medicines'] = $this->Admin_model->get_medicines();
				$data['prescriptions'] = $this->Admin_model->list_prescription_patient($id);
				$data['script_single_patient_assets'] = ['assets/js/teeth.js', 'assets/js/prescription.js'];
				$data['profile'] = $profile[0];
				$data['script'] = $this->mylibrary->generateSelect2('extralargemodal');
				// $data['script'] .= $this->mylibrary->generateSelect2('extralargemodal', 'reference_doctor');
				$data['script'] .= $this->mylibrary->generateSelect2('edit_patient');

				$check = $this->check_balance($id);
				$data['balance'] = $check['sum'];
				$data['sum_cr'] = $check['sum_cr'];
				$data['sum_dr'] = $check['sum_dr'];

				$data['process_percentage'] = $this->Admin_model->calculate_patient_process_completion($id);


				$this->load->view('header', $data);
				$this->load->view('patients/single', $data);
				$this->load->view('footer');
			} else {
				show_404();
			}
		} else {
			show_404();
			exit;
		}
	}

	public function ajax_turns_by_patient()
	{
		$patient_id = $this->input->post('patient_id');
		if (!$patient_id) {
			echo json_encode(['type' => 'error', 'message' => 'Invalid ID']);
			return;
		}

		$turns = $this->Admin_model->turns_by_patient_id($patient_id);
		echo json_encode(['type' => 'success', 'data' => $turns]);
	}


	public function single_tooth()
	{
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('slug');
			$datas = array(
				'id' => $record
			);
			$tooth = $this->Admin_model->single_tooth($datas);


			if (count($tooth) > 0) {
				$data['type'] = 'success';


				$tooth_content = $tooth[0];

				$tooth_diagnose = $this->Admin_model->tooth_has_diagnose($record, 'tooth');
				$diagnose_array = array();
				foreach ($tooth_diagnose as $diagnose) {
					$diagnose_array[] = $diagnose['diagnose_id'];
				}
				$data['content'] = array(
					'slug' => $tooth_content['id'],
					'name' => $tooth_content['name'],
					'diagnose' => $diagnose_array,
					'imgAddress' => $tooth_content['imgAddress'],
					'price' => $tooth_content['price'],
					// 'servicesNoArray' => $tooth_content['services'],
					'location' => $tooth_content['location'],
				);

				$endo = $this->Admin_model->single_endo_by_tooth_id($record);
				if (count($endo) != 0) {
					$endo_services = explode(',', $endo[0]['services']);

					$data['content']['endo'] = array(
						'r_name1' => $endo[0]['r_name1'],
						'r_name2' => $endo[0]['r_name2'],
						'r_name3' => $endo[0]['r_name3'],
						'r_name4' => $endo[0]['r_name4'],
						'r_name5' => $endo[0]['r_name5'],
						'r_width1' => $endo[0]['r_width1'],
						'r_width2' => $endo[0]['r_width2'],
						'r_width3' => $endo[0]['r_width3'],
						'r_width4' => $endo[0]['r_width4'],
						'r_width5' => $endo[0]['r_width5'],
						'price' => $endo[0]['price'],
						'details' => $endo[0]['details'],
						'services' => $endo_services,
						'TypeIrrigation' => '',
						'TypeSealer' => '',
						'typeObturation' => '',
						'root_number' => $endo[0]['root_number'],
					);

					$endo_basic_info = $this->Admin_model->endo_basic_information_by_id($endo[0]['id']);
					if (count($endo_basic_info) != 0) {
						foreach ($endo_basic_info as $basic_info) {
							if ($basic_info['category_name'] == 'نوع آبجوریشن') {
								$data['content']['endo']['typeObturation'] = $basic_info['basic_information_teeth_id'];
							} else if ($basic_info['category_name'] == 'نوع سیلر') {
								$data['content']['endo']['TypeSealer'] = $basic_info['basic_information_teeth_id'];
							} else if ($basic_info['category_name'] == 'نوع اریگیشن') {
								$data['content']['endo']['TypeIrrigation'] = $basic_info['basic_information_teeth_id'];
							}
						}
					}
					$data['content']['is_endo'] = 'true';
				} else {
					$data['content']['is_endo'] = 'false';
				}

				$restorative = $this->Admin_model->single_restorative_by_tooth_id($record);
				if (count($restorative) != 0) {
					$restorative_services = explode(',', $restorative[0]['services']);

					$data['content']['restorative'] = array(
						'price' => $restorative[0]['price'],
						'details' => $restorative[0]['details'],
						'services' => $restorative_services,
						'AmalgamBrand' => '',
						'bondingBrand' => '',
						'CompositeBrand' => '',
						'RestorativeMaterial' => '',
						'Material' => '',
						'CariesDepth' => '',
					);

					$restorative_basic_info = $this->Admin_model->restorative_basic_information_by_id($restorative[0]['id']);
					if (count($restorative_basic_info) != 0) {
						foreach ($restorative_basic_info as $basic_info) {
							if ($basic_info['category_name'] == 'عمق پوسیدگی') {
								$data['content']['restorative']['CariesDepth'] = $basic_info['basic_information_teeth_id'];
							} else if ($basic_info['category_name'] == 'ماده بیس یا لاینر') {
								$data['content']['restorative']['Material'] = $basic_info['basic_information_teeth_id'];
							} else if ($basic_info['category_name'] == 'ماده ترمیم') {
								$data['content']['restorative']['RestorativeMaterial'] = $basic_info['basic_information_teeth_id'];
							} else if ($basic_info['category_name'] == 'برند کامپوزیت') {
								$data['content']['restorative']['CompositeBrand'] = $basic_info['basic_information_teeth_id'];
							} else if ($basic_info['category_name'] == 'برند امالگام') {
								$data['content']['restorative']['AmalgamBrand'] = $basic_info['basic_information_teeth_id'];
							} else if ($basic_info['category_name'] == 'برند باندینگ') {
								$data['content']['restorative']['bondingBrand'] = $basic_info['basic_information_teeth_id'];
							}
						}
					}

					$data['content']['is_restorative'] = 'true';
				} else {
					$data['content']['is_restorative'] = 'false';
				}


				$pro = $this->Admin_model->single_prosthodontic_by_tooth_id($record);
				if (count($pro) != 0) {
					$pro_services = explode(',', $pro[0]['services']);

					$data['content']['prosthodontic'] = array(
						'price' => $pro[0]['price'],
						'details' => $pro[0]['details'],
						'services' => $pro_services,
						'type_restoration' => '',
						'filling_material' => '',
						'post' => '',
						'PrefabricatedPost' => '',
						'customPost' => '',
						'crown_material' => '',
						'color' => '',
						'pontic_design' => '',
						'impression_technique' => '',
						'impression_material' => '',
						'CementMaterial' => '',
					);

					$pro_basic_info = $this->Admin_model->prosthodontics_basic_information_by_id($pro[0]['id']);
					if (count($pro_basic_info) != 0) {
						foreach ($pro_basic_info as $basic_info) {
							if ($basic_info['category_name'] == 'Type of restoration') {
								$data['content']['prosthodontic']['type_restoration'] = $basic_info['basic_information_teeth_id'];
							} else if ($basic_info['category_name'] == 'Core material') {
								$data['content']['prosthodontic']['filling_material'] = $basic_info['basic_information_teeth_id'];
							} else if ($basic_info['category_name'] == 'Post') {
								$data['content']['prosthodontic']['post'] = $basic_info['basic_information_teeth_id'];
							} else if ($basic_info['category_name'] == 'Prefabricated post') {
								$data['content']['prosthodontic']['PrefabricatedPost'] = $basic_info['basic_information_teeth_id'];
							} else if ($basic_info['category_name'] == 'Custom post') {
								$data['content']['prosthodontic']['customPost'] = $basic_info['basic_information_teeth_id'];
							} else if ($basic_info['category_name'] == 'Material of crown') {
								$data['content']['prosthodontic']['crown_material'] = $basic_info['basic_information_teeth_id'];
							} else if ($basic_info['category_name'] == 'pontic design') {
								$data['content']['prosthodontic']['pontic_design'] = $basic_info['basic_information_teeth_id'];
							} else if ($basic_info['category_name'] == 'Impression Technique') {
								$data['content']['prosthodontic']['impression_technique'] = $basic_info['basic_information_teeth_id'];
							} else if ($basic_info['category_name'] == 'Impression materials') {
								$data['content']['prosthodontic']['impression_material'] = $basic_info['basic_information_teeth_id'];
							} else if ($basic_info['category_name'] == 'Cement Material') {
								$data['content']['prosthodontic']['CementMaterial'] = $basic_info['basic_information_teeth_id'];
							} else if ($basic_info['category_name'] == 'color') {
								$data['content']['prosthodontic']['color'] = explode(',', $basic_info['basic_information_teeth_id']);
							}
						}
					}

					$data['content']['is_prosthodontic'] = 'true';
				} else {
					$data['content']['is_prosthodontic'] = 'false';
				}
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function tooth_by_id($record)
	{
		$datas = array(
			'id' => $record
		);
		$tooth = $this->Admin_model->single_tooth($datas);


		if (count($tooth) > 0) {
			return $tooth[0];
		} else {
			return 'undefined';
		}
	}


	function patient_teeth()
	{
		$this->form_validation->set_rules('id', 'id', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('id');
			$teeth = $this->Admin_model->get_teeth_by_id($record);


			if (count($teeth) > 0) {
				$data['type'] = 'success';

				foreach ($teeth as $tooth) {
					$data['teeth'][] = array(
						'id' => $tooth['id'],
						'name' => $tooth['name'],
						'location' => $this->dentist->find_location($tooth['location']),
					);
				}
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function edit_patient()
	{
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('slug');
			$patient = $this->Admin_model->profile_patient($record);


			if (count($patient) > 0) {
				$data['type'] = 'success';

				$data['content'] = array(
					'slug' => $patient[0]['id'],
					'name' => $patient[0]['name'],
					'lname' => $patient[0]['lname'],
					'age' => $patient[0]['age'],
					'phone1' => $patient[0]['phone1'],
					'phone2' => $patient[0]['phone2'],
					'gender' => $patient[0]['gender'],
					'other_pains' => $patient[0]['other_pains'],
					'address' => $patient[0]['address'],
					'remarks' => $patient[0]['remarks'],
					'pains' => $patient[0]['pains'],
					'doctor_id' => $patient[0]['doctor_id'],
					'pains_select' => explode(',', $patient[0]['pains'])
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function update_patient()
	{
		$this->check_permission_function('Update Personal Information');
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('slug', 'slug', 'trim|required', array('required' => $this->lang('insert patient name error')));
		$this->form_validation->set_rules('name', 'name', 'trim|required', array('required' => $this->lang('insert patient name error')));
		$this->form_validation->set_rules('lname', 'lname', 'trim|required', array('required' => $this->lang('insert patient lname error')));
		$this->form_validation->set_rules('phone1', 'phone1', 'trim|required', array('required' => $this->lang('insert patient phone1 error')));
		$this->form_validation->set_rules('age', 'age', 'trim|required', array('required' => $this->lang('insert patient age error')));
		$this->form_validation->set_rules('gender', 'gender', 'trim|required', array('required' => $this->lang('insert patient gender error')));
		$this->form_validation->set_rules('doctor_id', 'doctor_id', 'trim|required', array('required' => $this->lang('insert patient doctor_id error')));
		$this->form_validation->set_rules('pains', 'pains', 'trim');
		$this->form_validation->set_rules('address', 'address', 'trim');
		$this->form_validation->set_rules('phone2', 'phone2', 'trim');
		$this->form_validation->set_rules('other_pains', 'other_pains', 'trim');
		$this->form_validation->set_rules('remarks', 'remarks', 'trim');
		if ($this->form_validation->run()) {
			$datas = array(
				'name' => $this->input->post('name'),
				'lname' => $this->input->post('lname'),
				'phone1' => $this->input->post('phone1'),
				'phone2' => $this->input->post('phone2'),
				'age' => $this->input->post('age'),
				'address' => $this->input->post('address'),
				'pains' => $this->input->post('pains'),
				'gender' => $this->input->post('gender'),
				'other_pains' => $this->input->post('other_pains'),
				'doctor_id' => $this->input->post('doctor_id'),
				'remarks' => $this->input->post('remarks'),
			);
			$id = $this->input->post('slug');
			$update = $this->Admin_model->update_patient($datas, $id);
			if ($update) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('update patient success');
				$data['alert']['type'] = 'success';

				$data['id'] = $id;


				$patient = $this->Admin_model->profile_patient($id)[0];
				$data['name'] = $patient['name'];
				$data['lname'] = $patient['lname'];
				if ($this->auth->has_permission('View Phone Numbers')) {
					$data['phone1'] = $patient['phone1'];
					$data['phone2'] = $patient['phone2'];
				} else {
					$data['phone1'] = null;
					$data['phone2'] = null;
				}
				$data['age'] = $patient['age'];
				$data['address'] = $patient['address'];
				$data['pains'] = $patient['pains'];
				$data['remarks'] = $patient['remarks'];
				$data['other_pains'] = $patient['other_pains'];
				$data['doctor_name'] = $patient['doctor_name'];
				$data['gender'] = $patient['gender'];
				$data['medical history'] = $this->lang('medical history');
				$data['lang doctor'] = $this->lang('reference doctor');
				$data['other diseases'] = $this->lang('other diseases');
				$data['desc'] = $this->lang('desc');
				$data['lang age'] = $this->lang('age');
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	public function insert_patient()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('name', 'name', 'trim|required', array('required' => $this->lang('insert patient name error')));
		$this->form_validation->set_rules('lname', 'lname', 'trim|required', array('required' => $this->lang('insert patient lname error')));
		$this->form_validation->set_rules('phone1', 'phone1', 'trim|required', array('required' => $this->lang('insert patient phone1 error')));
		$this->form_validation->set_rules('age', 'age', 'trim|required', array('required' => $this->lang('insert patient age error')));
		$this->form_validation->set_rules('gender', 'gender', 'trim|required', array('required' => $this->lang('insert patient gender error')));
		$this->form_validation->set_rules('doctor_id', 'doctor_id', 'trim|required', array('required' => $this->lang('insert patient doctor_id error')));
		$this->form_validation->set_rules('pains', 'pains', 'trim');
		$this->form_validation->set_rules('address', 'address', 'trim');
		$this->form_validation->set_rules('phone2', 'phone2', 'trim');
		$this->form_validation->set_rules('other_pains', 'other_pains', 'trim');
		$this->form_validation->set_rules('remarks', 'remarks', 'trim');
		if ($this->form_validation->run()) {

			$last_serial = $this->Admin_model->get_serial($this->mylibrary->getCurrentShamsiDate()['serial']);
			$number_of_month = str_replace($this->mylibrary->getCurrentShamsiDate()['serial'], '', $last_serial);
			$serial = $number_of_month + 1;
			if ($serial >= 10) {
				$serial_id = $this->mylibrary->getCurrentShamsiDate()['serial'] . $serial;
			} else {
				$serial_id = $this->mylibrary->getCurrentShamsiDate()['serial'] . '0' . $serial;
			}


			$datas = array(
				'name' => $this->input->post('name'),
				'lname' => $this->input->post('lname'),
				'phone1' => $this->input->post('phone1'),
				'phone2' => $this->input->post('phone2'),
				'age' => $this->input->post('age'),
				'address' => $this->input->post('address'),
				'pains' => $this->input->post('pains'),
				'doctor_id' => $this->input->post('doctor_id'),
				'gender' => $this->input->post('gender'),
				'other_pains' => $this->input->post('other_pains'),
				'remarks' => $this->input->post('remarks'),
				'status' => 'p',
				'serial_id' => $serial_id,
				'create' => $this->mylibrary->getCurrentShamsiDate()['date'] . ' ' . date('H:i:s'),
				'users_id' => $this->session->userdata($this->mylibrary->hash_session('u_id'))
			);

			$insert = $this->Admin_model->insert_patient($datas);
			if ($insert[0]) {

				// start insert turn

				$message = "سلام ";
				$message .= $this->mylibrary->get_patient_name($datas['name'], $datas['lname'], '', $datas['gender']);
				$message .= "
شما موفقانه به دیپارتمنت دندان شفاخانه تخصصی رازی برای تداوی دندان تان راجستر شده‌اید.
از اینکه مرکز مارا برای تداوی دندان‌تان انتخاب نموده‌اید از شما قلبا سپاسگذاریم.

“دیپارتمنت دندان شفاخانه تخصصی رازی”";

				$sms = $this->mylibrary->sendSms("+93" . $datas['phone1'], $message);


				$turn = array(
					'patient_id' => $insert[1],
					'date' => $this->mylibrary->getCurrentShamsiDate()['date'],
					'from_time' => date('H:i'),
					'to_time' => date('H:i', strtotime('+30 minutes')),
					'status' => 'p',
					'doctor_id' => $this->input->post('doctor_id'),
				);

				$this->Admin_model->insert_turn($turn);

				// End insert turn
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('insert patient success');
				$data['alert']['type'] = 'success';


				$data['id'] = $insert[1];

				$btns = '';
				$btns .= $this->mylibrary->generateBtnUpdate('edit_account', $data['id']);

				$btns .= $this->mylibrary->generateBtnDelete($insert[1], 'admin/delete_turn', 'turnsTable', 'update_balance');


				$data['tr'] = array(
					$datas['serial_id'],
					$this->mylibrary->get_patient_name($datas['name'], $datas['lname'], '', $datas['gender']),
					$datas['phone1'],
					$datas['pains'],
					$datas['other_pains'],
					$datas['remarks'],
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}

	public function insert_temp_patient()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('name', 'name', 'trim|required', array('required' => $this->lang('insert patient name error')));
		$this->form_validation->set_rules('lname', 'lname', 'trim|required', array('required' => $this->lang('insert patient lname error')));
		$this->form_validation->set_rules('phone1', 'phone1', 'trim|required', array('required' => $this->lang('insert patient phone1 error')));
		$this->form_validation->set_rules('age', 'age', 'trim|required', array('required' => $this->lang('insert patient age error')));
		$this->form_validation->set_rules('gender', 'gender', 'trim|required', array('required' => $this->lang('insert patient gender error')));
		$this->form_validation->set_rules('doctor_id', 'doctor_id', 'trim|required', array('required' => $this->lang('insert patient doctor_id error')));
		$this->form_validation->set_rules('pains', 'pains', 'trim');
		$this->form_validation->set_rules('address', 'address', 'trim');
		$this->form_validation->set_rules('phone2', 'phone2', 'trim');
		$this->form_validation->set_rules('other_pains', 'other_pains', 'trim');
		$this->form_validation->set_rules('remarks', 'remarks', 'trim');
		if ($this->form_validation->run()) {

			$datas = array(
				'name' => $this->input->post('name'),
				'lname' => $this->input->post('lname'),
				'phone1' => $this->input->post('phone1'),
				'phone2' => $this->input->post('phone2'),
				'age' => $this->input->post('age'),
				'address' => $this->input->post('address'),
				'pains' => $this->input->post('pains'),
				'doctor_id' => $this->input->post('doctor_id'),
				'gender' => $this->input->post('gender'),
				'other_pains' => $this->input->post('other_pains'),
				'remarks' => $this->input->post('remarks'),
				'status' => 'p',
				'create' => $this->mylibrary->getCurrentShamsiDate()['date'] . ' ' . date('H:i:s'),
				'users_id' => $this->session->userdata($this->mylibrary->hash_session('u_id'))
			);

			$insert = $this->Admin_model->insert_temp_patient($datas);
			if ($insert[0]) {

				// End insert turn
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('insert patient success');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}

	public function temp_to_permenant()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$id = $this->input->post('record');
			$profile = $this->Admin_model->single_temp_patient($id);
			if (count($profile) != 0) {
				$temp_patient = $profile[0];
				$last_serial = $this->Admin_model->get_serial($this->mylibrary->getCurrentShamsiDate()['serial']);
				$number_of_month = str_replace($this->mylibrary->getCurrentShamsiDate()['serial'], '', $last_serial);
				$serial = $number_of_month + 1;
				if ($serial >= 10) {
					$serial_id = $this->mylibrary->getCurrentShamsiDate()['serial'] . $serial;
				} else {
					$serial_id = $this->mylibrary->getCurrentShamsiDate()['serial'] . '0' . $serial;
				}
				$datas = array(
					'name' => $temp_patient['name'],
					'lname' => $temp_patient['lname'],
					'phone1' => $temp_patient['phone1'],
					'phone2' => $temp_patient['phone2'],
					'age' => $temp_patient['age'],
					'address' => $temp_patient['address'],
					'pains' => $temp_patient['pains'],
					'doctor_id' => $temp_patient['doctor_id'],
					'gender' => $temp_patient['gender'],
					'other_pains' => $temp_patient['other_pains'],
					'remarks' => $temp_patient['remarks'],
					'status' => 'p',
					'serial_id' => $serial_id,
					'create' => $this->mylibrary->getCurrentShamsiDate()['date'] . ' ' . date('H:i:s'),
					'users_id' => $this->session->userdata($this->mylibrary->hash_session('u_id'))
				);
				$insert = $this->Admin_model->insert_patient($datas);
				if ($insert[0]) {
					$this->Admin_model->remove_temp($id);

					// start insert turn

					$message = "سلام ";
					$message .= $this->mylibrary->get_patient_name($datas['name'], $datas['lname'], '', $datas['gender']);
					$message .= "
شما موفقانه به دیپارتمنت دندان شفاخانه تخصصی رازی برای تداوی دندان تان راجستر شده‌اید.
از اینکه مرکز مارا برای تداوی دندان‌تان انتخاب نموده‌اید از شما قلبا سپاسگذاریم.

“دیپارتمنت دندان شفاخانه تخصصی رازی”";

					$sms = $this->mylibrary->sendSms("+93" . $datas['phone1'], $message);


					$turn = array(
						'patient_id' => $insert[1],
						'date' => $this->mylibrary->getCurrentShamsiDate()['date'],
						'from_time' => date('H:i'),
						'to_time' => date('H:i', strtotime('+30 minutes')),
						'status' => 'p',
						'doctor_id' => $temp_patient['doctor_id'],
					);

					$this->Admin_model->insert_turn($turn);

					// End insert turn
					$data['id'] = $insert[1];
					$data['type'] = 'success';
					$data['alert']['title'] = $this->lang('success');
					$data['alert']['text'] = $this->lang('insert patient success');
					$data['alert']['type'] = 'success';

					$data['id'] = $insert[1];

					$btns = '';
					$btns .= $this->mylibrary->generateBtnUpdate('edit_account', $data['id']);

					$btns .= $this->mylibrary->generateBtnDelete($insert[1], 'admin/delete_turn', 'turnsTable', 'update_balance');


					$data['tr'] = array(
						$datas['serial_id'],
						$this->mylibrary->get_patient_name($datas['name'], $datas['lname'], '', $datas['gender']),
						$datas['phone1'],
						$datas['pains'],
						$datas['other_pains'],
						$datas['remarks'],
						$this->mylibrary->btn_group($btns)
					);
				} else {
					$data['type'] = 'error';
					$data['alert']['title'] = $this->lang('error');
					$data['alert']['text'] = $this->lang('problem');
					$data['alert']['type'] = 'error';
				}
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}


	function list_temp_patients()
	{
		$data = array();

		$patients = $this->Admin_model->get_temp_patients_extra();

		$data['type'] = 'success';
		if (count($patients) > 0) {
			$i = 1;
			foreach ($patients as $patient) {
				$data['content'][] = array(
					'number' => $i,
					'id' => $patient['id'],
					'patient_name' => $this->mylibrary->get_patient_name($patient['name'], $patient['lname'], '', $patient['gender']),
					'phone1' => $patient['phone1'],
					'phone2' => $patient['phone2'],
					'doctor_name' => $patient['doctor_name'],
					'pains' => $patient['pains'],
					'other_pains' => $patient['other_pains'],
					'remarks' => $patient['remarks'],
				);
				$i++;
			}
		} else {
			$data['content'] = array();
		}

		print_r(json_encode($data));
	}

	function archive_temp_patient()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$id = $this->input->post('record');

			$datas = array(
				'status' => 'a'
			);

			if ($this->Admin_model->update_temp_patient($datas, $id)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('archive patient');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}


	public function print_patient($id)
	{
		$data['title'] = $this->lang('turns');
		$data['page'] = "turns";
		if (is_null($id)) {
			redirect(base_url() . 'admin/turns/');
		}
		$profile = $this->Admin_model->profile_patient($id);
		if (count($profile) !== 1) {
			show_404();
		} else {
			$data['labs'] = $this->Admin_model->labs_patient_id($id);


			$data['teeth'] = $this->Admin_model->get_teeth_by_id($id);
			$data['profile'] = $profile[0];
			$this->load->view("prints/patient", $data);
		}
	}

	public function list_prostho_teeth()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {

			$teeth = $this->Admin_model->get_teeth_with_prosthodontics($this->input->post('record'));
			if ($teeth) {
				$data['type'] = 'success';
				$data['content'] = $teeth;
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	public function list_process_teeth()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {

			$teeth = $this->Admin_model->get_teeth_for_process($this->input->post('record'));
			if ($teeth) {
				$data['type'] = 'success';
				$data['content'] = $teeth;
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}


	public function labs()
	{
		$data['title'] = $this->lang('laboratory');
		$data['page'] = "labs";
		$data['labs'] = $this->Admin_model->list_labs();
		$data['accounts'] = $this->Admin_model->get_lab_account();


		$data['script'] = $this->mylibrary->generateSelect2();
		$data['script_date'] = $this->mylibrary->script_datepicker();
		$data['script_date'] .= $this->mylibrary->script_datepicker('update_date', 'div#update-date-div', 'update_date');
		$this->load->view('header', $data);
		$this->load->view('labs', $data);
		$this->load->view('footer');
	}


	public function insert_lab()
	{
		$data = array('type' => 'form_error', 'messages' => array());

		$this->form_validation->set_rules('patient_id', 'patient_id', 'trim|required', array('required' => $this->lang('insert lab customers_id error')));
		$this->form_validation->set_rules('customers_id', 'customers_id', 'trim|required', array('required' => $this->lang('insert lab customers_id error')));
		$this->form_validation->set_rules('teeth', 'teeth', 'trim|required', array('required' => $this->lang('insert lab teeth error')));
		$this->form_validation->set_rules('type', 'type', 'trim|required', array('required' => $this->lang('insert lab type error')));
		$this->form_validation->set_rules('color', 'color', 'trim|required', array('required' => $this->lang('insert lab color error')));
		$this->form_validation->set_rules('dr', 'dr', 'trim|required', array('required' => $this->lang('insert lab dr error')));
		$this->form_validation->set_rules('numberofUnits', 'numberofUnits', 'trim|required', array('required' => $this->lang('insert lab number of unit error')));
		$this->form_validation->set_rules('remarks', 'remarks', 'trim');
		if ($this->form_validation->run()) {
			$datas = array(
				'patient_id' => $this->input->post('patient_id'),
				'customers_id' => $this->input->post('customers_id'),
				'teeth' => $this->input->post('teeth'),
				'type' => $this->input->post('type'),
				'color' => $this->input->post('color'),
				'dr' => $this->input->post('dr'),
				'unit' => $this->input->post('numberofUnits'),
				'remarks' => $this->input->post('remarks'),
				'status' => 'p',
			);
			$insert = $this->Admin_model->insert_lab($datas);
			if ($insert[0]) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('insert lab success');
				$data['alert']['type'] = 'success';
				$data['alert']['type'] = 'success';
				$data['id'] = $insert[1];
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}

	public function tryLab()
	{
		$data = array('type' => 'form_error', 'messages' => array());

		$this->form_validation->set_rules('type', 'type', 'trim|required', array('required' => $this->lang('error')));
		$this->form_validation->set_rules('slug', 'slug', 'trim|required', array('required' => $this->lang('error')));
		$this->form_validation->set_rules('remarks', 'remarks', 'trim|required', array('required' => $this->lang('insert lab remarks error')));
		if ($this->form_validation->run()) {
			$type = $this->input->post('type');
			$slug = $this->input->post('slug');
			$remarks = $this->input->post('remarks');
			$datas = array(
				$type . '_try_status' => 'a',
				$type . '_try_datetime' => $this->mylibrary->getCurrentShamsiDate()['date'] . ' ' . date('H:i:s'),
				$type . '_try_message' => $remarks
			);
			$update = $this->Admin_model->update_lab($datas, array('id' => $this->input->post('slug')));
			if ($update) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang($type . ' try success');
				$data['alert']['type'] = 'success';
				$data['extraFunction'] = true;
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}


	public function update_lab()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('slug', 'slug', 'trim|required', array('required' => $this->lang('insert lab customers_id error')));
		$this->form_validation->set_rules('customers_id', 'customers_id', 'trim|required', array('required' => $this->lang('insert lab customers_id error')));
		$this->form_validation->set_rules('teeth', 'teeth', 'trim|required', array('required' => $this->lang('insert lab teeth error')));
		$this->form_validation->set_rules('type', 'type', 'trim|required', array('required' => $this->lang('insert lab type error')));
		$this->form_validation->set_rules('give_date', 'give_date', 'trim');
		$this->form_validation->set_rules('hour', 'hour', 'trim');
		$this->form_validation->set_rules('color', 'color', 'trim|required', array('required' => $this->lang('insert lab color error')));
		$this->form_validation->set_rules('dr', 'dr', 'trim|required', array('required' => $this->lang('insert lab dr error')));
		$this->form_validation->set_rules('remarks', 'remarks', 'trim');
		if ($this->form_validation->run()) {
			$datas = array(
				'customers_id' => $this->input->post('customers_id'),
				'teeth' => $this->input->post('teeth'),
				'type' => $this->input->post('type'),
				'give_date' => $this->input->post('give_date'),
				'hour' => $this->input->post('hour'),
				'color' => $this->input->post('color'),
				'dr' => $this->input->post('dr'),
				'remarks' => $this->input->post('remarks'),
			);
			$update = $this->Admin_model->update_lab($datas, array('id' => $this->input->post('slug')));
			if ($update) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('update lab success');
				$data['alert']['type'] = 'success';
				$data['alert']['type'] = 'success';
				$data['extraFunction'] = true;
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}

	public function init_lab()
	{

//		echo $this->input->post('hour');
//		exit();
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('slug', 'slug', 'trim|required', array('required' => $this->lang('insert lab customers_id error')));
		$this->form_validation->set_rules('give_date', 'give_date', 'trim|required', array('required' => $this->lang('insert lab give_date error')));
		$this->form_validation->set_rules('hour', 'hour', 'trim|required', array('required' => $this->lang('insert lab hour error')));
		$this->form_validation->set_rules('remarks', 'remarks', 'trim');
		if ($this->form_validation->run()) {
			$datas = array(
				'give_date' => $this->input->post('give_date'),
				'hour' => $this->input->post('hour'),
				'init_date' => $this->mylibrary->getCurrentShamsiDate()['date'] . ' ' . date('H:i:s'),
				'remarks' => $this->input->post('remarks'),
			);
			$update = $this->Admin_model->update_lab($datas, array('id' => $this->input->post('slug')));
			if ($update) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('update lab success');
				$data['alert']['type'] = 'success';
				$data['alert']['type'] = 'success';
				$data['id'] = $this->input->post('slug');
				$data['extraFunction'] = true;
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}


	public function delete_lab()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$datas = array(
				'id' => $this->input->post('record')
			);

			if ($this->Admin_model->delete_lab($datas)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('delete lab');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	public function finish_lab()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$datas = array(
				'status' => 'a',
				'receive_datetime' => $this->mylibrary->getCurrentShamsiDate()['date'] . ' ' . date('H:i:s'),
			);

			$update = $this->Admin_model->update_lab($datas, array('id' => $this->input->post('record')));
			if ($update) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('finish lab');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}


	public function install_lab()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$datas = array(
				'status' => 'a',
				'install_time' => $this->mylibrary->getCurrentShamsiDate()['date'] . ' ' . date('H:i:s'),
			);

			$update = $this->Admin_model->update_lab($datas, array('id' => $this->input->post('record')));
			if ($update) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('finish lab');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}


	public function show_try()
	{
		$this->form_validation->set_rules('type', 'type', 'trim|required', array('required' => $this->lang('problem')));
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('record');
			$type = $this->input->post('type');
			$datas = array(
				'id' => $record
			);
			$lab = $this->Admin_model->single_lab($datas);


			if (count($lab) > 0) {
				$data['type'] = 'success';

				if ($type == 'finish') {
					$data['content'] = array(
						'datetime' => $lab[0]['receive_datetime']
					);
				} elseif ($type == 'install') {
					$data['content'] = array(
						'datetime' => $lab[0]['receive_datetime']
					);
				} else {
					$data['content'] = array(
						'datetime' => $lab[0][$type . '_try_datetime'],
						'message' => $lab[0][$type . '_try_message'],
					);
				}
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function single_lab()
	{
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('slug');
			$datas = array(
				'id' => $record
			);
			$lab = $this->Admin_model->single_lab($datas);


			if (count($lab) > 0) {
				$data['type'] = 'success';

				$data['content'] = array(
					'lab_id' => $lab[0]['customers_id'],
					'teeth' => explode(',', $lab[0]['teeth']),
					'teeth_hidden' => $lab[0]['teeth'],
					'types' => explode(',', $lab[0]['type']),
					'types_hidden' => $lab[0]['type'],
					'delivery_date' => $lab[0]['give_date'],
					'delivery_time' => $lab[0]['hour'],
					'tooth_color' => $lab[0]['color'],
					'pay_amount' => $lab[0]['dr'],
					'remarks' => $lab[0]['remarks'],
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function list_labs_json()
	{
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$id = $this->input->post('record');

			$labs = $this->Admin_model->labs_patient_id($id);

			$datas = array();
			$i = 1;
			foreach ($labs as $lab) {
				$teeths = explode(',', $lab['teeth']);
				$teethName = '';
				foreach ($teeths as $tooth) {
					$info = $this->tooth_by_id($tooth);
					$teethName .= $info['location'];
					$teethName .= $info['name'];
					$teethName .= ',';
				}


				$types = explode(',', $lab['type']);
				$typesName = '';
				foreach ($types as $type) {
					$typesName .= $this->lang($type);
					$typesName .= ',';
				}
				$time = ($lab['hour'] != '') ? $this->mylibrary->from24to12($lab['hour']) : '';
				$datas[] = array(
					'number' => $i,
					'id' => $lab['id'],
					'lab_name' => $lab['lab_name'],
					'init_date' => $lab['init_date'],
					'teeth' => substr($teethName, 0, -1),
					'type' => substr($typesName, 0, -1),
					'delivery_date' => $lab['give_date'],
					'delivery_time' => $time,
					'pay_amount' => number_format($lab['dr']),
					'remarks' => (is_null($lab['remarks'])) ? '' : $lab['remarks'],
					'first_try_status' => $lab['first_try_status'],
					'second_try_status' => $lab['second_try_status'],
					'install_time' => $lab['install_time'],
					'status' => $lab['status'],
				);
				$i++;
			}

			$data['content']['labs'] = $datas;

			if (count($labs) >= 0) {
				$data['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function list_labs()
	{
		$this->form_validation->set_rules('from_date', 'From Date', 'trim');
		$this->form_validation->set_rules('to_date', 'To Date', 'trim');
		$this->form_validation->set_rules('lab_name', 'Lab Name', 'trim');
		$this->form_validation->set_rules('payment_status', 'Payment Status', 'trim');
		$this->form_validation->set_rules('case_status', 'Case Status', 'trim');

		if ($this->form_validation->run()) {
			$filters = [
				'from_date' => $this->input->post('from_date'),
				'to_date' => $this->input->post('to_date'),
				'lab_name' => $this->input->post('lab_name'),
				'payment_filter' => $this->input->post('payment_status'),
				'case_status' => $this->input->post('case_status')
			];
			// Fetch filtered labs from the model
			$labs = $this->Admin_model->get_filtered_labs($filters);

			$datas = [];
			$i = 1;
			foreach ($labs as $lab) {
				$teeths = explode(',', $lab['teeth']);
				$teethName = implode(',', array_map(fn($tooth) => $this->tooth_by_id($tooth)['location'] . $this->tooth_by_id($tooth)['name'], $teeths));

				$types = explode(',', $lab['type']);
				$typesName = implode(',', array_map(fn($type) => $this->lang($type), $types));

				$time = !empty($lab['hour']) ? $this->mylibrary->from24to12($lab['hour']) : '';

				$datas[] = [
					'number' => $i++,
					'id' => $lab['id'],
					'lab_name' => $lab['lab_name'],
					'patient_name' => $this->mylibrary->get_patient_name($lab['patient_name'], $lab['patient_lname'], $lab['serial_id'], $lab['gender']),
					'init_date' => $lab['init_date'],
					'teeth' => $teethName,
					'type' => $typesName,
					'patient_id' => $lab['patient_id'],
					'receive_datetime' => $lab['receive_datetime'],
					'delivery_date' => $lab['give_date'],
					'delivery_time' => $time,
					'pay_amount' => number_format($lab['dr']),
					'remarks' => $lab['remarks'] ?? '',
					'first_try_status' => $lab['first_try_status'],
					'second_try_status' => $lab['second_try_status'],
					'install_time' => $lab['install_time'],
					'status' => $lab['status'],
					'profile_access' => $this->auth->has_permission('Read Patient Profile'),
				];
			}

			$data['content']['labs'] = $datas;
			$data['type'] = count($labs) >= 0 ? 'success' : 'error';
			if ($data['type'] === 'error') {
				$data['alert'] = [
					'title' => $this->lang('error'),
					'text' => $this->lang('problem'),
					'type' => 'error'
				];
			}
		} else {
			$data['type'] = 'error';
			$data['alert'] = [
				'title' => $this->lang('error'),
				'text' => $this->lang('problem'),
				'type' => 'error'
			];
		}

		echo json_encode($data);
	}

	public function pay_lab()
	{
		$data = ['type' => 'form_error', 'messages' => []];

		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', [
			'required' => $this->lang('problem'),
			'is_natural_no_zero' => $this->lang('problem')
		]);

		if ($this->form_validation->run()) {
			$lab_id = $this->input->post('record');

			// Fetch lab record
			$lab = $this->Admin_model->get_lab_by_id($lab_id);
			if (!$lab) {
				$data['type'] = 'error';
				$data['alert'] = [
					'title' => $this->lang('error'),
					'text' => $this->lang('lab not found'),
					'type' => 'error'
				];
				echo json_encode($data);
				return;
			}

			// Fetch patient info
			$patient = $this->Admin_model->get_patient_by_id($lab['patient_id']);

			// Get tooth names from comma-separated IDs
			$toothNames = [];
			$toothIds = explode(',', $lab['teeth']);
			foreach ($toothIds as $toothId) {
				$toothInfo = $this->tooth_by_id(trim($toothId));
				if (!empty($toothInfo)) {
					$toothNames[] = $toothInfo['location'] . $toothInfo['name'];
				}
			}

			// Format translated types
			$types = explode(',', $lab['type']);
			$translatedTypes = array_map(function ($type) {
				return $this->lang($type);
			}, $types);

			// Compose remarks
			$remarks = $patient['name'] . ' ' . $patient['lname'] . ' - ' . $this->lang('teeth') . ': ' . implode(', ', $toothNames) . ' | ' . implode(', ', $translatedTypes);

			// Build balance_sheet data
			$balanceData = [
				'dr' => $lab['dr'],
				'shamsi' => $this->mylibrary->getCurrentShamsiDate()['date'],
				'customers_id' => $lab['customers_id'],
				'users_id' => $this->session->userdata($this->mylibrary->hash_session('u_id')),
				'remarks' => $remarks
			];

			// Insert into balance_sheet
			$insert_id = $this->Admin_model->insert_balance_sheet($balanceData);

			if ($insert_id) {
				// Update lab status to 'm' (paid)
				$this->Admin_model->update_lab(['pay_datetime' => $this->mylibrary->getCurrentShamsiDate()['date'] . date('H:i:s'), 'status' => 'm'], ['id' => $lab_id]);

				$data['type'] = 'success';
				$data['balance_id'] = $insert_id;
				$data['alert'] = [
					'title' => $this->lang('success'),
					'text' => $this->lang('lab payment recorded'),
					'type' => 'success'
				];
			} else {
				$data['type'] = 'error';
				$data['alert'] = [
					'title' => $this->lang('error'),
					'text' => $this->lang('problem'),
					'type' => 'error'
				];
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		echo json_encode($data);
	}


	public function print_lab($id)
	{
		$data['title'] = $this->lang('laboratory');
		$data['page'] = "turns";
		if (is_null($id)) {
			redirect(base_url() . 'admin/labs/');
		}

		$lab = $this->Admin_model->single_lab_print($id);
		if (count($lab) !== 1) {
			show_404();
		} else {
			$data['single'] = $lab[0];
			$data['title'] = 'print';
			$this->load->view("prints/lab", $data);
		}
	}


	public function check_balance($id = null)
	{
		$sum_cr = 0;
		$sum_dr = 0;
		$dr = $this->Admin_model->get_teeth_by_id($id);
		$cr = $this->Admin_model->get_turn_by_id($id);
		if (count($dr) !== 0) {
			foreach ($dr as $patient_dr) {
				$sum_dr += $patient_dr['price'];
			}
		}

		if (count($cr) !== 0) {
			foreach ($cr as $patient_cr) {
				$sum_cr += $patient_cr['cr'];
			}
		}

		$sum = $sum_dr - $sum_cr;

		$array = array(
			'sum_cr' => $sum_cr,
			'sum_dr' => $sum_dr,
			'sum' => $sum,
		);

		return $array;
	}


	public function balance_json()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$id = $this->input->post('record');

			$sum_cr = 0;
			$sum_dr = 0;
			$dr = $this->Admin_model->get_teeth_by_id($id);
			$cr = $this->Admin_model->get_turn_by_id($id);
			if (count($dr) !== 0) {
				foreach ($dr as $patient_dr) {
					$sum_dr += $patient_dr['price'];
				}
			}

			if (count($cr) !== 0) {
				foreach ($cr as $patient_cr) {
					$sum_cr += $patient_cr['cr'];
				}
			}

			$sum = $sum_dr - $sum_cr;

			$percentage = ($sum_dr != 0) ? ($sum_cr * 100) / $sum_dr : 100;

			$array = array(
				'sum_cr' => $sum_cr,
				'sum_dr' => $sum_dr,
				'sum' => $sum,
				'percentage_text' => $this->language->languages('payment percent status', null, round($percentage))
			);

			$data['type'] = 'success';
			$data['balance'] = $array;
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}


	public function accounts()
	{
		$this->check_permission_page('View Accounts');
		$data['title'] = $this->lang('accounts');
		$data['page'] = "accounts";
		$data['accounts'] = $this->Admin_model->get_accounts();

		$this->load->view('header', $data);
		$this->load->view('accounts', $data);
		$this->load->view('footer');
	}

	public function delete_account()
	{
		$this->check_permission_function('Delete Account');
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$id = $this->input->post('record');
			$receipts = $this->Admin_model->account_receipts($id);
			if (count($receipts) !== 0) {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('finance account has receipts');
				$data['alert']['type'] = 'error';
				print_r(json_encode($data));
				exit;
			}

			$datas = array(
				'id' => $id
			);

			if ($this->Admin_model->delete_account($datas)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('delete account');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}


	public function insert_account()
	{
		$this->check_permission_function('Create New Account');
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('name', 'name', 'trim|required', array('required' => $this->lang('insert account name error')));
		$this->form_validation->set_rules('phone', 'phone', 'trim');
		$this->form_validation->set_rules('lname', 'lname', 'trim');
		$this->form_validation->set_rules('type', 'type', 'trim|required', array('required' => $this->lang('insert account error type')));
		if ($this->form_validation->run()) {
			$datas = array(
				'name' => $this->input->post('name'),
				'lname' => $this->input->post('lname'),
				'phone' => $this->input->post('phone'),
				'type' => $this->input->post('type'),
				'users_id' => $this->session->userdata($this->mylibrary->hash_session('u_id'))
			);
			$insert = $this->Admin_model->insert_account($datas);
			if ($insert[0]) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('insert account success');
				$data['alert']['type'] = 'success';

				$data['id'] = $insert[1];

				$btns = '';
				$btns .= $this->mylibrary->generateBtnUpdate('edit_account', $data['id']);

				$btns .= $this->mylibrary->generateBtnDelete($insert[1], 'admin/delete_account');


				$fullname = $this->mylibrary->account_name($this->session->userdata($this->mylibrary->hash_session('u_fullname')), '', $this->session->userdata($this->mylibrary->hash_session('u_type')));

				$data['tr'] = array(
					$datas['name'],
					$datas['lname'],
					$datas['phone'],
					$this->lang($this->mylibrary->check_account_type($datas['type'])),
					$fullname,
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}


	public function single_account()
	{
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('slug');
			$datas = array(
				'id' => $record
			);
			$account = $this->Admin_model->single_account($datas);


			if (count($account) > 0) {
				$data['type'] = 'success';

				$data['content'] = array(
					'slug' => $account[0]['id'],
					'name' => $account[0]['name'],
					'lname' => $account[0]['lname'],
					'type' => $account[0]['type'],
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}


	public function update_account()
	{
		$this->check_permission_function('Update Account');
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('name', 'name', 'trim|required', array('required' => $this->lang('insert account name error')));
		$this->form_validation->set_rules('phone', 'phone', 'trim');
		$this->form_validation->set_rules('lname', 'lname', 'trim');
		$this->form_validation->set_rules('type', 'type', 'trim|required', array('required' => $this->lang('insert account error type')));
		if ($this->form_validation->run()) {
			$datas = array(
				'name' => $this->input->post('name'),
				'lname' => $this->input->post('lname'),
				'phone' => $this->input->post('phone'),
				'type' => $this->input->post('type'),
			);
			$id = $this->input->post('slug');
			$update = $this->Admin_model->update_account($datas, $id);
			if ($update) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('update account success');
				$data['alert']['type'] = 'success';

				$data['id'] = $id;

				$btns = '';

				$btns .= $this->mylibrary->generateBtnUpdate('edit_account', $data['id']);

				$btns .= $this->mylibrary->generateBtnDelete($data['id'], 'admin/delete_account');

				$account = $this->Admin_model->single_account_with_user($id)[0];
				$data['tr'] = array(
					$account['name'],
					$account['lname'],
					$account['phone'],
					$this->lang($this->mylibrary->check_account_type($account['type'])),
					$this->mylibrary->account_name($account['fname'], $account['lastname'], $account['role']),
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	public function receipts()
	{
		$data['title'] = $this->lang('receipts');
		$data['page'] = "receipts";
		$data['receipts'] = $this->Admin_model->get_receipts();
		$data['accounts'] = $this->Admin_model->get_account_with_no_user();
		$data['script'] = $this->mylibrary->generateSelect2();
		$data['script_date'] = $this->mylibrary->script_datepicker();
		$data['script_date'] .= $this->mylibrary->script_datepicker('update_date', 'div#update-date-div', 'update_date');
		$this->load->view('header', $data);
		$this->load->view('receipts', $data);
		$this->load->view('footer');
	}

	public function insert_receipt()
	{
		$this->check_permission_function('Create Expenses');
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('customers_id', 'customers_id', 'trim|required', array('required' => $this->lang('insert receipt customers_id error')));
		$this->form_validation->set_rules('type', 'type', 'trim|required', array('required' => $this->lang('insert receipt type error')));
		$this->form_validation->set_rules('amount', 'amount', 'trim|required', array('required' => $this->lang('insert receipt amount error')));
		$this->form_validation->set_rules('shamsi', 'shamsi', 'trim|required', array('required' => $this->lang('insert receipt date error')));
		$this->form_validation->set_rules('remarks', 'remarks', 'trim');
		if ($this->form_validation->run()) {

			$datas = array(
				'customers_id' => $this->input->post('customers_id'),
				'shamsi' => $this->input->post('shamsi'),
				'remarks' => $this->input->post('remarks'),
				'users_id' => $this->session->userdata($this->mylibrary->hash_session('u_id'))
			);

			$type = $this->input->post('type');
			if ($type == 'cr') {
				$datas['cr'] = $this->input->post('amount');
			} else {
				$datas['dr'] = $this->input->post('amount');
			}

			$insert = $this->Admin_model->insert_receipt($datas);
			if ($insert[0]) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('insert receipt success');
				$data['alert']['type'] = 'success';
				$data['extraFunction'] = 'print';
				$data['id'] = $insert[1];

				$btns = '';
				$btns .= $this->mylibrary->generateBtnUpdate('edit_receipt', $data['id']);

				$btns .= $this->mylibrary->generateBtnDelete($insert[1], 'admin/delete_receipt');


				$x = $this->Admin_model->get_single_balance_with_join($insert[1])[0];

				$data['tr'] = array(
					$this->mylibrary->finance_account_name($x['name'], $x['lname'], $x['type']),
					$this->lang($type),
					$this->input->post('amount'),
					$x['shamsi'],
					$x['user'],
					$x['remarks'],
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}

	public function single_receipt()
	{
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));

		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('slug');
			$datas = array(
				'id' => $record
			);
			$receipt = $this->Admin_model->balance_by_id($datas);


			if (count($receipt) > 0) {
				$data['type'] = 'success';

				$data['content'] = array(
					'slug' => $receipt[0]['id'],
					'customers_id' => $receipt[0]['customers_id'],
					'shamsi' => $receipt[0]['shamsi'],
					'remarks' => $receipt[0]['remarks'],
				);
				if ($receipt[0]['dr'] == 0) {
					$data['content']['type'] = 'cr';
					$data['content']['amount'] = $receipt[0]['cr'];
				} else {
					$data['content']['type'] = 'dr';
					$data['content']['amount'] = $receipt[0]['dr'];
				}
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function update_receipt()
	{
		$this->check_permission_function('Update Receipt');
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('slug', 'slug', 'trim|required', array('required' => $this->lang('insert receipt customers_id error')));
		$this->form_validation->set_rules('customers_id', 'customers_id', 'trim|required', array('required' => $this->lang('insert receipt customers_id error')));
		$this->form_validation->set_rules('type', 'type', 'trim|required', array('required' => $this->lang('insert receipt type error')));
		$this->form_validation->set_rules('amount', 'amount', 'trim|required', array('required' => $this->lang('insert receipt amount error')));
		$this->form_validation->set_rules('shamsi', 'shamsi', 'trim|required', array('required' => $this->lang('insert receipt date error')));
		$this->form_validation->set_rules('remarks', 'remarks', 'trim');

		if ($this->form_validation->run()) {
			$datas = array(
				'customers_id' => $this->input->post('customers_id'),
				'shamsi' => $this->input->post('shamsi'),
				'remarks' => $this->input->post('remarks'),
			);

			$type = $this->input->post('type');
			if ($type == 'cr') {

				$datas['dr'] = 0;
				$datas['cr'] = $this->input->post('amount');
			} else {
				$datas['dr'] = $this->input->post('amount');
				$datas['cr'] = 0;
			}
			$id = $this->input->post('slug');
			$update = $this->Admin_model->update_balance($datas, $id);
			if ($update) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('update receipt success');
				$data['alert']['type'] = 'success';

				$data['id'] = $id;

				$btns = '';

				$btns .= $this->mylibrary->generateBtnUpdate('edit_receipt', $data['id']);

				$btns .= $this->mylibrary->generateBtnDelete($data['id'], 'admin/delete_receipt');

				$x = $this->Admin_model->get_single_balance_with_join($id)[0];

				$data['tr'] = array(
					$this->mylibrary->finance_account_name($x['name'], $x['lname'], $x['type']),
					$this->lang($type),
					$this->input->post('amount'),
					$x['shamsi'],
					$x['user'],
					$x['remarks'],
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	public function demo()
	{
		$data['title'] = $this->lang('receipts');
		$data['page'] = "receipts";
		$data['turns'] = $this->Admin_model->turns_by_patient_id(1507);
		$data['services'] = $this->Admin_model->get_services();

		$data['medicines'] = $this->Admin_model->get_medicines();
		$data['receipts'] = $this->Admin_model->get_receipts();
		$data['accounts'] = $this->Admin_model->get_account_with_no_user();
		$data['script'] = $this->mylibrary->generateSelect2();
		$this->load->view('header', $data);
		$this->load->view('demo5', $data);
		$this->load->view('footer');
	}


	public function delete_receipt()
	{
		$this->check_permission_function('Delete Receipt');
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$datas = array(
				'id' => $this->input->post('record')
			);

			if ($this->Admin_model->delete_receipt($datas)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('delete receipt');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}


	// Start Turns

	public function turns()
	{
		$data['title'] = $this->lang('turns');
		$data['page'] = "turns";
		$data['receipts'] = $this->Admin_model->get_turns_page();
		$data['patients'] = $this->Admin_model->get_patients();
		$data['doctors'] = $this->Admin_model->get_doctors();
		$data['script'] = $this->mylibrary->generateSelect2();
		$data['script_date'] = $this->mylibrary->script_datepicker();
		$this->load->view('header', $data);
		$this->load->view('turns', $data);
		$this->load->view('footer');
	}

	public function get_teeth_by_patient()
	{
		$this->form_validation->set_rules('patient_id', 'patient_id', 'trim|required|is_natural_no_zero', [
			'required' => $this->lang('problem'),
			'is_natural_no_zero' => $this->lang('problem')
		]);

		if ($this->form_validation->run()) {
			$patient_id = $this->input->post('patient_id');

			$teeth = $this->Admin_model->get_teeth_by_patient_id($patient_id);

			if (!empty($teeth)) {
				$data['type'] = 'success';
				$data['content']['teeth'] = $teeth;
			} else {
				$data['type'] = 'error';
				$data['alert'] = [
					'title' => $this->lang('error'),
					'text' => $this->lang('no teeth found'),
					'type' => 'error'
				];
			}
		} else {
			$data['type'] = 'error';
			$data['alert'] = [
				'title' => $this->lang('error'),
				'text' => $this->lang('problem'),
				'type' => 'error'
			];
		}

		echo json_encode($data);
	}


	public function get_tooth_processes_by_teeth()
	{

		$this->form_validation->set_rules('teeth_ids[]', 'Teeth IDs', 'required');
		$this->form_validation->set_rules('patient_id', 'Patient ID', 'required');

		if (!$this->form_validation->run()) {
			echo json_encode([
				'type' => 'error',
				'alert' => [
					'title' => $this->lang('error'),
					'text' => $this->lang('problem'),
					'type' => 'error'
				]
			]);
			return;
		}

		$teeth_ids = $this->input->post('teeth_ids');
		$patient_id = $this->input->post('patient_id');

		if (!is_array($teeth_ids)) {
			echo json_encode([
				'type' => 'error',
				'alert' => [
					'title' => $this->lang('error'),
					'text' => $this->lang('invalid tooth data'),
					'type' => 'error'
				]
			]);
			return;
		}

		$results = [];

		foreach ($teeth_ids as $tooth_id) {
			$tooth_info = $this->Admin_model->get_tooth_basic_info($tooth_id);

			if (!$tooth_info) continue;

			$departments = ['endo', 'restorative', 'prosthodontics'];
			$tooth_data = [
				'tooth_id' => $tooth_id,
				'tooth_name' => $tooth_info['location'] . ' ' . $tooth_info['name'],
				'departments' => []
			];

			foreach ($departments as $dept) {
				$dept_data = $this->Admin_model->get_department_services_with_processes($tooth_id, $dept);
				if (!empty($dept_data)) {
					$tooth_data['departments'][] = [
						'department' => $dept,
						'services' => $dept_data
					];
				}
			}

			// Include recommended processes (with turn_id = NULL)
			$tooth_data['recommended'] = $this->Admin_model->get_unassigned_recommended_processes($patient_id, $tooth_id);

			// Include already done processes (for disabling in frontend)
			$tooth_data['done'] = $this->Admin_model->get_done_process_ids_by_tooth($tooth_id);
			$results[] = $tooth_data;
		}

		echo json_encode([
			'type' => 'success',
			'content' => $results
		]);
	}


	public function get_treatment_summary()
	{
		$this->form_validation->set_rules('turn_id', 'Turn ID', 'required|is_natural_no_zero');

		if (!$this->form_validation->run()) {
			echo json_encode([
				'type' => 'error',
				'message' => $this->lang('error')
			]);
			return;
		}

		$turn_id = $this->input->post('turn_id');

		// Get teeth related to this turn (whether recommended or done processes)
		$this->db->select('ttr.tooth_id, t.name, t.location');
		$this->db->from('turn_tooth_recommended ttr');
		$this->db->join('tooth t', 't.id = ttr.tooth_id');
		$this->db->where('ttr.turn_id', $turn_id);
		$this->db->group_by('ttr.tooth_id');
		$teeth = $this->db->get()->result_array();

		$result = [];

		// Check if no recommended processes were found, in that case fetch done processes directly
		if (empty($teeth)) {
			// No recommended processes found, so we'll get the done processes directly for all teeth
			$this->db->select('t.id as tooth_id, t.name, t.location');
			$this->db->from('tooth t');
			$this->db->join('turn_tooth_done ttd', 'ttd.tooth_id = t.id');
			$this->db->where('ttd.turn_id', $turn_id);
			$this->db->group_by('t.id');
			$teeth = $this->db->get()->result_array();
		}

		// Loop through each tooth and handle the departments, recommended and done processes
		foreach ($teeth as $tooth) {
			$tooth_id = $tooth['tooth_id'];
			$tooth_data = [
				'tooth_id' => $tooth_id,
				'tooth_name' => $tooth['location'] . ' ' . $tooth['name'],
				'departments' => []
			];

			$departments = ['endo', 'restorative', 'prosthodontics'];

			foreach ($departments as $dept) {
				// Check if there are recommended processes for this department
				$this->db->select('ttr.process_id, ttr.custom_label, p.name as process_name');
				$this->db->from('turn_tooth_recommended ttr');
				$this->db->join('processes p', 'p.id = ttr.process_id', 'left');
				$this->db->where('ttr.turn_id', $turn_id);
				$this->db->where('ttr.tooth_id', $tooth_id);
				$this->db->where('ttr.remarks', $dept);
				$recommended = $this->db->get()->result_array();

				$rec_list = [];
				foreach ($recommended as $rec) {
					$rec_list[] = [
						'label' => $rec['custom_label'] ?? $rec['process_name'],
						'process_id' => $rec['process_id']
					];
				}

				// If no recommended processes, we fall back to done processes
				if (empty($rec_list)) {
					// Get done processes for the department
					$this->db->select('process_id, custom_label, remarks');
					$this->db->from('turn_tooth_done');
					$this->db->where('turn_id', $turn_id);
					$this->db->where('tooth_id', $tooth_id);
					$this->db->where('remarks', $dept);
					$done = $this->db->get()->result_array();

					$done_list = [];
					$custom_text = '';

					foreach ($done as $dp) {
						$label = $dp['custom_label'] ?? '';
						if ($dp['process_id']) {
							$label = $this->Admin_model->get_process_name($dp['process_id']);
						}

						if ($dp['process_id']) {
							$done_list[] = ['label' => $label, 'process_id' => $dp['process_id'], 'type' => 'matched'];
						} elseif ($dp['custom_label']) {
							$done_list[] = ['label' => $dp['custom_label'], 'process_id' => null, 'type' => 'custom'];
							$custom_text .= $dp['custom_label'] . ", ";
						}
					}

					$tooth_data['departments'][] = [
						'name' => $dept,
						'recommended' => $rec_list,
						'done' => $done_list,
						'done_custom_text' => rtrim($custom_text, ', ')
					];
				} else {
					// If recommended processes are available, return them
					$tooth_data['departments'][] = [
						'name' => $dept,
						'recommended' => $rec_list,
						'done' => [],  // Empty done list as recommended is available
						'done_custom_text' => ''
					];
				}
			}

			$result[] = $tooth_data;
		}

		echo json_encode([
			'type' => 'success',
			'content' => $result
		]);
	}


	public function get_patient_process_completion()
	{
		$this->form_validation->set_rules('patient_id', 'Patient ID', 'trim|required|is_natural_no_zero');

		if (!$this->form_validation->run()) {
			echo json_encode([
				'type' => 'error',
				'alert' => [
					'title' => $this->lang('error'),
					'text' => $this->lang('invalid patient'),
					'type' => 'error'
				]
			]);
			return;
		}

		$patient_id = $this->input->post('patient_id');
		$percentage = $this->Admin_model->calculate_patient_process_completion($patient_id);

		echo json_encode([
			'type' => 'success',
			'percentage' => $percentage,
			'percentage_text' => $this->language->languages('payment percent status', null, $percentage)
		]);
	}

	public function get_recommended_by_turn()
	{
		$turn_id = $this->input->post('turn_id');

		if (!is_numeric($turn_id)) {
			echo json_encode([
				'type' => 'error',
				'alert' => [
					'title' => $this->lang('error'),
					'text' => $this->lang('invalid turn id'),
					'type' => 'error'
				]
			]);
			return;
		}

		$this->db->select('patient_id');
		$this->db->from('turn');
		$this->db->where('id', $turn_id);
		$turn = $this->db->get()->row_array();

		if (!$turn) {
			echo json_encode([
				'type' => 'error',
				'alert' => [
					'title' => $this->lang('error'),
					'text' => $this->lang('turn not found'),
					'type' => 'error'
				]
			]);
			return;
		}

		$recommended = $this->Admin_model->get_recommended_by_turn($turn_id);

		if (empty($recommended)) {
			// Fallback to show all processes for patient's teeth if none are recommended
			$teeth = $this->Admin_model->get_patient_teeth($turn['patient_id']); // Make sure this returns an array of teeth with id, name, location
			$fallback = [];

			foreach ($teeth as $tooth) {
				$departments = ['endo', 'restorative', 'prosthodontics'];
				$toothData = [
					'tooth_id' => $tooth['id'],
					'tooth_name' => $tooth['location'] . ' ' . $tooth['name'],
					'departments' => []
				];

				foreach ($departments as $dept) {
					$services = $this->Admin_model->get_department_services_with_processes($tooth['id'], $dept);
					$deptProcesses = [];

					foreach ($services as $service) {
						if (!empty($service['processes'])) {
							foreach ($service['processes'] as $proc) {
								$deptProcesses[] = [
									'process_id' => $proc['id'],
									'label' => $proc['name']
								];
							}
						}
					}

					$toothData['departments'][] = [
						'name' => $dept,
						'processes' => $deptProcesses,
						'custom' => ''
					];
				}

				$fallback[] = $toothData;
			}

			echo json_encode([
				'type' => 'success',
				'content' => $fallback
			]);
			return;
		}

		echo json_encode([
			'type' => 'success',
			'content' => $recommended
		]);
	}

	public function insert_recommended_processes()
	{
		$this->form_validation->set_rules('patient_id', 'Patient ID', 'required|is_natural_no_zero');
		if (!$this->form_validation->run()) {
			echo json_encode([
				'type' => 'error',
				'alert' => [
					'title' => $this->lang('error'),
					'text' => $this->lang('invalid patient'),
					'type' => 'error'
				]
			]);
			return;
		}

		$patient_id = $this->input->post('patient_id');
		$tooth_ids = $this->input->post('tooth_id');
		$processes = $this->input->post('processes');
		$custom_processes = $this->input->post('custom_process');
		$user_id = $this->session->userdata($this->mylibrary->hash_session('u_id'));

		foreach ($tooth_ids as $tooth_id) {
			// Save checked processes
			if (!empty($processes[$tooth_id])) {
				foreach ($processes[$tooth_id] as $process_id) {
					$this->Admin_model->insert_recommended_process([
						'turn_id' => null,
						'tooth_id' => $tooth_id,
						'process_id' => $process_id, // ✅ Use actual ID
						'custom_label' => null,      // ✅ No label needed
						'remarks' => null,
						'users_id' => $user_id,
					]);
				}

			}

			// Save "other" (custom) text areas
			if (!empty($custom_processes[$tooth_id])) {
				foreach ($custom_processes[$tooth_id] as $dept => $label) {
					if (trim($label) !== '') {
						$this->Admin_model->insert_recommended_process([
							'turn_id' => null,
							'tooth_id' => $tooth_id,
							'process_id' => null,
							'custom_label' => $label,
							'remarks' => $dept,
							'users_id' => $user_id,
						]);
					}
				}
			}
		}

		echo json_encode([
			'type' => 'success',
			'alert' => [
				'title' => $this->lang('success'),
				'text' => $this->lang('recommended processes inserted'),
				'type' => 'success'
			]
		]);
	}

	public function assign_recommendations_to_turn($patient_id, $turn_id)
	{
		$this->load->model('Admin_model');

		$teeth = $this->Admin_model->get_teeth_ids_by_patient($patient_id);
		if (empty($teeth)) return;

		$this->db->where('turn_id', 0);
		$this->db->where_in('tooth_id', $teeth);
		$this->db->update('turn_tooth_recommended', ['turn_id' => $turn_id]);
	}


	function list_turns_json()
	{
		$this->form_validation->set_rules('date', 'date', 'trim');
		$this->form_validation->set_rules('doctor', 'doctor', 'trim');
		if ($this->form_validation->run()) {
			$data = array();
			$date = $this->input->post('date');
			$doctor = $this->input->post('doctor');


// Pass both parameters correctly
			$turns = $this->Admin_model->get_turns_page($date ?: null, $doctor ?: null);


			$data['type'] = 'success';
			if (count($turns) > 0) {
				$i = 1;
				foreach ($turns as $turn) {
					$data['content'][] = array(
						'number' => $i,
						'id' => $turn['id'],
						'patient_name' => $this->mylibrary->get_patient_name($turn['name'], $turn['lname'], '', $turn['gender']),
						'patient_id' => $turn['patient_id'],
						'doctor_name' => $turn['doctor_name'],
						'date' => $turn['date'],
						'time' => $turn['from_time'] . ' - ' . $turn['to_time'],
						'profile_access' => $this->auth->has_permission('Read Patient Profile'),
					);
					$i++;
				}
			} else {
				$data['content'] = array();
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function list_turns_json_dashboard()
	{
		// Validate input
		$this->form_validation->set_rules('doctor_id', 'doctor_id', 'trim', array('required' => $this->lang('problem')));

		if ($this->form_validation->run()) {
			$data = array();
			$doctor_id = $this->input->post('doctor_id');
			$date = $this->mylibrary->getCurrentShamsiDate()['date'];

			// Build extra query condition
			$extra = " AND turn.date = '$date'";
			if ($doctor_id != '0') {
				$extra .= " AND turn.doctor_id = '$doctor_id'";
			}

			// Fetch turns based on the conditions
			$turns = $this->Admin_model->get_turns_extra($extra);

			$data['type'] = 'success';

			if (count($turns) > 0) {
				$i = 1;
				foreach ($turns as $turn) {
					$data['content'][] = array(
						'number' => $i,
						'id' => $turn['id'],
						'patient_name' => $this->mylibrary->get_patient_name(
							$turn['name'],
							$turn['lname'],
							'',
							$turn['gender']
						),
						'patient_id' => $turn['patient_id'],
						'doctor_name' => $turn['doctor_name'],
						'date' => $turn['date'],
						'hour' => $turn['from_time'] . ' - ' . $turn['to_time'], // Combine time range
						'profile_access' => $this->auth->has_permission('Read Patient Profile'),
					);
					$i++;
				}
			} else {
				$data['content'] = array(); // No turns found
			}
		} else {
			// Validation error response
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		// Output the response as JSON
		print_r(json_encode($data));
	}


	public function print_turn($id)
	{
		$data['title'] = $this->lang('turns');
		$data['page'] = "turns";
		if (is_null($id)) {
			redirect(base_url() . 'admin/turns/');
		}
		$x = $this->Admin_model->turns_factor($id);
		if (count($x) !== 1) {
			show_404();
		} else {
			$check = $this->check_balance($x[0]['patient_id']);
			$data['balance'] = $check['sum'];
			$data['sum_cr'] = $check['sum_cr'];
			$data['sum_dr'] = $check['sum_dr'];

			$data['single'] = $this->Admin_model->turns_factor($id)[0];
			$data['title'] = 'نوبت ' . $x[0]['name'] . ' ' . $x[0]['lname'];
			$data['single'] = $x[0];
			$this->load->view("prints/turn", $data);
		}
	}

	function sms_turns_list()
	{
		$this->form_validation->set_rules('date', 'date', 'trim', array('required' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$date = $this->input->post('date');
			$turns = $this->Admin_model->get_turns($date);


			$data['type'] = 'success';
			if (count($turns) > 0) {
				$i = 1;
				foreach ($turns as $turn) {
					$message = "سلام ";
					$message .= $this->mylibrary->get_patient_name($turn['name'], $turn['lname'], '', $turn['gender']);
					$message .= "
شما به تاریخ ";
					$message .= $turn['date'];
					$message .= " ساعت";
					$message .= $this->dentist->find_time($turn['hour']);
					$message .= ". نوبت تداوی برای دندان خود دارید 
لطفا به وقت معینه حاضر شوید.
“دیپارتمنت دندان شفاخانه تخصصی رازی”
          ";
					if ($this->mylibrary->sendSms("+93" . $turn['phone1'], $message)) {
						$data['alert']['title'] = $this->lang('success');
						$data['alert']['text'] = $this->lang('sms sent');
						$data['alert']['type'] = 'success';
					}
				}
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('sms sent');
				$data['alert']['type'] = 'success';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}


	public function print_payment($id)
	{
		$data['title'] = $this->lang('turns');
		$data['page'] = "turns";
		if (is_null($id)) {
			redirect(base_url() . 'admin/labs/');
		}

		$turn = $this->Admin_model->turns_factor($id);
		if (count($turn) !== 1) {
			show_404();
		} else {
			$check = $this->check_balance($turn[0]['patient_id']);
			$data['balance'] = $check['sum'];
			$data['sum_cr'] = $check['sum_cr'];
			$data['sum_dr'] = $check['sum_dr'];

			$data['single'] = $turn[0];
			$data['title'] = 'print';
			$this->load->view("prints/turn_payment", $data);
		}
	}

	public function check_turns()
	{
		// Validate input
		$this->form_validation->set_rules('doctor', 'Doctor', 'trim|required');
		$this->form_validation->set_rules('date', 'Date', 'trim|required');
		$this->form_validation->set_rules('patient_time', 'Patient Time', 'trim');

		if ($this->form_validation->run()) {
			$doctor_id = $this->input->post('doctor');
			$date = $this->input->post('date');
			$patient_time = $this->input->post('patient_time');

			// Fetch available time slots for the doctor
			$available_slots = $this->Admin_model->calculate_available_time_slots($doctor_id, $date);

			if (empty($available_slots)) {
				// No available slots or doctor on leave
				$response = [
					'type' => 'error',
					'alert' => [
						'title' => 'No Available Slots',
						'text' => 'The doctor is either unavailable or on leave.',
						'type' => 'error'
					]
				];
			} else {
				// Return the available slots
				$response = [
					'type' => 'success',
					'content' => [
						'time_slots' => $available_slots
					]
				];
			}
		} else {
			// Validation error
			$response = [
				'type' => 'error',
				'alert' => [
					'title' => 'Invalid Input',
					'text' => 'Please ensure all fields are correctly filled.',
					'type' => 'error'
				]
			];
		}

		// Output response as JSON
		echo json_encode($response);
	}


	public function list_patients()
	{

		$this->form_validation->set_rules('serial_id', 'serial_id', 'trim');
		$this->form_validation->set_rules('fullname', 'fullname', 'trim');
		if ($this->form_validation->run()) {
			$serial_id = $this->input->post('serial_id');
			$fullname = $this->input->post('fullname');

			if ($fullname != '' && $serial_id != '') {
				$extra = "patient.serial_id = '$serial_id' AND CONCAT(patient.name, ' ', patient.lname) LIKE '%$fullname%'";
				$patients = $this->Admin_model->get_patients_extra($extra);
			} elseif ($serial_id != '') {
				$extra = "patient.serial_id = '$serial_id'";
				$patients = $this->Admin_model->get_patients_extra($extra);
			} elseif ($fullname != '') {
				$extra = "CONCAT(patient.name, ' ', patient.lname) LIKE '%$fullname%'";
				$patients = $this->Admin_model->get_patients_extra($extra);
			} else {
				$patients = array();
			}

			$datas = array();

			foreach ($patients as $patient) {
				$name = $patient['name'] . ' ' . $patient['lname'];
				$datas[] = array(
					'serial_id' => $patient['serial_id'],
					'fullname' => $name,
					'phone1' => $patient['phone1'],
					'id' => $patient['id'],
					'doctor_name' => $patient['doctor_name'],
					'pains' => $patient['pains'],
					'profile_access' => $this->auth->has_permission('Read Patient Profile'),
				);
			}

			$data['content']['patients'] = $datas;

			if (count($patients) >= 0) {
				$data['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}


	public function check_turns_page()
	{
		$this->form_validation->set_rules('doctor', 'doctor', 'trim');
		$this->form_validation->set_rules('date', 'date', 'trim');
		$this->form_validation->set_rules('patient_id', 'date', 'trim');
		$this->form_validation->set_rules('hour', 'date', 'trim');
		if ($this->form_validation->run()) {
			$doctor = $this->input->post('doctor');
			$date = $this->input->post('date');
			$patient_id = $this->input->post('patient_id');
			$hour = $this->input->post('hour');

			if (!empty($doctor)) {
				$turns = $this->Admin_model->check_turns($date, $doctor);
			} else {
				$turns = $this->Admin_model->check_turns($date);
			}

			$datas = array();

			foreach ($turns as $turn) {
				$datas[] = array(
					'patient_name' => $this->mylibrary->get_patient_name($turn['name'], $turn['lname'], $turn['serial_id']),
					'doctor_name' => $turn['doctor_name'],
					'hour' => $this->dentist->find_time($turn['hour']),
				);
			}

			$data['content']['turns'] = $datas;

			if (count($turns) >= 0) {
				$data['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function single_turn()
	{
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('slug');
			$datas = array(
				'id' => $record
			);
			$turn = $this->Admin_model->single_turn($datas);


			if (count($turn) > 0) {
				$data['type'] = 'success';

				$data['content'] = array(
					'slug' => $turn[0]['id'],
					'date' => $turn[0]['date'],
					'doctor_id' => $turn[0]['doctor_id'],
					'from_time' => $turn[0]['from_time'],
					'to_time' => $turn[0]['to_time'],
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function list_turns_pending()
	{
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('slug');
			$datas = array(
				'patient_id' => $record,
				'status' => 'p'
			);
			$turns = $this->Admin_model->turn_by_patient($datas);
			if (count($turns) > 0) {
				$data['type'] = 'success';

				foreach ($turns as $turn) {
					$data['content']['turns'][] = array('date' => $turn['date'], 'id' => $turn['id'], 'hour_key' => '1', 'hour' => $turn['from_time'] . ' - ' . $turn['to_time']);
				}
			} else {
				$data['type'] = 'success';
				$data['content']['turns'] = array();
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function list_turns_payment_pending()
	{
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('slug');
			$datas = array(
				'patient_id' => $record,
				'payment_status' => 'p'
			);
			$turns = $this->Admin_model->turn_by_patient($datas);
			if (count($turns) > 0) {
				$data['type'] = 'success';

				foreach ($turns as $turn) {
					$data['content']['turns'][] = array('date' => $turn['date'], 'id' => $turn['id'], 'hour_key' => '1', 'hour' => $turn['from_time'] . ' - ' . $turn['to_time']);
				}
			} else {
				$data['type'] = 'success';
				$data['content']['turns'] = array();
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}


	public function update_turn()
	{
//		print_r($_POST);
//		exit();
		$data = ['type' => 'form_error', 'messages' => []];

		// === Validation Rules ===
		$this->form_validation->set_rules('patient_id', 'patient_id', 'trim|required', [
			'required' => $this->lang('insert turn patient_id error')
		]);
		$this->form_validation->set_rules('date', 'date', 'trim|required', [
			'required' => $this->lang('insert turn date error')
		]);
		$this->form_validation->set_rules('dateOld', 'date', 'trim|required', [
			'required' => $this->lang('insert turn date error')
		]);
		$this->form_validation->set_rules('doctor_id', 'doctor_id', 'trim|required', [
			'required' => $this->lang('insert turn doctor_id error')
		]);
		$this->form_validation->set_rules('doctorOld', 'doctor_id', 'trim|required', [
			'required' => $this->lang('insert turn doctor_id error')
		]);
		$this->form_validation->set_rules('from_time', 'from_time', 'trim|required', [
			'required' => $this->lang('insert turn hour error')
		]);
		$this->form_validation->set_rules('to_time', 'to_time', 'trim|required', [
			'required' => $this->lang('insert turn hour error')
		]);
		$this->form_validation->set_rules('fromTimeOld', 'from_time', 'trim|required');
		$this->form_validation->set_rules('toTimeOld', 'to_time', 'trim|required');

		if (!$this->form_validation->run()) {
			foreach ($_POST as $key => $value) {
				if (form_error($key)) {
					$data['messages'][] = strip_tags(form_error($key));
				}
			}
			$data['title'] = $this->lang('error');
			echo json_encode($data);
			return;
		}

		// === Extract and Compare Times ===
		$dateOld = $this->input->post('dateOld');
		$date = $this->input->post('date');
		$fromOld = $this->input->post('fromTimeOld');
		$fromNew = $this->input->post('from_time');
		$toOld = $this->input->post('toTimeOld');
		$toNew = $this->input->post('to_time');
		$doctorOld = $this->input->post('doctorOld');
		$doctor_id = $this->input->post('doctor_id');
		$id = $this->input->post('slug');

		$isSameTime = ($date === $dateOld) && ($fromOld === $fromNew) && ($toOld === $toNew);

		$hasConflict = $this->Admin_model->check_turn_conflict($date, $doctor_id, $fromNew, $toNew);

		if (!$isSameTime && $hasConflict) {
			echo json_encode([
				'type' => 'error',
				'alert' => [
					'title' => $this->lang('error'),
					'text' => $this->lang('turn conflict'),
					'type' => 'error'
				]
			]);
			return;
		}

		// === Update Turn ===
		$datas = [
			'date' => $date,
			'from_time' => $fromNew,
			'to_time' => $toNew,
			'doctor_id' => $doctor_id,
		];

		$update = $this->Admin_model->update_turn($datas, $id);

		if (!$update) {
			echo json_encode([
				'type' => 'error',
				'alert' => [
					'title' => $this->lang('error'),
					'text' => $this->lang('problem'),
					'type' => 'error'
				]
			]);
			return;
		}

		// === Success Response ===
		$data['type'] = 'success';
		$data['id'] = $id;
		$data['alert'] = [
			'title' => $this->lang('success'),
			'text' => $this->lang('update service success'),
			'type' => 'success'
		];

		$btns = '';
		$turns = $this->Admin_model->check_turns($date, $doctor_id, $fromNew);
		$turn = !empty($turns) ? $turns[0] : null;


		$btns .= $this->mylibrary->generateBtnUpdate('edit_turn', $id);
		$btns .= $this->mylibrary->generateBtnPrint('print_turn', $id);
		$btns .= $this->mylibrary->generateBtnDelete($id, 'admin/delete_turn', 'turnsTable', 'update_balance');

		$data['tr'] = [
			$turn ? $turn['doctor_name'] : '',
			$date,
			"<bdo dir='ltr'>{$fromNew} - {$toNew}</bdo>",
			$turn ? $turn['cr'] : '',
			$this->mylibrary->btn_group($btns)
		];

		echo json_encode($data);
	}


	public function pay_turn()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('slug', 'slug', 'trim|required', array('required' => $this->lang('insert payment turn error')));
		$this->form_validation->set_rules('cr', 'cr', 'trim|required', array('required' => $this->lang('insert payment amount error')));
		if ($this->form_validation->run()) {
			$datas = array(
				'cr' => $this->input->post('cr'),
				'pay_date' => $this->mylibrary->getCurrentShamsiDate()['date'],
				'payment_status' => 'a',
				'paid_user_id' => $this->session->userdata($this->mylibrary->hash_session('u_id'))
			);
			$id = $this->input->post('slug');
			$update = $this->Admin_model->update_turn($datas, $id);
			if ($update) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('insert payment success');
				$data['alert']['type'] = 'success';

				$data['id'] = $id;

				$btns = '';


				$turn = $this->Admin_model->turn_after_payment($id)[0];

				$btns .= $this->mylibrary->generateBtnUpdate('edit_turn', $data['id']);
				$btns .= $this->mylibrary->generateBtnPrint('print_turn', $data['id']);
				if ($turn['status'] == 'p') {
					$btns .= $this->mylibrary->generateBtnStatus($data['id'], 'admin/accept_turn');
				} else {
					$btns .= $this->mylibrary->generateBtnStatus($data['id'], 'admin/pending_turn', 'a');
				}

				$btns .= $this->mylibrary->generateBtnDelete($data['id'], 'admin/delete_turn', 'turnsTable', 'update_balance');
				$data['tr'] = array(
					$turn['doctor_name'],
					$turn['date'],
					$turn['from_time'] . ' - ' . $turn['to_time'],
					$turn['cr'],
					$turn['paid_user_name'],
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	public function insert_turn_done_processes()
	{
		$data = ['type' => 'form_error', 'messages' => []];

		$this->form_validation->set_rules('turn_id', 'Turn ID', 'trim|required|is_natural_no_zero', [
			'required' => $this->lang('error'),
			'is_natural_no_zero' => $this->lang('error')
		]);

		if (!$this->form_validation->run()) {
			foreach ($_POST as $key => $value) {
				if (form_error($key)) {
					$data['messages'][] = strip_tags(form_error($key));
				}
			}
			$data['title'] = $this->lang('error');
			echo json_encode($data);
			return;
		}

		$turn_id = $this->input->post('turn_id');
		$tooth_ids = $this->input->post('tooth_id');
		$done_processes = $this->input->post('done_process');
		$done_custom_processes = $this->input->post('done_custom_process');
		$user_id = $this->session->userdata($this->mylibrary->hash_session('u_id'));

		// Remove existing done processes (optional cleanup)
		$this->Admin_model->delete_turn_done_processes($turn_id);

		// Insert new done processes
		foreach ($tooth_ids as $tooth_id) {
			if (!empty($done_processes[$tooth_id])) {
				foreach ($done_processes[$tooth_id] as $dept => $process_list) {
					foreach ($process_list as $item) {
						$this->Admin_model->insert_done_process([
							'turn_id' => $turn_id,
							'tooth_id' => $tooth_id,
							'process_id' => is_numeric($item) ? $item : null,
							'custom_label' => is_numeric($item) ? null : $item,
							'remarks' => $dept,
							'users_id' => $user_id
						]);
					}
				}
			}

			if (!empty($done_custom_processes[$tooth_id])) {
				foreach ($done_custom_processes[$tooth_id] as $dept => $label) {
					if (trim($label)) {
						$this->Admin_model->insert_done_process([
							'turn_id' => $turn_id,
							'tooth_id' => $tooth_id,
							'process_id' => null,
							'custom_label' => $label,
							'remarks' => $dept,
							'users_id' => $user_id
						]);
					}
				}
			}
		}

		// ✅ Update turn status and treatment_date via model
		$this->Admin_model->complete_turn($turn_id);

		echo json_encode([
			'type' => 'success',
			'alert' => [
				'title' => $this->lang('success'),
				'text' => $this->lang('turn processes saved'),
				'type' => 'success'
			]
		]);
	}


	public function insert_turn()
	{
		$data = ['type' => 'form_error', 'messages' => []];

		// === Form validation ===
		$this->form_validation->set_rules('patient_id', 'patient_id', 'trim|required', [
			'required' => $this->lang('insert turn patient_id error')
		]);
		$this->form_validation->set_rules('date', 'date', 'trim|required', [
			'required' => $this->lang('insert turn date error')
		]);
		$this->form_validation->set_rules('doctor_id', 'doctor_id', 'trim|required', [
			'required' => $this->lang('insert turn doctor_id error')
		]);
		$this->form_validation->set_rules('from_time', 'from_time', 'trim|required', [
			'required' => $this->lang('insert turn hour error')
		]);
		$this->form_validation->set_rules('to_time', 'to_time', 'trim|required', [
			'required' => $this->lang('insert turn hour error')
		]);

		if (!$this->form_validation->run()) {
			foreach ($_POST as $key => $value) {
				if (form_error($key)) {
					$data['messages'][] = strip_tags(form_error($key));
				}
			}
			$data['title'] = $this->lang('error');
			echo json_encode($data);
			return;
		}

		$patient_id = $this->input->post('patient_id');
		$tooth_ids = $this->Admin_model->get_patient_tooth_ids($patient_id);

		// === Check if any recommended processes exist for this patient (with NULL turn_id) ===
		$has_recommended = false;
		if (!empty($tooth_ids)) {
			$this->db->where('turn_id IS NULL', null, false);
			$this->db->where_in('tooth_id', $tooth_ids);
			$has_recommended = $this->db->count_all_results('turn_tooth_recommended') > 0;
		}

		if (!$has_recommended) {
			echo json_encode([
				'type' => 'error',
				'alert' => [
					'title' => $this->lang('error'),
					'text' => $this->lang('please insert recommended processes first'),
					'type' => 'error'
				]
			]);
			return;
		}

		// === Check for time conflict ===
		$conflict = $this->Admin_model->check_turn_conflict(
			$this->input->post('date'),
			$this->input->post('doctor_id'),
			$this->input->post('from_time'),
			$this->input->post('to_time')
		);

		if ($conflict) {
			echo json_encode([
				'type' => 'error',
				'alert' => [
					'title' => $this->lang('error'),
					'text' => $this->lang('turn conflict'),
					'type' => 'error'
				]
			]);
			return;
		}

		// === Insert turn ===
		$datas = [
			'patient_id' => $patient_id,
			'date' => $this->input->post('date'),
			'from_time' => $this->input->post('from_time'),
			'to_time' => $this->input->post('to_time'),
			'status' => 'p',
			'doctor_id' => $this->input->post('doctor_id')
		];

		$insert = $this->Admin_model->insert_turn_form($datas);

		if (!$insert[0]) {
			echo json_encode([
				'type' => 'error',
				'alert' => [
					'title' => $this->lang('error'),
					'text' => $this->lang('problem'),
					'type' => 'error'
				]
			]);
			return;
		}

		$turn_id = $insert[1];

		// === Assign recommended processes to this turn ===
		$this->db->where('turn_id IS NULL', null, false);
		$this->db->where_in('tooth_id', $tooth_ids);
		$this->db->update('turn_tooth_recommended', ['turn_id' => $turn_id]);

		$data['type'] = 'success';
		$data['id'] = $turn_id;
		$data['extraFunction'] = 'print';
		$data['alert'] = [
			'title' => $this->lang('success'),
			'text' => $this->lang('insert turn success'),
			'type' => 'success'
		];

		$btns = $this->mylibrary->generateBtnUpdate('edit_turn', $turn_id);
		$btns .= $this->mylibrary->generateBtnPrint('print_turn', $turn_id);
		$btns .= $this->mylibrary->generateBtnStatus($turn_id, 'admin/accept_turn');
		$btns .= $this->mylibrary->generateBtnDelete($turn_id, 'admin/delete_turn', 'turnsTable', 'update_balance');

		$turn = $this->Admin_model->check_turns($this->input->post('date'), $this->input->post('doctor_id'), $this->input->post('hour'))[0];
		$hour = $this->input->post('from_time') . ' - ' . $this->input->post('to_time');

		$data['tr'] = [
			$turn['doctor_name'],
			$datas['date'],
			$hour,
			$turn['cr'],
			$this->lang('not paid'),
			$this->mylibrary->btn_group($btns)
		];

		echo json_encode($data);
	}

	public function delete_turn()
	{
		$data = ['type' => 'form_error', 'messages' => []];

		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', [
			'required' => $this->lang('problem'),
			'is_natural_no_zero' => $this->lang('problem')
		]);

		if (!$this->form_validation->run()) {
			foreach ($_POST as $key => $value) {
				if (form_error($key)) {
					$data['messages'][] = strip_tags(form_error($key));
				}
			}
			echo json_encode($data);
			return;
		}

		$turn_id = $this->input->post('record');

		// Check if there are any done processes linked to this turn
		$hasDoneProcesses = $this->db
			->where('turn_id', $turn_id)
			->count_all_results('turn_tooth_done');

		if ($hasDoneProcesses > 0) {
			echo json_encode([
				'type' => 'error',
				'alert' => [
					'title' => $this->lang('error'),
					'text' => $this->lang('cannot delete turn with done processes'),
					'type' => 'error'
				]
			]);
			return;
		}

		// Reset recommended processes (set turn_id = NULL)
		$this->db->where('turn_id', $turn_id);
		$this->db->update('turn_tooth_recommended', ['turn_id' => null]);

		// Now delete the turn
		$deleted = $this->Admin_model->delete_turn(['id' => $turn_id]);

		if ($deleted) {
			echo json_encode([
				'type' => 'success',
				'alert' => [
					'title' => $this->lang('success'),
					'text' => $this->lang('delete turn'),
					'type' => 'success'
				]
			]);
		} else {
			echo json_encode([
				'type' => 'error',
				'alert' => [
					'title' => $this->lang('error'),
					'text' => $this->lang('problem'),
					'type' => 'error'
				]
			]);
		}
	}


	public function accept_turn()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$datas = array(
				'id' => $this->input->post('record')
			);

			if ($this->Admin_model->change_payment_status_turn('a', $datas)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('accept turn');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}


	public function pending_turn()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$datas = array(
				'id' => $this->input->post('record')
			);

			if ($this->Admin_model->change_payment_status_turn('p', $datas)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('accept turn');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}


	// Start Teeth


	public function insert_tooth()
	{


		$is_endo = ($this->input->post('checkbox2') == 'endo') ? TRUE : FALSE;
		$is_restorative = ($this->input->post('checkbox1') == 'restorative') ? TRUE : FALSE;
		$is_prosthodontics = ($this->input->post('checkbox3') == 'Prosthodontics') ? TRUE : FALSE;

		// echo '<pre>';
		// print_r($_POST);
		// echo '<pre>';
		// exit;

		$data = array('type' => 'form_error', 'messages' => array());

		$this->form_validation->set_rules('diagnose', 'diagnose', 'trim|required', array('required' => $this->lang('insert tooth diagnose error')));

		// Start Endo
		if ($is_endo) {
			$this->form_validation->set_rules('endo_services', 'endo_services', 'trim|required', array('required' => $this->lang('insert tooth services error')));
			$this->form_validation->set_rules('price', 'price', 'trim|required', array('required' => $this->lang('insert tooth price error')));
		} else {
			$this->form_validation->set_rules('endo_services', 'endo_services', 'trim');
			$this->form_validation->set_rules('price', 'price', 'trim');
		}
		$this->form_validation->set_rules('patient_id', 'patient_id', 'trim|required', array('required' => $this->lang('insert tooth patient_id error')));
		$this->form_validation->set_rules('name', 'name', 'trim|required', array('required' => $this->lang('insert tooth name error')));
		$this->form_validation->set_rules('imgAddress', 'imgAddress', 'trim|required', array('required' => $this->lang('insert tooth price error')));
		$this->form_validation->set_rules('location', 'location', 'trim|required', array('required' => $this->lang('insert tooth location error')));

		$this->form_validation->set_rules('r_name1', 'r_name1', 'trim');
		$this->form_validation->set_rules('r_width1', 'r_width1', 'trim');
		$this->form_validation->set_rules('r_name2', 'r_name2', 'trim');
		$this->form_validation->set_rules('r_width2', 'r_width2', 'trim');
		$this->form_validation->set_rules('r_name3', 'r_name3', 'trim');
		$this->form_validation->set_rules('r_width3', 'r_width3', 'trim');
		$this->form_validation->set_rules('r_name4', 'r_name4', 'trim');
		$this->form_validation->set_rules('r_width4', 'r_width4', 'trim');
		$this->form_validation->set_rules('r_name5', 'r_name5', 'trim');
		$this->form_validation->set_rules('r_width5', 'r_width5', 'trim');

		$this->form_validation->set_rules('typeObturation', 'typeObturation', 'trim');
		$this->form_validation->set_rules('TypeSealer', 'TypeSealer', 'trim');
		$this->form_validation->set_rules('TypeIrrigation', 'TypeIrrigation', 'trim');

		$this->form_validation->set_rules('details', 'details', 'trim');
		$this->form_validation->set_rules('root_number', 'root_number', 'trim');
		// End Endo

		// Start Restorative
		if ($is_restorative) {
			$this->form_validation->set_rules('restorative_services', 'restorative_services', 'trim|required', array('required' => $this->lang('insert tooth services error')));
			$this->form_validation->set_rules('price_restorative', 'price_restorative', 'trim|required', array('required' => $this->lang('insert tooth price error')));
		} else {
			$this->form_validation->set_rules('restorative_services', 'restorative_services', 'trim');
			$this->form_validation->set_rules('price_restorative', 'price_restorative', 'trim');
		}

		$this->form_validation->set_rules('CariesDepth', 'CariesDepth', 'trim');
		$this->form_validation->set_rules('Material', 'Material', 'trim');
		$this->form_validation->set_rules('RestorativeMaterial', 'RestorativeMaterial', 'trim');
		$this->form_validation->set_rules('CompositeBrand', 'CompositeBrand', 'trim');
		$this->form_validation->set_rules('bondingBrand', 'bondingBrand', 'trim');
		$this->form_validation->set_rules('AmalgamBrand', 'AmalgamBrand', 'trim');
		$this->form_validation->set_rules('restorativeDescription', 'restorativeDescription', 'trim');
		// End Restorative

		// Start Prosthodontics
		if ($is_prosthodontics) {
			$this->form_validation->set_rules('pro_services', 'pro_services', 'trim|required', array('required' => $this->lang('insert tooth services error')));
			$this->form_validation->set_rules('price_pro', 'price_pro', 'trim|required', array('required' => $this->lang('insert tooth price error')));
		} else {
			$this->form_validation->set_rules('pro_services', 'pro_services', 'trim');
			$this->form_validation->set_rules('price_pro', 'price_pro', 'trim');
		}

		$this->form_validation->set_rules('type_restoration', 'type_restoration', 'trim');
		$this->form_validation->set_rules('filling_material', 'filling_material', 'trim');
		$this->form_validation->set_rules('post', 'post', 'trim');
		$this->form_validation->set_rules('PrefabricatedPost', 'PrefabricatedPost', 'trim');
		$this->form_validation->set_rules('customPost', 'customPost', 'trim');
		$this->form_validation->set_rules('crown_material', 'crown_material', 'trim');
		$this->form_validation->set_rules('color', 'color', 'trim');
		$this->form_validation->set_rules('pontic_design', 'pontic_design', 'trim');
		$this->form_validation->set_rules('impression_technique', 'impression_technique', 'trim');
		$this->form_validation->set_rules('impression_material', 'impression_material', 'trim');
		$this->form_validation->set_rules('CementMaterial', 'CementMaterial', 'trim');
		$this->form_validation->set_rules('details_pro', 'details_pro', 'trim');
		// End Prosthodontics

		$this->form_validation->set_rules('total_price', 'total_price', 'trim|required', array('required' => $this->lang('insert tooth price error')));
		// start
		if ($this->form_validation->run()) {


			$diagnoses = explode(',', $this->input->post('diagnose'));

			$main_data = array(
				'name' => $this->input->post('name'),
				'location' => $this->input->post('location'),
				'create_date' => $this->mylibrary->getCurrentShamsiDate()['date'] . ' - ' . date('H:i:s'),
				'imgAddress' => $this->input->post('imgAddress'),
				'price' => $this->input->post('total_price'),
				'patient_id' => $this->input->post('patient_id'),
				'users_id' => $this->session->userdata($this->mylibrary->hash_session('u_id')),
			);

			$insert = $this->Admin_model->insert_tooth($main_data);
			if ($insert[0]) {
				if ($is_endo) {
					$endo_data = array(
						'r_name1' => $this->input->post('r_name1'),
						'r_name2' => $this->input->post('r_name2'),
						'r_name3' => $this->input->post('r_name3'),
						'r_name4' => $this->input->post('r_name4'),
						'r_name5' => $this->input->post('r_name5'),
						'r_width1' => $this->input->post('r_width1'),
						'r_width2' => $this->input->post('r_width2'),
						'r_width3' => $this->input->post('r_width3'),
						'r_width4' => $this->input->post('r_width4'),
						'r_width5' => $this->input->post('r_width5'),
						'services' => $this->input->post('endo_services'),
						'price' => $this->input->post('price'),
						'details' => $this->input->post('details'),
						'root_number' => $this->input->post('root_number'),
						'modify_date' => $this->mylibrary->getCurrentShamsiDate()['date'] . ' - ' . date('H:i:s'),
						'tooth_id' => $insert[1],
					);
					$insert_endo = $this->Admin_model->insert_endo($endo_data);
					if ($insert_endo[0]) {
						$endo_services = explode(',', $this->input->post('endo_services'));
						if (count($endo_services) != 0) {
							foreach ($endo_services as $endo_service) {
								$data_for_insert_service = array(
									'endo_id' => $insert_endo[1],
									'services_id' => $endo_service
								);
								$this->Admin_model->insert_endo_has_services($data_for_insert_service);
							}
						}
						if ($this->input->post('typeObturation')) {
							$insert_endo_basic_info_data = array(
								'endo_id' => $insert_endo[1],
								'basic_information_teeth_id' => $this->input->post('typeObturation')
							);
							$this->Admin_model->insert_endo_basic_info($insert_endo_basic_info_data);
						}
						if ($this->input->post('TypeSealer')) {
							$insert_endo_basic_info_data = array(
								'endo_id' => $insert_endo[1],
								'basic_information_teeth_id' => $this->input->post('TypeSealer')
							);
							$this->Admin_model->insert_endo_basic_info($insert_endo_basic_info_data);
						}
						if ($this->input->post('TypeIrrigation')) {
							$insert_endo_basic_info_data = array(
								'endo_id' => $insert_endo[1],
								'basic_information_teeth_id' => $this->input->post('TypeIrrigation')
							);
							$this->Admin_model->insert_endo_basic_info($insert_endo_basic_info_data);
						}
					}
				}
				if ($is_restorative) {
					$restorative_data = array(
						'price' => $this->input->post('price_restorative'),
						'services' => $this->input->post('restorative_services'),
						'details' => $this->input->post('restorativeDescription'),
						'modify_date' => $this->mylibrary->getCurrentShamsiDate()['date'] . ' - ' . date('H:i:s'),
						'tooth_id' => $insert[1],
					);
					$insert_restorative = $this->Admin_model->insert_restorative($restorative_data);
					if ($insert_restorative[0]) {


						$restorative_services = explode(',', $this->input->post('restorative_services'));
						if (count($restorative_services) != 0) {
							foreach ($restorative_services as $restorative_service) {
								$data_for_insert_service = array(
									'restorative_id' => $insert_restorative[1],
									'services_id' => $restorative_service
								);
								$this->Admin_model->insert_restorative_has_services($data_for_insert_service);
							}
						}


						if ($this->input->post('CariesDepth')) {
							$insert_restorative_basic_info_data = array(
								'restorative_id' => $insert_restorative[1],
								'basic_information_teeth_id' => $this->input->post('CariesDepth')
							);
							$this->Admin_model->insert_restorative_basic_info($insert_restorative_basic_info_data);
						}
						if ($this->input->post('Material')) {
							$insert_restorative_basic_info_data = array(
								'restorative_id' => $insert_restorative[1],
								'basic_information_teeth_id' => $this->input->post('Material')
							);
							$this->Admin_model->insert_restorative_basic_info($insert_restorative_basic_info_data);
						}

						if ($this->input->post('RestorativeMaterial')) {
							$insert_restorative_basic_info_data = array(
								'restorative_id' => $insert_restorative[1],
								'basic_information_teeth_id' => $this->input->post('RestorativeMaterial')
							);
							$this->Admin_model->insert_restorative_basic_info($insert_restorative_basic_info_data);
						}

						if ($this->input->post('CompositeBrand')) {
							$insert_restorative_basic_info_data = array(
								'restorative_id' => $insert_restorative[1],
								'basic_information_teeth_id' => $this->input->post('CompositeBrand')
							);
							$this->Admin_model->insert_restorative_basic_info($insert_restorative_basic_info_data);
						}

						if ($this->input->post('bondingBrand')) {
							$insert_restorative_basic_info_data = array(
								'restorative_id' => $insert_restorative[1],
								'basic_information_teeth_id' => $this->input->post('bondingBrand')
							);
							$this->Admin_model->insert_restorative_basic_info($insert_restorative_basic_info_data);
						}

						if ($this->input->post('AmalgamBrand')) {
							$insert_restorative_basic_info_data = array(
								'restorative_id' => $insert_restorative[1],
								'basic_information_teeth_id' => $this->input->post('AmalgamBrand')
							);
							$this->Admin_model->insert_restorative_basic_info($insert_restorative_basic_info_data);
						}
					}
				}

				if ($is_prosthodontics) {
					$prosthodontics_data = array(
						'price' => $this->input->post('price_pro'),
						'services' => $this->input->post('pro_services'),
						'details' => $this->input->post('details_pro'),
						'modify_date' => $this->mylibrary->getCurrentShamsiDate()['date'] . ' - ' . date('H:i:s'),
						'tooth_id' => $insert[1],
					);
					$insert_prosthodontics = $this->Admin_model->insert_prosthodontics($prosthodontics_data);
					if ($insert_prosthodontics[0]) {


						$pro_services = explode(',', $this->input->post('pro_services'));
						if (count($pro_services) != 0) {
							foreach ($pro_services as $pro_service) {
								$data_for_insert_service = array(
									'prosthodontics_id' => $insert_prosthodontics[1],
									'services_id' => $pro_service
								);
								$this->Admin_model->insert_prosthodontics_has_services($data_for_insert_service);
							}
						}


						if ($this->input->post('type_restoration')) {
							$insert_prosthodontics_basic_info_data = array(
								'prosthodontics_id' => $insert_prosthodontics[1],
								'basic_information_teeth_id' => $this->input->post('type_restoration')
							);
							$this->Admin_model->insert_prosthodontics_basic_info($insert_prosthodontics_basic_info_data);
						}

						if ($this->input->post('filling_material')) {
							$insert_prosthodontics_basic_info_data = array(
								'prosthodontics_id' => $insert_prosthodontics[1],
								'basic_information_teeth_id' => $this->input->post('filling_material')
							);
							$this->Admin_model->insert_prosthodontics_basic_info($insert_prosthodontics_basic_info_data);
						}


						if ($this->input->post('post')) {
							$insert_prosthodontics_basic_info_data = array(
								'prosthodontics_id' => $insert_prosthodontics[1],
								'basic_information_teeth_id' => $this->input->post('post')
							);
							$this->Admin_model->insert_prosthodontics_basic_info($insert_prosthodontics_basic_info_data);
						}

						if ($this->input->post('PrefabricatedPost')) {
							$insert_prosthodontics_basic_info_data = array(
								'prosthodontics_id' => $insert_prosthodontics[1],
								'basic_information_teeth_id' => $this->input->post('PrefabricatedPost')
							);
							$this->Admin_model->insert_prosthodontics_basic_info($insert_prosthodontics_basic_info_data);
						}

						if ($this->input->post('customPost')) {
							$insert_prosthodontics_basic_info_data = array(
								'prosthodontics_id' => $insert_prosthodontics[1],
								'basic_information_teeth_id' => $this->input->post('customPost')
							);
							$this->Admin_model->insert_prosthodontics_basic_info($insert_prosthodontics_basic_info_data);
						}

						if ($this->input->post('crown_material')) {
							$insert_prosthodontics_basic_info_data = array(
								'prosthodontics_id' => $insert_prosthodontics[1],
								'basic_information_teeth_id' => $this->input->post('crown_material')
							);
							$this->Admin_model->insert_prosthodontics_basic_info($insert_prosthodontics_basic_info_data);
						}

						if ($this->input->post('pontic_design')) {
							$insert_prosthodontics_basic_info_data = array(
								'prosthodontics_id' => $insert_prosthodontics[1],
								'basic_information_teeth_id' => $this->input->post('pontic_design')
							);
							$this->Admin_model->insert_prosthodontics_basic_info($insert_prosthodontics_basic_info_data);
						}

						if ($this->input->post('impression_technique')) {
							$insert_prosthodontics_basic_info_data = array(
								'prosthodontics_id' => $insert_prosthodontics[1],
								'basic_information_teeth_id' => $this->input->post('impression_technique')
							);
							$this->Admin_model->insert_prosthodontics_basic_info($insert_prosthodontics_basic_info_data);
						}
						if ($this->input->post('impression_material')) {
							$insert_prosthodontics_basic_info_data = array(
								'prosthodontics_id' => $insert_prosthodontics[1],
								'basic_information_teeth_id' => $this->input->post('impression_material')
							);
							$this->Admin_model->insert_prosthodontics_basic_info($insert_prosthodontics_basic_info_data);
						}
						if ($this->input->post('CementMaterial')) {
							$insert_prosthodontics_basic_info_data = array(
								'prosthodontics_id' => $insert_prosthodontics[1],
								'basic_information_teeth_id' => $this->input->post('CementMaterial')
							);
							$this->Admin_model->insert_prosthodontics_basic_info($insert_prosthodontics_basic_info_data);
						}
						if ($this->input->post('color') !== '') {
							$colors = explode(',', $this->input->post('color'));
							foreach ($colors as $color) {
								$insert_prosthodontics_basic_info_data = array(
									'prosthodontics_id' => $insert_prosthodontics[1],
									'basic_information_teeth_id' => $color
								);
								$this->Admin_model->insert_prosthodontics_basic_info($insert_prosthodontics_basic_info_data);
							}
						}

					}
				}


				if (count($diagnoses) != 0) {
					foreach ($diagnoses as $diagnose) {
						$data_for_insert = array(
							'tooth_id' => $insert[1],
							'diagnose_id' => $diagnose
						);
						$this->Admin_model->insert_tooth_has_diagnose($data_for_insert);
					}
				}
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('insert tooth success');
				$data['alert']['type'] = 'success';

				$data['extraFunction'] = true;
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}

	public function update_tooth()
	{
		$this->check_permission_function('Update Teeth');
		// Determine which sections are active based on checkboxes
		$is_endo = isset($_POST['checkbox2']);
		$is_restorative = isset($_POST['checkbox1']);
		$is_prosthodontics = isset($_POST['checkbox3']);

		// Validation rules
		$this->form_validation->set_rules('diagnose', 'diagnose', 'trim|required', array('required' => $this->lang('insert tooth diagnose error')));
		$this->form_validation->set_rules('name', 'name', 'trim|required', array('required' => $this->lang('insert tooth name error')));
		$this->form_validation->set_rules('location', 'location', 'trim|required', array('required' => $this->lang('insert tooth location error')));
		$this->form_validation->set_rules('total_price', 'total_price', 'trim|required', array('required' => $this->lang('insert tooth price error')));

		if ($is_endo) {
			$this->form_validation->set_rules('endo_services', 'endo_services', 'trim|required', array('required' => $this->lang('insert tooth services error')));
			$this->form_validation->set_rules('price', 'price', 'trim|required', array('required' => $this->lang('insert tooth price error')));
		}

		if ($is_restorative) {
			$this->form_validation->set_rules('restorative_services', 'restorative_services', 'trim|required', array('required' => $this->lang('insert tooth services error')));
			$this->form_validation->set_rules('price_restorative', 'price_restorative', 'trim|required', array('required' => $this->lang('insert tooth price error')));
		}

		if ($is_prosthodontics) {
			$this->form_validation->set_rules('pro_services', 'pro_services', 'trim|required', array('required' => $this->lang('insert tooth services error')));
			$this->form_validation->set_rules('price_pro', 'price_pro', 'trim|required', array('required' => $this->lang('insert tooth price error')));
		}

		// Run validation
		if ($this->form_validation->run()) {
			// Extract post data
			$tooth_id = $this->input->post('tooth_id');
			$diagnoses = explode(',', $this->input->post('diagnose'));

			// Prepare main tooth data
			$main_data = array(
				'name' => $this->input->post('name'),
				'location' => $this->input->post('location'),
				'imgAddress' => $this->input->post('imgAddress'),
				'price' => $this->input->post('total_price'),
			);

			// Update main tooth record
			$update_status = $this->Admin_model->update_tooth($tooth_id, $main_data);

			if ($update_status) {
				// **Delete existing basic info & services**
				$this->Admin_model->delete_endo_basic_info($tooth_id);
				$this->Admin_model->delete_restorative_basic_info($tooth_id);
				$this->Admin_model->delete_prosthodontics_basic_info($tooth_id);
				$this->Admin_model->delete_endo_services($tooth_id);
				$this->Admin_model->delete_restorative_services($tooth_id);
				$this->Admin_model->delete_prosthodontics_services($tooth_id);

				// Handle Endodontic Data
				if ($is_endo) {
					$endo_data = array(
						'services' => $this->input->post('endo_services'),
						'price' => $this->input->post('price'),
						'details' => $this->input->post('details'),
						'root_number' => $this->input->post('root_number'),
						'modify_date' => $this->mylibrary->getCurrentShamsiDate()['date'] . ' - ' . date('H:i:s'),
						'r_name1' => $this->input->post('r_name1'),
						'r_name2' => $this->input->post('r_name2'),
						'r_name3' => $this->input->post('r_name3'),
						'r_name4' => $this->input->post('r_name4'),
						'r_name5' => $this->input->post('r_name5'),
						'r_width1' => $this->input->post('r_width1'),
						'r_width2' => $this->input->post('r_width2'),
						'r_width3' => $this->input->post('r_width3'),
						'r_width4' => $this->input->post('r_width4'),
						'r_width5' => $this->input->post('r_width5'),
					);


					$endos = $this->Admin_model->single_endo_by_tooth_id($tooth_id);


					if (count($endos) == 1) {
						$this->Admin_model->update_endo($tooth_id, $endo_data);
						$endo = $endos[0];
					} else {
						$endo_data['tooth_id'] = $tooth_id;
						$this->Admin_model->insert_endo($endo_data);
						$endo = $this->Admin_model->single_endo_by_tooth_id($tooth_id)[0];
					}

					// Insert Endo Services (use endo_id instead of tooth_id)
					$endo_services = explode(',', $this->input->post('endo_services'));
					foreach ($endo_services as $service_id) {
						$this->Admin_model->insert_endo_has_services(array(
							'endo_id' => $endo['id'],  // Using endo_id correctly
							'services_id' => $service_id
						));
					}

					// Insert Endo Basic Info
					foreach (['typeObturation', 'TypeSealer', 'TypeIrrigation'] as $key) {
						if ($this->input->post($key)) {
							$this->Admin_model->insert_endo_basic_info(array(
								'endo_id' => $endo['id'],
								'basic_information_teeth_id' => $this->input->post($key)
							));
						}
					}
				}

				// Handle Restorative Data (same logic for checking services, and avoiding duplicates)
				if ($is_restorative) {
					$restorative_data = array(
						'services' => $this->input->post('restorative_services'),
						'price' => $this->input->post('price_restorative'),
						'details' => $this->input->post('restorativeDescription'),
						'modify_date' => $this->mylibrary->getCurrentShamsiDate()['date'] . ' - ' . date('H:i:s'),
					);

					$restorative_check = $this->Admin_model->single_restorative_by_tooth_id($tooth_id);

					if (count($restorative_check) == 1) {
						$this->Admin_model->update_restorative($tooth_id, $restorative_data);
						$restorative = $restorative_check[0];
					} else {
						$restorative_data['tooth_id'] = $tooth_id;
						$this->Admin_model->insert_restorative($restorative_data);
						$restorative_check = $this->Admin_model->single_restorative_by_tooth_id($tooth_id);
						$restorative = $restorative_check[0];
					}


					// Insert Restorative Services
					$restorative_services = explode(',', $this->input->post('restorative_services'));
					foreach ($restorative_services as $restorative_service) {

						$this->Admin_model->insert_restorative_has_services(array(
							'restorative_id' => $restorative['id'],
							'services_id' => $restorative_service
						));
					}

					// Insert Restorative Basic Info
					foreach (['CariesDepth', 'Material', 'RestorativeMaterial', 'CompositeBrand', 'bondingBrand', 'AmalgamBrand'] as $key) {
						if ($this->input->post($key)) {
							$this->Admin_model->insert_restorative_basic_info(array(
								'restorative_id' => $restorative['id'],
								'basic_information_teeth_id' => $this->input->post($key)
							));
						}
					}
				}

				// Handle Prosthodontic Data
				if ($is_prosthodontics) {
					$prosthodontics_data = array(
						'services' => $this->input->post('pro_services'),
						'price' => $this->input->post('price_pro'),
						'details' => $this->input->post('details_pro'),
						'modify_date' => $this->mylibrary->getCurrentShamsiDate()['date'] . ' - ' . date('H:i:s'),
					);

					$pro_check = $this->Admin_model->single_prosthodontic_by_tooth_id($tooth_id);

					if (count($pro_check) == 1) {
						$this->Admin_model->update_prosthodontics($tooth_id, $prosthodontics_data);
						$pro = $pro_check[0];
					} else {
						$prosthodontics_data['tooth_id'] = $tooth_id;
						$this->Admin_model->insert_prosthodontics($prosthodontics_data);
						$pro_check = $this->Admin_model->single_prosthodontic_by_tooth_id($tooth_id);
						$pro = $pro_check[0];
					}


					// Insert Prosthodontic Services
					$pro_services = explode(',', $this->input->post('pro_services'));
					foreach ($pro_services as $pro_service) {
						$this->Admin_model->insert_prosthodontics_has_services(array(
							'prosthodontics_id' => $pro['id'],
							'services_id' => $pro_service
						));
					}

					// Insert Prosthodontic Basic Info
					foreach (['type_restoration', 'filling_material', 'post', 'PrefabricatedPost', 'customPost', 'crown_material', 'pontic_design', 'impression_technique', 'impression_material', 'CementMaterial'] as $key) {
						if ($this->input->post($key)) {
							$this->Admin_model->insert_prosthodontics_basic_info(array(
								'prosthodontics_id' => $pro['id'],
								'basic_information_teeth_id' => $this->input->post($key)
							));
						}
					}
				}

				// Handle Diagnoses Update
				if (!empty($diagnoses)) {
					$this->Admin_model->delete_tooth_diagnoses($tooth_id);
					foreach ($diagnoses as $diagnose) {
						$data_for_insert = array(
							'tooth_id' => $tooth_id,
							'diagnose_id' => $diagnose
						);
						$this->Admin_model->insert_tooth_has_diagnose($data_for_insert);
					}
				}

				// Success response
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');
				$data['alert']['text'] = $this->lang('update tooth success');
				$data['alert']['type'] = 'success';
				$data['extraFunction'] = true;
			} else {
				// Error response
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			// Validation error response
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
					$data['title'] = $this->lang('error');
				}
			}
		}
		print_r(json_encode($data));
	}

	public function delete_tooth()
	{
		$this->check_permission_function('Delete Teeth');
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$datas = array(
				'id' => $this->input->post('record')
			);

			if ($this->Admin_model->delete_tooth($datas)) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('delete tooth');
				$data['alert']['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	public function list_teeth_json()
	{
		$this->form_validation->set_rules('record', 'record', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$id = $this->input->post('record');

			$teeth = $this->Admin_model->get_teeth_by_id_with_diagnose($id);

			$datas = array();
			$i = 1;
			foreach ($teeth as $tooth) {
				$datas[] = array(
					'number' => $i,
					'id' => $tooth['id'],
					'name' => $tooth['name'],
					'services' => $this->services_name_multiple([$tooth['endo_services'], $tooth['restorative_services'], $tooth['prosthodontics_services']]),
					'diagnose' => $tooth['diagnose'],
					'location' => $this->dentist->find_location($tooth['location']),
					'price' => $tooth['price'],
				);
				$i++;
			}

			$data['content']['teeth'] = $datas;
			$data['content']['delete_access'] = $this->auth->has_permission('Delete Teeth');
			$data['content']['update_access'] = $this->auth->has_permission('Update Teeth');

			if (count($teeth) >= 0) {
				$data['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}
	// End Teeth


	// Start Reports

	public function report_receipts()
	{
		$data['title'] = $this->lang('report receipts');
		$data['page'] = "report_receipts";
		$data['accounts'] = $this->Admin_model->get_account_with_no_user();
		$data['script'] = $this->mylibrary->generateSelect2();

		$this->load->view('header', $data);
		$this->load->view('reports/receipts', $data);
		$this->load->view('footer');
	}


	public function report_ajax_receipt()
	{
		$this->form_validation->set_rules('from', 'from', 'trim');
		$this->form_validation->set_rules('to', 'to', 'trim');
		$this->form_validation->set_rules('customers_id', 'customers_id', 'trim');
		if ($this->form_validation->run()) {
			$extra = '';
			if ((isset($_POST['from']) && isset($_POST['to'])) && (!empty($_POST['from']) && !empty($_POST['to']))) {
				$extra .= "DATE(shamsi) BETWEEN DATE('" . $_POST['from'] . "') AND DATE('" . $_POST['to'] . "') ";
				if (!empty($_POST['customers_id'])) {
					$extra .= "AND customers_id = '" . $_POST['customers_id'] . "' ";
				}
			} elseif (isset($_POST['customers_id'])) {
				if (!empty($_POST['customers_id'])) {
					$extra .= "customers_id = '" . $_POST['customers_id'] . "' ";
				}
			}

			$datas = array();
			$sum_cr = 0;
			$sum_dr = 0;
			if (!empty($extra)) {
				$receipts = $this->Admin_model->get_report_balance_sheet($extra);

				foreach ($receipts as $receipt) {
					if (is_null($receipt['cr']) || $receipt['cr'] == 0) {
						$type = $this->lang('dr');
					} else {
						$type = $this->lang('cr');
					}
					$datas[] = array(
						'id' => $receipt['id'],
						'date' => $receipt['shamsi'],
						'account_name' => $receipt['customer_name'],
						'cr' => $receipt['cr'],
						'dr' => $receipt['dr'],
						'remarks' => $receipt['remarks'],
						'user_name' => $receipt['user_name'],
						'type' => $type,
						'delete_access' => $this->auth->has_permission('Delete Receipt'),
						'update_access' => $this->auth->has_permission('Update Receipt'),
					);
					$sum_cr += $receipt['cr'];
					$sum_dr += $receipt['dr'];
				}
			} else {
				$receipts = array();
			}


			$data['content']['receipts'] = $datas;
			$data['content']['sum_cr'] = $sum_cr;
			$data['content']['sum_dr'] = $sum_dr;
			$data['content']['balance'] = $sum_cr - $sum_dr;
			if (count($receipts) >= 0) {
				$data['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}


	public function report_ajax_patients_paid()
	{
		$this->form_validation->set_rules('from', 'from', 'trim');
		$this->form_validation->set_rules('to', 'to', 'trim');
		$this->form_validation->set_rules('patient_id', 'patient_id', 'trim');
		if ($this->form_validation->run()) {
			$extra = '';
			if ((isset($_POST['from']) && isset($_POST['to'])) && (!empty($_POST['from']) && !empty($_POST['to']))) {
				$extra .= "DATE(date) BETWEEN DATE('" . $_POST['from'] . "') AND DATE('" . $_POST['to'] . "') ";
				if (!empty($_POST['patient_id'])) {
					$extra .= "AND patient_id = '" . $_POST['patient_id'] . "' ";
				}
			} elseif (isset($_POST['patient_id'])) {
				if (!empty($_POST['patient_id']) && $_POST['patient_id'] != '0') {
					$extra .= "patient_id = '" . $_POST['patient_id'] . "' ";
				}
			}

			$datas = array();
			$sum_cr = 0;
			if ($extra != '') {
				$receipts = $this->Admin_model->get_turns_paid($extra);
				foreach ($receipts as $receipt) {
					$datas[] = array(
						'date' => $receipt['date'],
						'patient_name' => $this->mylibrary->get_patient_name($receipt['name'], $receipt['lname'], '', $receipt['gender']),
						'cr' => $receipt['cr'],
						'serial_id' => $receipt['serial_id'],
					);
					$sum_cr += $receipt['cr'];
				}
			} else {
				$receipts = array();
			}

			$data['content']['receipts'] = $datas;
			$data['content']['sum_cr'] = $sum_cr;
			if (count($receipts) >= 0) {
				$data['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function report_ajax_patients_income()
	{
		$this->form_validation->set_rules('from', 'from', 'trim');
		$this->form_validation->set_rules('to', 'to', 'trim');
		$this->form_validation->set_rules('patient_id', 'patient_id', 'trim');
		if ($this->form_validation->run()) {
			$extra = '';
			if ((isset($_POST['from']) && isset($_POST['to'])) && (!empty($_POST['from']) && !empty($_POST['to']))) {
				$extra .= "DATE(tooth.create_date) BETWEEN DATE('" . $_POST['from'] . "') AND DATE('" . $_POST['to'] . "') ";
				if (!empty($_POST['patient_id'])) {
					$extra .= "AND patient_id = '" . $_POST['patient_id'] . "' ";
				}
			} elseif (isset($_POST['patient_id'])) {
				if (!empty($_POST['patient_id']) && $_POST['patient_id'] != '0') {
					$extra .= "patient_id = '" . $_POST['patient_id'] . "' ";
				}
			}

			$datas = array();


			$sum_cr = 0;

			if (!empty($extra)) {
				$receipts = $this->Admin_model->get_tooth_income($extra);
				foreach ($receipts as $receipt) {
					$services = '';

					$datas[] = array(
						'date' => $receipt['create_date'],
						'patient_name' => $this->mylibrary->get_patient_name($receipt['name'], $receipt['lname'], '', $receipt['gender']),
						'tooth_name' => $receipt['tooth_name'] . ' (' . $this->dentist->find_location($receipt['location']) . ')',
						'cr' => $receipt['price'],
					);
					$sum_cr += $receipt['price'];
				}
			} else {
				$receipts = array();
			}


			$data['content']['receipts'] = $datas;
			$data['content']['sum_cr'] = $sum_cr;
			if (count($receipts) >= 0) {
				$data['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	public function report_ajax_psatients_expenses()
	{
		$this->form_validation->set_rules('from', 'from', 'trim');
		$this->form_validation->set_rules('to', 'to', 'trim');
		$this->form_validation->set_rules('patient_id', 'patient_id', 'trim');
		if ($this->form_validation->run()) {
			$extra = '';
			if ((isset($_POST['from']) && isset($_POST['to'])) && (!empty($_POST['from']) && !empty($_POST['to']))) {
				$extra .= "DATE(labs.give_date) BETWEEN DATE('" . $_POST['from'] . "') AND DATE('" . $_POST['to'] . "') ";
				if (!empty($_POST['patient_id'])) {
					$extra .= "AND labs.patient_id = '" . $_POST['patient_id'] . "' ";
				}
				if (!empty($_POST['lab_id'])) {
					$extra .= "AND labs.customers_id = '" . $_POST['lab_id'] . "' ";
				}
			} elseif ((isset($_POST['patient_id']) && isset($_POST['lab_id'])) && (!empty($_POST['patient_id']) && !empty($_POST['lab_id'])) && ($_POST['patient_id'] != '0' && $_POST['lab_id'] != '0')) {
				$extra .= "labs.patient_id = '" . $_POST['patient_id'] . "' ";
				$extra .= "AND labs.customers_id = '" . $_POST['lab_id'] . "' ";
			} elseif (isset($_POST['lab_id']) && $_POST['lab_id'] != '0') {
				if (!empty($_POST['lab_id'])) {
					$extra .= " labs.customers_id = '" . $_POST['lab_id'] . "' ";
				}
			} elseif (isset($_POST['patient_id']) && $_POST['patient_id'] != '0') {
				if (!empty($_POST['patient_id'])) {
					$extra .= " labs.patient_id = '" . $_POST['patient_id'] . "' ";
				}
			}

			$datas = array();
			$sum_dr = 0;

			if (!empty($extra)) {
				$receipts = $this->Admin_model->get_labs_expenses($extra);
				foreach ($receipts as $receipt) {
					$teeths = explode(',', $receipt['teeth']);
					$teethName = '';
					foreach ($teeths as $tooth) {
						$info = $this->tooth_by_id($tooth);
						$teethName .= $info['name'];
						$teethName .= ' (';
						$teethName .= $this->dentist->find_location($info['location']);
						$teethName .= '),';
					}
					$types = explode(',', $receipt['type']);
					$typesName = '';
					foreach ($types as $type) {
						$typesName .= $this->lang($type);
						$typesName .= ',';
					}
					$datas[] = array(
						'patient_name' => $this->mylibrary->get_patient_name($receipt['name'], $receipt['lname'], '', $receipt['gender']),
						'laboratory' => $receipt['lab_name'],
						'teeth' => substr($teethName, 0, -1),
						'tooth_type' => substr($typesName, 0, -1),
						'delivery_date' => $receipt['give_date'],
						'dr' => $receipt['dr'],
						'remarks' => $receipt['remarks'],
					);
					$sum_dr += $receipt['dr'];
				}
			} else {
				$receipts = array();
			}


			$data['content']['receipts'] = $datas;
			$data['content']['sum_dr'] = $sum_dr;
			if (count($receipts) >= 0) {
				$data['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}


	public function print_expense($id)
	{
		$data['title'] = $this->lang('expenses');
		$data['page'] = "turns";
		if (is_null($id)) {
			redirect(base_url() . 'admin/');
		}

		$data = array();
		$datas = array(
			'id' => $id
		);
		$receipt = $this->Admin_model->balance_by_id($datas);

		if (count($receipt) !== 1) {
			show_404();
		} else {
			$data['single'] = $receipt[0];
			$data['title'] = 'print';
			$this->load->view("prints/expense", $data);
		}
	}

	public
	function report_ajax_psatients_balance()
	{
		$this->form_validation->set_rules('from', 'from', 'trim');
		$this->form_validation->set_rules('to', 'to', 'trim');
		$this->form_validation->set_rules('patient_id', 'patient_id', 'trim');
		if ($this->form_validation->run()) {
			$extra = '';
			if ((isset($_POST['from']) && isset($_POST['to'])) && (!empty($_POST['from']) && !empty($_POST['to']))) {
				$extra .= "DATE(patient.create) BETWEEN DATE('" . $_POST['from'] . "') AND DATE('" . $_POST['to'] . "') ";
				if (!empty($_POST['patient_id'])) {
					$extra .= "AND patient.id = '" . $_POST['patient_id'] . "' ";
				}
				if (!empty($_POST['status'])) {
					$extra .= "AND patient.status = '" . $_POST['status'] . "' ";
				}
			} elseif ((isset($_POST['patient_id']) && isset($_POST['status'])) && (!empty($_POST['patient_id']) && !empty($_POST['status'])) && ($_POST['patient_id'] != '0' && $_POST['status'] != '0')) {
				$extra .= "patient.id = '" . $_POST['patient_id'] . "' ";
				$extra .= "AND patient.status = '" . $_POST['status'] . "' ";
			} elseif (isset($_POST['status']) && $_POST['status'] != '0') {
				if (!empty($_POST['status'])) {
					$extra .= " patient.status = '" . $_POST['status'] . "' ";
				}
			} elseif (isset($_POST['patient_id']) && $_POST['patient_id'] != '0') {
				if (!empty($_POST['patient_id'])) {
					$extra .= " patient.id = '" . $_POST['patient_id'] . "' ";
				}
			}

			$datas = array();
			$sum_dr = 0;
			$sum_cr = 0;

			if (!empty($extra)) {
				$receipts = $this->Admin_model->get_patient_balance_report($extra);

				foreach ($receipts as $receipt) {

					$check = $this->check_balance($receipt['id']);
					$balance = $check['sum'];
					$cr = $check['sum_cr'];
					$dr = $check['sum_dr'];


//					Code for accept all patients which their balance are 0

//					if ($balance == 0) {
//						$datas = array(
//							'status' => 'a'
//						);
//						$id = $receipt['id'];
//						$this->Admin_model->update_patient($datas, $id);
//					}

					$datas[] = array(
						'patient_name' => $this->mylibrary->get_patient_name($receipt['name'], $receipt['lname'], $receipt['serial_id'], $receipt['gender']),
						'phone' => $receipt['phone1'],
						'dateTime' => $receipt['create'],
						'status' => $this->mylibrary->check_status($receipt['status']),
						'cr' => $cr,
						'dr' => $dr,
						'balance' => $cr - $dr,
						'remarks' => $receipt['remarks'],
					);
					$sum_dr += $dr;
					$sum_cr += $cr;
				}
			} else {
				$receipts = array();
			}
			$data['content']['receipts'] = $datas;
			$data['content']['sum_dr'] = $sum_dr;
			$data['content']['sum_cr'] = $sum_cr;
			$data['content']['balance'] = $sum_cr - $sum_dr;
			if (count($receipts) >= 0) {
				$data['type'] = 'success';
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}


	public
	function report_patients()
	{
		$data['title'] = $this->lang('report patients');
		$data['page'] = "report_patients";
		$data['patients'] = $this->Admin_model->get_patients_for_report();
		$data['lab_accounts'] = $this->Admin_model->get_lab_account();
		$data['script'] = $this->mylibrary->generateSelect2();

		$this->load->view('header', $data);
		$this->load->view('reports/patients', $data);
		$this->load->view('footer');
	}

	// End Reports

	public
	function _404()
	{
		$this->load->view('errors/html/error_404');
	}

	public
	function logout()
	{
		session_destroy();
		redirect(base_url());
	}

	public
	function phoneBook()
	{
		$data['title'] = $this->lang('phonebook');
		$data['page'] = "phonebook";
		$data['receipts'] = $this->Admin_model->get_turns_phonebook();
		$data['script'] = $this->mylibrary->generateSelect2();
		$data['script_date'] = $this->mylibrary->script_datepicker();
		$this->load->view('header', $data);
		$this->load->view('phonebook', $data);
		$this->load->view('footer');
	}

	public
	function failedCall()
	{
		$data = array('type' => 'form_error', 'messages' => array());
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		$this->form_validation->set_rules('type', 'type', 'trim|required', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		$this->form_validation->set_rules('remarks', 'remarks', 'trim');
		if ($this->form_validation->run()) {
			$type = $this->input->post('type');
			if ($type == 'call1') {
				$datas = array(
					'date_phone1' => $this->mylibrary->getCurrentShamsiDate()['date'],
					'hour_phone1' => date('H:i:s'),
					'remarks_phone1' => $this->input->post('remarks')
				);
			} elseif ($type == 'call2') {
				$datas = array(
					'date_phone2' => $this->mylibrary->getCurrentShamsiDate()['date'],
					'hour_phone2' => date('H:i:s'),
					'remarks_phone2' => $this->input->post('remarks')
				);
			} elseif ($type == 'call3') {
				$datas = array(
					'date_phone3' => $this->mylibrary->getCurrentShamsiDate()['date'],
					'hour_phone3' => date('H:i:s'),
					'remarks_phone3' => $this->input->post('remarks')
				);
			}
			$id = $this->input->post('slug');
			$update = $this->Admin_model->update_turn($datas, $id);
			if ($update) {
				$data['type'] = 'success';
				$data['alert']['title'] = $this->lang('success');;
				$data['alert']['text'] = $this->lang('update turn success');
				$data['alert']['type'] = 'success';

				$data['id'] = $id;
				$data['callType'] = $type;

				$service = $this->Admin_model->get_turns_where('AND turn.id = ' . $id)[0];
				$btns = '';

				$btns .= $this->mylibrary->generateBtnProfilePatient($service['patient_id']);

				// $btns .= $this->mylibrary->generateBtnAccept($id, 'admin/accept_turn');
				// $btns .= $this->mylibrary->generateBtnPayment($id, 'admin/accept_turn');

				if ($type == 'call1') {
					$btns .= $this->mylibrary->generateBtnCall($id, 'call1', 'btn-outline-success', false, 'eye');
					$btns .= $this->mylibrary->generateBtnCall($id, 'call2', 'btn-outline-secondary', false);
					$btns .= $this->mylibrary->generateBtnCall($id, 'call3', 'btn-outline-danger', true);
				} elseif ($type == 'call2') {
					$btns .= $this->mylibrary->generateBtnCall($id, 'call1', 'btn-outline-success', false, 'eye');
					$btns .= $this->mylibrary->generateBtnCall($id, 'call2', 'btn-outline-secondary', false, 'eye');
					$btns .= $this->mylibrary->generateBtnCall($id, 'call3', 'btn-outline-danger');
				} elseif ($type == 'call3') {
					$btns .= $this->mylibrary->generateBtnCall($id, 'call1', 'btn-outline-success', false, 'eye');
					$btns .= $this->mylibrary->generateBtnCall($id, 'call2', 'btn-outline-secondary', false, 'eye');
					$btns .= $this->mylibrary->generateBtnCall($id, 'call3', 'btn-outline-danger', false, 'eye');
					$updatePatient = array('status' => 'b');
					$this->Admin_model->update_patient($updatePatient, $service['patient_id']);
					$data['extraFunction'] = $id;
				}

				$data['tr'] = array(
					$this->mylibrary->get_patient_name($service['name'], $service['lname'], $service['serial_id'], $service['gender']),
					$service['doctor_name'],
					$service['date'],
					$this->dentist->find_time($service['hour']),
					$this->mylibrary->btn_group($btns)
				);
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			foreach ($_POST as $key => $value) {
				if (form_error($key) !== '') {
					$error = form_error($key);
					$data['messages'][] = substr($error, 3, -4);
				}
			}
		}

		print_r(json_encode($data));
	}

	public
	function single_phonebook()
	{
		$this->form_validation->set_rules('slug', 'slug', 'trim|required|is_natural_no_zero', array('required' => $this->lang('problem'), 'is_natural_no_zero' => $this->lang('problem')));
		$this->form_validation->set_rules('type', 'type', 'trim|required', array('required' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$record = $this->input->post('slug');
			$datas = array(
				'id' => $record
			);
			$service = $this->Admin_model->single_turn($datas);
			if (count($service) > 0) {
				$data['type'] = 'success';
				$type = $this->input->post('type');
				if ($type == 'call1') {
					$data['content'] = array(
						'date' => $service[0]['date_phone1'],
						'hour' => $service[0]['hour_phone1'],
						'remarks' => $service[0]['remarks_phone1'],
					);
				} elseif ($type == 'call2') {
					$data['content'] = array(
						'date' => $service[0]['date_phone2'],
						'hour' => $service[0]['hour_phone2'],
						'remarks' => $service[0]['remarks_phone2'],
					);
				} elseif ($type == 'call3') {
					$data['content'] = array(
						'date' => $service[0]['date_phone3'],
						'hour' => $service[0]['hour_phone3'],
						'remarks' => $service[0]['remarks_phone3'],
					);
				}
			} else {
				$data['type'] = 'error';
				$data['alert']['title'] = $this->lang('error');
				$data['alert']['text'] = $this->lang('problem');
				$data['alert']['type'] = 'error';
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}

	function list_phonebook_json()
	{
		$this->form_validation->set_rules('date', 'date', 'trim', array('required' => $this->lang('problem')));
		if ($this->form_validation->run()) {
			$data = array();
			$date = $this->input->post('date');
			if ($date !== '') {
				$turns = $this->Admin_model->get_turns_phonebook($date);
			} else {
				$turns = $this->Admin_model->get_turns_phonebook();
			}


			$data['type'] = 'success';
			if (count($turns) > 0) {
				$i = 1;
				foreach ($turns as $turn) {
					$btns = '';

					if ($this->auth->has_permission('Read Patient Profile')) {
						$btns .= $this->mylibrary->generateBtnProfilePatient($turn['patient_id']);
					}

					// $btns .= $this->mylibrary->generateBtnAccept($turn['id'], 'admin/accept_turn');
					// $btns .= $this->mylibrary->generateBtnPayment($turn['id'], 'admin/accept_turn');

					if (empty($turn['date_phone1'])) {
						$btns .= $this->mylibrary->generateBtnCall($turn['id'], 'call1', 'btn-outline-success', false);
						$btns .= $this->mylibrary->generateBtnCall($turn['id'], 'call2', 'btn-outline-secondary', true);
						$btns .= $this->mylibrary->generateBtnCall($turn['id'], 'call3', 'btn-outline-danger', true);
					} elseif (empty($turn['date_phone2'])) {
						$btns .= $this->mylibrary->generateBtnCall($turn['id'], 'call1', 'btn-outline-success', false, 'eye');
						$btns .= $this->mylibrary->generateBtnCall($turn['id'], 'call2', 'btn-outline-secondary', false);
						$btns .= $this->mylibrary->generateBtnCall($turn['id'], 'call3', 'btn-outline-danger', true);
					} elseif (empty($turn['date_phone3'])) {
						$btns .= $this->mylibrary->generateBtnCall($turn['id'], 'call1', 'btn-outline-success', false, 'eye');
						$btns .= $this->mylibrary->generateBtnCall($turn['id'], 'call2', 'btn-outline-secondary', false, 'eye');
						$btns .= $this->mylibrary->generateBtnCall($turn['id'], 'call3', 'btn-outline-danger', false);
					} else {
						$btns .= $this->mylibrary->generateBtnCall($turn['id'], 'call1', 'btn-outline-success', false, 'eye');
						$btns .= $this->mylibrary->generateBtnCall($turn['id'], 'call2', 'btn-outline-secondary', false, 'eye');
						$btns .= $this->mylibrary->generateBtnCall($turn['id'], 'call3', 'btn-outline-danger', false, 'eye');
					}
					$data['content'][] = array(
						'number' => $i,
						'id' => $turn['id'],
						'patient_name' => $this->mylibrary->get_patient_name($turn['name'], $turn['lname'], '', $turn['gender']),
						'patient_id' => $turn['patient_id'],
						'doctor_name' => $turn['doctor_name'],
						'date' => $turn['date'],
						'time' => $turn['from_time'] . ' - ' . $turn['to_time'],
						'btns' => $btns
					);
					$i++;
				}
			} else {
				$data['content'] = array();
			}
		} else {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->lang('problem');
			$data['alert']['type'] = 'error';
		}

		print_r(json_encode($data));
	}


	//Start Roles
	public
	function roles()
	{
		$this->check_permission_page();
		$this->load->model('Role_model');
		$data['title'] = $this->lang('role and permission');
		$data['page'] = "users";
		$data['roles'] = $this->Role_model->get_all_roles();

		$this->load->view('header', $data);
		$this->load->view('users/roles', $data);
		$this->load->view('footer');
	}

	public
	function edit_role($id = null)
	{
		if (!is_null($id)) {
			$this->load->model('Role_model');
			$role = $this->Role_model->get_role($id);

			$permissions = $this->Role_model->get_assigned_permissions($role[0]->id);


			if (count($role) !== 0) {
				$this->load->model('Permission_model');

				$data['title'] = $role[0]->role_name;
				$data['page'] = "single_role";
				$data['assigned_permissions'] = $permissions;
				$data['role'] = $role[0];
				$data['permissions'] = $this->Permission_model->get_permissions_with_categories();

				// Start List of primary information for insert teeth

				$this->load->view('header', $data);
				$this->load->view('users/user_role_edit', $data);
				$this->load->view('footer');
			} else {
				show_404();
				exit();
			}
		} else {
			show_404();
			exit;
		}
	}

	//End Roles

	public
	function init()
	{
		$this->load->model('Role_model');
		$data['title'] = $this->lang('role and permission');
		$data['page'] = "users";
		$data['roles'] = $this->Role_model->get_all_roles();
//		$data['script_single_patient_assets'] = ['assets/js/users.js'];

		$this->load->view('header', $data);
		$this->load->view('test/init', $data);
		$this->load->view('footer');
	}

	function render($file_path, $data = null)
	{
		if (!is_null($data)) {
			return $this->load->view($file_path, $data);
		} else {
			return $this->load->view($file_path);
		}
	}

	function check_permission_page($permission_name = 'admin')
	{
		if ($permission_name == 'admin') {
			if ($this->session->userdata($this->mylibrary->hash_session('u_role')) != ucwords('A')) {

				show_404();
				exit();
			}

		} elseif (!$this->auth->has_permission($permission_name)) {
			show_404();
			exit();
		}
	}

	function isAdmin()
	{
		if ($this->session->userdata($this->mylibrary->hash_session('u_role')) == ucwords('A')) {
			return true;
		}
		return false;
	}

	function check_permission_function($permission_name)
	{
		if (!$this->auth->has_permission($permission_name)) {
			$data['type'] = 'error';
			$data['alert']['title'] = $this->lang('error');
			$data['alert']['text'] = $this->language->languages('do not have access to this function');
			$data['alert']['type'] = 'error';
			print_r(json_encode($data));
			exit;
		}
	}

}
