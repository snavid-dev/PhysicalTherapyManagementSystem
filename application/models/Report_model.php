<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model
{
	public function summary($from, $to)
	{
		$payments = $this->db
			->select_sum('amount')
			->where('payment_date >=', $from)
			->where('payment_date <=', $to)
			->get('payments')
			->row_array();

		return array(
			'turns' => (int) $this->db->where('turn_date >=', $from)->where('turn_date <=', $to)->count_all_results('turns'),
			'payments' => (float) ($payments['amount'] ?: 0),
			'leaves' => (int) $this->db->where('start_date >=', $from)->where('start_date <=', $to)->count_all_results('doctor_leaves'),
			'new_patients' => (int) $this->db->where('DATE(created_at) >=', $from)->where('DATE(created_at) <=', $to)->count_all_results('patients'),
		);
	}

	public function turns($from, $to)
	{
		return $this->db
			->select('turns.*, patients.first_name AS patient_first_name, patients.last_name AS patient_last_name, users.first_name AS therapist_first_name, users.last_name AS therapist_last_name')
			->from('turns')
			->join('patients', 'patients.id = turns.patient_id')
			->join('users', 'users.id = turns.doctor_id')
			->where('turns.turn_date >=', $from)
			->where('turns.turn_date <=', $to)
			->order_by('turns.turn_date', 'desc')
			->order_by('turns.turn_time', 'desc')
			->get()
			->result_array();
	}

	public function payments($from, $to)
	{
		return $this->db
			->select('payments.*, patients.first_name, patients.last_name')
			->from('payments')
			->join('patients', 'patients.id = payments.patient_id')
			->where('payments.payment_date >=', $from)
			->where('payments.payment_date <=', $to)
			->order_by('payments.payment_date', 'desc')
			->get()
			->result_array();
	}

	public function leaves($from, $to)
	{
		return $this->db
			->select('doctor_leaves.*, users.first_name, users.last_name')
			->from('doctor_leaves')
			->join('users', 'users.id = doctor_leaves.doctor_id')
			->where('doctor_leaves.start_date >=', $from)
			->where('doctor_leaves.start_date <=', $to)
			->order_by('doctor_leaves.start_date', 'desc')
			->get()
			->result_array();
	}
}
