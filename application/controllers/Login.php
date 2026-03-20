<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Base_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Login_model');
	}

	public function index()
	{
		if ($this->auth->check()) {
			redirect('dashboard');
		}

		if ($this->input->method() === 'post') {
			$this->form_validation->set_rules('username', 'Username', 'required|trim');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if ($this->form_validation->run()) {
				$user = $this->Login_model->find_by_username($this->input->post('username', TRUE));

				if ($user && password_verify($this->input->post('password'), $user['password'])) {
					$this->auth->login($user);
					redirect('dashboard');
				}

				$this->session->set_flashdata('error', t('Invalid username or password.'));
			}
		}

		$this->load->view('login', array(
			'title' => t('Physical Therapy Clinic'),
			'current_locale' => app_locale(),
			'current_theme' => app_theme(),
			'is_rtl' => is_rtl_locale(),
		));
	}

	public function logout()
	{
		$this->auth->logout();
		$this->session->set_flashdata('success', t('You have been logged out.'));
		redirect('login');
	}
}
