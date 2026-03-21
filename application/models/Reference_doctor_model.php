<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reference_doctor_model extends CI_Model
{
	public function get_all()
	{
		return $this->db
			->select('reference_doctors.*, COUNT(patients.id) AS total_referred')
			->from('reference_doctors')
			->join('patients', 'patients.referred_by = reference_doctors.id', 'left')
			->group_by('reference_doctors.id')
			->order_by('reference_doctors.first_name', 'asc')
			->order_by('reference_doctors.last_name', 'asc')
			->get()
			->result_array();
	}

	public function get_by_id($id)
	{
		return $this->db
			->select('reference_doctors.*, COUNT(patients.id) AS total_referred')
			->from('reference_doctors')
			->join('patients', 'patients.referred_by = reference_doctors.id', 'left')
			->where('reference_doctors.id', (int) $id)
			->group_by('reference_doctors.id')
			->get()
			->row_array();
	}

	public function create($data)
	{
		$this->db->insert('reference_doctors', $data);
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		return $this->db
			->where('id', (int) $id)
			->update('reference_doctors', $data);
	}

	public function set_inactive($id)
	{
		return $this->db
			->where('id', (int) $id)
			->update('reference_doctors', array('status' => 'inactive'));
	}

	public function set_active($id)
	{
		return $this->db
			->where('id', (int) $id)
			->update('reference_doctors', array('status' => 'active'));
	}

	public function get_referred_patients($doctor_id, $date_from, $date_to)
	{
		return $this->db
			->select("id, CONCAT(first_name, ' ', last_name) AS full_name, gender, phone, created_at", FALSE)
			->from('patients')
			->where('referred_by', (int) $doctor_id)
			->where('created_at >=', $date_from)
			->where('created_at <=', $date_to)
			->order_by('created_at', 'desc')
			->order_by('id', 'desc')
			->get()
			->result_array();
	}

	public function count_referred_patients($doctor_id, $date_from, $date_to)
	{
		return (int) $this->db
			->from('patients')
			->where('referred_by', (int) $doctor_id)
			->where('created_at >=', $date_from)
			->where('created_at <=', $date_to)
			->count_all_results();
	}
}
