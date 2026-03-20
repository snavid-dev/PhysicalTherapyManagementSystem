<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave_model extends CI_Model
{
	public function all()
	{
		return $this->db
			->select('doctor_leaves.*, users.first_name, users.last_name')
			->from('doctor_leaves')
			->join('users', 'users.id = doctor_leaves.doctor_id')
			->order_by('doctor_leaves.start_date', 'desc')
			->get()
			->result_array();
	}

	public function find($id)
	{
		return $this->db->get_where('doctor_leaves', array('id' => (int) $id))->row_array();
	}

	public function create($data)
	{
		$this->db->insert('doctor_leaves', $data);
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		return $this->db->where('id', (int) $id)->update('doctor_leaves', $data);
	}

	public function delete($id)
	{
		return $this->db->where('id', (int) $id)->delete('doctor_leaves');
	}
}
