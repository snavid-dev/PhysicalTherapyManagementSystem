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
	public function __construct()
	{
		parent::__construct();
		$this->boot_preferences();
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
