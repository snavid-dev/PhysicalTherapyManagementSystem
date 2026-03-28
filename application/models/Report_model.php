<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model
{
	public function summary($from, $to)
	{
		return array(
			'turns' => (int) $this->db->where('turn_date >=', $from)->where('turn_date <=', $to)->count_all_results('turns'),
			'leaves' => (int) $this->db->where('start_date >=', $from)->where('start_date <=', $to)->count_all_results('doctor_leaves'),
			'new_patients' => (int) $this->db->where('DATE(created_at) >=', $from)->where('DATE(created_at) <=', $to)->count_all_results('patients'),
		);
	}

	public function turns($from, $to)
	{
		return $this->db
			->select('turns.*, patients.first_name AS patient_first_name, patients.last_name AS patient_last_name, users.first_name AS therapist_first_name, users.last_name AS therapist_last_name')
			->from('turns')
			->join('patients', 'patients.id = turns.patient_id')
			->join('users', 'users.id = turns.doctor_id')
			->where('turns.turn_date >=', $from)
			->where('turns.turn_date <=', $to)
			->order_by('turns.turn_date', 'desc')
			->order_by('turns.turn_time', 'desc')
			->get()
			->result_array();
	}

	public function leaves($from, $to)
	{
		return $this->db
			->select('doctor_leaves.*, users.first_name, users.last_name')
			->from('doctor_leaves')
			->join('users', 'users.id = doctor_leaves.doctor_id')
			->where('doctor_leaves.start_date >=', $from)
			->where('doctor_leaves.start_date <=', $to)
			->order_by('doctor_leaves.start_date', 'desc')
			->get()
			->result_array();
	}

	public function get_daily_register($filters)
	{
		return $this->daily_register_base_query($filters)
			->select("
				turns.id,
				turns.patient_id,
				turns.turn_number,
				TRIM(CONCAT(patients.first_name, ' ', COALESCE(patients.last_name, ''))) AS patient_name,
				patients.gender,
				patients.referred_by AS reference_doctor_id,
				TRIM(CONCAT(reference_doctors.first_name, ' ', COALESCE(reference_doctors.last_name, ''))) AS reference_doctor_name,
				sections.name AS section_name,
				TRIM(CONCAT(staff.first_name, ' ', COALESCE(staff.last_name, ''))) AS staff_name,
				turns.fee,
				turns.payment_type,
				turns.cash_collected,
				turns.wallet_deducted,
				turns.discount_amount,
				turns.turn_date,
				turns.turn_time,
				turns.notes,
				turns.status
			", FALSE)
			->order_by('turns.turn_date', 'asc')
			->order_by('turns.id', 'asc')
			->get()
			->result_array();
	}

	public function get_daily_register_summary($filters)
	{
		$filters = $this->normalize_daily_register_filters($filters);

		$summary_row = $this->daily_register_base_query($filters)
			->select("
				COUNT(turns.id) AS total_turns,
				COALESCE(SUM(turns.fee), 0) AS total_fees,
				COALESCE(SUM(turns.cash_collected), 0) AS total_cash,
				COALESCE(SUM(turns.topup_amount), 0) AS total_turn_topups,
				COALESCE(SUM(turns.wallet_deducted), 0) AS total_wallet_used,
				COALESCE(SUM(turns.discount_amount), 0) AS total_discounts
			", FALSE)
			->get()
			->row_array();

		$manual_wallet_topups = $this->get_manual_wallet_topups_total($filters);
		$total_wallet_topups = round((float) ($summary_row['total_turn_topups'] ?? 0) + $manual_wallet_topups, 2);

		$turn_rows = $this->daily_register_base_query($filters)
			->select('turns.id')
			->order_by('turns.id', 'asc')
			->get()
			->result_array();

		$turn_ids = array_map('intval', array_column($turn_rows, 'id'));
		$debts_by_turn = array();
		$total_debts = 0.00;

		if (!empty($turn_ids) && $this->db->table_exists('patient_debts')) {
			$debt_rows = $this->db
				->select('turn_id, COALESCE(SUM(amount), 0) AS total_amount', FALSE)
				->from('patient_debts')
				->where_in('turn_id', $turn_ids)
				->where('status', 'open')
				->group_by('turn_id')
				->get()
				->result_array();

			foreach ($debt_rows as $debt_row) {
				$amount = (float) $debt_row['total_amount'];
				$debts_by_turn[(int) $debt_row['turn_id']] = $amount;
				$total_debts += $amount;
			}
		}

		$income_by_section = $this->daily_register_base_query($filters)
			->select("
				turns.section_id,
				sections.name AS section_name,
				COUNT(turns.id) AS total_turns,
				COALESCE(SUM(turns.cash_collected), 0) AS total_cash
			", FALSE)
			->group_by('turns.section_id')
			->group_by('sections.name')
			->order_by('sections.name', 'asc')
			->get()
			->result_array();

		return array(
			'total_turns' => (int) ($summary_row['total_turns'] ?? 0),
			'total_fees' => (float) ($summary_row['total_fees'] ?? 0),
			'total_cash' => (float) ($summary_row['total_cash'] ?? 0),
			'total_turn_topups' => (float) ($summary_row['total_turn_topups'] ?? 0),
			'total_manual_wallet_topups' => $manual_wallet_topups,
			'total_wallet_topups' => $total_wallet_topups,
			'total_patient_income' => round((float) ($summary_row['total_cash'] ?? 0) + $total_wallet_topups, 2),
			'total_wallet_used' => (float) ($summary_row['total_wallet_used'] ?? 0),
			'total_discounts' => (float) ($summary_row['total_discounts'] ?? 0),
			'total_debts' => round($total_debts, 2),
			'debts_by_turn' => $debts_by_turn,
			'income_by_section' => $income_by_section,
		);
	}

	protected function daily_register_base_query($filters)
	{
		$filters = $this->normalize_daily_register_filters($filters);

		$query = $this->db
			->from('turns')
			->join('patients', 'patients.id = turns.patient_id')
			->join('reference_doctors', 'reference_doctors.id = patients.referred_by', 'left')
			->join('sections', 'sections.id = turns.section_id', 'left')
			->join('staff', 'staff.id = turns.staff_id', 'left')
			->where('turns.turn_date >=', $filters['date_from'])
			->where('turns.turn_date <=', $filters['date_to']);

		if ($filters['section_id'] !== NULL) {
			$query->where('turns.section_id', $filters['section_id']);
		}

		if ($filters['gender'] !== NULL) {
			$query->where('patients.gender', $filters['gender']);
		}

		return $query;
	}

	protected function normalize_daily_register_filters($filters)
	{
		$filters = is_array($filters) ? $filters : array();
		$gender = strtolower(trim((string) ($filters['gender'] ?? '')));

		if ($gender === 'male') {
			$gender = 'Male';
		} elseif ($gender === 'female') {
			$gender = 'Female';
		} else {
			$gender = NULL;
		}

		$section_id = isset($filters['section_id']) ? (int) $filters['section_id'] : 0;

		return array(
			'date_from' => trim((string) ($filters['date_from'] ?? '')),
			'date_to' => trim((string) ($filters['date_to'] ?? '')),
			'section_id' => $section_id > 0 ? $section_id : NULL,
			'gender' => $gender,
		);
	}

	protected function get_manual_wallet_topups_total($filters)
	{
		if (
			$filters['section_id'] !== NULL
			|| !$this->db->table_exists('patient_wallet_transactions')
			|| !$this->db->table_exists('patients')
		) {
			return 0.00;
		}

		$rows = $this->db
			->select('patient_wallet_transactions.id, patient_wallet_transactions.amount')
			->from('patient_wallet_transactions')
			->join('patients', 'patients.id = patient_wallet_transactions.patient_id')
			->join(
				'turns',
				"turns.patient_id = patient_wallet_transactions.patient_id
				AND turns.topup_amount = patient_wallet_transactions.amount
				AND turns.topup_amount > 0
				AND DATE(turns.created_at) = DATE(patient_wallet_transactions.created_at)",
				'left',
				FALSE
			)
			->where('patient_wallet_transactions.type', 'topup')
			->where('patient_wallet_transactions.amount >', 0)
			->where('patient_wallet_transactions.turn_id IS NULL', NULL, FALSE)
			->where('DATE(patient_wallet_transactions.created_at) >=', $filters['date_from'])
			->where('DATE(patient_wallet_transactions.created_at) <=', $filters['date_to'])
			->group_start()
				->where("patient_wallet_transactions.note IS NOT NULL AND patient_wallet_transactions.note != ''", NULL, FALSE)
				->or_where('turns.id IS NULL', NULL, FALSE)
			->group_end()
			->group_by('patient_wallet_transactions.id')
			->order_by('patient_wallet_transactions.id', 'asc');

		if ($filters['gender'] !== NULL) {
			$rows->where('patients.gender', $filters['gender']);
		}

		$result = $rows->get()->result_array();
		$total = 0.00;

		foreach ($result as $row) {
			$total += (float) ($row['amount'] ?? 0);
		}

		return round($total, 2);
	}
}
