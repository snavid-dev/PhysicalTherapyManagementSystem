<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_model extends CI_Model
{
	public function get_all()
	{
		$this->ensure_schema();

		return $this->db
			->select('staff.*, staff_types.name AS staff_type_name, users.username AS linked_username, users.first_name AS linked_first_name, users.last_name AS linked_last_name')
			->from('staff')
			->join('staff_types', 'staff_types.id = staff.staff_type_id')
			->join('users', 'users.id = staff.user_id', 'left')
			->order_by('staff.first_name', 'asc')
			->order_by('staff.last_name', 'asc')
			->get()
			->result_array();
	}

	public function get_by_id($id)
	{
		$this->ensure_schema();

		return $this->db
			->select('staff.*, staff_types.name AS staff_type_name, users.username AS linked_username, users.first_name AS linked_first_name, users.last_name AS linked_last_name')
			->from('staff')
			->join('staff_types', 'staff_types.id = staff.staff_type_id')
			->join('users', 'users.id = staff.user_id', 'left')
			->where('staff.id', (int) $id)
			->get()
			->row_array();
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
		$this->db->insert('staff', $data);
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		$this->ensure_schema();
		return $this->db->where('id', (int) $id)->update('staff', $data);
	}

	public function set_inactive($id)
	{
		$this->ensure_schema();
		return $this->db
			->where('id', (int) $id)
			->update('staff', array('status' => 'inactive'));
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

	public function get_approved_leaves_this_month($staff_id)
	{
		$staff = $this->get_by_id($staff_id);

		if (empty($staff) || empty($staff['user_id'])) {
			return 0;
		}

		$month_start = date('Y-m-01');
		$month_end = date('Y-m-t');
		$rows = $this->db
			->select('start_date, end_date')
			->from('doctor_leaves')
			->where('doctor_id', (int) $staff['user_id'])
			->where('status', 'approved')
			->where('start_date <=', $month_end)
			->where('end_date >=', $month_start)
			->get()
			->result_array();

		$total_days = 0;

		foreach ($rows as $row) {
			$effective_start = max($row['start_date'], $month_start);
			$effective_end = min($row['end_date'], $month_end);

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

		if (!$this->db->table_exists('staff')) {
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `staff` (
					`id` int unsigned NOT NULL AUTO_INCREMENT,
					`user_id` int unsigned DEFAULT NULL,
					`staff_type_id` int unsigned NOT NULL,
					`first_name` varchar(100) NOT NULL,
					`last_name` varchar(100) NOT NULL,
					`gender` enum('male','female') NOT NULL,
					`section` enum('male','female','both','na') NOT NULL DEFAULT 'na',
					`monthly_leave_quota` tinyint unsigned NOT NULL DEFAULT 4,
					`salary` decimal(12,2) NOT NULL DEFAULT 0.00,
					`salary_type` enum('fixed','hourly') NOT NULL DEFAULT 'fixed',
					`status` enum('active','inactive') NOT NULL DEFAULT 'active',
					`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`),
					KEY `staff_user_id_index` (`user_id`),
					KEY `staff_staff_type_id_index` (`staff_type_id`),
					CONSTRAINT `staff_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
					CONSTRAINT `staff_staff_type_fk` FOREIGN KEY (`staff_type_id`) REFERENCES `staff_types` (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
			");
		}

		foreach (array('Doctor', 'Physiotherapist', 'Cleaner', 'Manager') as $type_name) {
			$exists = $this->db
				->where('name', $type_name)
				->limit(1)
				->get('staff_types')
				->row_array();

			if ($exists) {
				continue;
			}

			$this->db->insert('staff_types', array('name' => $type_name));
		}

		foreach (array('Intern', 'Helper', 'Marketer') as $type_name) {
			$exists = $this->db
				->where('name', $type_name)
				->limit(1)
				->get('staff_types')
				->row_array();

			if ($exists) {
				continue;
			}

			$this->db->insert('staff_types', array('name' => $type_name));
		}
	}
}
