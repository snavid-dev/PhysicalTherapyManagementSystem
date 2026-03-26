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

		$from_input = trim((string) $this->input->get('from', TRUE));
		$to_input = trim((string) $this->input->get('to', TRUE));
		$from = $from_input !== '' ? $this->gregorian_date_from_shamsi($from_input) : date('Y-m-01');
		$to = $to_input !== '' ? $this->gregorian_date_from_shamsi($to_input) : date('Y-m-t');

		if ($from === '' || $to === '' || $from > $to) {
			$from = date('Y-m-01');
			$to = date('Y-m-t');
			$from_input = to_shamsi($from);
			$to_input = to_shamsi($to);
		}

		if ($from_input === '') {
			$from_input = to_shamsi($from);
		}

		if ($to_input === '') {
			$to_input = to_shamsi($to);
		}

		$this->render('reports/index', array(
			'title' => t('Reports'),
			'current_section' => 'reports',
			'from' => $from_input,
			'to' => $to_input,
			'summary' => $this->Report_model->summary($from, $to),
			'turns' => $this->Report_model->turns($from, $to),
			'payments' => $this->Report_model->payments($from, $to),
			'leaves' => $this->Report_model->leaves($from, $to),
		));
	}
}
