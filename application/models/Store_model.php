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
			->select('stock_requisitions.*, users.first_name, users.last_name, approved_users.first_name as approver_first, approved_users.last_name as approver_last, from_loc.name as from_location, to_loc.name as to_location')
			->from('stock_requisitions')
			->join('users', 'stock_requisitions.requested_by = users.id')
			->join('users as approved_users', 'stock_requisitions.approved_by = approved_users.id', 'left')
			->join('store_locations as from_loc', 'stock_requisitions.from_location_id = from_loc.id')
			->join('store_locations as to_loc', 'stock_requisitions.to_location_id = to_loc.id')
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
	public function create_sale($patient_id, $location_id, $sold_by, $subtotal, $discount, $tax, $total, $payment_method, $items, $payment_id = NULL, $customer_name = NULL, $customer_phone = NULL)
	{
		$this->db->trans_start();

		$payment_method = trim($payment_method);

		$sale_id = $this->db->insert('store_sales', array(
			'patient_id' => $patient_id ? (int) $patient_id : NULL,
			'customer_name' => $customer_name ? trim($customer_name) : NULL,
			'customer_phone' => $customer_phone ? trim($customer_phone) : NULL,
			'location_id' => (int) $location_id,
			'sold_by' => (int) $sold_by,
			'subtotal' => round((float) $subtotal, 2),
			'discount' => round((float) $discount, 2),
			'tax' => round((float) $tax, 2),
			'total' => round((float) $total, 2),
			'payment_method' => $payment_method,
			'status' => 'completed',
			'debt_status' => $payment_method === 'debt' ? 'open' : 'none',
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

	public function clear_sale_debt($sale_id, $user_id)
	{
		return $this->db
			->where('id', (int) $sale_id)
			->where('payment_method', 'debt')
			->where('debt_status', 'open')
			->update('store_sales', array(
				'debt_status' => 'cleared',
				'debt_cleared_at' => date('Y-m-d H:i:s'),
				'debt_cleared_by' => (int) $user_id
			));
	}

	public function count_open_debts()
	{
		return $this->db
			->where('payment_method', 'debt')
			->where('debt_status', 'open')
			->count_all_results('store_sales');
	}

	// ===== Reports =====
	// Item-level rows: the natural base for revenue/cost/profit and
	// product-level filtering (a product filter needs to see individual
	// line items, not whole sales).
	public function get_sales_report_rows($filters = array())
	{
		$this->db
			->select('store_sales.id as sale_id, store_sales.created_at, store_sales.payment_method, store_sales.patient_id, store_sales.sold_by, store_sale_items.qty, store_sale_items.line_total, store_sale_items.unit_cost_at_sale, users.first_name, users.last_name')
			->from('store_sale_items')
			->join('store_sales', 'store_sale_items.sale_id = store_sales.id')
			->join('store_product_variants', 'store_sale_items.variant_id = store_product_variants.id')
			->join('users', 'store_sales.sold_by = users.id');

		$this->apply_sale_filters($filters, TRUE);

		return $this->db->get()->result_array();
	}

	// Sale-level rows for the reports detail table (drives the debt "clear" action).
	public function get_sales_list($filters = array(), $limit = 300)
	{
		// CI3's query builder is one stateful object on $this->db, so a
		// sub-select must be fully compiled (get_compiled_select() resets the
		// builder) before the outer select/from/join chain starts.
		$product_subquery = NULL;
		if (!empty($filters['product_id'])) {
			$product_subquery = $this->db->select('sale_id')
				->from('store_sale_items')
				->join('store_product_variants', 'store_sale_items.variant_id = store_product_variants.id')
				->where('store_product_variants.product_id', (int) $filters['product_id'])
				->get_compiled_select();
		}

		$this->db
			->select('store_sales.*, users.first_name, users.last_name, patients.first_name as patient_first_name, patients.last_name as patient_last_name')
			->from('store_sales')
			->join('users', 'store_sales.sold_by = users.id')
			->join('patients', 'store_sales.patient_id = patients.id', 'left');

		if ($product_subquery !== NULL) {
			$this->db->where('store_sales.id IN (' . $product_subquery . ')', NULL, FALSE);
		}

		$this->apply_sale_filters($filters, FALSE);

		return $this->db->order_by('store_sales.created_at', 'desc')->limit($limit)->get()->result_array();
	}

	protected function apply_sale_filters($filters, $via_items)
	{
		if (!empty($filters['date_from'])) {
			$this->db->where('store_sales.created_at >=', $filters['date_from'] . ' 00:00:00');
		}
		if (!empty($filters['date_to'])) {
			$this->db->where('store_sales.created_at <=', $filters['date_to'] . ' 23:59:59');
		}
		if (!empty($filters['payment_method'])) {
			$this->db->where('store_sales.payment_method', trim($filters['payment_method']));
		}
		if (!empty($filters['customer_type'])) {
			if ($filters['customer_type'] === 'patient') {
				$this->db->where('store_sales.patient_id IS NOT NULL', NULL, FALSE);
			} elseif ($filters['customer_type'] === 'external') {
				$this->db->where('store_sales.patient_id IS NULL', NULL, FALSE);
			}
		}
		if ($via_items && !empty($filters['product_id'])) {
			$this->db->where('store_product_variants.product_id', (int) $filters['product_id']);
		}
	}

	// ===== Sale Batches (bulk sell, manager approval) =====
	public function create_sale_batch($created_by, $customers, $note = NULL)
	{
		$this->db->trans_start();

		$total_amount = 0;
		foreach ($customers as $customer) {
			foreach ($customer['items'] as $item) {
				$total_amount += (int) $item['qty'] * (float) $item['unit_price'];
			}
		}

		$this->db->insert('store_sale_batches', array(
			'created_by' => (int) $created_by,
			'status' => 'pending',
			'total_amount' => round($total_amount, 2),
			'note' => $note ? trim($note) : NULL,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		));
		$batch_id = $this->db->insert_id();

		foreach ($customers as $customer) {
			$this->db->insert('store_sale_batch_customers', array(
				'batch_id' => $batch_id,
				'patient_id' => $customer['patient_id'] ? (int) $customer['patient_id'] : NULL,
				'customer_name' => $customer['patient_id'] ? NULL : trim($customer['customer_name']),
				'customer_phone' => $customer['patient_id'] ? NULL : ($customer['customer_phone'] ? trim($customer['customer_phone']) : NULL),
				'payment_method' => trim($customer['payment_method'])
			));
			$batch_customer_id = $this->db->insert_id();

			foreach ($customer['items'] as $item) {
				$this->db->insert('store_sale_batch_items', array(
					'batch_customer_id' => $batch_customer_id,
					'variant_id' => (int) $item['variant_id'],
					'qty' => (int) $item['qty'],
					'unit_price' => round((float) $item['unit_price'], 2)
				));
			}
		}

		$this->db->trans_complete();
		return $this->db->trans_status() ? $batch_id : FALSE;
	}

	public function get_sale_batches($status = NULL)
	{
		$this->db
			->select('store_sale_batches.*, users.first_name, users.last_name, approved_users.first_name as approver_first, approved_users.last_name as approver_last')
			->from('store_sale_batches')
			->join('users', 'store_sale_batches.created_by = users.id')
			->join('users as approved_users', 'store_sale_batches.approved_by = approved_users.id', 'left');

		if ($status !== NULL) {
			$this->db->where('store_sale_batches.status', trim($status));
		}

		return $this->db
			->order_by('store_sale_batches.created_at', 'desc')
			->get()
			->result_array();
	}

	public function get_sale_batch_by_id($batch_id)
	{
		return $this->db
			->select('store_sale_batches.*, users.first_name, users.last_name')
			->from('store_sale_batches')
			->join('users', 'store_sale_batches.created_by = users.id')
			->where('store_sale_batches.id', (int) $batch_id)
			->get()
			->row_array();
	}

	public function get_batch_customers($batch_id)
	{
		return $this->db
			->select("store_sale_batch_customers.*, patients.first_name, patients.last_name")
			->from('store_sale_batch_customers')
			->join('patients', 'store_sale_batch_customers.patient_id = patients.id', 'left')
			->where('store_sale_batch_customers.batch_id', (int) $batch_id)
			->get()
			->result_array();
	}

	public function get_batch_items($batch_customer_id)
	{
		return $this->db
			->select('store_sale_batch_items.*, store_product_variants.variant_label, store_products.name as product_name')
			->from('store_sale_batch_items')
			->join('store_product_variants', 'store_sale_batch_items.variant_id = store_product_variants.id')
			->join('store_products', 'store_product_variants.product_id = store_products.id')
			->where('store_sale_batch_items.batch_customer_id', (int) $batch_customer_id)
			->get()
			->result_array();
	}

	public function set_batch_customer_sale_id($batch_customer_id, $sale_id)
	{
		return $this->db
			->where('id', (int) $batch_customer_id)
			->update('store_sale_batch_customers', array('sale_id' => (int) $sale_id));
	}

	public function update_sale_batch_status($batch_id, $status, $approved_by, $reject_reason = NULL)
	{
		return $this->db
			->where('id', (int) $batch_id)
			->update('store_sale_batches', array(
				'status' => trim($status),
				'approved_by' => (int) $approved_by,
				'reject_reason' => $reject_reason ? trim($reject_reason) : NULL,
				'updated_at' => date('Y-m-d H:i:s')
			));
	}

	// ===== Suppliers =====
	public function get_all_suppliers()
	{
		return $this->db
			->where('is_active', 1)
			->order_by('name', 'asc')
			->get('store_suppliers')
			->result_array();
	}

	public function get_supplier_by_id($id)
	{
		return $this->db
			->get_where('store_suppliers', array('id' => (int) $id))
			->row_array();
	}

	public function create_supplier($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');
		$this->db->insert('store_suppliers', $data);
		return $this->db->insert_id();
	}

	public function update_supplier($id, $data)
	{
		return $this->db
			->where('id', (int) $id)
			->update('store_suppliers', $data);
	}

	// ===== Stock Receipts =====
	public function create_stock_receipt($supplier_id, $received_by, $items, $note = NULL)
	{
		$this->db->trans_start();

		$total_cost = 0;
		foreach ($items as $item) {
			$total_cost += (int) $item['qty'] * round((float) $item['unit_cost'], 2);
		}

		$receipt_id = $this->db->insert('store_stock_receipts', array(
			'supplier_id' => $supplier_id ? (int) $supplier_id : NULL,
			'received_by' => (int) $received_by,
			'note' => $note ? trim($note) : NULL,
			'created_at' => date('Y-m-d H:i:s')
		));

		$receipt_id = $this->db->insert_id();

		foreach ($items as $item) {
			$this->db->insert('store_stock_receipt_items', array(
				'receipt_id' => $receipt_id,
				'variant_id' => (int) $item['variant_id'],
				'qty' => (int) $item['qty'],
				'unit_cost' => round((float) $item['unit_cost'], 2)
			));

			$this->db->where('id', (int) $item['variant_id'])
				->update('store_product_variants', array(
					'cost_price' => round((float) $item['unit_cost'], 2)
				));
		}

		$expense_data = array(
			'category_id' => $this->get_inventory_purchase_category_id(),
			'amount' => $total_cost,
			'description' => 'Stock receipt #' . $receipt_id . ($supplier_id ? ' from supplier' : ''),
			'created_by' => (int) $received_by,
			'expense_date' => date('Y-m-d')
		);

		$this->db->insert('expenses', $expense_data);
		$expense_id = $this->db->insert_id();

		$this->db
			->where('id', $receipt_id)
			->update('store_stock_receipts', array('expense_id' => $expense_id));

		$this->db->trans_complete();

		return $this->db->trans_status() ? $receipt_id : FALSE;
	}

	protected function get_inventory_purchase_category_id()
	{
		$category = $this->db
			->where('name', 'Inventory Purchase')
			->get('expense_categories')
			->row_array();
		return $category ? $category['id'] : 3;
	}

	public function get_stock_receipts($limit = 50, $offset = 0)
	{
		return $this->db
			->select('store_stock_receipts.*, users.first_name, users.last_name, store_suppliers.name as supplier_name')
			->from('store_stock_receipts')
			->join('users', 'store_stock_receipts.received_by = users.id')
			->join('store_suppliers', 'store_stock_receipts.supplier_id = store_suppliers.id', 'left')
			->order_by('store_stock_receipts.created_at', 'desc')
			->limit($limit, $offset)
			->get()
			->result_array();
	}

	public function get_receipt_by_id($receipt_id)
	{
		return $this->db
			->select('store_stock_receipts.*, users.first_name, users.last_name, store_suppliers.name as supplier_name')
			->from('store_stock_receipts')
			->join('users', 'store_stock_receipts.received_by = users.id')
			->join('store_suppliers', 'store_stock_receipts.supplier_id = store_suppliers.id', 'left')
			->where('store_stock_receipts.id', (int) $receipt_id)
			->get()
			->row_array();
	}

	public function get_receipt_items($receipt_id)
	{
		return $this->db
			->select('store_stock_receipt_items.*, store_product_variants.variant_label, store_products.name as product_name')
			->from('store_stock_receipt_items')
			->join('store_product_variants', 'store_stock_receipt_items.variant_id = store_product_variants.id')
			->join('store_products', 'store_product_variants.product_id = store_products.id')
			->where('store_stock_receipt_items.receipt_id', (int) $receipt_id)
			->get()
			->result_array();
	}
}
