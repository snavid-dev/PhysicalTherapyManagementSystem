<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('app_locale')) {
	function app_locale()
	{
		$ci =& get_instance();
		return $ci->session->userdata('app_locale') ?: 'farsi';
	}
}

if (!function_exists('app_theme')) {
	function app_theme()
	{
		$ci =& get_instance();
		return $ci->session->userdata('app_theme') ?: 'light';
	}
}

if (!function_exists('is_rtl_locale')) {
	function is_rtl_locale($locale = NULL)
	{
		$locale = $locale ?: app_locale();
		return in_array($locale, array('farsi', 'persian', 'fa'), TRUE);
	}
}

if (!function_exists('t')) {
	function t($key)
	{
		$ci =& get_instance();
		$line = $ci->lang->line($key);
		return $line ? $line : $key;
	}
}

if (!function_exists('format_number')) {
	function format_number($number, $decimals = 0)
	{
		$formatted = number_format((float) $number, (int) $decimals);

		if (!is_rtl_locale()) {
			return $formatted;
		}

		return strtr($formatted, array(
			'0' => '۰',
			'1' => '۱',
			'2' => '۲',
			'3' => '۳',
			'4' => '۴',
			'5' => '۵',
			'6' => '۶',
			'7' => '۷',
			'8' => '۸',
			'9' => '۹',
			',' => '٬',
			'.' => '٫',
		));
	}
}

if (!function_exists('format_amount')) {
	function format_amount($number)
	{
		$number = (float) $number;
		$decimals = ((int) $number == $number) ? 0 : 2;
		return format_number($number, $decimals);
	}
}

if (!function_exists('safe_turn_cash_note')) {
	function safe_turn_cash_note($turn_id)
	{
		return t('Cash payment for turn') . ' #' . format_number($turn_id);
	}
}

if (!function_exists('safe_turn_wallet_topup_note')) {
	function safe_turn_wallet_topup_note($turn_id)
	{
		return t('Wallet top-up for turn') . ' #' . format_number($turn_id);
	}
}

if (!function_exists('safe_turn_cash_reversal_note')) {
	function safe_turn_cash_reversal_note($turn_id)
	{
		return t('Reversal of cash payment for turn') . ' #' . format_number($turn_id);
	}
}

if (!function_exists('safe_turn_wallet_topup_reversal_note')) {
	function safe_turn_wallet_topup_reversal_note($turn_id)
	{
		return t('Reversal of wallet top-up for turn') . ' #' . format_number($turn_id);
	}
}

if (!function_exists('safe_patient_payment_note')) {
	function safe_patient_payment_note($payment_id)
	{
		return t('Patient payment') . ' #' . format_number($payment_id);
	}
}

if (!function_exists('safe_patient_wallet_topup_note')) {
	function safe_patient_wallet_topup_note($patient_id)
	{
		return t('Wallet top-up for patient') . ' #' . format_number($patient_id);
	}
}

if (!function_exists('safe_salary_payment_note')) {
	function safe_salary_payment_note($staff_id, $month = NULL)
	{
		$note = t('Salary payment for staff') . ' #' . format_number($staff_id);

		if ($month !== NULL && trim((string) $month) !== '') {
			$display_month = preg_match('/^\d{4}-\d{2}$/', trim((string) $month))
				? gregorian_month_to_shamsi($month)
				: $month;
			$note .= ' ' . t('month') . ' ' . $display_month;
		}

		return $note;
	}
}

if (!function_exists('safe_reference_label')) {
	function safe_reference_label($reference_table, $reference_id)
	{
		$reference_table = trim((string) $reference_table);
		$reference_id = (int) $reference_id;

		if ($reference_id <= 0 || $reference_table === '') {
			return '&mdash;';
		}

		switch ($reference_table) {
			case 'turns':
				return html_escape(t('Turn No.') . ' ' . format_number($reference_id));

			case 'expenses':
				return html_escape(t('Expense No.') . ' ' . format_number($reference_id));

			case 'payments':
				return html_escape(t('Payment No.') . ' ' . format_number($reference_id));

			case 'patient_wallet_transactions':
				return html_escape(t('Wallet Transaction No.') . ' ' . format_number($reference_id));

			case 'staff_salary_payments':
				return html_escape(t('Salary Payment No.') . ' ' . format_number($reference_id));

			default:
				return html_escape($reference_table . ' #' . $reference_id);
		}
	}
}

if (!function_exists('to_shamsi')) {
	function to_shamsi($date, $format = 'Y/m/d')
	{
		$CI =& get_instance();
		$CI->load->library('Shamsi');
		return $CI->shamsi->to_shamsi($date, $format);
	}
}

if (!function_exists('to_gregorian')) {
	function to_gregorian($shamsi_date)
	{
		$CI =& get_instance();
		$CI->load->library('Shamsi');
		return $CI->shamsi->to_gregorian($shamsi_date);
	}
}

if (!function_exists('shamsi_today')) {
	function shamsi_today($format = 'Y/m/d')
	{
		$CI =& get_instance();
		$CI->load->library('Shamsi');
		return $CI->shamsi->shamsi_today($format);
	}
}

if (!function_exists('shamsi_now')) {
	function shamsi_now($format = 'Y/m/d H:i')
	{
		$CI =& get_instance();
		$CI->load->library('Shamsi');
		return $CI->shamsi->shamsi_now($format);
	}
}

if (!function_exists('shamsi_month_range')) {
	function shamsi_month_range($shamsi_year, $shamsi_month)
	{
		$CI =& get_instance();
		$CI->load->library('Shamsi');
		return $CI->shamsi->shamsi_month_range($shamsi_year, $shamsi_month);
	}
}

if (!function_exists('gregorian_month_to_shamsi')) {
	function gregorian_month_to_shamsi($gregorian_ym)
	{
		$CI =& get_instance();
		$CI->load->library('Shamsi');
		return $CI->shamsi->gregorian_month_to_shamsi($gregorian_ym);
	}
}

if (!function_exists('shamsi_month_to_gregorian')) {
	function shamsi_month_to_gregorian($shamsi_ym)
	{
		$CI =& get_instance();
		$CI->load->library('Shamsi');
		return $CI->shamsi->shamsi_month_to_gregorian($shamsi_ym);
	}
}
