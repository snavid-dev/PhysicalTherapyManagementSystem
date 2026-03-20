<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Preferences extends CI_Controller
{
	public function language($locale = 'farsi')
	{
		$locale = in_array($locale, array('farsi', 'english'), TRUE) ? $locale : 'farsi';
		$this->session->set_userdata('app_locale', $locale);
		$this->redirect_back();
	}

	public function theme($theme = 'light')
	{
		$theme = in_array($theme, array('light', 'dark'), TRUE) ? $theme : 'light';
		$this->session->set_userdata('app_theme', $theme);
		$this->redirect_back();
	}

	protected function redirect_back()
	{
		$back = $this->input->server('HTTP_REFERER');
		redirect($back ? $back : 'dashboard');
	}
}
