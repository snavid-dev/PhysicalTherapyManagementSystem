<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Safe_model');
	}

	protected function diagnoses_tables_ready()
	{
		return $this->db->table_exists('diagnoses') && $this->db->table_exists('patient_diagnoses');
	}

	public function get_all()
	{
		return $this->db
			->select("patients.*, CONCAT(reference_doctors.first_name, ' ', reference_doctors.last_name) AS referred_by_name", FALSE)
			->from('patients')
			->join('reference_doctors', 'reference_doctors.id = patients.referred_by', 'left')
			->order_by('patients.first_name', 'asc')
			->order_by('patients.last_name', 'asc')
			->get()
			->result_array();
	}

	public function get_by_id($id)
	{
		return $this->db
			->select("patients.*, CONCAT(reference_doctors.first_name, ' ', reference_doctors.last_name) AS referred_by_name", FALSE)
			->from('patients')
			->join('reference_doctors', 'reference_doctors.id = patients.referred_by', 'left')
			->where('patients.id', (int) $id)
			->get()
			->row_array();
	}

	public function all()
	{
		return $this->get_all();
	}

	public function find($id)
	{
		return $this->get_by_id($id);
	}

	public function create($data)
	{
		$this->db->insert('patients', $this->normalize_patient_payload($data));
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		return $this->db->where('id', (int) $id)->update('patients', $this->normalize_patient_payload($data));
	}

	public function delete($id)
	{
		$id = (int) $id;

		$this->db->trans_begin();

		$safe_deleted = $this->Safe_model->delete_transactions_for_patient($id);
		$deleted = $this->db->where('id', $id)->delete('patients');

		if ($safe_deleted === FALSE || !$deleted || $this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();

		return TRUE;
	}

	public function get_diagnoses_for_patient($patient_id)
	{
		if (!$this->diagnoses_tables_ready()) {
			return array();
		}

		return $this->db
			->select('diagnoses.id, diagnoses.name, diagnoses.name_fa')
			->from('patient_diagnoses')
			->join('diagnoses', 'diagnoses.id = patient_diagnoses.diagnosis_id')
			->where('patient_diagnoses.patient_id', (int) $patient_id)
			->order_by('diagnoses.name', 'asc')
			->get()
			->result_array();
	}

	public function save_diagnoses($patient_id, $diagnosis_ids)
	{
		if (!$this->diagnoses_tables_ready()) {
			return TRUE;
		}

		$patient_id = (int) $patient_id;
		$diagnosis_ids = is_array($diagnosis_ids) ? $diagnosis_ids : array();

		$this->db->where('patient_id', $patient_id)->delete('patient_diagnoses');

		if (empty($diagnosis_ids)) {
			return TRUE;
		}

		$rows = array();

		foreach (array_unique(array_map('intval', $diagnosis_ids)) as $diagnosis_id) {
			if ($diagnosis_id <= 0) {
				continue;
			}

			$rows[] = array(
				'patient_id' => $patient_id,
				'diagnosis_id' => $diagnosis_id,
			);
		}

		if (empty($rows)) {
			return TRUE;
		}

		return $this->db->insert_batch('patient_diagnoses', $rows);
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

	public function get_active_reference_doctors()
	{
		return $this->db
			->select("id, CONCAT(first_name, ' ', last_name) AS full_name", FALSE)
			->from('reference_doctors')
			->where('status', 'active')
			->order_by('first_name', 'asc')
			->order_by('last_name', 'asc')
			->get()
			->result_array();
	}

	public function get_all_diagnoses()
	{
		if (!$this->db->table_exists('diagnoses')) {
			return array();
		}

		return $this->db
			->from('diagnoses')
			->order_by('name', 'asc')
			->get()
			->result_array();
	}

	protected function normalize_patient_payload($data)
	{
		if (!is_array($data)) {
			return array();
		}

		if (array_key_exists('last_name', $data)) {
			$last_name = trim((string) $data['last_name']);
			$data['last_name'] = $last_name === '' ? NULL : $last_name;
		}

		return $data;
	}
}
