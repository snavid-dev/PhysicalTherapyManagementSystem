<?php


class Users extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Users_model');
		if ($this->session->userdata('logged_in') !== true) {
			$this->session->set_flashdata('er_msg', 'You are not logged in please login to access the page');
			redirect(base_url('login'));
		}

		if($this->session->userdata('u_type') == 'admin'){
			redirect(base_url('admin'));
		}

		$x = $this->Users_model->get_single_user($this->session->userdata('u_id'));

		if ($x[0]->password !== $this->session->userdata('u_pwd')) {
			session_destroy();
		}
		if ($x[0]->status !== "A") {
            session_destroy();
        }
	}

	public function index()
	{

		if ($this->session->userdata('u_type') == "employer") {
			redirect(base_url('employer'));
		}elseif ($this->session->userdata('u_type') == "freelancer") {
			redirect(base_url('freelancer'));
		}elseif($this->session->userdata('u_type') == 'admin'){
			redirect(base_url('admin'));
		}
	}
	public function profile()
	{
		$data['provinces'] = array(
			'Badakhshan', 'Badghis', 'Baghlan', 'Balkh', 'Bamian', 'Daikondi', 'Farah', 'Faryab', 'Ghazni',
			'Ghowr', 'Helmand', 'Herat', 'Jowzjan', 'Kabul', 'Kandahar', 'Kapisa', 'Khowst', 'Konar', 'Kondoz', 'Laghman', 'Lowgar',
			'Nangarhar', 'Nimruz', 'Nurestan', 'Oruzgan', 'Paktia', 'Paktika', 'Panjshir', 'Parvan', 'Samangan', 'Sar-e Pol', 'Takhar', 'Vardak', 'Zabol'
		);
		if ($this->session->userdata('u_type') == 'freelancer') {
			$data['user_data'] = $this->Users_model->get_user($this->session->userdata('u_id'));
		}elseif ($this->session->userdata('u_type') == 'employer') {
			$data['user_data'] = $this->Users_model->single_user($this->session->userdata('u_id'));
		}

		
		
		$data['title'] = "Profile";
		$this->load->view('header', $data);
		$this->load->view('users/sidebar', $data);
		$this->load->view('users/settings');
		$this->load->view('users/footer');
	}

	public function edit()
	{
		$x = $this->Users_model->get_user($this->session->userdata('u_id'));
		if ($this->input->post('fb') !== $x[0]->fb) {
			$this->form_validation->set_rules('fb', 'Facebook', 'is_unique[users.fb]');
		}
		if ($this->input->post('telegram') !== $x[0]->telegram) {
			$this->form_validation->set_rules('telegram', 'Telegram', 'is_unique[users.telegram]');
		}

		if (!empty($this->input->post('old'))) {
			$this->form_validation->set_rules('old', 'Old Password', 'min_length[8]');
			$this->form_validation->set_rules('confirm', 'Confirm Password', 'matches[new]|min_length[8]');
			$this->form_validation->set_rules('new', 'New Password', 'required');
		}

		if ($this->session->userdata('u_type') == 'freelancer') {
			$this->form_validation->set_rules('fullname', 'Full Name', 'required');
		} elseif ($this->session->userdata('u_type') == 'employer') {
			$this->form_validation->set_rules('company_name', 'Company Name', 'required');
		}


		if ($this->form_validation->run()) {


			if ($this->session->userdata('u_type') == "freelancer") {
				$data = array(
					'fullname' => $this->input->post('fullname'),
					'tagline' => $this->input->post('tagline'),
					'province' => $this->input->post('province'),
					'min_month_price' => $this->input->post('min'),
					'about' => $this->input->post('about'),
					'skills' => substr($this->input->post('skills'), 0, -1)
				);

				if (!empty($this->input->post('fb'))) {
					$data['fb'] = $this->input->post('fb');
				}

				if (!empty($this->input->post('telegram'))) {
					$data['telegram'] = $this->input->post('telegram');
				}

				if (empty($this->input->post('skills'))) {
					$data['skills'] = "";
				}

				if (!empty($_FILES['user_file']['name'])) {
					$config = array(
						'upload_path' => './assets/user_images/',
						'allowed_types' => 'gif|jpg|png|jpeg',
						'encrypt_name' => true,
						'remove_spaces' => true,
						'detect_mime' => true
					);
					$this->load->library('upload', $config);

					// Alternately you can set preferences by calling the ``initialize()`` method. Useful if you auto-load the class:
					$this->upload->initialize($config);

					if ($this->upload->do_upload('user_file')) {
						if ($this->input->post('old_pic') !== "default.png") {
							unlink('assets/user_images/' . $this->input->post('old_pic'));
						}
						$data['photo'] = $this->upload->data('file_name');
						$this->session->set_userdata('u_photo', $this->upload->data('file_name'));
					} else {
						$this->session->set_flashdata('er_msg', $this->upload->display_errors());
						$this->goBack();
					}
				}

				if (!empty($_FILES['user_cv']['name'])) {
					$config = array(
						'upload_path' => './assets/user_cv/',
						'allowed_types' => 'doc|docx|pdf|zip',
						'encrypt_name' => true,
						'remove_spaces' => true,
						'detect_mime' => true
					);
					$this->load->library('upload', $config);

					// Alternately you can set preferences by calling the ``initialize()`` method. Useful if you auto-load the class:
					$this->upload->initialize($config);

					if ($this->upload->do_upload('user_cv')) {
						if (!empty($this->input->post('old_cv'))) {
							unlink('assets/user_cv/' . $this->input->post('old_cv'));
							$datas = array(
								'filename' => $this->upload->data('file_name'),
								'users_id' => $this->session->userdata('u_id')
							);

							if ($this->Users_model->edit_document($datas, $this->input->post('old_cv'))) {
								$this->session->set_flashdata('success_msg', 'Your CV updated successfully');
								$this->goBack();
							}

						}
						$datas = array(
							'filename' => $this->upload->data('file_name'),
							'users_id' => $this->session->userdata('u_id')
						);
						
						if ($this->Users_model->upload_document($datas)) {
							$this->session->set_flashdata('success_msg', 'Your CV uploaded successfully');
							$this->goBack();
						}
						
					} else {
						$this->session->set_flashdata('er_msg', $this->upload->display_errors());
						$this->goBack();
					}
				}



				if (!empty($this->input->post('old'))) {
					$pwd = md5(md5($this->input->post('old')) . 'P@RDAZ!SH');
					$datas = $this->Users_model->get_user($this->session->userdata('u_id'));

					if ($datas[0]->password == $pwd) {
						$data['password'] = md5(md5($this->input->post('new')) . 'P@RDAZ!SH');
					} else {
						$this->session->set_flashdata('er_msg', 'You wrote incorrect password');
						$this->goBack();
					}
				}
			}


			if ($this->session->userdata('u_type') == 'employer') {

				$data = array(
					'company_name' => $this->input->post('company_name'),
					'tagline' => $this->input->post('tagline'),
					'province' => implode(',', $this->input->post('province', true)),
					'min_month_price' => $this->input->post('min'),
					'fb' => $this->input->post('fb'),
					'telegram' => $this->input->post('telegram'),
					'about' => $this->input->post('about')
				);

				if (!empty($this->input->post('old'))) {
					$pwd = md5(md5($this->input->post('old')) . 'P@RDAZ!SH');
					$datas = $this->Users_model->get_user($this->session->userdata('u_id'));

					if ($datas[0]->password == $pwd) {
						$data['password'] = md5(md5($this->input->post('new')) . 'P@RDAZ!SH');
					} else {
						$this->session->set_flashdata('er_msg', 'You wrote incorrect password');
						$this->goBack();
					}
				}

				if (!empty($_FILES['user_file']['name'])) {
					$config = array(
						'upload_path' => './assets/user_images/',
						'allowed_types' => 'gif|jpg|png',
						'encrypt_name' => true,
						'remove_spaces' => true,
						'detect_mime' => true
					);
					$this->load->library('upload', $config);

					// Alternately you can set preferences by calling the ``initialize()`` method. Useful if you auto-load the class:
					$this->upload->initialize($config);

					if ($this->upload->do_upload('user_file')) {
						if ($this->input->post('old_pic') !== "default.png") {
							unlink('assets/user_images/' . $this->input->post('old_pic'));
						}
						$data['photo'] = $this->upload->data('file_name');
						$this->session->set_userdata('u_photo', $this->upload->data('file_name'));
					} else {
						$this->session->set_flashdata('er_msg', $this->upload->display_errors());
						$this->goBack();
					}
				}
			}


			if ($this->Users_model->edit_user($this->session->userdata('u_id'), $data)) {
				$this->session->set_flashdata('success_msg', 'Edited Succesfully');
				$this->goBack();
			} else {
				$this->session->set_flashdata('error_msg', 'Edit Failed');
			}
		} else {
			$this->session->set_flashdata('er_msg', validation_errors());
			$this->goBack();
		}
	}

	public function logout()
	{
		session_destroy();
		redirect(base_url());
	}

	public function goBack()
	{
		header("Location: {$_SERVER['HTTP_REFERER']}");
		exit;
	}
}
