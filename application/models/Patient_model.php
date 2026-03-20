<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_model extends CI_Model
{
	public function all()
	{
		return $this->db->order_by('first_name', 'asc')->order_by('last_name', 'asc')->get('patients')->result_array();
	}

	public function find($id)
	{
		return $this->db->get_where('patients', array('id' => (int) $id))->row_array();
	}

	public function create($data)
	{
		$this->db->insert('patients', $data);
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		return $this->db->where('id', (int) $id)->update('patients', $data);
	}

	public function delete($id)
	{
		return $this->db->where('id', (int) $id)->delete('patients');
	}

	public function turn_history($patient_id)
	{
		return $this->db
			->select('turns.*, users.first_name, users.last_name')
			->from('turns')
			->join('users', 'users.id = turns.doctor_id', 'left')
			->where('turns.patient_id', (int) $patient_id)
			->order_by('turns.turn_date', 'desc')
			->order_by('turns.turn_time', 'desc')
			->get()
			->result_array();
	}

	public function payment_history($patient_id)
	{
		return $this->db
			->from('payments')
			->where('patient_id', (int) $patient_id)
			->order_by('payment_date', 'desc')
			->order_by('id', 'desc')
			->get()
			->result_array();
	}
}
