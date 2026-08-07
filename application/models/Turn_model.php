<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Turn_model extends CI_Model
{
	protected $schema_ready = FALSE;

	public function all()
	{
		$this->ensure_schema();

		return $this->db
			->select("turns.*, patients.first_name AS patient_first_name, patients.last_name AS patient_last_name, patients.father_name AS patient_father_name, sections.name AS section_name, CONCAT(staff.first_name, ' ', staff.last_name) AS staff_full_name, CONCAT(users.first_name, ' ', users.last_name) AS doctor_full_name", FALSE)
			->from('turns')
			->join('patients', 'patients.id = turns.patient_id')
			->join('sections', 'sections.id = turns.section_id', 'left')
			->join('staff', 'staff.id = turns.staff_id', 'left')
			->join('users', 'users.id = turns.doctor_id', 'left')
			->order_by('turns.turn_date', 'desc')
			->order_by('turns.id', 'desc')
			->get()
			->result_array();
	}

	/**
	 * Server-side DataTables source for the turns index. The full table grew to
	 * tens of thousands of rows; rendering every row server-side froze the page,
	 * so the list now pages from the DB (25 at a time) with search + ordering done
	 * in SQL. Returns ['data' => rows, 'records_total' => int, 'records_filtered' => int].
	 */
	public function get_datatable($params)
	{
		$this->ensure_schema();

		$orderable = array(
			0 => 'turns.id',
			1 => 'turns.turn_date',
			2 => 'turns.turn_number',
			3 => 'patients.first_name',
			4 => 'patients.last_name',
			5 => 'sections.name',
			6 => 'staff.first_name',
			7 => 'turns.fee',
			8 => 'turns.payment_type',
		);

		$records_total = (int) $this->db->count_all_results('turns');

		// Filtered count (joins + search), keeping the builder so the data query
		// below reuses the same FROM/JOIN/WHERE.
		$this->apply_datatable_joins();
		$this->apply_datatable_search($params['search'] ?? '');
		$records_filtered = (int) $this->db->count_all_results('', FALSE);

		$order_col = isset($params['order_col']) ? (int) $params['order_col'] : 1;
		$order_dir = (isset($params['order_dir']) && strtolower((string) $params['order_dir']) === 'asc') ? 'asc' : 'desc';
		$order_by = isset($orderable[$order_col]) ? $orderable[$order_col] : 'turns.turn_date';

		$this->db->order_by($order_by, $order_dir);
		if ($order_by !== 'turns.id') {
			$this->db->order_by('turns.id', 'desc');
		}

		$this->db->select("turns.*, patients.first_name AS patient_first_name, patients.last_name AS patient_last_name, patients.father_name AS patient_father_name, sections.name AS section_name, CONCAT(staff.first_name, ' ', staff.last_name) AS staff_full_name, CONCAT(users.first_name, ' ', users.last_name) AS doctor_full_name", FALSE);

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

	protected function apply_datatable_joins()
	{
		$this->db
			->from('turns')
			->join('patients', 'patients.id = turns.patient_id')
			->join('sections', 'sections.id = turns.section_id', 'left')
			->join('staff', 'staff.id = turns.staff_id', 'left')
			->join('users', 'users.id = turns.doctor_id', 'left');
	}

	protected function apply_datatable_search($search)
	{
		$search = trim((string) $search);

		if ($search === '') {
			return;
		}

		foreach (preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY) as $term) {
			$this->db
				->group_start()
					->like('patients.first_name', $term)
					->or_like('patients.last_name', $term)
					->or_like('patients.father_name', $term)
					->or_like('sections.name', $term)
					->or_like('staff.first_name', $term)
					->or_like('staff.last_name', $term)
					->or_like('turns.payment_type', $term)
					->or_like('turns.id', $term)
					->or_like('turns.turn_number', $term)
				->group_end();
		}
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
		return $this->db->where('id', (int) $id)->delete('turns');
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
		$this->ensure_turn_indexes();
		$this->schema_ready = TRUE;
	}

	/**
	 * turn_date had no index at all — every list/report query filters or sorts by
	 * it, so at this table's size every one of those was a full table scan +
	 * filesort. section_id/staff_id back the report date-range filters.
	 */
	protected function ensure_turn_indexes()
	{
		if (!$this->db->table_exists('turns')) {
			return;
		}

		$this->add_index_if_missing('turns', 'turns_turn_date_id_index', '(`turn_date`, `id`)');
		$this->add_index_if_missing('turns', 'turns_section_id_index', '(`section_id`)');
		$this->add_index_if_missing('turns', 'turns_staff_id_index', '(`staff_id`)');
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
		$this->add_column_if_missing('turns', 'historical_wallet_deducted', "ALTER TABLE `turns` ADD COLUMN `historical_wallet_deducted` decimal(12,2) NOT NULL DEFAULT 0.00 AFTER `wallet_deducted`");
		$this->add_column_if_missing('turns', 'cash_wallet_deducted', "ALTER TABLE `turns` ADD COLUMN `cash_wallet_deducted` decimal(12,2) NOT NULL DEFAULT 0.00 AFTER `historical_wallet_deducted`");
		$this->add_column_if_missing('turns', 'cash_collected', "ALTER TABLE `turns` ADD COLUMN `cash_collected` decimal(12,2) NOT NULL DEFAULT 0.00 AFTER `cash_wallet_deducted`");
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
