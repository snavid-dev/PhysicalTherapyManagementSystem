<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Preferences extends Base_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Diagnosis_model');
		$this->load->model('Expense_category_model');
	}

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

	public function diagnoses()
	{
		$this->require_permission('manage_patients');

		$this->render('preferences/diagnoses', array(
			'title' => t('Diagnoses'),
			'current_section' => 'preferences',
			'schema_ready' => $this->Diagnosis_model->schema_ready(),
			'diagnoses' => $this->Diagnosis_model->get_all(),
		));
	}

	public function diagnoses_store()
	{
		$this->require_permission('manage_patients');
		$this->require_post();

		if (!$this->Diagnosis_model->schema_ready()) {
			$this->session->set_flashdata('error', t('diagnosis_schema_missing'));
			return redirect('preferences/diagnoses');
		}

		$this->validate_diagnosis_form();

		if (!$this->form_validation->run()) {
			$this->session->set_flashdata('error', validation_errors("\n", "\n"));
			return redirect('preferences/diagnoses');
		}

		if (!$this->Diagnosis_model->create($this->diagnosis_payload())) {
			$this->session->set_flashdata('error', t('Unable to save diagnosis right now.'));
			return redirect('preferences/diagnoses');
		}

		$this->session->set_flashdata('success', t('Diagnosis created successfully.'));
		redirect('preferences/diagnoses');
	}

	public function diagnoses_update($id)
	{
		$this->require_permission('manage_patients');
		$this->require_post();

		if (!$this->Diagnosis_model->schema_ready()) {
			$this->session->set_flashdata('error', t('diagnosis_schema_missing'));
			return redirect('preferences/diagnoses');
		}

		show_404_if_empty($this->Diagnosis_model->get_by_id($id));
		$this->validate_diagnosis_form();

		if (!$this->form_validation->run()) {
			$this->session->set_flashdata('error', validation_errors("\n", "\n"));
			return redirect('preferences/diagnoses');
		}

		if (!$this->Diagnosis_model->update($id, $this->diagnosis_payload())) {
			$this->session->set_flashdata('error', t('Unable to save diagnosis right now.'));
			return redirect('preferences/diagnoses');
		}

		$this->session->set_flashdata('success', t('Diagnosis updated successfully.'));
		redirect('preferences/diagnoses');
	}

	public function diagnoses_delete($id)
	{
		$this->require_permission('manage_patients');
		$this->require_post();

		if (!$this->Diagnosis_model->schema_ready()) {
			$this->session->set_flashdata('error', t('diagnosis_schema_missing'));
			return redirect('preferences/diagnoses');
		}

		show_404_if_empty($this->Diagnosis_model->get_by_id($id));

		if ($this->Diagnosis_model->is_in_use($id)) {
			$this->session->set_flashdata('error', t('diagnosis_in_use'));
			return redirect('preferences/diagnoses');
		}

		if (!$this->Diagnosis_model->delete($id)) {
			$this->session->set_flashdata('error', t('Unable to delete diagnosis right now.'));
			return redirect('preferences/diagnoses');
		}

		$this->session->set_flashdata('success', t('Diagnosis deleted successfully.'));
		redirect('preferences/diagnoses');
	}

	public function expense_categories()
	{
		$this->require_permission('manage_expenses');

		$this->render('preferences/expense_categories', array(
			'title' => t('expense_categories'),
			'current_section' => 'preferences',
			'categories' => $this->Expense_category_model->get_all(),
		));
	}

	public function expense_categories_store()
	{
		$this->require_permission('manage_expenses');
		$this->require_post();
		$this->validate_expense_category_form();

		if (!$this->form_validation->run()) {
			$this->session->set_flashdata('error', validation_errors("\n", "\n"));
			return redirect('preferences/expense-categories');
		}

		$this->Expense_category_model->create($this->expense_category_payload());
		$this->session->set_flashdata('success', t('Expense category created successfully.'));
		redirect('preferences/expense-categories');
	}

	public function expense_categories_update($id)
	{
		$this->require_permission('manage_expenses');
		$this->require_post();
		show_404_if_empty($this->Expense_category_model->get_by_id($id));
		$this->validate_expense_category_form();

		if (!$this->form_validation->run()) {
			$this->session->set_flashdata('error', validation_errors("\n", "\n"));
			return redirect('preferences/expense-categories');
		}

		$this->Expense_category_model->update($id, $this->expense_category_payload());
		$this->session->set_flashdata('success', t('Expense category updated successfully.'));
		redirect('preferences/expense-categories');
	}

	public function expense_categories_delete($id)
	{
		$this->require_permission('manage_expenses');
		$this->require_post();
		show_404_if_empty($this->Expense_category_model->get_by_id($id));

		if (!$this->Expense_category_model->delete($id)) {
			$this->session->set_flashdata('error', t('expense_category_in_use'));
			return redirect('preferences/expense-categories');
		}

		$this->session->set_flashdata('success', t('Expense category deleted successfully.'));
		redirect('preferences/expense-categories');
	}

	protected function redirect_back()
	{
		$back = $this->input->server('HTTP_REFERER');
		redirect($back ? $back : 'dashboard');
	}

	protected function validate_diagnosis_form()
	{
		$this->form_validation->set_rules('name', 'Diagnosis Name (EN)', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('name_fa', 'Diagnosis Name (FA)', 'trim|max_length[200]');
	}

	protected function diagnosis_payload()
	{
		return array(
			'name' => trim((string) $this->input->post('name', TRUE)),
			'name_fa' => $this->null_if_empty($this->input->post('name_fa', TRUE)),
		);
	}

	protected function validate_expense_category_form()
	{
		$this->form_validation->set_rules('name', 'Name (EN)', 'required|trim|max_length[150]');
		$this->form_validation->set_rules('name_fa', 'Name (FA)', 'trim|max_length[150]');
	}

	protected function expense_category_payload()
	{
		return array(
			'name' => trim((string) $this->input->post('name', TRUE)),
			'name_fa' => $this->null_if_empty($this->input->post('name_fa', TRUE)),
		);
	}

	protected function null_if_empty($value)
	{
		$value = trim((string) $value);
		return $value === '' ? NULL : $value;
	}

	protected function require_post()
	{
		if (strtolower($this->input->method()) !== 'post') {
			show_error('Access denied.', 403);
		}
	}

	protected function require_permission($permission_name)
	{
		if (!$this->auth->check()) {
			$this->session->set_flashdata('error', t('Please sign in first.'));
			redirect('login');
		}

		$this->auth->require_permission($permission_name);
	}
}
