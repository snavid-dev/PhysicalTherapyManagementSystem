<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Section_model extends CI_Model
{
	public function get_all()
	{
		$this->ensure_schema();

		return $this->db
			->order_by('name', 'asc')
			->get('sections')
			->result_array();
	}

	public function get_by_id($id)
	{
		$this->ensure_schema();

		return $this->db
			->get_where('sections', array('id' => (int) $id))
			->row_array();
	}

	public function create($data)
	{
		$this->ensure_schema();
		$this->db->insert('sections', $data);
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		$this->ensure_schema();
		return $this->db->where('id', (int) $id)->update('sections', $data);
	}

	public function delete($id)
	{
		$this->ensure_schema();
		$this->db->where('section_id', (int) $id)->update('staff', array('section_id' => NULL));
		if ($this->db->table_exists('staff_sections')) {
			$this->db->where('section_id', (int) $id)->delete('staff_sections');
		}
		return $this->db->where('id', (int) $id)->delete('sections');
	}

	public function get_staff_by_section($section_id)
	{
		$this->ensure_schema();

		return $this->db
			->select('staff.*, staff_types.name AS staff_type_name')
			->from('staff')
			->join('staff_sections', 'staff_sections.staff_id = staff.id')
			->join('staff_types', 'staff_types.id = staff.staff_type_id')
			->where('staff_sections.section_id', (int) $section_id)
			->order_by('staff.first_name', 'asc')
			->order_by('staff.last_name', 'asc')
			->get()
			->result_array();
	}

	public function get_staff_type_counts($section_id)
	{
		$this->ensure_schema();

		$rows = $this->db
			->select('staff_types.name AS staff_type_name, COUNT(staff.id) AS total')
			->from('staff')
			->join('staff_sections', 'staff_sections.staff_id = staff.id')
			->join('staff_types', 'staff_types.id = staff.staff_type_id')
			->where('staff_sections.section_id', (int) $section_id)
			->group_by('staff.staff_type_id')
			->order_by('total', 'desc')
			->get()
			->result_array();

		return $rows;
	}

	protected function ensure_schema()
	{
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

		if ((int) $this->db->count_all_results('sections') === 0) {
			foreach (array('Male Section', 'Female Section', 'Both Sections') as $name) {
				$this->db->insert('sections', array(
					'name' => $name,
					'default_fee' => 0,
				));
			}
		}
	}
}
