<?php


class Login extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->language->startup('fa');
		$this->load->model('Login_model');
		if ($this->session->userdata($this->mylibrary->hash_session('logged_in')) == true) {
			redirect(base_url('admin'));
		}
		$this->mylibrary->check_theme();
	}

	public function lang($key)
	{
		return $this->language->languages($key, $_COOKIE['language']);
	}

	public function index()
	{
		$data = array();
		$data['title'] = $this->language->languages('system name');
		$this->form_validation->set_rules('username', 'User Name', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run() == true) {
			$email = $this->input->post('username', TRUE);
			$pass = $this->mylibrary->hash($this->input->post('password'));
			$auth = $this->Login_model->auth($email, $pass);
			if ($auth) {
				if (ucwords($auth['status']) == 'A') {
					// if ($auth['role'] == "admin") {
					$id = $auth['id'];
					$fullname = $auth['fname'] . ' ' . $auth['lname'];
					$photo = $auth['photo'];
					$role = $auth['role'];
					$u_status = $auth['status'];
					$sesdata = array(
						$this->mylibrary->hash_session('u_fullname') => $fullname,
						$this->mylibrary->hash_session('u_id') => $id,
						$this->mylibrary->hash_session('u_pass') => $pass,
						$this->mylibrary->hash_session('u_photo') => $photo,
						$this->mylibrary->hash_session('u_status') => $u_status,
						$this->mylibrary->hash_session('u_role') => $role,
						$this->mylibrary->hash_session('logged_in') => TRUE
					);
					// }
				} elseif (ucwords($auth['status']) == 'P') {
					$this->session->set_flashdata('er_msg', 'حساب شما فعلا معلق است');
					redirect(base_url('login'));
				}
				$this->session->set_userdata($sesdata);
				redirect(base_url('Admin/'));
			} else {
				$this->session->set_flashdata('er_msg', $this->language->languages('wrong user or pass'));
				////when auth == false
				$this->load->view('login', $data);
			}
		} else {
			$this->load->view('login', $data);
		}
	}

	public function register()
	{
		$this->form_validation->set_rules('username', 'User Name', 'required|trim|is_unique[users.username]');
		$this->form_validation->set_rules('fullname', 'Full Name', 'required|trim');
		$this->form_validation->set_rules('phone1', 'Phone Number', 'required|trim|is_unique[users.phone]|max_length[10]|min_length[10]');
		$this->form_validation->set_rules('email', 'Email Address', 'valid_email|required|trim|is_unique[users.email]|max_length[30]');
		$this->form_validation->set_rules('password1', 'Password', 'required|min_length[8]');
		$this->form_validation->set_rules('password2', 'Password Confirmation', 'required|matches[password1]');
		if ($this->form_validation->run()) {
			$pass = self::HASH_PASSWORD($this->input->post('password1'));
			$data = array(
				'fullname' => $this->input->post('fullname'),
				'password' => $pass,
				'username' => $this->input->post('username'),
				'slug' => url_title($this->input->post('username'), '-', TRUE),
				'status' => "P",
				'uniqid' => uniqid(),
				'type' => 'freelancer',
				'phone' => $this->input->post('phone1'),
				'email' => $this->input->post('email'),
				'photo' => 'default.png'
			);

			$id = $this->Login_model->new_member($data);
			if ($id) {
				$x = $this->Login_model->select_username($id);
				$encode = substr(base64_encode('21^5%9^3$22^17%2$3#13' . base64_encode(base64_encode($x[0]->username) . ',' . base64_encode($x[0]->uniqid)) . '#354!231#32%'), 0, -2);
				$link = base_url('Login/verify_account/' . $encode);
				$receptionist = $x[0]->email;
				$subject = "Do not Reply";
				$message = "Your verfication Link is <br>" . $link;
				$this->load->library('email');
				$config['protocol'] = 'smtp';
				$config['smtp_host'] = 'ssl://mail.cyborgtech.co';
				$config['smtp_port'] = '465';
				$config['smtp_timeout'] = '7';
				$config['smtp_user'] = 'info@jobs.cyborgtech.co';
				$config['smtp_pass'] = 'y$LWNi}X7Mh~';
				$config['charset'] = 'utf-8';
				$config['newline'] = "\r\n";
				$config['mailtype'] = 'html'; // or html
				$config['validation'] = TRUE; // bool whether to validate email or not
				$this->email->initialize($config);
				$this->email->from('info@jobs.cyborgtech.co', 'Customer Service');
				$this->email->set_mailtype('html');
				$this->email->to($receptionist);
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();
				$this->session->set_flashdata('success_msg', 'You are registered Successfully Please Check Your Email');
				redirect(base_url('login'));
			}
		} else {
			$this->load->view('header');
			$this->load->view('register');
			$this->load->view('footer');
		}
	}

	public function goBack()
	{
		header("Location: {$_SERVER['HTTP_REFERER']}");
		exit;
	}
}
