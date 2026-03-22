<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debt_model extends CI_Model
{
	protected $schema_ready = FALSE;

	public function create($patient_id, $turn_id, $amount, $note = NULL)
	{
		$this->ensure_schema();

		$data = array(
			'patient_id' => (int) $patient_id,
			'turn_id' => (int) $turn_id,
			'amount' => round((float) $amount, 2),
			'status' => 'open',
			'note' => $note,
		);

		$this->db->insert('patient_debts', $data);
		return $this->db->insert_id();
	}

	public function get_open_debts($patient_id)
	{
		$this->ensure_schema();

		return $this->base_debt_query()
			->where('patient_debts.patient_id', (int) $patient_id)
			->where('patient_debts.status', 'open')
			->order_by('patient_debts.created_at', 'asc')
			->order_by('patient_debts.id', 'asc')
			->get()
			->result_array();
	}

	public function get_total_open_debt($patient_id)
	{
		$this->ensure_schema();

		$row = $this->db
			->select('COALESCE(SUM(amount), 0) AS total_open_debt', FALSE)
			->from('patient_debts')
			->where('patient_id', (int) $patient_id)
			->where('status', 'open')
			->get()
			->row_array();

		return $row ? (float) $row['total_open_debt'] : 0.00;
	}

	public function clear_debts($patient_id, $cash_available, $clearing_turn_id)
	{
		$this->ensure_schema();

		$cash_available = round((float) $cash_available, 2);
		$debts = $this->get_open_debts($patient_id);

		foreach ($debts as $debt) {
			if ($cash_available <= 0) {
				break;
			}

			$debt_amount = round((float) $debt['amount'], 2);

			if ($cash_available >= $debt_amount) {
				$this->db
					->where('id', (int) $debt['id'])
					->update('patient_debts', array(
						'amount' => $debt_amount,
						'status' => 'cleared',
						'cleared_at' => date('Y-m-d H:i:s'),
						'cleared_by_turn_id' => $clearing_turn_id === NULL ? NULL : (int) $clearing_turn_id,
					));

				$cash_available = round($cash_available - $debt_amount, 2);
				continue;
			}

			if ($cash_available > 0) {
				$this->db
					->where('id', (int) $debt['id'])
					->update('patient_debts', array(
						'amount' => round($debt_amount - $cash_available, 2),
					));

				$cash_available = 0.00;
				break;
			}
		}

		return $cash_available;
	}

	public function get_all_debts_for_patient($patient_id)
	{
		$this->ensure_schema();

		return $this->base_debt_query()
			->where('patient_debts.patient_id', (int) $patient_id)
			->order_by('patient_debts.created_at', 'desc')
			->order_by('patient_debts.id', 'desc')
			->get()
			->result_array();
	}

	protected function base_debt_query()
	{
		return $this->db
			->select("patient_debts.*, turns.turn_date, turns.turn_time, turns.turn_number, sections.name AS section_name, DATE(patient_debts.created_at) AS debt_date", FALSE)
			->from('patient_debts')
			->join('turns', 'turns.id = patient_debts.turn_id', 'left')
			->join('sections', 'sections.id = turns.section_id', 'left');
	}

	protected function ensure_schema()
	{
		if ($this->schema_ready) {
			return;
		}

		$this->ensure_turn_columns();

		if (!$this->db->table_exists('patient_debts')) {
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `patient_debts` (
					`id` int unsigned NOT NULL AUTO_INCREMENT,
					`patient_id` int unsigned NOT NULL,
					`turn_id` int unsigned NOT NULL,
					`amount` decimal(12,2) NOT NULL,
					`status` enum('open','cleared') NOT NULL DEFAULT 'open',
					`cleared_at` timestamp NULL DEFAULT NULL,
					`cleared_by_turn_id` int unsigned DEFAULT NULL,
					`note` varchar(255) DEFAULT NULL,
					`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (`id`),
					KEY `patient_debts_patient_id_index` (`patient_id`),
					KEY `patient_debts_turn_id_index` (`turn_id`),
					KEY `patient_debts_cleared_by_turn_id_index` (`cleared_by_turn_id`),
					CONSTRAINT `patient_debts_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
					CONSTRAINT `patient_debts_turn_fk` FOREIGN KEY (`turn_id`) REFERENCES `turns` (`id`) ON DELETE CASCADE,
					CONSTRAINT `patient_debts_cleared_turn_fk` FOREIGN KEY (`cleared_by_turn_id`) REFERENCES `turns` (`id`) ON DELETE SET NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
			");
		}

		$this->schema_ready = TRUE;
	}

	protected function ensure_turn_columns()
	{
		if (!$this->db->table_exists('turns')) {
			return;
		}

		$this->add_column_if_missing('turns', 'section_id', "ALTER TABLE `turns` ADD COLUMN `section_id` int unsigned DEFAULT NULL AFTER `doctor_id`");
		$this->add_column_if_missing('turns', 'turn_number', "ALTER TABLE `turns` ADD COLUMN `turn_number` tinyint unsigned DEFAULT NULL AFTER `section_id`");
	}

	protected function add_column_if_missing($table, $column, $sql)
	{
		if (!$this->db->field_exists($column, $table)) {
			$this->db->query($sql);
		}
	}
}
