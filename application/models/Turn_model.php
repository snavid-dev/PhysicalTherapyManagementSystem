<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Turn_model extends CI_Model
{
	protected $schema_ready = FALSE;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Safe_model');
	}

	public function all()
	{
		$this->ensure_schema();

		return $this->db
			->select("turns.*, patients.first_name AS patient_first_name, patients.last_name AS patient_last_name, sections.name AS section_name, CONCAT(staff.first_name, ' ', staff.last_name) AS staff_full_name, CONCAT(users.first_name, ' ', users.last_name) AS doctor_full_name", FALSE)
			->from('turns')
			->join('patients', 'patients.id = turns.patient_id')
			->join('sections', 'sections.id = turns.section_id', 'left')
			->join('staff', 'staff.id = turns.staff_id', 'left')
			->join('users', 'users.id = turns.doctor_id', 'left')
			->order_by('turns.turn_date', 'desc')
			->order_by('turns.turn_time', 'desc')
			->order_by('turns.id', 'desc')
			->get()
			->result_array();
	}

	public function find($id)
	{
		$this->ensure_schema();
		return $this->db->get_where('turns', array('id' => (int) $id))->row_array();
	}

	public function create($data)
	{
		$this->ensure_schema();
		$this->db->insert('turns', $data);
		return $this->db->insert_id();
	}

	public function create_many($rows)
	{
		$this->ensure_schema();
		$this->db->trans_start();
		foreach ($rows as $row) {
			$this->db->insert('turns', $row);
		}
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function update($id, $data)
	{
		$this->ensure_schema();
		return $this->db->where('id', (int) $id)->update('turns', $data);
	}

	public function delete($id)
	{
		$this->ensure_schema();
		$id = (int) $id;

		$this->db->trans_begin();

		$safe_deleted = $this->Safe_model->delete_transaction_by_reference('turns', $id);
		$deleted = $this->db->where('id', $id)->delete('turns');

		if ($safe_deleted === FALSE || !$deleted || $this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();

		return TRUE;
	}

	public function get_staff_by_section($section_id)
	{
		$this->ensure_schema();

		$section = $this->db
			->select('id, name')
			->from('sections')
			->where('id', (int) $section_id)
			->limit(1)
			->get()
			->row_array();

		if (!$section) {
			return array();
		}

		$section_ids = $this->related_section_ids($section['name'], (int) $section['id']);

		return $this->db
			->distinct()
			->select("staff.id, staff.user_id, CONCAT(staff.first_name, ' ', staff.last_name) AS full_name", FALSE)
			->from('staff')
			->join('staff_sections', 'staff_sections.staff_id = staff.id')
			->join('staff_types', 'staff_types.id = staff.staff_type_id')
			->where('staff.status', 'active')
			->where('staff.user_id IS NOT NULL', NULL, FALSE)
			->where_in('staff_types.name', array('Doctor', 'Physiotherapist'))
			->where_in('staff_sections.section_id', $section_ids)
			->order_by('staff.first_name', 'asc')
			->order_by('staff.last_name', 'asc')
			->get()
			->result_array();
	}

	public function get_staff_member($staff_id)
	{
		$this->ensure_schema();

		return $this->db
			->select('staff.*, staff_types.name AS staff_type_name')
			->from('staff')
			->join('staff_types', 'staff_types.id = staff.staff_type_id')
			->where('staff.id', (int) $staff_id)
			->limit(1)
			->get()
			->row_array();
	}

	public function get_section_fee($section_id)
	{
		$this->ensure_schema();

		$row = $this->db
			->select('default_fee')
			->from('sections')
			->where('id', (int) $section_id)
			->limit(1)
			->get()
			->row_array();

		return $row ? (float) $row['default_fee'] : 0.00;
	}

	public function get_turns_for_patient($patient_id)
	{
		$this->ensure_schema();

		return $this->db
			->select("turns.*, sections.name AS section_name, CONCAT(staff.first_name, ' ', staff.last_name) AS staff_full_name, CONCAT(users.first_name, ' ', users.last_name) AS doctor_full_name", FALSE)
			->from('turns')
			->join('sections', 'sections.id = turns.section_id', 'left')
			->join('staff', 'staff.id = turns.staff_id', 'left')
			->join('users', 'users.id = turns.doctor_id', 'left')
			->where('turns.patient_id', (int) $patient_id)
			->order_by('turns.turn_date', 'desc')
			->order_by('turns.turn_time', 'desc')
			->order_by('turns.id', 'desc')
			->get()
			->result_array();
	}

	public function get_next_session_number($patient_id, $section_id)
	{
		$this->ensure_schema();

		$row = $this->db
			->select_max('turn_number')
			->from('turns')
			->where('patient_id', (int) $patient_id)
			->where('section_id', (int) $section_id)
			->get()
			->row_array();

		$max_turn_number = isset($row['turn_number']) ? (int) $row['turn_number'] : 0;

		return $max_turn_number > 0 ? $max_turn_number + 1 : 1;
	}

	protected function related_section_ids($section_name, $section_id)
	{
		$section_ids = array((int) $section_id);
		$normalized_name = strtolower(trim((string) $section_name));
		$both_section_id = $this->lookup_section_id_by_name('Both Sections');

		if (in_array($normalized_name, array('male section', 'female section'), TRUE) && $both_section_id !== NULL) {
			$section_ids[] = $both_section_id;
		}

		return array_values(array_unique(array_filter($section_ids)));
	}

	protected function lookup_section_id_by_name($name)
	{
		$this->ensure_schema();

		$row = $this->db
			->select('id')
			->from('sections')
			->where('name', $name)
			->limit(1)
			->get()
			->row_array();

		return $row ? (int) $row['id'] : NULL;
	}

	protected function ensure_schema()
	{
		if ($this->schema_ready) {
			return;
		}

		$this->ensure_turn_columns();
		$this->ensure_turn_time_nullable();
		$this->ensure_turn_status_values();
		$this->schema_ready = TRUE;
	}

	protected function ensure_turn_columns()
	{
		if (!$this->db->table_exists('turns')) {
			return;
		}

		$this->add_column_if_missing('turns', 'section_id', "ALTER TABLE `turns` ADD COLUMN `section_id` int unsigned DEFAULT NULL AFTER `doctor_id`");
		$this->add_column_if_missing('turns', 'staff_id', "ALTER TABLE `turns` ADD COLUMN `staff_id` int unsigned DEFAULT NULL AFTER `section_id`");
		$this->add_column_if_missing('turns', 'turn_number', "ALTER TABLE `turns` ADD COLUMN `turn_number` tinyint unsigned DEFAULT NULL AFTER `staff_id`");
		$this->add_column_if_missing('turns', 'fee', "ALTER TABLE `turns` ADD COLUMN `fee` decimal(12,2) NOT NULL DEFAULT 0.00 AFTER `turn_number`");
		$this->add_column_if_missing('turns', 'discount_percent', "ALTER TABLE `turns` ADD COLUMN `discount_percent` decimal(5,2) NOT NULL DEFAULT 0.00 AFTER `fee`");
		$this->add_column_if_missing('turns', 'discount_amount', "ALTER TABLE `turns` ADD COLUMN `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00 AFTER `discount_percent`");
		$this->add_column_if_missing('turns', 'payment_type', "ALTER TABLE `turns` ADD COLUMN `payment_type` enum('prepaid','cash','deferred','free') NOT NULL DEFAULT 'cash' AFTER `discount_amount`");
		$this->add_column_if_missing('turns', 'wallet_deducted', "ALTER TABLE `turns` ADD COLUMN `wallet_deducted` decimal(12,2) NOT NULL DEFAULT 0.00 AFTER `payment_type`");
		$this->add_column_if_missing('turns', 'cash_collected', "ALTER TABLE `turns` ADD COLUMN `cash_collected` decimal(12,2) NOT NULL DEFAULT 0.00 AFTER `wallet_deducted`");
		$this->add_column_if_missing('turns', 'topup_amount', "ALTER TABLE `turns` ADD COLUMN `topup_amount` decimal(12,2) NOT NULL DEFAULT 0.00 AFTER `cash_collected`");
	}

	protected function ensure_turn_time_nullable()
	{
		$column = $this->column_definition('turns', 'turn_time');

		if (!$column) {
			return;
		}

		if (stripos((string) $column['Type'], 'time') === 0 && strtoupper((string) $column['Null']) === 'YES' && $column['Default'] === NULL) {
			return;
		}

		$this->db->query("ALTER TABLE `turns` MODIFY COLUMN `turn_time` TIME DEFAULT NULL");
	}

	protected function ensure_turn_status_values()
	{
		$column = $this->column_definition('turns', 'status');

		if (!$column) {
			return;
		}

		$expected_type = "enum('accepted','scheduled','completed','cancelled')";
		if (strtolower((string) $column['Type']) === $expected_type && (string) $column['Default'] === 'accepted') {
			return;
		}

		$this->db->query("ALTER TABLE `turns` MODIFY COLUMN `status` ENUM('accepted','scheduled','completed','cancelled') NOT NULL DEFAULT 'accepted'");
	}

	protected function column_definition($table, $column)
	{
		$query = $this->db->query("SHOW COLUMNS FROM `{$table}` LIKE " . $this->db->escape($column));
		return $query ? $query->row_array() : NULL;
	}

	protected function add_column_if_missing($table, $column, $sql)
	{
		if (!$this->db->field_exists($column, $table)) {
			$this->db->query($sql);
		}
	}
}
