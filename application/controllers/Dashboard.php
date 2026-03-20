<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Dashboard_model');
	}

	public function index()
	{
		$this->render('dashboard/index', array(
			'title' => t('Dashboard'),
			'current_section' => 'dashboard',
			'stats' => $this->Dashboard_model->stats(),
			'today_turns' => $this->Dashboard_model->today_turns(),
			'recent_payments' => $this->Dashboard_model->recent_payments(),
		));
	}
}
