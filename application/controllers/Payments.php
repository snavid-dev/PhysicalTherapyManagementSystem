<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Payment_model');
		$this->load->model('Patient_model');
	}

	public function index()
	{
		$this->require_permission('manage_payments');

		$this->render('payments/index', array(
			'title' => t('Payments'),
			'current_section' => 'payments',
			'payments' => $this->Payment_model->all(),
			'total_received' => $this->Payment_model->total_received(),
		));
	}

	public function create()
	{
		$this->require_permission('manage_payments');
		$this->form(NULL, 'payments/store');
	}

	public function show($id)
	{
		$this->require_permission('manage_payments');
		$payment = $this->Payment_model->find_with_patient($id);
		show_404_if_empty($payment);

		$this->render('payments/show', array(
			'title' => t('Payment Details'),
			'current_section' => 'payments',
			'payment' => $payment,
		));
	}

	public function store()
	{
		$this->require_permission('manage_payments');
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form(NULL, 'payments/store');
		}

		$this->Payment_model->create($this->payment_payload());
		$this->session->set_flashdata('success', t('Payment recorded successfully.'));
		redirect('payments');
	}

	public function edit($id)
	{
		$this->require_permission('manage_payments');
		$payment = $this->Payment_model->find($id);
		show_404_if_empty($payment);
		$this->form($payment, 'payments/' . $id . '/update');
	}

	public function update($id)
	{
		$this->require_permission('manage_payments');
		$payment = $this->Payment_model->find($id);
		show_404_if_empty($payment);
		$this->validate_form();

		if (!$this->form_validation->run()) {
			return $this->form($payment, 'payments/' . $id . '/update');
		}

		$this->Payment_model->update($id, $this->payment_payload());
		$this->session->set_flashdata('success', t('Payment updated successfully.'));
		redirect('payments');
	}

	public function delete($id)
	{
		$this->require_permission('manage_payments');
		$payment = $this->Payment_model->find($id);
		show_404_if_empty($payment);

		if (!$this->Payment_model->delete($id)) {
			$this->session->set_flashdata('error', t('Unable to delete record right now.'));
			return redirect('payments');
		}

		$this->session->set_flashdata('success', t('Payment deleted successfully.'));
		redirect('payments');
	}

	protected function form($payment, $action)
	{
		$this->render('payments/form', array(
			'title' => $payment ? t('Edit Payment') : t('Create Payment'),
			'current_section' => 'payments',
			'payment' => $payment,
			'action' => $action,
			'patients' => $this->Patient_model->all(),
		));
	}

	protected function validate_form()
	{
		$this->form_validation->set_rules('patient_id', 'Patient', 'required|integer');
		$this->form_validation->set_rules('payment_date', 'Payment date', 'required');
		$this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
		$this->form_validation->set_rules('payment_method', 'Payment method', 'required');
	}

	protected function payment_payload()
	{
		return array(
			'patient_id' => (int) $this->input->post('patient_id'),
			'payment_date' => $this->input->post('payment_date', TRUE),
			'amount' => $this->input->post('amount', TRUE),
			'payment_method' => $this->input->post('payment_method', TRUE),
			'reference_number' => $this->input->post('reference_number', TRUE),
			'notes' => $this->input->post('notes', TRUE),
		);
	}
}
