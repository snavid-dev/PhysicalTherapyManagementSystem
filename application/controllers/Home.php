<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Home_model');
		$this->load->model('Users_model');

		$x = $this->Users_model->get_single_user($this->session->userdata('u_id'));

		if ($this->session->userdata('logged_in') !== null) {


			if ($x[0]->password !== $this->session->userdata('u_pwd')) {
				session_destroy();
			}

			if ($x[0]->status !== "A") {
				session_destroy();
			}
		}
	}

	public function index()
	{

		$data['provincess'] = array(
			'Anywhere',
			'Badakhshan', 'Badghis', 'Baghlan', 'Balkh', 'Bamian', 'Daikondi', 'Farah', 'Faryab', 'Ghazni',
			'Ghowr', 'Helmand', 'Herat', 'Jowzjan', 'Kabul', 'Kandahar', 'Kapisa', 'Khowst', 'Konar', 'Kondoz', 'Laghman', 'Lowgar',
			'Nangarhar', 'Nimruz', 'Nurestan', 'Oruzgan', 'Paktia', 'Paktika', 'Panjshir', 'Parvan', 'Samangan', 'Sar-e Pol', 'Takhar', 'Vardak', 'Zabol'
		);
		$this->load->view('header', $data);
		// $this->load->view('index', $data);
		$this->load->view('footer');
	}

	public function provinces()
	{
		$locations = $this->Home_model->get_all_provinces();
		$cyborg = "";
		foreach ($locations as $location) {
			$provinces = explode(',', $location->province);
			foreach ($provinces as $provincess) {
				$cyborg .= $provincess . ",";
			}
		}

		$pardazis = substr($cyborg, 0, -1);

		$province = array_count_values(explode(',', $pardazis));

		return $province;
	}


	public function categories($category = null)
	{
		if ($category == null) {
			redirect(base_url());
		}
		$cat_name = $this->Home_model->get_category_name($category);
		if (empty($cat_name)) {
			$this->_404();
		} else {
			$data['categories'] = [
				'Accounting and Finance', 'Clerical & Data Entry', 'Counseling', 'Court Administration',
				'Human Resources', 'Investigative', 'IT and Computers', 'Law Enforcement', 'Management', 'Miscellaneous', 'Public Relations'
			];

			$data['provinces'] = array(
				'Anywhere',
				'Badakhshan', 'Badghis', 'Baghlan', 'Balkh', 'Bamian', 'Daikondi', 'Farah', 'Faryab', 'Ghazni',
				'Ghowr', 'Helmand', 'Herat', 'Jowzjan', 'Kabul', 'Kandahar', 'Kapisa', 'Khowst', 'Konar', 'Kondoz', 'Laghman', 'Lowgar',
				'Nangarhar', 'Nimruz', 'Nurestan', 'Oruzgan', 'Paktia', 'Paktika', 'Panjshir', 'Parvan', 'Samangan', 'Sar-e Pol', 'Takhar', 'Vardak', 'Zabol'
			);


			$config['base_url'] = base_url('home/categories/' . $category);
			$config['per_page'] = 6;
			$config['num_links'] = 5;
			$config['cur_tag_open'] = "<li><a href='#' disabled class=\"current-page\">";
			$config['cur_tag_close'] = "</a></li>";
			if ($this->Home_model->num_rows_SBC($category, 'job') > 0) {
				$config['total_rows'] = $this->Home_model->num_rows_SBC($category, 'job');
			}

			if ($this->Home_model->num_rows_SBC($category, 'task') > 0) {
				$config['total_rows'] = $this->Home_model->num_rows_SBC($category, 'task');
			}


			$config['num_tag_open'] = "<li>";
			$config['num_tag_close'] = "</li>";
			$config['full_tag_open'] = "<div class='pagination-container margin-top-20 margin-bottom-20'><nav class='pagination'><ul>";
			$config['full_tag_close'] = "</ul></nav></div>";
			$config['first_link'] = "first";
			$config['next_link'] = "<i class='icon-material-outline-keyboard-arrow-right'></i>";
			$config['prev_link'] = "<i class='icon-material-outline-keyboard-arrow-left'></i>";
			$config['next_tag_open'] = "<li class='next'><i class='prev-post'>";
			$config['next_tag_close'] = "</i></li>";
			$config['prev_tag_open'] = "<li class='prev'>";
			$config['prev_tag_close'] = "</li>";
			$this->pagination->initialize($config);

			$data['title'] = "Posts of " . $cat_name[0]->category;
			$data['jobs'] = $this->Home_model->SBC($category, 'job', $config['per_page'], $this->uri->segment(4));
			$data['tasks'] = $this->Home_model->SBC($category, 'task', $config['per_page'], $this->uri->segment(4));
			$this->load->view('header', $data);
			$this->load->view('sidebar', $data);
			$this->load->view('category', $data);
			$this->load->view('footer');
		}
	}

	public function single_job($date = null, $slug = null)
	{
		if ($slug == null || $date == null) {
			redirect(base_url());
		}
		$data['single'] = $this->Home_model->single_job($date, urldecode($slug));
		if ($data['single'][0]->opportunity_type == 'task') {
			redirect(base_url('home/single_task/' . $date . '/' . $slug));
		}
		if (count($data['single']) == 0) {
			$this->_404();
		} else {
			if ($data['single'][0]->status == 'P') {
				if ($this->session->userdata('logged_in') == true) {
					if (count($this->Home_model->check_user_job($date, $slug, $this->session->userdata('u_id'))) == 0) {
						if ($this->session->userdata('u_type') !== 'admin') {
							$this->session->set_flashdata('error_msg', 'This Job is not verfied or it is expired');
							redirect(base_url());
						} else {
							$data['title'] = $data['single'][0]->job_title;
							$data['similars'] = $this->Home_model->similar_jobs();
							$this->load->view('header', $data);
							$this->load->view('single-job', $data);
							$this->load->view('footer');
						}
					} else {
						$data['title'] = $data['single'][0]->job_title;
						$data['similars'] = $this->Home_model->similar_jobs();
						$this->load->view('header', $data);
						$this->load->view('single-job', $data);
						$this->load->view('footer');
					}
				} else {
					$this->session->set_flashdata('error_msg', 'This Job is not verfied or it is expired');
					redirect(base_url());
				}
			} else {
				$data['title'] = $data['single'][0]->job_title;
				$data['similars'] = $this->Home_model->similar_jobs();
				$this->load->view('header', $data);
				$this->load->view('single-job', $data);
				$this->load->view('footer');
			}
		}
	}


	public function single_task($date = null, $slug = null)
	{
		if ($slug == null || $date == null) {
			redirect(base_url());
		}
		$data['single'] = $this->Home_model->single_job($date, $slug);
		if ($data['single'][0]->opportunity_type == 'job') {
			redirect(base_url('home/single_job/' . $date . '/' . $slug));
		}
		if (count($data['single']) == 0) {
			$this->_404();
		} else {
			if ($data['single'][0]->status == 'P' || $data['single'][0]->diff < 0) {
				if ($this->session->userdata('logged_in') == true) {
					if (count($this->Home_model->check_user_job($date, $slug, $this->session->userdata('u_id'))) == 0) {
						if ($this->session->userdata('u_type') !== 'admin') {
							$this->session->set_flashdata('error_msg', 'This Task is not verfied or it is expired');
							redirect(base_url());
						} else {
							$data['bidders'] = $this->Home_model->show_bidders($data['single'][0]->id);
							$data['title'] = $data['single'][0]->job_title;
							$this->load->view('header', $data);
							$this->load->view('single-task', $data);
							$this->load->view('footer');
						}
					} else {
						$data['bidders'] = $this->Home_model->show_bidders($data['single'][0]->id);
						$data['title'] = $data['single'][0]->job_title;
						$this->load->view('header', $data);
						$this->load->view('single-task', $data);
						$this->load->view('footer');
					}
				} else {
					$this->session->set_flashdata('error_msg', 'This Task is not verfied or it is expired');
					redirect(base_url());
				}
			} else {
				$data['bidders'] = $this->Home_model->show_bidders($data['single'][0]->id);
				$data['title'] = $data['single'][0]->job_title;
				$this->load->view('header', $data);
				$this->load->view('single-task', $data);
				$this->load->view('footer');
			}
		}
	}

	public function apply($date = null, $slug = null)
	{

		if ($slug == null || $date == null) {
			redirect(base_url());
		}

		if ($this->session->userdata('logged_in') !== true) {
			$this->session->set_flashdata('er_msg', 'Please login to apply the Job');
			redirect(base_url('login'));
		}

		if ($this->session->userdata('logged_in') == true) {
			$check_slug = $this->Home_model->check_slug($slug, $date);
			if (count($check_slug) == 0) {
				$this->_404();
			} elseif (count($check_slug) >= 1) {

				$user_status  = $this->Home_model->user_status($this->session->userdata('u_id'));

				if ($this->session->userdata('u_type') !== 'freelancer' ||  $user_status[0]->status !== 'A') {
					$this->session->set_flashdata('error_msg', 'For Now You can not Apply Jobs');
					redirect(base_url());
				} elseif (count($this->Home_model->apply_before($this->session->userdata('u_id'), $check_slug[0]->id)) == 0) {
					$data = array(
						'users_id' => $this->session->userdata('u_id'),
						'apply_date' => date('Y/m/d H:i:s'),
						'type' => 'apply',
						'status' => "P",
						'opportunity_id' => $check_slug[0]->id
					);

					if ($this->Home_model->apply($data) === true) {
						$this->session->set_flashdata('successs_msg', 'You Applied Successfully');
						redirect(base_url());
					}
				} else {
					$this->session->set_flashdata('error_msg', 'You applied this Job before');
					redirect(base_url());
				}
			}
		}
	}

	public function _404()
	{
		$data['title'] = '404 Page Not Found';
		$this->load->view('header', $data);
		$this->load->view('404');
		$this->load->view('footer');
	}

	public function bid()
	{

		if (empty($this->input->post('slug')) || empty($this->input->post('date'))) {
			$this->session->set_flashdata('error_msg', 'There Was an error due submiting your Bid');
			redirect(base_url());
		}

		if ($this->session->userdata('logged_in') == true) {

			$user_status  = $this->Home_model->user_status($this->session->userdata('u_id'));

			$check_slug = $this->Home_model->check_slug($this->input->post('slug'), $this->input->post('date'));

			if (($this->session->userdata('u_type') !== 'freelancer') ||  ($user_status[0]->status !== 'A') || (count($check_slug) == 0)) {
				$this->session->set_flashdata('error_msg', 'For Now You can not Bid Tasks');
				redirect(base_url());
				//exit();
			}
			if (count($this->Home_model->apply_before($this->session->userdata('u_id'), $check_slug[0]->id)) == 0) {
				$data = array(
					'users_id' => $this->session->userdata('u_id'),
					'opportunity_id' => $check_slug[0]->id,
					'type' => 'bid',
					'status' => "P",
					'minimal_rate' => $this->input->post('min_rate'),
					'delivery_time' => $this->input->post('qtyInput') . " " . ucfirst($this->input->post('duration'))
				);


				if ($this->Home_model->apply($data) === true) {
					$this->session->set_flashdata('successs_msg', 'You Bidded Successfully');
					redirect(base_url());
				}
			} else {
				$this->session->set_flashdata('error_msg', 'You Bided this Task before');
				redirect(base_url());
			}
		} else {
			$this->session->set_flashdata('er_msg', 'You are not logged in please login to place a bid');
			redirect(base_url('login'));
		}
	}

	public function rfp()
	{
		$config['base_url'] = base_url('home/rfp');
		$config['per_page'] = 8;
		$config['num_links'] = 5;
		$config['cur_tag_open'] = "<li><a href='#' disabled class=\"current-page\">";
		$config['cur_tag_close'] = "</a></li>";
		$config['total_rows'] = $this->Home_model->num_rows_rfp();
		$config['num_tag_open'] = "<li>";
		$config['num_tag_close'] = "</li>";
		$config['full_tag_open'] = "<div class='pagination-container margin-top-20 margin-bottom-20'><nav class='pagination'><ul>";
		$config['full_tag_close'] = "</ul></nav></div>";
		$config['first_link'] = "first";
		$config['next_link'] = "<i class='icon-material-outline-keyboard-arrow-right'></i>";
		$config['prev_link'] = "<i class='icon-material-outline-keyboard-arrow-left'></i>";
		$config['next_tag_open'] = "<li class='next'><i class='prev-post'>";
		$config['next_tag_close'] = "</i></li>";
		$config['prev_tag_open'] = "<li class='prev'>";
		$config['prev_tag_close'] = "</li>";
		$this->pagination->initialize($config);

		$data['count'] = $this->Home_model->num_rows_rfp();
		$data['title'] = "Request For Proposals";
		$data['rfps'] = $this->Home_model->show_rfp($config['per_page'], $this->uri->segment(3));
		$this->load->view('header', $data);
		$this->load->view('page-rfp', $data);
		$this->load->view('footer');
	}

	public function single_proposal($date = null, $slug = null)
	{

		if (empty($date) || empty($slug)) {
			redirect(base_url());
			exit();
		}
		$data['single'] = $this->Home_model->single_rfp($date, $slug);
		if (empty($data['single'])) {
			$this->_404();
		} else {
			if ($data['single'][0]->status == 'P' || $data['single'][0]->diff < 0) {
				if ($this->session->userdata('logged_in') == true) {
					if (count($this->Home_model->check_user_rfp($date, $slug, $this->session->userdata('u_id'))) == 0) {
						$this->session->set_flashdata('error_msg', 'This Proposal is not verfied or it is expired');
						redirect(base_url());
					}
				} else {
					$this->session->set_flashdata('error_msg', 'This Proposal is not verfied or it is expired');
					redirect(base_url());
				}
			}

			$data['title'] = $data['single'][0]->title;

			$this->load->view('header', $data);
			$this->load->view('single-rfp', $data);
			$this->load->view('footer');
		}
	}

	public function rfq()
	{

		$config['base_url'] = base_url('home/rfq');
		$config['per_page'] = 8;
		$config['num_links'] = 5;
		$config['cur_tag_open'] = "<li><a href='#' disabled class=\"current-page\">";
		$config['cur_tag_close'] = "</a></li>";
		$config['total_rows'] = $this->Home_model->num_rows_rfq();
		$config['num_tag_open'] = "<li>";
		$config['num_tag_close'] = "</li>";
		$config['full_tag_open'] = "<div class='pagination-container margin-top-20 margin-bottom-20'><nav class='pagination'><ul>";
		$config['full_tag_close'] = "</ul></nav></div>";
		$config['first_link'] = "first";
		$config['next_link'] = "<i class='icon-material-outline-keyboard-arrow-right'></i>";
		$config['prev_link'] = "<i class='icon-material-outline-keyboard-arrow-left'></i>";
		$config['next_tag_open'] = "<li class='next'><i class='prev-post'>";
		$config['next_tag_close'] = "</i></li>";
		$config['prev_tag_open'] = "<li class='prev'>";
		$config['prev_tag_close'] = "</li>";
		$this->pagination->initialize($config);

		$data['title'] = "Request For Quotations";
		$data['rfqs'] = $this->Home_model->show_rfq($config['per_page'], $this->uri->segment(3));
		$this->load->view('header', $data);
		$this->load->view('page-rfq', $data);
		$this->load->view('footer');
	}

	public function single_quotation($date = null, $slug = null)
	{

		if (empty($date) || empty($slug)) {
			redirect(base_url());
			exit();
		}
		$data['single'] = $this->Home_model->single_rfq($date, $slug);
		if (empty($data['single'])) {
			$this->_404();
		} else {
			if ($data['single'][0]->status == 'P' || $data['single'][0]->diff < 0) {
				if ($this->session->userdata('logged_in') == true) {
					if (count($this->Home_model->check_user_rfq($date, $slug, $this->session->userdata('u_id'))) == 0) {
						$this->session->set_flashdata('error_msg', 'This Quotation is not verfied or it is expired');
						redirect(base_url());
					}
				} else {
					$this->session->set_flashdata('error_msg', 'This Quotation is not verfied or it is expired');
					redirect(base_url());
				}
			}

			$data['title'] = $data['single'][0]->title;

			$this->load->view('header', $data);
			$this->load->view('single-rfq', $data);
			$this->load->view('footer');
		}
	}

	public function jobs()
	{
		$offset = $this->uri->segment(3);
		$extra = "";
		$keys = substr($this->input->post('keywords'), 0, -1);
		$types = $this->input->post('type[]');
		$categories = $this->input->post('categories[]');
		$provinces = $this->input->post('province[]');
		$title = $this->input->post('title');
		if (!empty($types)) {
			$x = "";
			foreach ($types as $type) {
				$x .= "opportunity.type ='" . $type . "' OR ";
				$this->session->set_flashdata($type, "");
			}

			$extra .= "AND (" . substr($x, 0, -3) . ") ";
		}

		if (!empty($title)) {
			$extra .= "AND (opportunity.job_title LIKE '" . $title . "%') ";
		}

		if (!empty($categories)) {
			$x = "";
			foreach ($categories as $category) {
				$x .= "opportunity.type ='" . $category . "' OR ";
			}

			$extra .= "AND (" . substr($x, 0, -3) . ") ";
		}

		if (!empty($provinces)) {
			$x = "";
			foreach ($provinces as $province) {
				$x .= "opportunity.province LIKE '%" . $province . "%' OR ";
			}

			$extra .= "AND (" . substr($x, 0, -3) . ") ";
		}

		if (!empty($keys)) {
			$keyss = explode(',', $keys);
			$x = "";
			foreach ($keyss as $key) {
				$x .= "opportunity.job_title
				 LIKE '%" . $key . "%' OR ";
			}

			$extra .= "AND (" . substr($x, 0, -3) . ") ";
		}



		$config['base_url'] = base_url('home/jobs');
		$config['per_page'] = 12;
		$config['num_links'] = 5;
		$config['cur_tag_open'] = "<li><a href='#' disabled class=\"current-page\">";
		$config['cur_tag_close'] = "</a></li>";
		$config['total_rows'] = $this->Home_model->num_rows_jobs($extra);
		$config['num_tag_open'] = "<li>";
		$config['num_tag_close'] = "</li>";
		$config['full_tag_open'] = "<div class='pagination-container margin-top-20 margin-bottom-20'><nav class='pagination'><ul>";
		$config['full_tag_close'] = "</ul></nav></div>";
		$config['first_link'] = "first";
		$config['next_link'] = "<i class='icon-material-outline-keyboard-arrow-right'></i>";
		$config['prev_link'] = "<i class='icon-material-outline-keyboard-arrow-left'></i>";
		$config['next_tag_open'] = "<li class='next'><i class='prev-post'>";
		$config['next_tag_close'] = "</i></li>";
		$config['prev_tag_open'] = "<li class='prev'>";
		$config['prev_tag_close'] = "</li>";
		$this->pagination->initialize($config);


		$data['jobs'] = $this->Home_model->jobs($extra, $config['per_page'], $offset);

		$data['categories'] = [
			'Accounting and Finance', 'Clerical & Data Entry', 'Counseling', 'Court Administration',
			'Human Resources', 'Investigative', 'IT and Computers', 'Law Enforcement', 'Management', 'Miscellaneous', 'Public Relations'
		];

		$data['count'] = $this->Home_model->num_rows_jobs($extra);

		$data['provinces'] = array(
			'Anywhere',
			'Badakhshan', 'Badghis', 'Baghlan', 'Balkh', 'Bamian', 'Daikondi', 'Farah', 'Faryab', 'Ghazni',
			'Ghowr', 'Helmand', 'Herat', 'Jowzjan', 'Kabul', 'Kandahar', 'Kapisa', 'Khowst', 'Konar', 'Kondoz', 'Laghman', 'Lowgar',
			'Nangarhar', 'Nimruz', 'Nurestan', 'Oruzgan', 'Paktia', 'Paktika', 'Panjshir', 'Parvan', 'Samangan', 'Sar-e Pol', 'Takhar', 'Vardak', 'Zabol'
		);
		$data['title'] = "Jobs | cyborg";
		$this->load->view('header', $data);
		$this->load->view('page-job', $data);
		$this->load->view('footer-small');
	}

	public function province($province)
	{
		$offset = $this->uri->segment(4);
		$extra = "";
		if (!empty($pv)) {
			$extra .= "AND opportunity.province LIKE '%" . $pv . "%' ";
		}

		$config['base_url'] = base_url('home/province');
		$config['per_page'] = 12;
		$config['num_links'] = 5;
		$config['cur_tag_open'] = "<li><a href='#' disabled class=\"current-page\">";
		$config['cur_tag_close'] = "</a></li>";
		$config['total_rows'] = $this->Home_model->num_rows_jobs($extra);
		$config['num_tag_open'] = "<li>";
		$config['num_tag_close'] = "</li>";
		$config['full_tag_open'] = "<div class='pagination-container margin-top-20 margin-bottom-20'><nav class='pagination'><ul>";
		$config['full_tag_close'] = "</ul></nav></div>";
		$config['first_link'] = "first";
		$config['next_link'] = "<i class='icon-material-outline-keyboard-arrow-right'></i>";
		$config['prev_link'] = "<i class='icon-material-outline-keyboard-arrow-left'></i>";
		$config['next_tag_open'] = "<li class='next'><i class='prev-post'>";
		$config['next_tag_close'] = "</i></li>";
		$config['prev_tag_open'] = "<li class='prev'>";
		$config['prev_tag_close'] = "</li>";
		$this->pagination->initialize($config);


		$data['jobs'] = $this->Home_model->jobs($extra, $config['per_page'], $offset);

		$data['categories'] = [
			'Accounting and Finance', 'Clerical & Data Entry', 'Counseling', 'Court Administration',
			'Human Resources', 'Investigative', 'IT and Computers', 'Law Enforcement', 'Management', 'Miscellaneous', 'Public Relations'
		];

		$data['count'] = $this->Home_model->num_rows_jobs($extra);

		$data['provinces'] = array(
			'Anywhere',
			'Badakhshan', 'Badghis', 'Baghlan', 'Balkh', 'Bamian', 'Daikondi', 'Farah', 'Faryab', 'Ghazni',
			'Ghowr', 'Helmand', 'Herat', 'Jowzjan', 'Kabul', 'Kandahar', 'Kapisa', 'Khowst', 'Konar', 'Kondoz', 'Laghman', 'Lowgar',
			'Nangarhar', 'Nimruz', 'Nurestan', 'Oruzgan', 'Paktia', 'Paktika', 'Panjshir', 'Parvan', 'Samangan', 'Sar-e Pol', 'Takhar', 'Vardak', 'Zabol'
		);
		$data['title'] = "Jobs | cyborg";
		$this->load->view('header', $data);
		$this->load->view('provinces', $data);
		$this->load->view('footer-small');
	}


	public function tasks()
	{

		$extra = "";
		$keys = substr($this->input->post('keywords'), 0, -1);
		$types = $this->input->post('type[]');
		$categories = $this->input->post('categories[]');
		$provinces = $this->input->post('province[]');
		if (!empty($types)) {
			$x = "";
			foreach ($types as $type) {
				$x .= "opportunity.type ='" . $type . "' OR ";
				$this->session->set_flashdata($type, "");
			}

			$extra .= "AND (" . substr($x, 0, -3) . ") ";
		}

		if (!empty($categories)) {
			$x = "";
			foreach ($categories as $category) {
				$x .= "opportunity.type ='" . $category . "' OR ";
			}

			$extra .= "AND (" . substr($x, 0, -3) . ") ";
		}

		if (!empty($provinces)) {
			$x = "";
			foreach ($provinces as $province) {
				$x .= "opportunity.province LIKE '%" . $province . "%' OR ";
			}

			$extra .= "AND (" . substr($x, 0, -3) . ") ";
		}

		if (!empty($keys)) {
			$keyss = explode(',', $keys);
			$x = "";
			foreach ($keyss as $key) {
				$x .= "opportunity.job_title
				 LIKE '%" . $key . "%' OR ";
			}

			$extra .= "AND (" . substr($x, 0, -3) . ") ";
		}

		$config['base_url'] = base_url('home/tasks');
		$config['per_page'] = 6;
		$config['num_links'] = 5;
		$config['cur_tag_open'] = "<li><a href='#' disabled class=\"current-page\">";
		$config['cur_tag_close'] = "</a></li>";
		$config['total_rows'] = $this->Home_model->num_rows_tasks($extra);
		$config['num_tag_open'] = "<li>";
		$config['num_tag_close'] = "</li>";
		$config['full_tag_open'] = "<div class='pagination-container margin-top-20 margin-bottom-20'><nav class='pagination'><ul>";
		$config['full_tag_close'] = "</ul></nav></div>";
		$config['first_link'] = "first";
		$config['next_link'] = "<i class='icon-material-outline-keyboard-arrow-right'></i>";
		$config['prev_link'] = "<i class='icon-material-outline-keyboard-arrow-left'></i>";
		$config['next_tag_open'] = "<li class='next'><i class='prev-post'>";
		$config['next_tag_close'] = "</i></li>";
		$config['prev_tag_open'] = "<li class='prev'>";
		$config['prev_tag_close'] = "</li>";
		$this->pagination->initialize($config);

		$data['provinces'] = array(
			'Anywhere',
			'Badakhshan', 'Badghis', 'Baghlan', 'Balkh', 'Bamian', 'Daikondi', 'Farah', 'Faryab', 'Ghazni',
			'Ghowr', 'Helmand', 'Herat', 'Jowzjan', 'Kabul', 'Kandahar', 'Kapisa', 'Khowst', 'Konar', 'Kondoz', 'Laghman', 'Lowgar',
			'Nangarhar', 'Nimruz', 'Nurestan', 'Oruzgan', 'Paktia', 'Paktika', 'Panjshir', 'Parvan', 'Samangan', 'Sar-e Pol', 'Takhar', 'Vardak', 'Zabol'
		);

		$data['categories'] = [
			'Admin Support', 'Customer Service', 'Data Analytic', 'Design and Creative',
			'Legal', 'Software Developing', 'IT and Networking', 'Writing', 'Translation', 'Sales and Marketing'
		];

		$data['count'] = $this->Home_model->num_rows_tasks($extra);
		$data['tasks'] = $this->Home_model->tasks($extra, $config['per_page'], $this->uri->segment(3));
		$data['title'] = "Tasks | cyborg";
		$this->load->view('header', $data);
		$this->load->view('page-task', $data);
		$this->load->view('footer');
	}


	public function companies($letter = null)
	{

		switch ($letter) {
			case null:
				$data['letter'] = 'extra';
				$extra = "";
				break;
			case 'A':
				$data['letter'] = "a";
				$extra = "AND users.company_name LIKE 'A%'";
				break;

			case 'B':
				$data['letter'] = "b";
				$extra = "AND users.company_name LIKE 'B%'";
				break;

			case 'B':
				$data['letter'] = "b";
				$extra = "AND users.company_name LIKE 'B%'";
				break;

			case 'C':
				$data['letter'] = "c";
				$extra = "AND users.company_name LIKE 'C%'";
				break;

			case 'D':
				$data['letter'] = "d";
				$extra = "AND users.company_name LIKE 'D%'";
				break;
			case 'E':
				$data['letter'] = "e";
				$extra = "AND users.company_name LIKE 'E%'";
				break;
			case 'F':
				$data['letter'] = "f";
				$extra = "AND users.company_name LIKE 'F%'";
				break;
			case 'G':
				$data['letter'] = "g";
				$extra = "AND users.company_name LIKE 'G%'";
				break;
			case 'H':
				$data['letter'] = "h";
				$extra = "AND users.company_name LIKE 'H%'";
				break;
			case 'I':
				$data['letter'] = "i";
				$extra = "AND users.company_name LIKE 'I%'";
				break;
			case 'J':
				$data['letter'] = "j";
				$extra = "AND users.company_name LIKE 'J%'";
				break;
			case 'K':
				$data['letter'] = "k";
				$extra = "AND users.company_name LIKE 'K%'";
				break;
			case 'L':
				$data['letter'] = "l";
				$extra = "AND users.company_name LIKE 'L%'";
				break;
			case 'M':
				$data['letter'] = "m";
				$extra = "AND users.company_name LIKE 'M%'";
				break;
			case 'N':
				$data['letter'] = "n";
				$extra = "AND users.company_name LIKE 'N%'";
				break;
			case 'O':
				$data['letter'] = "o";
				$extra = "AND users.company_name LIKE 'O%'";
				break;
			case 'P':
				$data['letter'] = "p";
				$extra = "AND users.company_name LIKE 'P%'";
				break;
			case 'Q':
				$data['letter'] = "q";
				$extra = "AND users.company_name LIKE 'Q%'";
				break;
			case 'R':
				$data['letter'] = "r";
				$extra = "AND users.company_name LIKE 'R%'";
				break;
			case 'S':
				$data['letter'] = "s";
				$extra = "AND users.company_name LIKE 'S%'";
				break;
			case 'T':
				$data['letter'] = "t";
				$extra = "AND users.company_name LIKE 'T%'";
				break;
			case 'U':
				$data['letter'] = "u";
				$extra = "AND users.company_name LIKE 'U%'";
				break;
			case 'V':
				$data['letter'] = "v";
				$extra = "AND users.company_name LIKE 'V%'";
				break;
			case 'W':
				$data['letter'] = "w";
				$extra = "AND users.company_name LIKE 'W%'";
				break;
			case 'X':
				$data['letter'] = "x";
				$extra = "AND users.company_name LIKE 'X%'";
				break;
			case 'Y':
				$data['letter'] = "y";
				$extra = "AND users.company_name LIKE 'Y%'";
				break;
			case 'Z':
				$data['letter'] = "z";
				$extra = "AND users.company_name LIKE 'Z%'";
				break;
			default:
				$this->_404();
				break;
		}
		if (isset($extra)) {

			$data['companies'] = $this->Home_model->select_company($extra);
			$data['title'] = "Companies | cyborg";
			$this->load->view('header', $data);
			$this->load->view('page-company', $data);
			$this->load->view('footer');
		}
	}

	public function company($slug = null)
	{
		if ($slug == null) {
			redirect(base_url());
			exit();
		}
		$data['company'] = $this->Home_model->check_company($slug);



		if ($this->session->userdata('u_type') == 'admin') {
			$data['jobs'] = $this->Home_model->job_where($data['company'][0]->id);
		}

		if (count($data['company']) == 0) {
			$this->_404();
		} elseif ($data['company'][0]->status == 'P') {
			$data['jobs'] = $this->Home_model->jobs_where($data['company'][0]->id);

			if ($data['company'][0]->id !== $this->session->userdata('u_id')) {
				if (($this->session->userdata('u_type') !== 'admin')) {
					$this->session->set_flashdata('error_msg', 'The company You attempted is not verfied yet');
					redirect(base_url());
				}

				if (($this->session->userdata('u_type') == 'admin')) {
					$data['title'] = ucwords($data['company'][0]->company_name);
					$this->load->view('header', $data);
					$this->load->view('single-employer', $data);
					$this->load->view('footer');
				}
			} elseif (($data['company'][0]->id == $this->session->userdata('u_id')) || ($this->session->userdata('u_type') == 'admin')) {
				$data['title'] = ucwords($data['company'][0]->company_name);
				$this->load->view('header', $data);
				$this->load->view('single-employer', $data);
				$this->load->view('footer');
			}
		} else {
			$data['jobs'] = $this->Home_model->jobs_where($data['company'][0]->id);
			$data['title'] = ucwords($data['company'][0]->company_name);
			$this->load->view('header', $data);
			$this->load->view('single-employer', $data);
			$this->load->view('footer');
		}
	}

	public function freelancer($slug = null)
	{
		if ($slug == null) {
			redirect(base_url());
			exit();
		}

		$data['freelancer'] = $this->Home_model->check_freelancer($slug);
		if (count($data['freelancer']) == 0) {
			$this->_404();
		} else {
			$data['document'] = $this->Home_model->get_user_document($data['freelancer'][0]->id);

			if ($data['freelancer'][0]->status == 'P') {
				if ($this->session->userdata('u_type') == 'admin') {
					$data['title'] = ucwords($data['freelancer'][0]->fullname);
					$this->load->view('header', $data);
					$this->load->view('single-freelancer', $data);
					$this->load->view('footer');
				} elseif ($data['freelancer'][0]->id !== $this->session->userdata('u_id')) {
					$this->session->set_flashdata('error_msg', 'The freelancer You attempted is not verfied yet');
					redirect(base_url());
					exit();
				} elseif ($data['freelancer'][0]->id == $this->session->userdata('u_id')) {
					$data['title'] = ucwords($data['freelancer'][0]->fullname);
					$this->load->view('header', $data);
					$this->load->view('single-freelancer', $data);
					$this->load->view('footer');
				}
			} else {
				$data['title'] = ucwords($data['freelancer'][0]->fullname);
				$this->load->view('header', $data);
				$this->load->view('single-freelancer', $data);
				$this->load->view('footer');
			}
		}
	}

	public function freelancers()
	{
		$extra = "";
		$keys = substr($this->input->post('keywords'), 0, -1);
		$skills = substr($this->input->post('skills'), 0, -1);
		$types = $this->input->post('type[]');
		$provinces = $this->input->post('province[]');

		if (!empty($keys)) {
			$keyss = explode(',', $keys);
			$x = "";
			foreach ($keyss as $key) {
				$x .= "users.fullname
				 LIKE '%" . $key . "%' OR ";
			}

			$extra .= "AND (" . substr($x, 0, -3) . ") ";
		}

		if (!empty($provinces)) {
			$x = "";
			foreach ($provinces as $province) {
				$x .= "users.province LIKE '%" . $province . "%' OR ";
			}

			$extra .= "AND (" . substr($x, 0, -3) . ") ";
		}

		if (!empty($types) && empty($skills)) {
			$x = "";
			foreach ($types as $type) {
				$x .= "users.skills LIKE '%" . $type . "%' OR ";
			}

			$extra .= "AND (" . substr($x, 0, -3) . ") ";
		}

		if (!empty($skills) && empty($types)) {
			$x = "";
			$skillss = explode(',', $skills);
			foreach ($skillss as $skill) {
				$x .= "users.skills LIKE '%" . $skill . "%' OR ";
			}

			$extra .= "AND (" . substr($x, 0, -3) . ") ";
		}

		$config['base_url'] = base_url('home/freelancers');
		$config['per_page'] = 6;
		$config['num_links'] = 5;
		$config['cur_tag_open'] = "<li><a href='#' disabled class=\"current-page\">";
		$config['cur_tag_close'] = "</a></li>";
		$config['total_rows'] = $this->Home_model->num_rows_freelancers($extra);
		$config['num_tag_open'] = "<li>";
		$config['num_tag_close'] = "</li>";
		$config['full_tag_open'] = "<div class='pagination-container margin-top-20 margin-bottom-20'><nav class='pagination'><ul>";
		$config['full_tag_close'] = "</ul></nav></div>";
		$config['first_link'] = "first";
		$config['next_link'] = "<i class='icon-material-outline-keyboard-arrow-right'></i>";
		$config['prev_link'] = "<i class='icon-material-outline-keyboard-arrow-left'></i>";
		$config['next_tag_open'] = "<li class='next'><i class='prev-post'>";
		$config['next_tag_close'] = "</i></li>";
		$config['prev_tag_open'] = "<li class='prev'>";
		$config['prev_tag_close'] = "</li>";
		$this->pagination->initialize($config);




		$data['provinces'] = array(
			'Anywhere',
			'Badakhshan', 'Badghis', 'Baghlan', 'Balkh', 'Bamian', 'Daikondi', 'Farah', 'Faryab', 'Ghazni',
			'Ghowr', 'Helmand', 'Herat', 'Jowzjan', 'Kabul', 'Kandahar', 'Kapisa', 'Khowst', 'Konar', 'Kondoz', 'Laghman', 'Lowgar',
			'Nangarhar', 'Nimruz', 'Nurestan', 'Oruzgan', 'Paktia', 'Paktika', 'Panjshir', 'Parvan', 'Samangan', 'Sar-e Pol', 'Takhar', 'Vardak', 'Zabol'
		);
		$data['count'] = $this->Home_model->num_rows_freelancers($extra);
		$data['freelancers'] = $this->Home_model->freelancers($extra, $config['per_page'], $this->uri->segment(3));
		$data['title'] = "Freelancers | cyborg";
		$this->load->view('header', $data);
		$this->load->view('page-freelancer', $data);
		$this->load->view('footer-small');
	}


	public function goBack()
	{
		header("Location: {$_SERVER['HTTP_REFERER']}");
		exit;
	}
}
