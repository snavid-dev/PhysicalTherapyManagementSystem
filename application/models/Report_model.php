<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model
{
	public function get_new_patients_in_range($from, $to)
	{
		$from = trim((string) $from);
		$to = trim((string) $to);

		if ($from === '' || $to === '') {
			return array();
		}

		return $this->db
			->select("patients.id, patients.first_name, patients.last_name, patients.father_name, patients.gender, patients.phone, patients.phone2, patients.created_at,
				TRIM(CONCAT(reference_doctors.first_name, ' ', COALESCE(reference_doctors.last_name, ''))) AS referred_by_name", FALSE)
			->from('patients')
			->join('reference_doctors', 'reference_doctors.id = patients.referred_by', 'left')
			->where('DATE(patients.created_at) >=', $from)
			->where('DATE(patients.created_at) <=', $to)
			->order_by('patients.created_at', 'desc')
			->order_by('patients.id', 'desc')
			->get()
			->result_array();
	}

	public function get_debtors($from = '', $to = '')
	{
		$wallet_table = $this->db->table_exists('patient_wallet');
		$debt_table = $this->db->table_exists('patient_debts');

		$this->db
			->select("
				patients.id,
				patients.first_name,
				patients.last_name,
				patients.father_name,
				patients.phone,
				COALESCE(wallet.balance, 0) AS wallet_balance,
				COALESCE(debts.open_debt, 0) AS open_debt,
				last_turn.last_turn_date
			", FALSE)
			->from('patients');

		if ($wallet_table) {
			$this->db->join('(SELECT patient_id, balance FROM patient_wallet) AS wallet', 'wallet.patient_id = patients.id', 'left', FALSE);
		} else {
			$this->db->join('(SELECT NULL AS patient_id, 0 AS balance) AS wallet', '1 = 0', 'left', FALSE);
		}

		if ($debt_table) {
			$this->db->join("
				(
					SELECT patient_id, COALESCE(SUM(amount), 0) AS open_debt
					FROM patient_debts
					WHERE status = 'open'
					GROUP BY patient_id
				) AS debts
			", 'debts.patient_id = patients.id', 'left', FALSE);
		} else {
			$this->db->join('(SELECT NULL AS patient_id, 0 AS open_debt) AS debts', '1 = 0', 'left', FALSE);
		}

		$this->db->join("
			(
				SELECT patient_id, MAX(turn_date) AS last_turn_date
				FROM turns
				GROUP BY patient_id
			) AS last_turn
		", 'last_turn.patient_id = patients.id', 'left', FALSE);

		// Real debtors only: wallet < 0 OR open debt > 0 after M1 reconciliation.
		$this->db->group_start()
			->where('COALESCE(wallet.balance, 0) <', 0, FALSE)
			->or_where('COALESCE(debts.open_debt, 0) >', 0, FALSE)
		->group_end();

		if ($from !== '') {
			$this->db->where('last_turn.last_turn_date >=', $from);
		}

		if ($to !== '') {
			$this->db->where('last_turn.last_turn_date <=', $to);
		}

		return $this->db
			->order_by('COALESCE(debts.open_debt, 0)', 'desc', FALSE)
			->order_by('COALESCE(wallet.balance, 0)', 'asc', FALSE)
			->order_by('patients.first_name', 'asc')
			->order_by('patients.last_name', 'asc')
			->get()
			->result_array();
	}

	public function get_outstanding_balances($filters = array())
	{
		$filters = $this->normalize_patient_report_filters($filters);
		$wallet_table = $this->db->table_exists('patient_wallet');
		$debt_table = $this->db->table_exists('patient_debts');

		$this->db
			->select("
				patients.id,
				patients.first_name,
				patients.last_name,
				patients.phone,
				patients.phone2,
				COALESCE(wallet.balance, 0) AS wallet_balance,
				COALESCE(debts.open_debt, 0) AS open_debt,
				last_turn.last_turn_date
			", FALSE)
			->from('patients');

		if ($wallet_table) {
			$this->db->join('(SELECT patient_id, balance FROM patient_wallet) AS wallet', 'wallet.patient_id = patients.id', 'left', FALSE);
		} else {
			$this->db->join('(SELECT NULL AS patient_id, 0 AS balance) AS wallet', '1 = 0', 'left', FALSE);
		}

		if ($debt_table) {
			$this->db->join("
				(
					SELECT patient_id, COALESCE(SUM(amount), 0) AS open_debt
					FROM patient_debts
					WHERE status = 'open'
					GROUP BY patient_id
				) AS debts
			", 'debts.patient_id = patients.id', 'left', FALSE);
		} else {
			$this->db->join('(SELECT NULL AS patient_id, 0 AS open_debt) AS debts', '1 = 0', 'left', FALSE);
		}

		$this->db->join("
			(
				SELECT patient_id, MAX(turn_date) AS last_turn_date
				FROM turns
				GROUP BY patient_id
			) AS last_turn
		", 'last_turn.patient_id = patients.id', 'left', FALSE);

		$this->apply_patient_search_filter($filters['search']);

		switch ($filters['status']) {
			case 'negative_wallet':
				$this->db->where('COALESCE(wallet.balance, 0) <', 0, FALSE);
				break;

			case 'debt':
				$this->db->where('COALESCE(debts.open_debt, 0) >', 0, FALSE);
				break;

			case 'both':
				$this->db->where('COALESCE(wallet.balance, 0) <', 0, FALSE);
				$this->db->where('COALESCE(debts.open_debt, 0) >', 0, FALSE);
				break;

			default:
				$this->db->group_start()
					->where('COALESCE(wallet.balance, 0) <', 0, FALSE)
					->or_where('COALESCE(debts.open_debt, 0) >', 0, FALSE)
				->group_end();
				break;
		}

		return $this->db
			->order_by('COALESCE(debts.open_debt, 0)', 'desc', FALSE)
			->order_by('COALESCE(wallet.balance, 0)', 'asc', FALSE)
			->order_by('patients.first_name', 'asc')
			->order_by('patients.last_name', 'asc')
			->get()
			->result_array();
	}

	public function get_patient_financial_summary($filters = array())
	{
		$filters = $this->normalize_patient_report_filters($filters);
		$wallet_table = $this->db->table_exists('patient_wallet');
		$debt_table = $this->db->table_exists('patient_debts');
		$turn_summary_sql = $this->patient_turn_summary_subquery($filters['from'], $filters['to']);

		$this->db
			->select("
				patients.id,
				patients.first_name,
				patients.last_name,
				patients.phone,
				patients.phone2,
				COALESCE(wallet.balance, 0) AS wallet_balance,
				COALESCE(debts.open_debt, 0) AS open_debt,
				COALESCE(turn_summary.total_turns, 0) AS total_turns,
				COALESCE(turn_summary.total_turn_fees, 0) AS total_turn_fees,
				turn_summary.last_turn_date
			", FALSE)
			->from('patients');

		if ($wallet_table) {
			$this->db->join('(SELECT patient_id, balance FROM patient_wallet) AS wallet', 'wallet.patient_id = patients.id', 'left', FALSE);
		} else {
			$this->db->join('(SELECT NULL AS patient_id, 0 AS balance) AS wallet', '1 = 0', 'left', FALSE);
		}

		if ($debt_table) {
			$this->db->join("
				(
					SELECT patient_id, COALESCE(SUM(amount), 0) AS open_debt
					FROM patient_debts
					WHERE status = 'open'
					GROUP BY patient_id
				) AS debts
			", 'debts.patient_id = patients.id', 'left', FALSE);
		} else {
			$this->db->join('(SELECT NULL AS patient_id, 0 AS open_debt) AS debts', '1 = 0', 'left', FALSE);
		}

		$this->db->join('(' . $turn_summary_sql . ') AS turn_summary', 'turn_summary.patient_id = patients.id', 'left', FALSE);

		$this->apply_patient_search_filter($filters['search']);

		return $this->db
			->order_by('COALESCE(turn_summary.total_turns, 0)', 'desc', FALSE)
			->order_by('turn_summary.last_turn_date', 'desc')
			->order_by('patients.first_name', 'asc')
			->order_by('patients.last_name', 'asc')
			->get()
			->result_array();
	}

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
		// Ensure the leave table has been migrated to its staff-based shape before
		// we join it on staff_id (Leave_model owns that one-time migration).
		$this->load->model('Leave_model');
		$this->Leave_model->ensure_schema();

		return $this->db
			->select('doctor_leaves.*, staff.first_name, staff.last_name')
			->from('doctor_leaves')
			->join('staff', 'staff.id = doctor_leaves.staff_id')
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
				turns.topup_amount,
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
				COALESCE(SUM(turns.cash_collected), 0) AS total_cash,
				COALESCE(SUM(turns.topup_amount), 0) AS total_wallet_topups,
				COALESCE(SUM(turns.cash_collected + turns.topup_amount), 0) AS total_received
			", FALSE)
			->group_by('turns.section_id')
			->group_by('sections.name')
			->order_by('sections.name', 'asc')
			->get()
			->result_array();

		$safe_in_out = $this->get_safe_in_out_totals($filters);
		$total_debt_payments = (float) $safe_in_out['debt_payments'];
		$total_refunds = (float) $safe_in_out['refunds'];
		$cash_in = round((float) ($summary_row['total_cash'] ?? 0), 2);
		$net_patient_income = round($cash_in + $total_wallet_topups + $total_debt_payments - $total_refunds, 2);

		return array(
			'total_turns' => (int) ($summary_row['total_turns'] ?? 0),
			'total_fees' => (float) ($summary_row['total_fees'] ?? 0),
			'total_cash' => $cash_in,
			'total_turn_topups' => (float) ($summary_row['total_turn_topups'] ?? 0),
			'total_manual_wallet_topups' => $manual_wallet_topups,
			'total_wallet_topups' => $total_wallet_topups,
			'total_debt_payments' => round($total_debt_payments, 2),
			'total_refunds' => round($total_refunds, 2),
			'total_patient_income' => $net_patient_income,
			'total_wallet_used' => (float) ($summary_row['total_wallet_used'] ?? 0),
			'total_discounts' => (float) ($summary_row['total_discounts'] ?? 0),
			'total_debts' => round($total_debts, 2),
			'debts_by_turn' => $debts_by_turn,
			'income_by_section' => $income_by_section,
		);
	}

	protected function get_safe_in_out_totals($filters)
	{
		$filters = $this->normalize_daily_register_filters($filters);
		$result = array('debt_payments' => 0.00, 'refunds' => 0.00);

		if (
			!$this->db->table_exists('safe_transactions')
			|| $filters['gender'] !== NULL
			|| $filters['date_from'] === ''
			|| $filters['date_to'] === ''
		) {
			return $result;
		}

		// Section/staff filters need the columns this feature added; skip the
		// whole breakdown on older schemas rather than silently ignoring the filter.
		if (
			(!empty($filters['section_ids']) || !empty($filters['staff_ids']))
			&& (!$this->db->field_exists('section_id', 'safe_transactions') || !$this->db->field_exists('staff_id', 'safe_transactions'))
		) {
			return $result;
		}

		$query = $this->db
			->select("
				COALESCE(SUM(CASE WHEN source = 'patient_debt_payment' AND type = 'in' THEN amount ELSE 0 END), 0) AS debt_payments,
				COALESCE(SUM(CASE WHEN source = 'patient_refund' AND type = 'out' THEN amount ELSE 0 END), 0) AS refunds
			", FALSE)
			->from('safe_transactions')
			// Compare against the indexed created_at column directly (not wrapped in
			// DATE()) so this can use safe_transactions_created_at_index instead of
			// scanning the whole table.
			->where('safe_transactions.created_at >=', $filters['date_from'] . ' 00:00:00')
			->where('safe_transactions.created_at <=', $filters['date_to'] . ' 23:59:59');

		if (!empty($filters['section_ids'])) {
			$query->where_in('safe_transactions.section_id', $filters['section_ids']);
		}

		if (!empty($filters['staff_ids'])) {
			$query->where_in('safe_transactions.staff_id', $filters['staff_ids']);
		}

		$row = $query->get()->row_array();

		if ($row) {
			$result['debt_payments'] = round((float) $row['debt_payments'], 2);
			$result['refunds'] = round((float) $row['refunds'], 2);
		}

		return $result;
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

		if (!empty($filters['section_ids'])) {
			$query->where_in('turns.section_id', $filters['section_ids']);
		}

		if (!empty($filters['staff_ids'])) {
			$query->where_in('turns.staff_id', $filters['staff_ids']);
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

		$section_ids = array();
		if (!empty($filters['section_ids']) && is_array($filters['section_ids'])) {
			foreach ($filters['section_ids'] as $section_id) {
				$section_id = (int) $section_id;
				if ($section_id > 0) {
					$section_ids[$section_id] = $section_id;
				}
			}
		} elseif (isset($filters['section_id'])) {
			$section_id = (int) $filters['section_id'];
			if ($section_id > 0) {
				$section_ids[$section_id] = $section_id;
			}
		}

		$staff_ids = array();
		if (!empty($filters['staff_ids']) && is_array($filters['staff_ids'])) {
			foreach ($filters['staff_ids'] as $staff_id) {
				$staff_id = (int) $staff_id;
				if ($staff_id > 0) {
					$staff_ids[$staff_id] = $staff_id;
				}
			}
		}

		return array(
			'date_from' => trim((string) ($filters['date_from'] ?? '')),
			'date_to' => trim((string) ($filters['date_to'] ?? '')),
			'section_ids' => array_values($section_ids),
			'staff_ids' => array_values($staff_ids),
			'gender' => $gender,
		);
	}

	protected function get_manual_wallet_topups_total($filters)
	{
		if (
			!$this->db->table_exists('patient_wallet_transactions')
			|| !$this->db->table_exists('patients')
		) {
			return 0.00;
		}

		// Section/staff filters need the columns this feature added; skip the whole
		// breakdown on older schemas rather than silently ignoring the filter.
		if (
			(!empty($filters['section_ids']) || !empty($filters['staff_ids']))
			&& (!$this->db->field_exists('section_id', 'patient_wallet_transactions') || !$this->db->field_exists('staff_id', 'patient_wallet_transactions'))
		) {
			return 0.00;
		}

		$query = $this->db
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

		if ($this->db->field_exists('fund_type', 'patient_wallet_transactions')) {
			$query->where('patient_wallet_transactions.fund_type', 'cash_topup');
		}

		if (!empty($filters['section_ids'])) {
			$query->where_in('patient_wallet_transactions.section_id', $filters['section_ids']);
		}

		if (!empty($filters['staff_ids'])) {
			$query->where_in('patient_wallet_transactions.staff_id', $filters['staff_ids']);
		}

		if ($filters['gender'] !== NULL) {
			$query->where('patients.gender', $filters['gender']);
		}

		$result = $query->get()->result_array();
		$total = 0.00;

		foreach ($result as $row) {
			$total += (float) ($row['amount'] ?? 0);
		}

		return round($total, 2);
	}

	protected function normalize_patient_report_filters($filters)
	{
		$filters = is_array($filters) ? $filters : array();
		$status = strtolower(trim((string) ($filters['status'] ?? 'all')));

		if (!in_array($status, array('all', 'negative_wallet', 'debt', 'both'), TRUE)) {
			$status = 'all';
		}

		return array(
			'status' => $status,
			'search' => trim((string) ($filters['search'] ?? '')),
			'from' => trim((string) ($filters['from'] ?? '')),
			'to' => trim((string) ($filters['to'] ?? '')),
		);
	}

	protected function apply_patient_search_filter($search)
	{
		$search = trim((string) $search);

		if ($search === '') {
			return;
		}

		foreach (preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY) as $term) {
			$this->db->group_start()
				->like('patients.first_name', $term)
				->or_like('patients.last_name', $term)
				->or_like('patients.father_name', $term)
				->or_like('patients.phone', $term)
				->or_like('patients.phone2', $term)
			->group_end();
		}
	}

	protected function patient_turn_summary_subquery($from, $to)
	{
		$from = trim((string) $from);
		$to = trim((string) $to);
		$where = array();

		if ($from !== '') {
			$where[] = "turn_date >= " . $this->db->escape($from);
		}

		if ($to !== '') {
			$where[] = "turn_date <= " . $this->db->escape($to);
		}

		$sql = "
			SELECT
				patient_id,
				COUNT(id) AS total_turns,
				COALESCE(SUM(fee), 0) AS total_turn_fees,
				MAX(turn_date) AS last_turn_date
			FROM turns
		";

		if (!empty($where)) {
			$sql .= ' WHERE ' . implode(' AND ', $where);
		}

		$sql .= ' GROUP BY patient_id';

		return $sql;
	}
}
