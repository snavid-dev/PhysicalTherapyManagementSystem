<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Turn_model extends CI_Model
{
	public function all()
	{
		return $this->db
			->select('turns.*, patients.first_name AS patient_first_name, patients.last_name AS patient_last_name, users.first_name AS therapist_first_name, users.last_name AS therapist_last_name')
			->from('turns')
			->join('patients', 'patients.id = turns.patient_id')
			->join('users', 'users.id = turns.doctor_id')
			->order_by('turns.turn_date', 'desc')
			->order_by('turns.turn_time', 'desc')
			->get()
			->result_array();
	}

	public function find($id)
	{
		return $this->db->get_where('turns', array('id' => (int) $id))->row_array();
	}

	public function create($data)
	{
		$this->db->insert('turns', $data);
		return $this->db->insert_id();
	}

	public function create_many($rows)
	{
		$this->db->trans_start();
		foreach ($rows as $row) {
			$this->db->insert('turns', $row);
		}
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function update($id, $data)
	{
		return $this->db->where('id', (int) $id)->update('turns', $data);
	}

	public function delete($id)
	{
		return $this->db->where('id', (int) $id)->delete('turns');
	}
}
