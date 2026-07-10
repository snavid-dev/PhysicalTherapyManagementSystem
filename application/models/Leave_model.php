<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave_model extends CI_Model
{
	protected $schema_ready = FALSE;

	public function all()
	{
		$this->ensure_schema();

		return $this->db
			->select('doctor_leaves.*, staff.first_name, staff.last_name, staff_types.name AS staff_type_name')
			->from('doctor_leaves')
			->join('staff', 'staff.id = doctor_leaves.staff_id')
			->join('staff_types', 'staff_types.id = staff.staff_type_id', 'left')
			->order_by('doctor_leaves.start_date', 'desc')
			->get()
			->result_array();
	}

	public function find($id)
	{
		$this->ensure_schema();

		return $this->db->get_where('doctor_leaves', array('id' => (int) $id))->row_array();
	}

	public function create($data)
	{
		$this->ensure_schema();

		$this->db->insert('doctor_leaves', $data);
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		$this->ensure_schema();

		return $this->db->where('id', (int) $id)->update('doctor_leaves', $data);
	}

	public function delete($id)
	{
		$this->ensure_schema();

		return $this->db->where('id', (int) $id)->delete('doctor_leaves');
	}

	/**
	 * Leaves used to be tied to user accounts (doctor_id -> users.id), so only
	 * therapists/doctors with a login could ever have a leave recorded. They are
	 * now tied to the employee record (staff_id -> staff.id) so every staff
	 * member can take leave and have it affect their salary. This migrates the
	 * live table in place, backfilling staff_id from the staff.user_id link.
	 */
	public function ensure_schema()
	{
		if ($this->schema_ready) {
			return;
		}
		$this->schema_ready = TRUE;

		if (!$this->db->table_exists('doctor_leaves')) {
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `doctor_leaves` (
					`id` int unsigned NOT NULL AUTO_INCREMENT,
					`staff_id` int unsigned NOT NULL,
					`start_date` date NOT NULL,
					`end_date` date NOT NULL,
					`status` varchar(30) NOT NULL DEFAULT 'approved',
					`reason` varchar(255) DEFAULT NULL,
					`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`),
					KEY `doctor_leaves_staff_id_index` (`staff_id`),
					CONSTRAINT `doctor_leaves_staff_fk` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
			");
			return;
		}

		// 1. Add the new staff_id column (nullable while we backfill) if the table
		//    is still on the old doctor_id-only shape.
		if (!$this->db->field_exists('staff_id', 'doctor_leaves')) {
			$this->db->query("ALTER TABLE `doctor_leaves` ADD COLUMN `staff_id` int unsigned NULL AFTER `id`");

			// Backfill from the existing user link: doctor_id was a users.id, and
			// each staff member points at its user via staff.user_id.
			if ($this->db->field_exists('doctor_id', 'doctor_leaves')) {
				$this->db->query("
					UPDATE `doctor_leaves` dl
					JOIN `staff` s ON s.user_id = dl.doctor_id
					SET dl.staff_id = s.id
				");
			}
		}

		// 2. Drop the legacy doctor_id column (and its FK to users) if it is still
		//    present from a partial migration.
		if ($this->db->field_exists('doctor_id', 'doctor_leaves')) {
			$fk = $this->db
				->query("
					SELECT CONSTRAINT_NAME AS name
					FROM information_schema.KEY_COLUMN_USAGE
					WHERE TABLE_SCHEMA = DATABASE()
					  AND TABLE_NAME = 'doctor_leaves'
					  AND COLUMN_NAME = 'doctor_id'
					  AND REFERENCED_TABLE_NAME = 'users'
					LIMIT 1
				")
				->row_array();

			if (!empty($fk['name'])) {
				$this->db->query("ALTER TABLE `doctor_leaves` DROP FOREIGN KEY `" . $fk['name'] . "`");
			}

			$this->db->query("ALTER TABLE `doctor_leaves` DROP COLUMN `doctor_id`");
		}

		// 3. Finalize while staff_id is still nullable: drop any leave that could
		//    not map to an employee (e.g. a generic/seed login that has no staff
		//    record) and enforce NOT NULL to match the canonical schema. This block
		//    is self-healing, so a table left half-migrated by an earlier run still
		//    converges to the final shape.
		if ($this->column_is_nullable('staff_id')) {
			$this->db->query("DELETE FROM `doctor_leaves` WHERE `staff_id` IS NULL");
			$this->db->query("ALTER TABLE `doctor_leaves` MODIFY `staff_id` int unsigned NOT NULL");
		}

		// 4. Make sure the staff index + foreign key exist (an earlier partial run
		//    may already have added them).
		if (!$this->index_exists('doctor_leaves_staff_id_index')) {
			$this->db->query("ALTER TABLE `doctor_leaves` ADD KEY `doctor_leaves_staff_id_index` (`staff_id`)");
		}

		if (!$this->staff_fk_exists()) {
			$this->db->query("ALTER TABLE `doctor_leaves` ADD CONSTRAINT `doctor_leaves_staff_fk` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE");
		}
	}

	protected function column_is_nullable($column)
	{
		$row = $this->db
			->query("
				SELECT IS_NULLABLE AS nullable
				FROM information_schema.COLUMNS
				WHERE TABLE_SCHEMA = DATABASE()
				  AND TABLE_NAME = 'doctor_leaves'
				  AND COLUMN_NAME = ?
				LIMIT 1
			", array($column))
			->row_array();

		return $row && strtoupper($row['nullable']) === 'YES';
	}

	protected function index_exists($index)
	{
		$row = $this->db
			->query("
				SELECT 1 AS found
				FROM information_schema.STATISTICS
				WHERE TABLE_SCHEMA = DATABASE()
				  AND TABLE_NAME = 'doctor_leaves'
				  AND INDEX_NAME = ?
				LIMIT 1
			", array($index))
			->row_array();

		return !empty($row);
	}

	protected function staff_fk_exists()
	{
		$row = $this->db
			->query("
				SELECT 1 AS found
				FROM information_schema.KEY_COLUMN_USAGE
				WHERE TABLE_SCHEMA = DATABASE()
				  AND TABLE_NAME = 'doctor_leaves'
				  AND COLUMN_NAME = 'staff_id'
				  AND REFERENCED_TABLE_NAME = 'staff'
				LIMIT 1
			")
			->row_array();

		return !empty($row);
	}
}
