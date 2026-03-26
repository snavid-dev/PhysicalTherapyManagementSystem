<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Safe_model');
	}

	public function all()
	{
		return $this->db
			->select('payments.*, patients.first_name, patients.last_name')
			->from('payments')
			->join('patients', 'patients.id = payments.patient_id')
			->order_by('payments.payment_date', 'desc')
			->order_by('payments.id', 'desc')
			->get()
			->result_array();
	}

	public function find($id)
	{
		return $this->db->get_where('payments', array('id' => (int) $id))->row_array();
	}

	public function find_with_patient($id)
	{
		return $this->db
			->select('payments.*, patients.first_name, patients.last_name, patients.father_name')
			->from('payments')
			->join('patients', 'patients.id = payments.patient_id')
			->where('payments.id', (int) $id)
			->limit(1)
			->get()
			->row_array();
	}

	public function create($data)
	{
		$this->db->insert('payments', $data);
		$payment_id = $this->db->insert_id();

		if ($payment_id) {
			$note = trim((string) ($data['notes'] ?? ''));

			if ($note === '') {
				$note = safe_patient_payment_note($payment_id);
			}

			$this->Safe_model->log_transaction(
				'in',
				'patient_payment',
				$data['amount'] ?? 0,
				$payment_id,
				'payments',
				$note,
				$this->session->userdata('user_id'),
				$this->datetime_from_date($data['payment_date'] ?? NULL)
			);
		}

		return $payment_id;
	}

	public function update($id, $data)
	{
		return $this->db->where('id', (int) $id)->update('payments', $data);
	}

	public function delete($id)
	{
		$id = (int) $id;

		$this->db->trans_begin();

		$safe_deleted = $this->Safe_model->delete_transaction_by_reference('payments', $id, 'patient_payment');
		$deleted = $this->db->where('id', $id)->delete('payments');

		if ($safe_deleted === FALSE || !$deleted || $this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();

		return TRUE;
	}

	public function total_received()
	{
		$row = $this->db->select_sum('amount')->get('payments')->row_array();
		return (float) ($row['amount'] ?: 0);
	}

	protected function datetime_from_date($date)
	{
		$date = trim((string) $date);
		$parsed = DateTime::createFromFormat('Y-m-d', $date);

		if ($parsed && $parsed->format('Y-m-d') === $date) {
			return $date . ' 12:00:00';
		}

		return NULL;
	}
}
