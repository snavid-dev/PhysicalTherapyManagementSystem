<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store_model extends CI_Model
{
	// ===== Product Categories =====
	public function get_all_categories()
	{
		return $this->db
			->where('is_active', 1)
			->order_by('name', 'asc')
			->get('store_product_categories')
			->result_array();
	}

	public function get_category_by_id($id)
	{
		return $this->db
			->get_where('store_product_categories', array('id' => (int) $id))
			->row_array();
	}

	public function create_category($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');
		$this->db->insert('store_product_categories', $data);
		return $this->db->insert_id();
	}

	public function update_category($id, $data)
	{
		return $this->db
			->where('id', (int) $id)
			->update('store_product_categories', $data);
	}

	public function delete_category($id)
	{
		$has_products = $this->db
			->where('category_id', (int) $id)
			->count_all_results('store_products') > 0;

		if ($has_products) {
			return FALSE;
		}

		return $this->db
			->where('id', (int) $id)
			->delete('store_product_categories');
	}

	// ===== Products & Variants =====
	public function get_all_products()
	{
		return $this->db
			->select('store_products.*, store_product_categories.name as category_name')
			->from('store_products')
			->join('store_product_categories', 'store_products.category_id = store_product_categories.id')
			->where('store_products.is_active', 1)
			->order_by('store_products.name', 'asc')
			->get()
			->result_array();
	}

	public function get_product_by_id($product_id)
	{
		return $this->db
			->get_where('store_products', array('id' => (int) $product_id))
			->row_array();
	}

	public function create_product($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');
		$this->db->insert('store_products', $data);
		return $this->db->insert_id();
	}

	public function update_product($product_id, $data)
	{
		return $this->db
			->where('id', (int) $product_id)
			->update('store_products', $data);
	}

	public function get_variants_by_product($product_id)
	{
		return $this->db
			->where('product_id', (int) $product_id)
			->where('is_active', 1)
			->order_by('variant_label', 'asc')
			->get('store_product_variants')
			->result_array();
	}

	public function get_variant_by_id($variant_id)
	{
		return $this->db
			->get_where('store_product_variants', array('id' => (int) $variant_id))
			->row_array();
	}

	public function create_variant($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');
		$this->db->insert('store_product_variants', $data);
		return $this->db->insert_id();
	}

	public function update_variant($variant_id, $data)
	{
		return $this->db
			->where('id', (int) $variant_id)
			->update('store_product_variants', $data);
	}

	public function get_variant_with_product($variant_id)
	{
		return $this->db
			->select('store_product_variants.*, store_products.name as product_name, store_products.category_id, store_product_categories.name as category_name')
			->from('store_product_variants')
			->join('store_products', 'store_product_variants.product_id = store_products.id')
			->join('store_product_categories', 'store_products.category_id = store_product_categories.id')
			->where('store_product_variants.id', (int) $variant_id)
			->get()
			->row_array();
	}

	// ===== Requisitions =====
	public function get_requisitions($status = NULL)
	{
		$this->db
			->select('stock_requisitions.*, users.first_name, users.last_name, approved_users.first_name as approver_first, approved_users.last_name as approver_last, from_loc.name as from_location, to_loc.name as to_location')
			->from('stock_requisitions')
			->join('users', 'stock_requisitions.requested_by = users.id')
			->join('users as approved_users', 'stock_requisitions.approved_by = approved_users.id', 'left')
			->join('store_locations as from_loc', 'stock_requisitions.from_location_id = from_loc.id')
			->join('store_locations as to_loc', 'stock_requisitions.to_location_id = to_loc.id');

		if ($status !== NULL) {
			$this->db->where('stock_requisitions.status', trim($status));
		}

		return $this->db
			->order_by('stock_requisitions.created_at', 'desc')
			->get()
			->result_array();
	}

	public function get_requisition_by_id($requisition_id)
	{
		return $this->db
			->select('stock_requisitions.*, users.first_name, users.last_name, approved_users.first_name as approver_first, approved_users.last_name as approver_last')
			->from('stock_requisitions')
			->join('users', 'stock_requisitions.requested_by = users.id')
			->join('users as approved_users', 'stock_requisitions.approved_by = approved_users.id', 'left')
			->where('stock_requisitions.id', (int) $requisition_id)
			->get()
			->row_array();
	}

	public function get_requisition_items($requisition_id)
	{
		return $this->db
			->select('stock_requisition_items.*, store_product_variants.variant_label, store_products.name as product_name')
			->from('stock_requisition_items')
			->join('store_product_variants', 'stock_requisition_items.variant_id = store_product_variants.id')
			->join('store_products', 'store_product_variants.product_id = store_products.id')
			->where('stock_requisition_items.requisition_id', (int) $requisition_id)
			->get()
			->result_array();
	}

	public function create_requisition($from_location_id, $to_location_id, $requested_by, $items)
	{
		$this->db->trans_start();

		$req_id = $this->db->insert('stock_requisitions', array(
			'from_location_id' => (int) $from_location_id,
			'to_location_id' => (int) $to_location_id,
			'requested_by' => (int) $requested_by,
			'status' => 'pending',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		));

		$req_id = $this->db->insert_id();

		foreach ($items as $item) {
			$this->db->insert('stock_requisition_items', array(
				'requisition_id' => $req_id,
				'variant_id' => (int) $item['variant_id'],
				'qty_requested' => (int) $item['qty']
			));
		}

		$this->db->trans_complete();

		return $this->db->trans_status() ? $req_id : FALSE;
	}

	public function approve_requisition($requisition_id, $approved_by, $items_approved)
	{
		$this->db->trans_start();

		$requisition = $this->get_requisition_by_id($requisition_id);
		if (!$requisition || $requisition['status'] !== 'pending') {
			$this->db->trans_rollback();
			return FALSE;
		}

		foreach ($items_approved as $item_id => $qty_approved) {
			$this->db
				->where('id', (int) $item_id)
				->update('stock_requisition_items', array('qty_approved' => (int) $qty_approved));
		}

		$this->db
			->where('id', (int) $requisition_id)
			->update('stock_requisitions', array(
				'status' => 'approved',
				'approved_by' => (int) $approved_by,
				'updated_at' => date('Y-m-d H:i:s')
			));

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function reject_requisition($requisition_id, $approved_by, $reason)
	{
		return $this->db
			->where('id', (int) $requisition_id)
			->update('stock_requisitions', array(
				'status' => 'rejected',
				'reject_reason' => trim($reason),
				'approved_by' => (int) $approved_by,
				'updated_at' => date('Y-m-d H:i:s')
			));
	}

	public function update_requisition_status($requisition_id, $status)
	{
		return $this->db
			->where('id', (int) $requisition_id)
			->update('stock_requisitions', array(
				'status' => trim($status),
				'updated_at' => date('Y-m-d H:i:s')
			));
	}

	public function update_requisition_item_received($item_id, $qty_received)
	{
		return $this->db
			->where('id', (int) $item_id)
			->update('stock_requisition_items', array('qty_received' => (int) $qty_received));
	}

	// ===== Sales =====
	public function create_sale($patient_id, $location_id, $sold_by, $subtotal, $discount, $tax, $total, $payment_method, $items, $payment_id = NULL)
	{
		$this->db->trans_start();

		$sale_id = $this->db->insert('store_sales', array(
			'patient_id' => $patient_id ? (int) $patient_id : NULL,
			'location_id' => (int) $location_id,
			'sold_by' => (int) $sold_by,
			'subtotal' => round((float) $subtotal, 2),
			'discount' => round((float) $discount, 2),
			'tax' => round((float) $tax, 2),
			'total' => round((float) $total, 2),
			'payment_method' => trim($payment_method),
			'status' => 'completed',
			'payment_id' => $payment_id ? (int) $payment_id : NULL,
			'created_at' => date('Y-m-d H:i:s')
		));

		$sale_id = $this->db->insert_id();

		foreach ($items as $item) {
			$this->db->insert('store_sale_items', array(
				'sale_id' => $sale_id,
				'variant_id' => (int) $item['variant_id'],
				'qty' => (int) $item['qty'],
				'unit_price' => round((float) $item['unit_price'], 2),
				'discount' => round((float) ($item['discount'] ?? 0), 2),
				'line_total' => round((float) $item['line_total'], 2),
				'unit_cost_at_sale' => round((float) $item['unit_cost_at_sale'], 2)
			));
		}

		$this->db->trans_complete();

		return $this->db->trans_status() ? $sale_id : FALSE;
	}

	public function get_sales($patient_id = NULL, $limit = 100, $offset = 0)
	{
		$this->db
			->select('store_sales.*, users.first_name, users.last_name')
			->from('store_sales')
			->join('users', 'store_sales.sold_by = users.id');

		if ($patient_id !== NULL) {
			$this->db->where('store_sales.patient_id', (int) $patient_id);
		}

		return $this->db
			->order_by('store_sales.created_at', 'desc')
			->limit($limit, $offset)
			->get()
			->result_array();
	}

	public function get_sale_by_id($sale_id)
	{
		return $this->db
			->get_where('store_sales', array('id' => (int) $sale_id))
			->row_array();
	}

	public function get_sale_items($sale_id)
	{
		return $this->db
			->select('store_sale_items.*, store_product_variants.variant_label, store_products.name as product_name')
			->from('store_sale_items')
			->join('store_product_variants', 'store_sale_items.variant_id = store_product_variants.id')
			->join('store_products', 'store_product_variants.product_id = store_products.id')
			->where('store_sale_items.sale_id', (int) $sale_id)
			->get()
			->result_array();
	}
}
