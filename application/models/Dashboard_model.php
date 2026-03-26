<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Safe_model');
	}

	public function stats()
	{
		$payments = $this->db
			->select_sum('amount')
			->where('payment_date >=', date('Y-m-01'))
			->where('payment_date <=', date('Y-m-t'))
			->get('payments')
			->row_array();

		return array(
			'patients' => (int) $this->db->count_all('patients'),
			'users' => (int) $this->db->count_all('users'),
			'today_turns' => (int) $this->db->where('turn_date', date('Y-m-d'))->count_all_results('turns'),
			'payments_this_month' => (float) ($payments['amount'] ?: 0),
		);
	}

	public function today_turns()
	{
		return $this->db
			->select('turns.*, patients.first_name AS patient_first_name, patients.last_name AS patient_last_name, users.first_name AS therapist_first_name, users.last_name AS therapist_last_name')
			->from('turns')
			->join('patients', 'patients.id = turns.patient_id')
			->join('users', 'users.id = turns.doctor_id')
			->where('turns.turn_date', date('Y-m-d'))
			->order_by('turns.turn_time', 'asc')
			->get()
			->result_array();
	}

	public function recent_payments()
	{
		return $this->db
			->select('payments.*, patients.first_name, patients.last_name')
			->from('payments')
			->join('patients', 'patients.id = payments.patient_id')
			->order_by('payments.payment_date', 'desc')
			->order_by('payments.id', 'desc')
			->limit(5)
			->get()
			->result_array();
	}

	public function get_safe_balance()
	{
		return $this->Safe_model->get_current_balance();
	}
}
