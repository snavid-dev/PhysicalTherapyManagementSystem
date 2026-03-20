<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_model extends CI_Model
{
	public function all()
	{
		return $this->db
			->select('payments.*, patients.first_name, patients.last_name')
			->from('payments')
			->join('patients', 'patients.id = payments.patient_id')
			->order_by('payments.payment_date', 'desc')
			->order_by('payments.id', 'desc')
			->get()
			->result_array();
	}

	public function find($id)
	{
		return $this->db->get_where('payments', array('id' => (int) $id))->row_array();
	}

	public function create($data)
	{
		$this->db->insert('payments', $data);
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		return $this->db->where('id', (int) $id)->update('payments', $data);
	}

	public function delete($id)
	{
		return $this->db->where('id', (int) $id)->delete('payments');
	}

	public function total_received()
	{
		$row = $this->db->select_sum('amount')->get('payments')->row_array();
		return (float) ($row['amount'] ?: 0);
	}
}
