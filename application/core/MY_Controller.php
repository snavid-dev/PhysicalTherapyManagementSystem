<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('show_404_if_empty')) {
	function show_404_if_empty($value)
	{
		if (empty($value)) {
			show_404();
			exit;
		}
	}
}

class Base_Controller extends CI_Controller
{
	protected static $schema_bootstrapped = FALSE;

	public function __construct()
	{
		parent::__construct();
		$this->boot_preferences();
		$this->bootstrap_schema_once();
	}

	/**
	 * Runs schema migrations that MUST execute outside any controller transaction.
	 * DDL implicitly commits, so doing this in lazy ensure_schema() inside a
	 * controller's trans_begin would silently end the outer transaction.
	 */
	protected function bootstrap_schema_once()
	{
		if (self::$schema_bootstrapped) {
			return;
		}
		self::$schema_bootstrapped = TRUE;

		if (!isset($this->db) || !$this->db) {
			return;
		}

		$this->load->model('Debt_model');
		$this->Debt_model->bootstrap_debt_type_migration();

		// Add staff_salary_records.daily_rate column outside any controller transaction,
		// then backfill so legacy records don't re-drift on the next calc.
		if ($this->db->table_exists('staff_salary_records') && !$this->db->field_exists('daily_rate', 'staff_salary_records')) {
			$this->db->query("ALTER TABLE `staff_salary_records` ADD COLUMN `daily_rate` decimal(12,4) NOT NULL DEFAULT 0.0000 AFTER `final_salary`");

			// Backfill: pin the daily_rate of every existing record to base_salary / days_in_month(month).
			// Same formula calculate_salary() uses, so subsequent reads return identical numbers.
			$this->db->query("
				UPDATE `staff_salary_records`
				SET `daily_rate` = `base_salary` / DAY(LAST_DAY(CONCAT(`month`, '-01')))
				WHERE `daily_rate` = 0
					AND `base_salary` > 0
					AND `month` REGEXP '^[0-9]{4}-[0-9]{2}$'
			");
		}
	}

	protected function boot_preferences()
	{
		$locale = $this->session->userdata('app_locale') ?: 'farsi';
		$theme = $this->session->userdata('app_theme') ?: 'light';

		if (!in_array($locale, array('farsi', 'english'), TRUE)) {
			$locale = 'farsi';
		}

		if (!in_array($theme, array('light', 'dark'), TRUE)) {
			$theme = 'light';
		}

		$this->session->set_userdata('app_locale', $locale);
		$this->session->set_userdata('app_theme', $theme);
		$this->lang->load('app', $locale);
	}

	protected function render($view, $data = array())
	{
		$data['auth_user'] = $this->auth->user();
		$data['current_locale'] = app_locale();
		$data['current_theme'] = app_theme();
		$data['is_rtl'] = is_rtl_locale();

		if ($this->auth->has_permission('approve_store_requisition')) {
			$data['pending_requisitions_count'] = $this->db
				->where('status', 'pending')
				->count_all_results('stock_requisitions');
		}

		if ($this->auth->has_permission('approve_store_sale_batch')) {
			$data['pending_sale_batches_count'] = $this->db
				->where('status', 'pending')
				->count_all_results('store_sale_batches');
		}

		$this->load->view('layout/header', $data);
		$this->load->view($view, $data);
		$this->load->view('layout/footer');
	}

	protected function gregorian_date_from_shamsi($value)
	{
		return to_gregorian($value);
	}

	protected function gregorian_month_from_shamsi($value)
	{
		return shamsi_month_to_gregorian($value);
	}

	protected function is_valid_shamsi_date_input($value)
	{
		return $this->gregorian_date_from_shamsi($value) !== '';
	}

	protected function is_valid_shamsi_month_input($value)
	{
		return $this->gregorian_month_from_shamsi($value) !== '';
	}
}

class Authenticated_Controller extends Base_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->auth->check()) {
			$this->session->set_flashdata('error', 'Please sign in first.');
			redirect('login');
		}
	}

	protected function require_permission($permission_name)
	{
		$this->auth->require_permission($permission_name);
	}
}
