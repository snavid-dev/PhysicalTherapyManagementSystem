<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Report_model');
		$this->load->model('Section_model');
		$this->load->model('Staff_model');
		$this->load->model('Safe_model');
		$this->load->model('Reference_doctor_model');
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

	public function debtors()
	{
		$this->require_permission('view_reports');

		$filters = $this->debtors_filters();
		$query_filters = $this->debtors_query_filters($filters);

		$this->render('reports/debtors', array(
			'title' => t('debtors_list'),
			'current_section' => 'reports',
			'debtors' => $this->Report_model->get_debtors($query_filters['from'], $query_filters['to']),
			'from' => $filters['from'],
			'to' => $filters['to'],
		));
	}

	public function debtors_print()
	{
		$this->require_permission('view_reports');

		$filters = $this->debtors_filters();
		$query_filters = $this->debtors_query_filters($filters);

		$this->load->view('reports/debtors_print', array(
			'title' => t('debtors_list'),
			'debtors' => $this->Report_model->get_debtors($query_filters['from'], $query_filters['to']),
			'from' => $filters['from'],
			'to' => $filters['to'],
		));
	}

	protected function debtors_filters()
	{
		return array(
			'from' => trim((string) $this->input->get('from', TRUE)),
			'to' => trim((string) $this->input->get('to', TRUE)),
		);
	}

	protected function debtors_query_filters($filters)
	{
		return array(
			'from' => $filters['from'] !== '' ? $this->gregorian_date_from_shamsi($filters['from']) : '',
			'to' => $filters['to'] !== '' ? $this->gregorian_date_from_shamsi($filters['to']) : '',
		);
	}

	public function new_patients()
	{
		$this->require_permission('view_reports');

		$today_shamsi = shamsi_today();
		$today_gregorian = date('Y-m-d');
		$default_from = date('Y-m-d', strtotime('-30 days'));
		$default_from_shamsi = to_shamsi($default_from);

		$from_input = trim((string) $this->input->get('from', TRUE));
		$to_input = trim((string) $this->input->get('to', TRUE));
		$from = $from_input !== '' ? $this->gregorian_date_from_shamsi($from_input) : $default_from;
		$to = $to_input !== '' ? $this->gregorian_date_from_shamsi($to_input) : $today_gregorian;

		if ($from === '' || $to === '' || $from > $to) {
			$from = $default_from;
			$to = $today_gregorian;
		}

		$from_input = $from_input !== '' && to_gregorian($from_input) !== '' ? $from_input : $default_from_shamsi;
		$to_input = $to_input !== '' && to_gregorian($to_input) !== '' ? $to_input : $today_shamsi;

		$this->render('reports/new_patients', array(
			'title' => t('new_patients_report'),
			'current_section' => 'reports',
			'from' => $from_input,
			'to' => $to_input,
			'patients' => $this->Report_model->get_new_patients_in_range($from, $to),
		));
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

	public function financial_summary()
	{
		$this->require_permission('view_reports');

		$data = $this->financial_summary_view_data();
		$data['title'] = t('financial_summary_report');
		$data['current_section'] = 'reports';

		$this->render('reports/financial_summary', $data);
	}

	public function financial_summary_print()
	{
		$this->require_permission('view_reports');

		$data = $this->financial_summary_view_data();
		$data['title'] = t('financial_summary_report');

		$this->load->view('reports/financial_summary_print', $data);
	}

	public function doctor_referrals()
	{
		$this->require_permission('view_reports');

		$data = $this->doctor_referrals_view_data();
		$data['title'] = t('doctor_referral_report');
		$data['current_section'] = 'reports';

		$this->render('reports/doctor_referrals', $data);
	}

	public function doctor_referrals_print()
	{
		$this->require_permission('view_reports');

		$data = $this->doctor_referrals_view_data();
		$data['title'] = t('doctor_referral_report');

		$this->load->view('reports/doctor_referrals_print', $data);
	}

	public function financial_summary_data()
	{
		$this->require_permission('view_reports');

		$range = $this->ajax_date_range();
		if ($range === NULL) {
			return $this->json_error(t('Please choose a valid date range.'), 422);
		}

		$sections = $this->Report_model->get_section_income_summary($range['from'], $range['to']);
		$sections = array_map(static function ($section) {
			$section['section_name'] = !empty($section['section_name']) ? t($section['section_name']) : t('section_na');
			return $section;
		}, $sections);

		$expenses_total = $this->Report_model->expenses_total($range['from'], $range['to']);
		$safe_summary = $this->Safe_model->get_summary($range['from'], $range['to']);

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => TRUE,
				'date_from' => $range['from_input'],
				'date_to' => $range['to_input'],
				'sections' => $sections,
				'expenses_total' => (float) $expenses_total,
				'safe_balance_before' => (float) ($safe_summary['opening_balance'] ?? 0),
				'safe_balance_after' => (float) ($safe_summary['closing_balance'] ?? 0),
			)));
	}

	public function doctor_referrals_data()
	{
		$this->require_permission('view_reports');

		$range = $this->ajax_date_range();
		if ($range === NULL) {
			return $this->json_error(t('Please choose a valid date range.'), 422);
		}

		$doctors = $this->Reference_doctor_model->get_referral_summary($range['from'] . ' 00:00:00', $range['to'] . ' 23:59:59');
		$doctors = array_map(static function ($doctor) {
			return array(
				'id' => (int) $doctor['id'],
				'name' => trim($doctor['first_name'] . ' ' . ($doctor['last_name'] ?? '')),
				'specialty' => $doctor['specialty'] ?? '',
				'referred_count' => (int) $doctor['referred_count'],
			);
		}, $doctors);

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => TRUE,
				'date_from' => $range['from_input'],
				'date_to' => $range['to_input'],
				'doctors' => $doctors,
			)));
	}

	protected function ajax_date_range()
	{
		if (strtolower($this->input->method()) !== 'post') {
			show_error('Access denied.', 403);
		}

		$from_input = trim((string) $this->input->post('date_from', TRUE));
		$to_input = trim((string) $this->input->post('date_to', TRUE));
		$from = $from_input !== '' ? $this->gregorian_date_from_shamsi($from_input) : '';
		$to = $to_input !== '' ? $this->gregorian_date_from_shamsi($to_input) : '';

		if ($from === '' || $to === '' || $from > $to) {
			return NULL;
		}

		return array('from' => $from, 'to' => $to, 'from_input' => $from_input, 'to_input' => $to_input);
	}

	protected function json_error($message, $status = 422)
	{
		return $this->output
			->set_status_header($status)
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => FALSE,
				'message' => $message,
			)));
	}

	protected function report_date_range()
	{
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

		return array('from' => $from, 'to' => $to, 'from_input' => $from_input, 'to_input' => $to_input);
	}

	protected function financial_summary_view_data()
	{
		$range = $this->report_date_range();

		return array(
			'from' => $range['from_input'],
			'to' => $range['to_input'],
			'sections' => $this->Report_model->get_section_income_summary($range['from'], $range['to']),
			'expenses_total' => $this->Report_model->expenses_total($range['from'], $range['to']),
			'safe_summary' => $this->Safe_model->get_summary($range['from'], $range['to']),
		);
	}

	protected function doctor_referrals_view_data()
	{
		$range = $this->report_date_range();

		return array(
			'from' => $range['from_input'],
			'to' => $range['to_input'],
			'doctors' => $this->Reference_doctor_model->get_referral_summary($range['from'] . ' 00:00:00', $range['to'] . ' 23:59:59'),
		);
	}

	protected function daily_register_view_data()
	{
		$today_shamsi = shamsi_today();
		$today_gregorian = date('Y-m-d');

		$date_from = trim((string) $this->input->get('date_from', TRUE));
		$date_to = trim((string) $this->input->get('date_to', TRUE));
		$section_ids = $this->input->get('section_ids');
		$legacy_section_id = (int) $this->input->get('section_id', TRUE);
		$staff_ids_input = $this->input->get('staff_ids');
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

		$normalized_staff_ids = array();
		if (is_array($staff_ids_input)) {
			foreach ($staff_ids_input as $staff_id) {
				$staff_id = (int) $staff_id;
				if ($staff_id > 0) {
					$normalized_staff_ids[$staff_id] = $staff_id;
				}
			}
		}

		$filters = array(
			'date_from' => $date_from_g,
			'date_to' => $date_to_g,
			'section_ids' => array_values($normalized_section_ids),
			'staff_ids' => array_values($normalized_staff_ids),
			'gender' => $gender,
		);

		return array(
			'turns' => $this->Report_model->get_daily_register($filters),
			'summary' => $this->Report_model->get_daily_register_summary($filters),
			'sections' => $this->Section_model->get_all(),
			'staff_options' => $this->Staff_model->get_active_therapists(),
			'filters' => array(
				'date_from' => $date_from,
				'date_to' => $date_to,
				'section_ids' => $filters['section_ids'],
				'staff_ids' => $filters['staff_ids'],
				'gender' => $gender,
			),
			'date_from' => $date_from,
			'date_to' => $date_to,
		);
	}
}
