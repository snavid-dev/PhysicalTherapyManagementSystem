<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Report_model');
	}

	public function index()
	{
		$this->require_permission('view_reports');

		$from = $this->input->get('from') ?: date('Y-m-01');
		$to = $this->input->get('to') ?: date('Y-m-t');

		$this->render('reports/index', array(
			'title' => t('Reports'),
			'current_section' => 'reports',
			'from' => $from,
			'to' => $to,
			'summary' => $this->Report_model->summary($from, $to),
			'turns' => $this->Report_model->turns($from, $to),
			'payments' => $this->Report_model->payments($from, $to),
			'leaves' => $this->Report_model->leaves($from, $to),
		));
	}
}
