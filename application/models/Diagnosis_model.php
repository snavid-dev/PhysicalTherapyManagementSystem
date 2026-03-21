<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diagnosis_model extends CI_Model
{
	protected function table_ready()
	{
		return $this->db->table_exists('diagnoses');
	}

	protected function pivot_table_ready()
	{
		return $this->db->table_exists('patient_diagnoses');
	}

	public function schema_ready()
	{
		return $this->table_ready();
	}

	public function get_all()
	{
		if (!$this->table_ready()) {
			return array();
		}

		return $this->db
			->from('diagnoses')
			->order_by('name', 'asc')
			->get()
			->result_array();
	}

	public function get_by_id($id)
	{
		if (!$this->table_ready()) {
			return NULL;
		}

		return $this->db
			->from('diagnoses')
			->where('id', (int) $id)
			->get()
			->row_array();
	}

	public function create($data)
	{
		if (!$this->table_ready()) {
			return FALSE;
		}

		$this->db->insert('diagnoses', $data);

		if ($this->db->affected_rows() < 1) {
			return FALSE;
		}

		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		if (!$this->table_ready()) {
			return FALSE;
		}

		return $this->db
			->where('id', (int) $id)
			->update('diagnoses', $data);
	}

	public function is_in_use($id)
	{
		if (!$this->pivot_table_ready()) {
			return FALSE;
		}

		return (bool) $this->db
			->from('patient_diagnoses')
			->where('diagnosis_id', (int) $id)
			->count_all_results();
	}

	public function delete($id)
	{
		if (!$this->table_ready()) {
			return FALSE;
		}

		return $this->db
			->where('id', (int) $id)
			->delete('diagnoses');
	}
}
