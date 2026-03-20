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
