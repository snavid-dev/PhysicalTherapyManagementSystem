<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_model extends CI_Model
{
	public function get_all()
	{
		$this->ensure_schema();

		$rows = $this->db
			->select('staff.*, staff_types.name AS staff_type_name, users.username AS linked_username, users.first_name AS linked_first_name, users.last_name AS linked_last_name')
			->from('staff')
			->join('staff_types', 'staff_types.id = staff.staff_type_id')
			->join('users', 'users.id = staff.user_id', 'left')
			->order_by('staff.first_name', 'asc')
			->order_by('staff.last_name', 'asc')
			->get()
			->result_array();

		foreach ($rows as &$row) {
			$row['sections'] = $this->get_sections($row['id']);
		}

		return $rows;
	}

	public function get_by_id($id)
	{
		$this->ensure_schema();

		$row = $this->db
			->select('staff.*, staff_types.name AS staff_type_name, users.username AS linked_username, users.first_name AS linked_first_name, users.last_name AS linked_last_name')
			->from('staff')
			->join('staff_types', 'staff_types.id = staff.staff_type_id')
			->join('users', 'users.id = staff.user_id', 'left')
			->where('staff.id', (int) $id)
			->get()
			->row_array();

		if ($row) {
			$row['sections'] = $this->get_sections($id);
		}

		return $row;
	}

	public function get_staff_types()
	{
		$this->ensure_schema();

		return $this->db
			->order_by('name', 'asc')
			->get('staff_types')
			->result_array();
	}

	public function create($data)
	{
		$this->ensure_schema();

		$section_ids = isset($data['section_ids']) ? (array) $data['section_ids'] : array();
		unset($data['section_ids']);

		$this->db->insert('staff', $data);
		$staff_id = $this->db->insert_id();
		$this->sync_sections($staff_id, $section_ids);

		return $staff_id;
	}

	public function update($id, $data)
	{
		$this->ensure_schema();

		$section_ids = isset($data['section_ids']) ? (array) $data['section_ids'] : array();
		unset($data['section_ids']);

		$this->db->where('id', (int) $id)->update('staff', $data);
		$this->sync_sections($id, $section_ids);

		return TRUE;
	}

	public function set_inactive($id)
	{
		$this->ensure_schema();

		return $this->db
			->where('id', (int) $id)
			->update('staff', array('status' => 'inactive'));
	}

	public function set_active($id)
	{
		$this->ensure_schema();

		return $this->db
			->where('id', (int) $id)
			->update('staff', array('status' => 'active'));
	}

	public function get_section_ids($staff_id)
	{
		$this->ensure_schema();

		$rows = $this->db
			->select('section_id')
			->from('staff_sections')
			->where('staff_id', (int) $staff_id)
			->get()
			->result_array();

		return array_map('intval', array_column($rows, 'section_id'));
	}

	public function get_sections($staff_id)
	{
		$this->ensure_schema();

		return $this->db
			->select('sections.*')
			->from('staff_sections')
			->join('sections', 'sections.id = staff_sections.section_id')
			->where('staff_sections.staff_id', (int) $staff_id)
			->order_by('sections.name', 'asc')
			->get()
			->result_array();
	}

	public function count_patients_last_month($staff_id)
	{
		$staff = $this->get_by_id($staff_id);

		if (empty($staff) || empty($staff['user_id'])) {
			return 0;
		}

		$month_start = date('Y-m-01', strtotime('first day of last month'));
		$month_end = date('Y-m-t', strtotime('last day of last month'));

		return (int) $this->db
			->from('turns')
			->where('doctor_id', (int) $staff['user_id'])
			->where('status', 'completed')
			->where('turn_date >=', $month_start)
			->where('turn_date <=', $month_end)
			->count_all_results();
	}

	public function get_approved_leaves_in_range($staff_id, $from_date, $to_date)
	{
		$staff = $this->get_by_id($staff_id);

		if (empty($staff) || empty($staff['user_id'])) {
			return 0;
		}

		$rows = $this->db
			->select('start_date, end_date')
			->from('doctor_leaves')
			->where('doctor_id', (int) $staff['user_id'])
			->where('status', 'approved')
			->where('start_date <=', $to_date)
			->where('end_date >=', $from_date)
			->get()
			->result_array();

		$total_days = 0;

		foreach ($rows as $row) {
			$effective_start = max($row['start_date'], $from_date);
			$effective_end = min($row['end_date'], $to_date);

			if ($effective_start > $effective_end) {
				continue;
			}

			$start = new DateTime($effective_start);
			$end = new DateTime($effective_end);
			$total_days += ((int) $start->diff($end)->days) + 1;
		}

		return $total_days;
	}

	protected function ensure_schema()
	{
		if (!$this->db->table_exists('staff_types')) {
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `staff_types` (
					`id` int unsigned NOT NULL AUTO_INCREMENT,
					`name` varchar(100) NOT NULL,
					`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
			");
		}

		if (!$this->db->table_exists('sections')) {
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `sections` (
					`id` int unsigned NOT NULL AUTO_INCREMENT,
					`name` varchar(100) NOT NULL,
					`default_fee` decimal(12,2) NOT NULL DEFAULT 0.00,
					`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
			");
		}

		if (!$this->db->table_exists('staff')) {
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `staff` (
					`id` int unsigned NOT NULL AUTO_INCREMENT,
					`user_id` int unsigned DEFAULT NULL,
					`staff_type_id` int unsigned NOT NULL,
					`first_name` varchar(100) NOT NULL,
					`last_name` varchar(100) NOT NULL,
					`gender` enum('male','female') NOT NULL,
					`section_id` int unsigned DEFAULT NULL,
					`monthly_leave_quota` tinyint unsigned NOT NULL DEFAULT 4,
					`salary` decimal(12,2) NOT NULL DEFAULT 0.00,
					`salary_type` enum('fixed','hourly') NOT NULL DEFAULT 'fixed',
					`status` enum('active','inactive') NOT NULL DEFAULT 'active',
					`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`),
					KEY `staff_user_id_index` (`user_id`),
					KEY `staff_staff_type_id_index` (`staff_type_id`),
					KEY `staff_section_id_index` (`section_id`),
					CONSTRAINT `staff_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
					CONSTRAINT `staff_staff_type_fk` FOREIGN KEY (`staff_type_id`) REFERENCES `staff_types` (`id`),
					CONSTRAINT `staff_section_fk` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
			");
		}

		if (!$this->db->table_exists('staff_sections')) {
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `staff_sections` (
					`staff_id` int unsigned NOT NULL,
					`section_id` int unsigned NOT NULL,
					PRIMARY KEY (`staff_id`, `section_id`),
					CONSTRAINT `staff_sections_staff_fk` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE,
					CONSTRAINT `staff_sections_section_fk` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
			");
		}

		if ((int) $this->db->count_all_results('sections') === 0) {
			foreach (array('Male Section', 'Female Section', 'Both Sections') as $name) {
				$this->db->insert('sections', array(
					'name' => $name,
					'default_fee' => 0,
				));
			}
		}

		$staff_fields = $this->db->field_data('staff');
		$staff_columns = array();
		foreach ($staff_fields as $field) {
			$staff_columns[] = $field->name;
		}

		if (!in_array('section_id', $staff_columns, TRUE)) {
			$this->db->query("ALTER TABLE `staff` ADD COLUMN `section_id` int unsigned DEFAULT NULL AFTER `gender`");
		}

		if (in_array('section', $staff_columns, TRUE)) {
			$section_map = array(
				'male' => 'Male Section',
				'female' => 'Female Section',
				'both' => 'Both Sections',
			);

			foreach ($section_map as $legacy_value => $section_name) {
				$section = $this->db->where('name', $section_name)->limit(1)->get('sections')->row_array();
				if ($section) {
					$this->db
						->where('section', $legacy_value)
						->where('section_id IS NULL', NULL, FALSE)
						->update('staff', array('section_id' => (int) $section['id']));
				}
			}
		}

		if (in_array('section_id', $staff_columns, TRUE)) {
			$staff_rows = $this->db
				->select('id, section_id')
				->from('staff')
				->where('section_id IS NOT NULL', NULL, FALSE)
				->get()
				->result_array();

			foreach ($staff_rows as $row) {
				$exists = $this->db
					->where('staff_id', (int) $row['id'])
					->where('section_id', (int) $row['section_id'])
					->limit(1)
					->get('staff_sections')
					->row_array();

				if (!$exists) {
					$this->db->insert('staff_sections', array(
						'staff_id' => (int) $row['id'],
						'section_id' => (int) $row['section_id'],
					));
				}
			}
		}

		foreach (array('Doctor', 'Physiotherapist', 'Cleaner', 'Manager') as $type_name) {
			$exists = $this->db
				->where('name', $type_name)
				->limit(1)
				->get('staff_types')
				->row_array();

			if (!$exists) {
				$this->db->insert('staff_types', array('name' => $type_name));
			}
		}

		foreach (array('Intern', 'Helper', 'Marketer') as $type_name) {
			$exists = $this->db
				->where('name', $type_name)
				->limit(1)
				->get('staff_types')
				->row_array();

			if (!$exists) {
				$this->db->insert('staff_types', array('name' => $type_name));
			}
		}
	}

	protected function sync_sections($staff_id, array $section_ids)
	{
		$this->db->where('staff_id', (int) $staff_id)->delete('staff_sections');

		$section_ids = array_unique(array_filter(array_map('intval', $section_ids)));

		foreach ($section_ids as $section_id) {
			$this->db->insert('staff_sections', array(
				'staff_id' => (int) $staff_id,
				'section_id' => (int) $section_id,
			));
		}

		$this->db
			->where('id', (int) $staff_id)
			->update('staff', array(
				'section_id' => !empty($section_ids) ? (int) reset($section_ids) : NULL,
			));
	}
}
