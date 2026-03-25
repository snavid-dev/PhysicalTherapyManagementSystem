<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense_category_model extends CI_Model
{
	public function get_all()
	{
		$this->ensure_schema();

		return $this->db
			->order_by('name', 'asc')
			->get('expense_categories')
			->result_array();
	}

	public function get_by_id($id)
	{
		$this->ensure_schema();

		return $this->db
			->get_where('expense_categories', array('id' => (int) $id))
			->row_array();
	}

	public function create($data)
	{
		$this->ensure_schema();

		$this->db->insert('expense_categories', $data);
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		$this->ensure_schema();

		return $this->db
			->where('id', (int) $id)
			->update('expense_categories', $data);
	}

	public function delete($id)
	{
		$this->ensure_schema();

		$has_expenses = $this->db
			->where('category_id', (int) $id)
			->count_all_results('expenses') > 0;

		if ($has_expenses) {
			return FALSE;
		}

		return $this->db
			->where('id', (int) $id)
			->delete('expense_categories');
	}

	protected function ensure_schema()
	{
		if (!$this->db->table_exists('expense_categories')) {
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `expense_categories` (
					`id` int unsigned NOT NULL AUTO_INCREMENT,
					`name` varchar(150) NOT NULL,
					`name_fa` varchar(150) DEFAULT NULL,
					`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
			");
		}

		$defaults = array(
			array('name' => 'Staff Salary Payment', 'name_fa' => 'پرداخت معاش کارمند'),
			array('name' => 'Rent / Utilities', 'name_fa' => 'کرایه و خدمات'),
			array('name' => 'Other', 'name_fa' => 'سایر'),
		);

		foreach ($defaults as $default) {
			$exists = $this->db
				->where('name', $default['name'])
				->limit(1)
				->get('expense_categories')
				->row_array();

			if (!$exists) {
				$this->db->insert('expense_categories', $default);
			}
		}
	}
}
