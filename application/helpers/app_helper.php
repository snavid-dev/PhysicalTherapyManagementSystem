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
