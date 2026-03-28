<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Dashboard_model');
		$this->load->model('Safe_model');
	}

	public function index()
	{
		$safe_balance = NULL;

		if ($this->auth->has_permission('view_safe')) {
			$safe_balance = $this->Dashboard_model->get_safe_balance();
		}

		$this->render('dashboard/index', array(
			'title' => t('Dashboard'),
			'current_section' => 'dashboard',
			'stats' => $this->Dashboard_model->stats(),
			'today_turns' => $this->Dashboard_model->today_turns(),
			'safe_balance' => $safe_balance,
			'today_shamsi' => shamsi_today(),
		));
	}
}
