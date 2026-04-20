<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Report_model');
		$this->load->model('Section_model');
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
			'leaves' => $this->Report_model->leaves($from, $to),
		));
	}

	public function daily_register()
	{
		$this->require_permission('view_reports');

		$data = $this->daily_register_view_data();
		$data['title'] = t('daily_register');
		$data['current_section'] = 'reports';

		$this->render('reports/daily_register', $data);
	}

	public function daily_register_print()
	{
		$this->require_permission('view_reports');

		$data = $this->daily_register_view_data();
		$data['title'] = t('daily_register');

		$this->load->view('reports/daily_register_print', $data);
	}

	public function outstanding_balances()
	{
		$this->require_permission('view_reports');

		$status_filter = strtolower(trim((string) $this->input->get('status', TRUE)));
		$search = trim((string) $this->input->get('search', TRUE));

		if (!in_array($status_filter, array('all', 'negative_wallet', 'debt', 'both'), TRUE)) {
			$status_filter = 'all';
		}

		$this->render('reports/outstanding_balances', array(
			'title' => t('Outstanding Balances'),
			'current_section' => 'reports',
			'filters' => array(
				'status' => $status_filter,
				'search' => $search,
			),
			'rows' => $this->Report_model->get_outstanding_balances(array(
				'status' => $status_filter,
				'search' => $search,
			)),
		));
	}

	public function patient_financial_summary()
	{
		$this->require_permission('view_reports');

		$search = trim((string) $this->input->get('search', TRUE));
		$from_input = trim((string) $this->input->get('from', TRUE));
		$to_input = trim((string) $this->input->get('to', TRUE));
		$from = $from_input !== '' ? $this->gregorian_date_from_shamsi($from_input) : '';
		$to = $to_input !== '' ? $this->gregorian_date_from_shamsi($to_input) : '';

		if ($from_input !== '' && $from === '') {
			$from_input = '';
		}

		if ($to_input !== '' && $to === '') {
			$to_input = '';
		}

		if ($from !== '' && $to !== '' && $from > $to) {
			$temp = $from;
			$from = $to;
			$to = $temp;

			$temp_input = $from_input;
			$from_input = $to_input;
			$to_input = $temp_input;
		}

		$this->render('reports/patient_financial_summary', array(
			'title' => t('Patient Financial Summary'),
			'current_section' => 'reports',
			'filters' => array(
				'search' => $search,
				'from' => $from_input,
				'to' => $to_input,
			),
			'rows' => $this->Report_model->get_patient_financial_summary(array(
				'search' => $search,
				'from' => $from,
				'to' => $to,
			)),
		));
	}

	protected function daily_register_view_data()
	{
		$today_shamsi = shamsi_today();
		$today_gregorian = date('Y-m-d');

		$date_from = trim((string) $this->input->get('date_from', TRUE));
		$date_to = trim((string) $this->input->get('date_to', TRUE));
		$section_ids = $this->input->get('section_ids');
		$legacy_section_id = (int) $this->input->get('section_id', TRUE);
		$gender = strtolower(trim((string) $this->input->get('gender', TRUE)));

		$date_from = $date_from !== '' ? $date_from : $today_shamsi;
		$date_to = $date_to !== '' ? $date_to : $today_shamsi;

		$date_from_g = to_gregorian($date_from) ?: $today_gregorian;
		$date_to_g = to_gregorian($date_to) ?: $today_gregorian;

		if (!to_gregorian($date_from)) {
			$date_from = $today_shamsi;
		}

		if (!to_gregorian($date_to)) {
			$date_to = $today_shamsi;
		}

		if ($date_from_g > $date_to_g) {
			$temp_display = $date_from;
			$date_from = $date_to;
			$date_to = $temp_display;

			$temp_date = $date_from_g;
			$date_from_g = $date_to_g;
			$date_to_g = $temp_date;
		}

		if (!in_array($gender, array('male', 'female'), TRUE)) {
			$gender = NULL;
		}

		$normalized_section_ids = array();
		if (is_array($section_ids)) {
			foreach ($section_ids as $section_id) {
				$section_id = (int) $section_id;
				if ($section_id > 0) {
					$normalized_section_ids[$section_id] = $section_id;
				}
			}
		}

		if (empty($normalized_section_ids) && $legacy_section_id > 0) {
			$normalized_section_ids[$legacy_section_id] = $legacy_section_id;
		}

		$filters = array(
			'date_from' => $date_from_g,
			'date_to' => $date_to_g,
			'section_ids' => array_values($normalized_section_ids),
			'gender' => $gender,
		);

		return array(
			'turns' => $this->Report_model->get_daily_register($filters),
			'summary' => $this->Report_model->get_daily_register_summary($filters),
			'sections' => $this->Section_model->get_all(),
			'filters' => array(
				'date_from' => $date_from,
				'date_to' => $date_to,
				'section_ids' => $filters['section_ids'],
				'gender' => $gender,
			),
			'date_from' => $date_from,
			'date_to' => $date_to,
		);
	}
}
