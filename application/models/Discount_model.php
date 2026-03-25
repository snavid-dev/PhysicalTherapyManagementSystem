<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discount_model extends CI_Model
{
	public function get_active_discount($patient_id, $section_id)
	{
		$this->ensure_schema();

		return $this->db
			->from('patient_discounts')
			->where('patient_id', (int) $patient_id)
			->where('section_id', (int) $section_id)
			->order_by('id', 'desc')
			->limit(1)
			->get()
			->row_array();
	}

	public function get_all_for_patient($patient_id)
	{
		$this->ensure_schema();

		return $this->db
			->select('patient_discounts.*, sections.name AS section_name')
			->from('patient_discounts')
			->join('sections', 'sections.id = patient_discounts.section_id')
			->where('patient_discounts.patient_id', (int) $patient_id)
			->order_by('patient_discounts.section_id', 'asc')
			->order_by('patient_discounts.created_at', 'desc')
			->order_by('patient_discounts.id', 'desc')
			->get()
			->result_array();
	}

	public function get_discounts_for_section($patient_id, $section_id)
	{
		$this->ensure_schema();

		return $this->db
			->from('patient_discounts')
			->where('patient_id', (int) $patient_id)
			->where('section_id', (int) $section_id)
			->order_by('created_at', 'desc')
			->order_by('id', 'desc')
			->get()
			->result_array();
	}

	public function create($patient_id, $section_id, $discount_percent, $note, $created_by)
	{
		$this->ensure_schema();

		if (!$this->valid_discount_percent($discount_percent)) {
			return FALSE;
		}

		$this->db->insert('patient_discounts', array(
			'patient_id' => (int) $patient_id,
			'section_id' => (int) $section_id,
			'discount_percent' => round((float) $discount_percent, 2),
			'note' => $this->null_if_empty($note),
			'created_by' => (int) $created_by > 0 ? (int) $created_by : NULL,
		));

		return $this->db->insert_id();
	}

	public function delete($id, $patient_id = NULL)
	{
		$this->ensure_schema();

		$this->db->where('id', (int) $id);

		if ($patient_id !== NULL) {
			$this->db->where('patient_id', (int) $patient_id);
		}

		$this->db->delete('patient_discounts');
		return $this->db->affected_rows() > 0;
	}

	public function calculate_discounted_fee($original_fee, $discount_percent)
	{
		$original_fee = round((float) $original_fee, 2);
		$discount_percent = round((float) $discount_percent, 2);
		$discount_amount = round($original_fee * $discount_percent / 100, 2);

		return array(
			'original_fee' => $original_fee,
			'discount_percent' => $discount_percent,
			'discount_amount' => $discount_amount,
			'final_fee' => round($original_fee - $discount_amount, 2),
		);
	}

	protected function valid_discount_percent($discount_percent)
	{
		$discount_percent = (float) $discount_percent;
		return $discount_percent >= 0.01 && $discount_percent <= 100;
	}

	protected function null_if_empty($value)
	{
		$value = trim((string) $value);
		return $value === '' ? NULL : $value;
	}

	protected function ensure_schema()
	{
		if ($this->db->table_exists('patient_discounts')) {
			return;
		}

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `patient_discounts` (
				`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				`patient_id` INT UNSIGNED NOT NULL,
				`section_id` INT UNSIGNED NOT NULL,
				`discount_percent` DECIMAL(5,2) NOT NULL,
				`note` VARCHAR(255) DEFAULT NULL,
				`created_by` INT UNSIGNED DEFAULT NULL,
				`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				KEY `patient_discounts_patient_id_index` (`patient_id`),
				KEY `patient_discounts_section_id_index` (`section_id`),
				KEY `patient_discounts_created_by_index` (`created_by`),
				CONSTRAINT `patient_discounts_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
				CONSTRAINT `patient_discounts_section_fk` FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE CASCADE,
				CONSTRAINT `patient_discounts_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
		");
	}
}
