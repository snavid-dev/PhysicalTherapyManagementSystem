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

	/**
	 * Server-side DataTables source for the patients index — same pattern as
	 * Turn_model::get_datatable(). The list used to ship every patient (and re-ship
	 * them on every pagination/sort click) with pagination/search done in JS.
	 */
	public function get_datatable($params)
	{
		$this->ensure_schema();

		$orderable = array(
			0 => 'patients.first_name',
			1 => 'patients.father_name',
			2 => 'patients.gender',
			3 => 'patients.age',
			4 => 'patients.phone',
		);

		$records_total = (int) $this->db->count_all_results('patients');

		$this->db->from('patients');
		$this->apply_datatable_search($params['search'] ?? '');
		$records_filtered = (int) $this->db->count_all_results('', FALSE);

		$order_col = isset($params['order_col']) ? (int) $params['order_col'] : 0;
		$order_dir = (isset($params['order_dir']) && strtolower((string) $params['order_dir']) === 'desc') ? 'desc' : 'asc';
		$order_by = isset($orderable[$order_col]) ? $orderable[$order_col] : 'patients.first_name';

		$this->db->order_by($order_by, $order_dir);
		if ($order_by !== 'patients.last_name') {
			$this->db->order_by('patients.last_name', $order_dir);
		}

		$this->db->select('patients.*');

		$length = isset($params['length']) ? (int) $params['length'] : 25;
		$start = isset($params['start']) ? max(0, (int) $params['start']) : 0;

		if ($length > 0) {
			$this->db->limit($length, $start);
		}

		$data = $this->db->get()->result_array();

		return array(
			'data' => $data,
			'records_total' => $records_total,
			'records_filtered' => $records_filtered,
		);
	}

	protected function apply_datatable_search($search)
	{
		$search = trim((string) $search);

		if ($search === '') {
			return;
		}

		$this->db
			->group_start()
				->like('patients.first_name', $search)
				->or_like('patients.last_name', $search)
				->or_like('patients.father_name', $search)
				->or_like('patients.phone', $search)
				->or_like('patients.phone2', $search)
			->group_end();
	}

	protected function ensure_schema()
	{
		if (!$this->db->table_exists('patients')) {
			return;
		}

		// Backs the default name-sorted listing (get_datatable()'s default order);
		// the leading-wildcard search itself can't use a btree index either way.
		$this->add_index_if_missing('patients', 'patients_first_last_name_index', '(`first_name`, `last_name`)');
	}

	protected function add_index_if_missing($table, $index_name, $columns_sql)
	{
		$exists = $this->db->query(
			"SELECT 1 FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ? LIMIT 1",
			array($table, $index_name)
		)->row_array();

		if (!$exists) {
			$this->db->query("ALTER TABLE `{$table}` ADD INDEX `{$index_name}` {$columns_sql}");
		}
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

	public function find_duplicate_identity($data, $exclude_id = NULL)
	{
		$identity = $this->normalized_identity_fields($data);

		if ($identity['first_name'] === NULL && $identity['last_name'] === NULL && $identity['father_name'] === NULL) {
			return NULL;
		}

		$this->db
			->select('id, first_name, last_name, father_name, phone')
			->from('patients');

		if ($exclude_id !== NULL) {
			$this->db->where('id !=', (int) $exclude_id);
		}

		$candidates = $this->db->get()->result_array();

		foreach ($candidates as $candidate) {
			$candidate_identity = $this->normalized_identity_fields($candidate);

			if ($candidate_identity['first_name'] !== $identity['first_name']) {
				continue;
			}

			if ($candidate_identity['last_name'] !== $identity['last_name']) {
				continue;
			}

			if ($candidate_identity['father_name'] !== $identity['father_name']) {
				continue;
			}

			// Phone is only a useful discriminator when both records actually have
			// one on file — requiring an exact match let an identical-name duplicate
			// through whenever either phone field was left blank.
			if ($identity['phone'] !== NULL && $candidate_identity['phone'] !== NULL && $candidate_identity['phone'] !== $identity['phone']) {
				continue;
			}

			return $candidate;
		}

		return NULL;
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

		if (array_key_exists('first_name', $data)) {
			$data['first_name'] = trim((string) $data['first_name']);
		}

		if (array_key_exists('last_name', $data)) {
			$data['last_name'] = $this->normalize_nullable_string($data['last_name']);
		}

		if (array_key_exists('father_name', $data)) {
			$data['father_name'] = $this->normalize_nullable_string($data['father_name']);
		}

		if (array_key_exists('phone', $data)) {
			$data['phone'] = $this->normalize_nullable_string($data['phone']);
		}

		if (array_key_exists('phone2', $data)) {
			$data['phone2'] = $this->normalize_nullable_string($data['phone2']);
		}

		return $data;
	}

	protected function normalized_identity_fields($data)
	{
		$data = $this->normalize_patient_payload($data);

		return array(
			'first_name' => array_key_exists('first_name', $data) ? $this->normalize_identity_string($data['first_name']) : NULL,
			'last_name' => array_key_exists('last_name', $data) ? $this->normalize_identity_string($data['last_name']) : NULL,
			'father_name' => array_key_exists('father_name', $data) ? $this->normalize_identity_string($data['father_name']) : NULL,
			'phone' => array_key_exists('phone', $data) ? $this->normalize_phone_for_identity($data['phone']) : NULL,
		);
	}

	protected function normalize_nullable_string($value)
	{
		$value = trim((string) $value);
		return $value === '' ? NULL : $value;
	}

	protected function normalize_identity_string($value)
	{
		$value = $this->normalize_nullable_string($value);

		if ($value === NULL) {
			return NULL;
		}

		$value = preg_replace('/\s+/u', ' ', $value);

		if (function_exists('mb_strtolower')) {
			return mb_strtolower($value, 'UTF-8');
		}

		return strtolower($value);
	}

	protected function normalize_phone_for_identity($value)
	{
		$value = $this->normalize_nullable_string($value);

		if ($value === NULL) {
			return NULL;
		}

		$digits = preg_replace('/\D+/', '', $value);

		if ($digits === '') {
			return NULL;
		}

		if (preg_match('/^93(\d{9})$/', $digits, $matches)) {
			return '0' . $matches[1];
		}

		return $digits;
	}
}
