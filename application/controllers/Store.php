<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store extends Authenticated_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Store_model');
		$this->load->model('Inventory_model');
		$this->auth->require_permission('view_store');
	}

	public function index()
	{
		$data['current_section'] = 'store';

		if ($this->auth->has_permission('manage_store')) {
			$today = date('Y-m-d');
			$rows = $this->Store_model->get_sales_report_rows(array('date_from' => $today, 'date_to' => $today));
			$revenue_today = 0;
			$sale_ids_today = array();
			foreach ($rows as $row) {
				$revenue_today += (float) $row['line_total'];
				$sale_ids_today[$row['sale_id']] = TRUE;
			}
			$data['today_revenue'] = $revenue_today;
			$data['today_sales_count'] = count($sale_ids_today);
			$data['open_debt_count'] = $this->Store_model->count_open_debts();
		}

		$this->render('store/index', $data);
	}

	public function categories()
	{
		$this->require_permission('manage_store');
		$data['categories'] = $this->Store_model->get_all_categories();
		$data['current_section'] = 'store';
		$this->render('store/categories', $data);
	}

	public function create_category()
	{
		$this->require_permission('manage_store');

		if ($this->input->method() === 'post') {
			$name = trim($this->input->post('name'));

			if (empty($name)) {
				$this->session->set_flashdata('error', t('category_name_required'));
				redirect('store/categories');
			}

			$this->Store_model->create_category(array(
				'name' => $name,
				'is_active' => 1
			));

			$this->session->set_flashdata('success', t('category_created'));
			redirect('store/categories');
		}

		$data['current_section'] = 'store';
		$this->render('store/category_form', $data);
	}

	public function edit_category($id)
	{
		$this->require_permission('manage_store');

		$data['category'] = $this->Store_model->get_category_by_id($id);
		if (!$data['category']) {
			show_404();
		}

		if ($this->input->method() === 'post') {
			$name = trim($this->input->post('name'));

			if (empty($name)) {
				$this->session->set_flashdata('error', t('category_name_required'));
				redirect('store/categories');
			}

			$this->Store_model->update_category($id, array('name' => $name));
			$this->session->set_flashdata('success', t('category_updated'));
			redirect('store/categories');
		}

		$data['current_section'] = 'store';
		$this->render('store/category_form', $data);
	}

	public function delete_category($id)
	{
		$this->require_permission('manage_store');

		if ($this->Store_model->delete_category($id)) {
			$this->session->set_flashdata('success', t('category_deleted'));
		} else {
			$this->session->set_flashdata('error', t('category_has_products'));
		}

		redirect('store/categories');
	}

	public function products()
	{
		$data['products'] = $this->Store_model->get_all_products();
		$data['current_section'] = 'store';
		$this->render('store/products', $data);
	}

	public function create_product()
	{
		$this->require_permission('manage_store');

		$data['categories'] = $this->Store_model->get_all_categories();
		$data['current_section'] = 'store';

		if ($this->input->method() === 'post') {
			$this->db->trans_start();

			$product_id = $this->Store_model->create_product(array(
				'category_id' => (int) $this->input->post('category_id'),
				'name' => trim($this->input->post('product_name')),
				'brand' => trim($this->input->post('brand')) ?: NULL,
				'unit' => trim($this->input->post('unit')) ?: 'piece',
				'is_active' => 1
			));

			$variant_label = trim($this->input->post('variant_label'));
			if ($variant_label) {
				$this->Inventory_model->record_movement(
					$this->Store_model->create_variant(array(
						'product_id' => $product_id,
						'variant_label' => $variant_label,
						'cost_price' => round((float) $this->input->post('cost_price'), 2),
						'sell_price' => round((float) $this->input->post('sell_price'), 2),
						'reorder_level' => (int) $this->input->post('reorder_level') ?: 0,
						'is_active' => 1
					)),
					1,
					'adjustment',
					0,
					$this->auth->user_id(),
					'opening',
					NULL
				);
			}

			$this->db->trans_complete();

			if ($this->db->trans_status()) {
				$this->session->set_flashdata('success', t('product_created'));
				redirect('store/products');
			} else {
				$this->session->set_flashdata('error', t('error_creating_product'));
			}
		}

		$this->render('store/product_form', $data);
	}

	public function edit_product($product_id)
	{
		$this->require_permission('manage_store');

		$data['product'] = $this->Store_model->get_product_by_id($product_id);
		$data['variants'] = $this->Store_model->get_variants_by_product($product_id);
		$data['categories'] = $this->Store_model->get_all_categories();
		$data['current_section'] = 'store';

		if (!$data['product']) {
			show_404();
		}

		if ($this->input->method() === 'post') {
			$this->Store_model->update_product($product_id, array(
				'category_id' => (int) $this->input->post('category_id'),
				'name' => trim($this->input->post('product_name')),
				'brand' => trim($this->input->post('brand')) ?: NULL,
				'unit' => trim($this->input->post('unit')) ?: 'piece'
			));

			$this->session->set_flashdata('success', t('product_updated'));
			redirect('store/products');
		}

		$this->render('store/product_form', $data);
	}

	public function view_product($product_id)
	{
		$data['product'] = $this->Store_model->get_product_by_id($product_id);

		if (!$data['product']) {
			show_404();
		}

		$data['variants'] = $this->Store_model->get_variants_by_product($product_id);
		$data['current_section'] = 'store';

		$this->render('store/product_show', $data);
	}

	public function create_variant($product_id)
	{
		$this->require_permission('manage_store');

		$product = $this->Store_model->get_product_by_id($product_id);
		if (!$product) {
			show_404();
		}

		$data['current_section'] = 'store';

		if ($this->input->method() === 'post') {
			$variant_id = $this->Store_model->create_variant(array(
				'product_id' => $product_id,
				'variant_label' => trim($this->input->post('variant_label')),
				'cost_price' => round((float) $this->input->post('cost_price'), 2),
				'sell_price' => round((float) $this->input->post('sell_price'), 2),
				'reorder_level' => (int) $this->input->post('reorder_level') ?: 0,
				'is_active' => 1
			));

			$this->session->set_flashdata('success', t('variant_created'));
			redirect('store/products');
		}

		$data['product'] = $product;
		$this->render('store/variant_form', $data);
	}

	public function edit_variant($variant_id)
	{
		$this->require_permission('manage_store');

		$data['variant'] = $this->Store_model->get_variant_with_product($variant_id);
		$data['current_section'] = 'store';
		if (!$data['variant']) {
			show_404();
		}

		if ($this->input->method() === 'post') {
			$this->Store_model->update_variant($variant_id, array(
				'variant_label' => trim($this->input->post('variant_label')),
				'cost_price' => round((float) $this->input->post('cost_price'), 2),
				'sell_price' => round((float) $this->input->post('sell_price'), 2),
				'reorder_level' => (int) $this->input->post('reorder_level') ?: 0
			));

			$this->session->set_flashdata('success', t('variant_updated'));
			redirect('store/products');
		}

		$this->render('store/variant_form', $data);
	}

	public function stock($location_id = 1)
	{
		$data['location'] = $this->Inventory_model->get_location_by_id($location_id);
		$data['current_section'] = 'store';
		if (!$data['location']) {
			show_404();
		}

		$data['locations'] = $this->Inventory_model->get_locations();
		$data['stock_levels'] = $this->Inventory_model->get_all_stock_levels();

		$this->render('store/stock', $data);
	}

	public function set_opening_stock()
	{
		$this->require_permission('manage_store');

		$data['current_section'] = 'store';

		if ($this->input->method() === 'post') {
			$variant_id = (int) $this->input->post('variant_id');
			$location_id = (int) $this->input->post('location_id');
			$qty = (int) $this->input->post('qty');
			$reason = trim($this->input->post('reason'));

			if ($qty < 0) {
				$this->session->set_flashdata('error', t('qty_cannot_be_negative'));
				redirect('store/stock/' . $location_id);
			}

			if ($reason === '') {
				$this->session->set_flashdata('error', t('stock_intake_reason_required'));
				redirect('store/stock/' . $location_id);
			}

			$this->Inventory_model->record_movement(
				$variant_id,
				$location_id,
				'adjustment',
				$qty,
				$this->auth->user_id(),
				'opening',
				NULL,
				NULL,
				$reason
			);

			$this->session->set_flashdata('success', t('opening_stock_set'));
			redirect('store/stock/' . $location_id);
		}

		$data['locations'] = $this->Inventory_model->get_locations();
		$this->render('store/opening_stock_form', $data);
	}

	// ===== Requisitions =====
	public function requisitions()
	{
		$data['requisitions'] = $this->Store_model->get_requisitions();
		$data['current_section'] = 'store';
		$this->render('store/requisitions', $data);
	}

	// Read-only detail page — approve_requisition()/receive_requisition() only
	// accept 'pending'/'in_transit' requisitions and redirect away otherwise, so
	// once a requisition is approved/rejected/received there was previously no
	// page left that could show what was in it.
	public function view_requisition($requisition_id)
	{
		$data['requisition'] = $this->Store_model->get_requisition_by_id($requisition_id);

		if (!$data['requisition']) {
			show_404();
		}

		$data['items'] = $this->Store_model->get_requisition_items($requisition_id);
		$data['current_section'] = 'store';

		$this->render('store/view_requisition', $data);
	}

	public function create_requisition()
	{
		$this->require_permission('manage_store');

		$data['locations'] = $this->Inventory_model->get_locations();
		$data['products'] = $this->Store_model->get_all_products();
		$warehouse_id = 2;
		foreach ($data['products'] as &$product) {
			$product['variants'] = $this->Store_model->get_variants_by_product($product['id']);
			foreach ($product['variants'] as &$variant) {
				$stock = $this->Inventory_model->get_stock_level($variant['id'], $warehouse_id);
				$variant['warehouse_available'] = $stock ? (int) $stock['qty_on_hand'] : 0;
			}
			unset($variant);
		}
		unset($product);
		$data['current_section'] = 'store';

		if ($this->input->method() === 'post') {
			$to_location = (int) $this->input->post('to_location_id');
			$variants = $this->input->post('variant_id');
			$quantities = $this->input->post('qty');

			if (empty($variants) || empty($quantities)) {
				$this->session->set_flashdata('error', t('requisition_empty'));
				redirect('store/requisitions');
			}

			$items = array();
			foreach ($variants as $k => $vid) {
				if (!empty($vid) && isset($quantities[$k])) {
					$items[] = array(
						'variant_id' => (int) $vid,
						'qty' => (int) $quantities[$k]
					);
				}
			}

			if (empty($items)) {
				$this->session->set_flashdata('error', t('requisition_empty'));
				redirect('store/requisitions');
			}

			$warehouse_id = 2;

			$req_id = $this->Store_model->create_requisition(
				$warehouse_id,
				$to_location,
				$this->auth->user_id(),
				$items
			);

			if ($req_id) {
				$this->session->set_flashdata('success', t('requisition_created'));
				redirect('store/requisitions');
			} else {
				$this->session->set_flashdata('error', t('error_creating_requisition'));
			}
		}

		$this->render('store/requisition_form', $data);
	}

	public function approve_requisition($requisition_id)
	{
		$this->require_permission('approve_store_requisition');

		$data['requisition'] = $this->Store_model->get_requisition_by_id($requisition_id);
		$data['items'] = $this->Store_model->get_requisition_items($requisition_id);
		$data['current_section'] = 'store';

		if (!$data['requisition']) {
			show_404();
		}

		if ($data['requisition']['status'] !== 'pending') {
			$this->session->set_flashdata('error', t('requisition_not_pending'));
			redirect('store/requisitions');
		}

		if ($this->input->method() === 'post') {
			$action = $this->input->post('action');

			if ($action === 'approve') {
				$this->db->trans_start();

				$items_approved = array();
				foreach ($data['items'] as $item) {
					$qty_key = 'qty_approved_' . $item['id'];
					$qty = (int) $this->input->post($qty_key);

					if ($qty < 0) {
						$this->session->set_flashdata('error', t('qty_cannot_be_negative'));
						redirect('store/requisitions');
					}

					if ($qty > 0) {
						$available = $this->Inventory_model->get_stock_level($item['variant_id'], $data['requisition']['from_location_id']);
						if (!$available || $available['qty_on_hand'] < $qty) {
							$this->db->trans_rollback();
							$this->session->set_flashdata('error', t('insufficient_warehouse_stock'));
							redirect('store/approve_requisition/' . $requisition_id);
						}
						$items_approved[$item['id']] = $qty;
					}
				}

				if (empty($items_approved)) {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', t('no_items_approved'));
					redirect('store/approve_requisition/' . $requisition_id);
				}

				if ($this->Store_model->approve_requisition($requisition_id, $this->auth->user_id(), $items_approved)) {
					$items = $this->Store_model->get_requisition_items($requisition_id);
					foreach ($items as $item) {
						if ($item['qty_approved']) {
							$this->Inventory_model->record_movement(
								$item['variant_id'],
								$data['requisition']['from_location_id'],
								'transfer_out',
								-$item['qty_approved'],
								$this->auth->user_id(),
								'requisition',
								$requisition_id
							);
						}
					}

					$this->Store_model->update_requisition_status($requisition_id, 'in_transit');
					$this->db->trans_complete();

					if ($this->db->trans_status()) {
						$this->session->set_flashdata('success', t('requisition_approved'));
						redirect('store/requisitions');
					} else {
						$this->session->set_flashdata('error', t('error_approving_requisition'));
					}
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', t('error_approving_requisition'));
				}
			} elseif ($action === 'reject') {
				$reason = trim($this->input->post('reject_reason'));
				if (empty($reason)) {
					$this->session->set_flashdata('error', t('reject_reason_required'));
					redirect('store/approve_requisition/' . $requisition_id);
				}

				if ($this->Store_model->reject_requisition($requisition_id, $this->auth->user_id(), $reason)) {
					$this->session->set_flashdata('success', t('requisition_rejected'));
					redirect('store/requisitions');
				} else {
					$this->session->set_flashdata('error', t('error_rejecting_requisition'));
				}
			}
		}

		$this->render('store/approve_requisition', $data);
	}

	public function receive_requisition($requisition_id)
	{
		$this->require_permission('manage_store');

		$data['requisition'] = $this->Store_model->get_requisition_by_id($requisition_id);
		$data['items'] = $this->Store_model->get_requisition_items($requisition_id);
		$data['current_section'] = 'store';

		if (!$data['requisition']) {
			show_404();
		}

		if ($data['requisition']['status'] !== 'in_transit') {
			$this->session->set_flashdata('error', t('requisition_not_in_transit'));
			redirect('store/requisitions');
		}

		if ($this->input->method() === 'post') {
			$this->db->trans_start();

			foreach ($data['items'] as $item) {
				$qty_key = 'qty_received_' . $item['id'];
				$qty_received = (int) $this->input->post($qty_key);

				if ($qty_received < 0) {
					$this->session->set_flashdata('error', t('qty_cannot_be_negative'));
					$this->db->trans_rollback();
					redirect('store/receive_requisition/' . $requisition_id);
				}

				if ($qty_received > 0) {
					$this->Inventory_model->record_movement(
						$item['variant_id'],
						$data['requisition']['to_location_id'],
						'transfer_in',
						$qty_received,
						$this->auth->user_id(),
						'requisition',
						$requisition_id
					);

					if ($qty_received !== $item['qty_approved']) {
						$this->Store_model->update_requisition_item_received($item['id'], $qty_received);
					}
				}
			}

			$this->Store_model->update_requisition_status($requisition_id, 'received');
			$this->db->trans_complete();

			if ($this->db->trans_status()) {
				$this->session->set_flashdata('success', t('requisition_received'));
				redirect('store/requisitions');
			} else {
				$this->session->set_flashdata('error', t('error_receiving_requisition'));
			}
		}

		$this->render('store/receive_requisition', $data);
	}

	// ===== Sales =====
	public function sell()
	{
		$this->require_permission('manage_store');

		$this->load->model('Patient_model');
		$data['patients'] = $this->Patient_model->all();
		$data['products'] = $this->Store_model->get_all_products();
		foreach ($data['products'] as &$product) {
			$product['variants'] = $this->Store_model->get_variants_by_product($product['id']);
		}
		unset($product);
		$data['current_section'] = 'store';

		if ($this->input->method() === 'post') {
			$this->load->model('Safe_model');
			$this->load->model('Wallet_model');

			$this->db->trans_start();

			$patient_id = $this->input->post('patient_id') ? (int) $this->input->post('patient_id') : NULL;
			$customer_name = trim((string) $this->input->post('customer_name'));
			$customer_phone = trim((string) $this->input->post('customer_phone'));
			$payment_method = trim($this->input->post('payment_method'));
			$variants = $this->input->post('variant_id') ?? array();
			$quantities = $this->input->post('qty') ?? array();
			$prices = $this->input->post('price') ?? array();

			if (!in_array($payment_method, array('cash', 'wallet', 'debt'), TRUE)) {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', t('invalid_payment_method'));
				redirect('store/sell');
			}

			if (!$patient_id && $customer_name === '') {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', t('customer_name_required'));
				redirect('store/sell');
			}

			if ($payment_method === 'wallet' && !$patient_id) {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', t('patient_required_for_wallet'));
				redirect('store/sell');
			}

			$items = array();
			$subtotal = 0;

			foreach ($variants as $k => $vid) {
				if (!empty($vid) && isset($quantities[$k], $prices[$k])) {
					$vid = (int) $vid;
					$qty = (int) $quantities[$k];
					$price = round((float) $prices[$k], 2);

					if ($qty <= 0 || $price < 0) continue;

					$variant = $this->Store_model->get_variant_by_id($vid);
					if (!$variant) continue;

					$available = $this->Inventory_model->get_stock_level($vid, 1);
					if (!$available || $available['qty_on_hand'] < $qty) {
						$this->db->trans_rollback();
						$this->session->set_flashdata('error', t('insufficient_front_desk_stock'));
						redirect('store/sell');
					}

					$line_total = $qty * $price;
					$items[] = array(
						'variant_id' => $vid,
						'qty' => $qty,
						'unit_price' => $price,
						'line_total' => $line_total,
						'unit_cost_at_sale' => $variant['cost_price']
					);

					$subtotal += $line_total;
				}
			}

			if (empty($items)) {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', t('cart_empty'));
				redirect('store/sell');
			}

			$discount = round((float) $this->input->post('discount'), 2);
			$tax = round((float) $this->input->post('tax'), 2);
			$total = $subtotal - $discount + $tax;

			if ($total <= 0) {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', t('invalid_sale_amount'));
				redirect('store/sell');
			}

			$sale_id = $this->Store_model->create_sale(
				$patient_id,
				1,
				$this->auth->user_id(),
				$subtotal,
				$discount,
				$tax,
				$total,
				$payment_method,
				$items,
				NULL,
				$patient_id ? NULL : $customer_name,
				$patient_id ? NULL : $customer_phone
			);

			if (!$sale_id) {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', t('error_creating_sale'));
				redirect('store/sell');
			}

			foreach ($items as $item) {
				$stock_recorded = $this->Inventory_model->record_movement(
					$item['variant_id'],
					1,
					'sale_out',
					-$item['qty'],
					$this->auth->user_id(),
					'sale',
					$sale_id
				);

				if (!$stock_recorded) {
					// A concurrent sale already took the remaining stock between the
					// availability check above and this write — reject the whole sale
					// rather than charging/logging money for stock that wasn't
					// actually there to sell.
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', t('insufficient_front_desk_stock'));
					redirect('store/sell');
				}
			}

			if ($payment_method === 'cash') {
				$this->Safe_model->log_transaction(
					'in',
					'store_sale',
					$total,
					$sale_id,
					'store_sales',
					'Store sale: ' . count($items) . ' item(s)',
					$this->auth->user_id()
				);
			} elseif ($payment_method === 'wallet') {
				$this->Wallet_model->deduct($patient_id, $total, NULL, 'Store purchase');
				$this->Wallet_model->recalculate_for_patient($patient_id);
			}
			// 'debt' (قرض): no Safe/Wallet effect at sale time — tracked via
			// store_sales.debt_status = 'open' until cleared from the sales report.

			$this->db->trans_complete();

			if ($this->db->trans_status()) {
				redirect('store/receipt/' . $sale_id);
			} else {
				$this->session->set_flashdata('error', t('error_creating_sale'));
				redirect('store/sell');
			}
		}

		$this->render('store/sell', $data);
	}

	public function receipt($sale_id)
	{
		$data['sale'] = $this->Store_model->get_sale_by_id($sale_id);
		$data['items'] = $this->Store_model->get_sale_items($sale_id);
		$data['current_section'] = 'store';

		if (!$data['sale']) {
			show_404();
		}

		$this->render('store/receipt', $data);
	}

	public function clear_sale_debt($sale_id)
	{
		$this->require_permission('manage_store');

		if ($this->Store_model->clear_sale_debt($sale_id, $this->auth->user_id())) {
			$this->session->set_flashdata('success', t('debt_cleared'));
		} else {
			$this->session->set_flashdata('error', t('error_clearing_debt'));
		}

		redirect('store/reports');
	}

	public function refund_sale($sale_id)
	{
		$this->require_permission('manage_store');

		$sale = $this->Store_model->get_sale_by_id($sale_id);

		if (!$sale || $sale['status'] !== 'completed') {
			$this->session->set_flashdata('error', t('error_refunding_sale'));
			redirect('store/reports');
		}

		$items = $this->Store_model->get_sale_items($sale_id);

		$this->db->trans_start();

		foreach ($items as $item) {
			$restocked = $this->Inventory_model->record_movement(
				$item['variant_id'],
				$sale['location_id'],
				'return_in',
				(int) $item['qty'],
				$this->auth->user_id(),
				'sale',
				$sale_id
			);

			if (!$restocked) {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', t('error_refunding_sale'));
				redirect('store/reports');
			}
		}

		$note = 'Store refund: sale #' . $sale_id;

		// Cash/card were paid into the physical safe, so the refund pays back out
		// of it. Wallet/prepayment never touched the safe at sale time (see
		// sell()), so the refund only credits the wallet back — mirrors the
		// cash/wallet asymmetry documented for the sell flow. 'debt' never
		// collected anything, so there's nothing financial to reverse; only the
		// stock and the sale status change.
		if (in_array($sale['payment_method'], array('cash', 'card'), TRUE)) {
			$this->load->model('Safe_model');
			$this->Safe_model->log_transaction(
				'out',
				'store_refund',
				$sale['total'],
				$sale_id,
				'store_sales',
				$note,
				$this->auth->user_id()
			);
		} elseif (in_array($sale['payment_method'], array('wallet', 'prepayment'), TRUE) && $sale['patient_id']) {
			$this->load->model('Wallet_model');
			$this->Wallet_model->top_up_cash($sale['patient_id'], $sale['total'], NULL, $note);
		}

		$updated = $this->Store_model->mark_sale_refunded($sale_id, $sale);

		if (!$updated) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', t('error_refunding_sale'));
			redirect('store/reports');
		}

		$this->db->trans_complete();

		if (!$this->db->trans_status()) {
			$this->session->set_flashdata('error', t('error_refunding_sale'));
			redirect('store/reports');
		}

		if (in_array($sale['payment_method'], array('wallet', 'prepayment'), TRUE) && $sale['patient_id']) {
			$this->Wallet_model->recalculate_for_patient($sale['patient_id']);
		}

		$this->session->set_flashdata('success', t('sale_refunded_success'));
		redirect('store/reports');
	}

	// Shared by delete_sale() and update_sale(): restocks every line item and
	// reverses whatever money effect the sale had, exactly like refund_sale()
	// — except cash/card use a hard delete of the safe row (delete_transaction_by_reference)
	// instead of a compensating 'out' entry, since both callers are about to either
	// remove the sale entirely or immediately re-apply corrected amounts, not
	// leave an audit trail of "this got refunded".
	private function reverse_sale_effects($sale)
	{
		$items = $this->Store_model->get_sale_items($sale['id']);

		foreach ($items as $item) {
			$restocked = $this->Inventory_model->record_movement(
				$item['variant_id'],
				$sale['location_id'],
				'return_in',
				(int) $item['qty'],
				$this->auth->user_id(),
				'sale',
				$sale['id']
			);

			if (!$restocked) {
				return FALSE;
			}
		}

		if (in_array($sale['payment_method'], array('cash', 'card'), TRUE)) {
			$this->load->model('Safe_model');
			if ($this->Safe_model->delete_transaction_by_reference('store_sales', $sale['id'], 'store_sale') === FALSE) {
				return FALSE;
			}
		} elseif (in_array($sale['payment_method'], array('wallet', 'prepayment'), TRUE) && $sale['patient_id']) {
			$this->load->model('Wallet_model');
			$this->Wallet_model->top_up_cash($sale['patient_id'], $sale['total'], NULL, 'Reversal for sale #' . $sale['id']);
		}
		// 'debt': nothing financial was ever collected, so nothing to reverse.

		return TRUE;
	}

	// Mirrors reverse_sale_effects(), but undoes a completed refund_sale()
	// instead of a live sale: destocks what the refund restocked, and pulls
	// back whatever the refund paid out, so update_sale() can re-apply the
	// corrected refund via apply_refund_effects() when editing a refunded sale.
	private function reverse_refund_effects($sale)
	{
		$items = $this->Store_model->get_sale_items($sale['id']);

		foreach ($items as $item) {
			$destocked = $this->Inventory_model->record_movement(
				$item['variant_id'],
				$sale['location_id'],
				'sale_out',
				-(int) $item['qty'],
				$this->auth->user_id(),
				'sale',
				$sale['id']
			);

			if (!$destocked) {
				return FALSE;
			}
		}

		if (in_array($sale['payment_method'], array('cash', 'card'), TRUE)) {
			$this->load->model('Safe_model');
			if ($this->Safe_model->delete_transaction_by_reference('store_sales', $sale['id'], 'store_refund') === FALSE) {
				return FALSE;
			}
		} elseif (in_array($sale['payment_method'], array('wallet', 'prepayment'), TRUE) && $sale['patient_id']) {
			$this->load->model('Wallet_model');
			$this->Wallet_model->deduct($sale['patient_id'], $sale['total'], NULL, 'Reversal for refund edit of sale #' . $sale['id']);
		}
		// 'debt': refund_sale() never touched money for a debt sale, so nothing to reverse.

		return TRUE;
	}

	// Mirrors refund_sale()'s restock + money-back effects, parameterized so
	// update_sale() can re-apply them with the corrected items/total after
	// reverse_refund_effects() has undone the original refund.
	private function apply_refund_effects($sale_id, $location_id, $items, $total, $payment_method, $patient_id)
	{
		foreach ($items as $item) {
			$restocked = $this->Inventory_model->record_movement(
				$item['variant_id'],
				$location_id,
				'return_in',
				(int) $item['qty'],
				$this->auth->user_id(),
				'sale',
				$sale_id
			);

			if (!$restocked) {
				return FALSE;
			}
		}

		$note = 'Store refund (edited): sale #' . $sale_id;

		if (in_array($payment_method, array('cash', 'card'), TRUE)) {
			$this->load->model('Safe_model');
			$this->Safe_model->log_transaction('out', 'store_refund', $total, $sale_id, 'store_sales', $note, $this->auth->user_id());
		} elseif (in_array($payment_method, array('wallet', 'prepayment'), TRUE) && $patient_id) {
			$this->load->model('Wallet_model');
			$this->Wallet_model->top_up_cash($patient_id, $total, NULL, $note);
			$this->Wallet_model->recalculate_for_patient($patient_id);
		}
		// 'debt': nothing financial to redo, mirrors refund_sale().

		return TRUE;
	}

	public function delete_sale($sale_id)
	{
		$this->require_permission('manage_store');

		$sale = $this->Store_model->get_sale_by_id($sale_id);

		if (!$sale || $sale['status'] !== 'completed') {
			$this->session->set_flashdata('error', t('error_deleting_sale'));
			redirect('store/reports');
		}

		$this->db->trans_start();

		if (!$this->reverse_sale_effects($sale)) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', t('error_deleting_sale'));
			redirect('store/reports');
		}

		$this->db->delete('store_sales', array('id' => (int) $sale_id));

		$this->db->trans_complete();

		if (!$this->db->trans_status()) {
			$this->session->set_flashdata('error', t('error_deleting_sale'));
			redirect('store/reports');
		}

		if (in_array($sale['payment_method'], array('wallet', 'prepayment'), TRUE) && $sale['patient_id']) {
			$this->load->model('Wallet_model');
			$this->Wallet_model->recalculate_for_patient($sale['patient_id']);
		}

		$this->session->set_flashdata('success', t('sale_deleted_success'));
		redirect('store/reports');
	}

	public function edit_sale($sale_id)
	{
		$this->require_permission('manage_store');

		$sale = $this->Store_model->get_sale_by_id($sale_id);

		if (!$sale || !in_array($sale['status'], array('completed', 'refunded'), TRUE)) {
			$this->session->set_flashdata('error', t('error_editing_sale'));
			redirect('store/reports');
		}

		$this->load->model('Patient_model');
		$data['patients'] = $this->Patient_model->all();
		$data['products'] = $this->Store_model->get_all_products();
		foreach ($data['products'] as &$product) {
			$product['variants'] = $this->Store_model->get_variants_by_product($product['id']);
		}
		unset($product);
		$data['current_section'] = 'store';
		$data['is_edit'] = TRUE;
		// A refunded sale's patient/customer/payment method are locked (see
		// update_sale()) — only the refunded items themselves are editable.
		$data['is_refund_edit'] = $sale['status'] === 'refunded';
		$data['sale'] = $sale;
		$data['items'] = $this->Store_model->get_sale_items($sale_id);

		$this->render('store/sell', $data);
	}

	public function update_sale($sale_id)
	{
		$this->require_permission('manage_store');

		$sale = $this->Store_model->get_sale_by_id($sale_id);

		if (!$sale || !in_array($sale['status'], array('completed', 'refunded'), TRUE)) {
			$this->session->set_flashdata('error', t('error_editing_sale'));
			redirect('store/reports');
		}

		// Editing an already-refunded sale only corrects which items/qty were
		// refunded. Patient, customer, and payment method stay locked to the
		// original sale — reverse_refund_effects()/apply_refund_effects() below
		// only know how to reverse and redo money for that original method, not
		// migrate it to a different one.
		$is_refund_edit = $sale['status'] === 'refunded';

		$this->load->model('Safe_model');
		$this->load->model('Wallet_model');

		$this->db->trans_start();

		$reversed = $is_refund_edit ? $this->reverse_refund_effects($sale) : $this->reverse_sale_effects($sale);

		if (!$reversed) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', t('error_editing_sale'));
			redirect('store/edit_sale/' . $sale_id);
		}

		if ($is_refund_edit) {
			$patient_id = $sale['patient_id'] ? (int) $sale['patient_id'] : NULL;
			$customer_name = (string) $sale['customer_name'];
			$customer_phone = (string) $sale['customer_phone'];
			$payment_method = $sale['payment_method'];
		} else {
			$patient_id = $this->input->post('patient_id') ? (int) $this->input->post('patient_id') : NULL;
			$customer_name = trim((string) $this->input->post('customer_name'));
			$customer_phone = trim((string) $this->input->post('customer_phone'));
			$payment_method = trim($this->input->post('payment_method'));

			if (!in_array($payment_method, array('cash', 'wallet', 'debt'), TRUE)) {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', t('invalid_payment_method'));
				redirect('store/edit_sale/' . $sale_id);
			}

			if (!$patient_id && $customer_name === '') {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', t('customer_name_required'));
				redirect('store/edit_sale/' . $sale_id);
			}

			if ($payment_method === 'wallet' && !$patient_id) {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', t('patient_required_for_wallet'));
				redirect('store/edit_sale/' . $sale_id);
			}
		}

		$variants = $this->input->post('variant_id') ?? array();
		$quantities = $this->input->post('qty') ?? array();
		$prices = $this->input->post('price') ?? array();

		$items = array();
		$subtotal = 0;

		foreach ($variants as $k => $vid) {
			if (!empty($vid) && isset($quantities[$k], $prices[$k])) {
				$vid = (int) $vid;
				$qty = (int) $quantities[$k];
				$price = round((float) $prices[$k], 2);

				if ($qty <= 0 || $price < 0) continue;

				$variant = $this->Store_model->get_variant_by_id($vid);
				if (!$variant) continue;

				if (!$is_refund_edit) {
					// reverse_sale_effects() above already restocked the sale's original
					// items, so this check is against post-reversal availability — an
					// unchanged line correctly sees its own qty as available again.
					// Not needed for a refund edit: apply_refund_effects() only ever
					// adds stock back (return_in), so it can never oversell.
					$available = $this->Inventory_model->get_stock_level($vid, $sale['location_id']);
					if (!$available || $available['qty_on_hand'] < $qty) {
						$this->db->trans_rollback();
						$this->session->set_flashdata('error', t('insufficient_front_desk_stock'));
						redirect('store/edit_sale/' . $sale_id);
					}
				}

				$line_total = $qty * $price;
				$items[] = array(
					'variant_id' => $vid,
					'qty' => $qty,
					'unit_price' => $price,
					'line_total' => $line_total,
					'unit_cost_at_sale' => $variant['cost_price']
				);

				$subtotal += $line_total;
			}
		}

		if (empty($items)) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', t('cart_empty'));
			redirect('store/edit_sale/' . $sale_id);
		}

		$discount = round((float) $this->input->post('discount'), 2);
		$tax = round((float) $this->input->post('tax'), 2);
		$total = $subtotal - $discount + $tax;

		if ($total <= 0) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', t('invalid_sale_amount'));
			redirect('store/edit_sale/' . $sale_id);
		}

		$updated = $this->Store_model->update_sale(
			$sale_id,
			$patient_id,
			$subtotal,
			$discount,
			$tax,
			$total,
			$payment_method,
			$items,
			$patient_id ? NULL : $customer_name,
			$patient_id ? NULL : $customer_phone
		);

		if (!$updated) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', t('error_editing_sale'));
			redirect('store/edit_sale/' . $sale_id);
		}

		if ($is_refund_edit) {
			// Store_model::update_sale() unconditionally reopens a debt sale's
			// debt_status ('open') for the live-edit path — wrong here since a
			// refunded debt sale never collected anything and was already marked
			// 'cleared' by refund_sale(). Re-apply mark_sale_refunded() against the
			// pre-edit $sale snapshot to restore that (a no-op for cash/wallet).
			$this->Store_model->mark_sale_refunded($sale_id, $sale);

			if (!$this->apply_refund_effects($sale_id, $sale['location_id'], $items, $total, $payment_method, $patient_id)) {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', t('error_editing_sale'));
				redirect('store/edit_sale/' . $sale_id);
			}
		} else {
			foreach ($items as $item) {
				$stock_recorded = $this->Inventory_model->record_movement(
					$item['variant_id'],
					$sale['location_id'],
					'sale_out',
					-$item['qty'],
					$this->auth->user_id(),
					'sale',
					$sale_id
				);

				if (!$stock_recorded) {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', t('insufficient_front_desk_stock'));
					redirect('store/edit_sale/' . $sale_id);
				}
			}

			if ($payment_method === 'cash') {
				$this->Safe_model->log_transaction(
					'in',
					'store_sale',
					$total,
					$sale_id,
					'store_sales',
					'Store sale (edited): ' . count($items) . ' item(s)',
					$this->auth->user_id()
				);
			} elseif ($payment_method === 'wallet') {
				$this->Wallet_model->deduct($patient_id, $total, NULL, 'Store purchase (edited)');
				$this->Wallet_model->recalculate_for_patient($patient_id);
			}
			// 'debt': update_sale() already reset debt_status to 'open' via Store_model::update_sale().
		}

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('success', t('sale_updated_success'));
			redirect('store/receipt/' . $sale_id);
		} else {
			$this->session->set_flashdata('error', t('error_editing_sale'));
			redirect('store/edit_sale/' . $sale_id);
		}
	}

	// ===== Reports =====
	public function reports()
	{
		$this->require_permission('manage_store');

		$from_input = trim((string) $this->input->get('date_from', TRUE));
		$to_input = trim((string) $this->input->get('date_to', TRUE));
		$date_from = $from_input !== '' ? $this->gregorian_date_from_shamsi($from_input) : date('Y-m-01');
		$date_to = $to_input !== '' ? $this->gregorian_date_from_shamsi($to_input) : date('Y-m-d');

		if ($date_from === '' || $date_to === '' || $date_from > $date_to) {
			$date_from = date('Y-m-01');
			$date_to = date('Y-m-d');
		}

		$filters = array(
			'date_from' => $date_from,
			'date_to' => $date_to,
			'product_id' => $this->input->get('product_id', TRUE) ?: NULL,
			'payment_method' => $this->input->get('payment_method', TRUE) ?: NULL,
			'customer_type' => $this->input->get('customer_type', TRUE) ?: NULL
		);

		$rows = $this->Store_model->get_sales_report_rows($filters);

		$revenue = 0;
		$cost = 0;
		$sale_ids = array();
		$by_user = array();

		foreach ($rows as $row) {
			$revenue += (float) $row['line_total'];
			$cost += (float) $row['qty'] * (float) $row['unit_cost_at_sale'];
			$sale_ids[$row['sale_id']] = TRUE;

			$uid = (int) $row['sold_by'];
			if (!isset($by_user[$uid])) {
				$by_user[$uid] = array(
					'name' => trim($row['first_name'] . ' ' . $row['last_name']),
					'total' => 0
				);
			}
			$by_user[$uid]['total'] += (float) $row['line_total'];
		}

		uasort($by_user, function ($a, $b) {
			return $b['total'] <=> $a['total'];
		});

		$data['summary'] = array(
			'revenue' => $revenue,
			'cost' => $cost,
			'profit' => $revenue - $cost,
			'sales_count' => count($sale_ids)
		);
		$data['by_user'] = $by_user;
		$data['sales'] = $this->Store_model->get_sales_list($filters);
		$data['products'] = $this->Store_model->get_all_products();
		$data['filters'] = array(
			'date_from' => to_shamsi($date_from),
			'date_to' => to_shamsi($date_to),
			'product_id' => $filters['product_id'],
			'payment_method' => $filters['payment_method'],
			'customer_type' => $filters['customer_type']
		);
		$data['current_section'] = 'store';

		$this->render('store/reports', $data);
	}

	// ===== Bulk Sell (manager approval) =====
	public function bulk_sell()
	{
		$this->require_permission('manage_store');

		$this->load->model('Patient_model');
		$data['patients'] = $this->Patient_model->all();
		$data['products'] = $this->Store_model->get_all_products();
		foreach ($data['products'] as &$product) {
			$product['variants'] = $this->Store_model->get_variants_by_product($product['id']);
		}
		unset($product);
		$data['current_section'] = 'store';

		if ($this->input->method() === 'post') {
			$customers_raw = $this->input->post('customers') ?? array();
			$customers = array();

			foreach ($customers_raw as $row) {
				$patient_id = !empty($row['patient_id']) ? (int) $row['patient_id'] : NULL;
				$customer_name = trim($row['customer_name'] ?? '');
				$payment_method = trim($row['payment_method'] ?? '');

				if (!in_array($payment_method, array('cash', 'wallet', 'debt'), TRUE)) continue;
				if (!$patient_id && $customer_name === '') continue;
				if ($payment_method === 'wallet' && !$patient_id) continue;

				$items = array();
				$variant_ids = $row['variant_id'] ?? array();
				$qtys = $row['qty'] ?? array();
				$prices = $row['price'] ?? array();

				foreach ($variant_ids as $k => $vid) {
					if (empty($vid) || !isset($qtys[$k], $prices[$k])) continue;
					$qty = (int) $qtys[$k];
					$price = round((float) $prices[$k], 2);
					if ($qty <= 0 || $price < 0) continue;

					$items[] = array(
						'variant_id' => (int) $vid,
						'qty' => $qty,
						'unit_price' => $price
					);
				}

				if (empty($items)) continue;

				$customers[] = array(
					'patient_id' => $patient_id,
					'customer_name' => $customer_name,
					'customer_phone' => trim($row['customer_phone'] ?? ''),
					'payment_method' => $payment_method,
					'items' => $items
				);
			}

			if (empty($customers)) {
				$this->session->set_flashdata('error', t('bulk_sell_empty'));
				redirect('store/bulk_sell');
			}

			$batch_id = $this->Store_model->create_sale_batch($this->auth->user_id(), $customers);

			if ($batch_id) {
				$this->session->set_flashdata('success', t('bulk_sell_submitted'));
				redirect('store/sale_batches');
			} else {
				$this->session->set_flashdata('error', t('error_creating_sale'));
			}
		}

		$this->render('store/bulk_sell', $data);
	}

	public function sale_batches()
	{
		$data['batches'] = $this->Store_model->get_sale_batches();
		$data['current_section'] = 'store';
		$this->render('store/sale_batches', $data);
	}

	// Read-only detail page — approve_sale_batch() only accepts 'pending' batches
	// and redirects away otherwise, so once a batch is approved/rejected there
	// was previously no page left that could show what was in it.
	public function view_sale_batch($batch_id)
	{
		$data['batch'] = $this->Store_model->get_sale_batch_by_id($batch_id);

		if (!$data['batch']) {
			show_404();
		}

		$data['customers'] = $this->Store_model->get_batch_customers($batch_id);
		foreach ($data['customers'] as &$customer) {
			$customer['items'] = $this->Store_model->get_batch_items($customer['id']);
		}
		unset($customer);
		$data['current_section'] = 'store';

		$this->render('store/view_sale_batch', $data);
	}

	public function approve_sale_batch($batch_id)
	{
		$this->require_permission('approve_store_sale_batch');

		$data['batch'] = $this->Store_model->get_sale_batch_by_id($batch_id);
		$data['customers'] = $this->Store_model->get_batch_customers($batch_id);
		foreach ($data['customers'] as &$customer) {
			$customer['items'] = $this->Store_model->get_batch_items($customer['id']);
		}
		unset($customer);
		$data['current_section'] = 'store';

		if (!$data['batch']) {
			show_404();
		}

		if ($data['batch']['status'] !== 'pending') {
			$this->session->set_flashdata('error', t('sale_batch_not_pending'));
			redirect('store/sale_batches');
		}

		if ($this->input->method() === 'post') {
			$action = $this->input->post('action');

			if ($action === 'approve') {
				$this->load->model('Safe_model');
				$this->load->model('Wallet_model');

				$this->db->trans_start();

				// Aggregate stock check across the whole batch before touching anything —
				// several customers can request the same variant in one batch.
				$needed = array();
				foreach ($data['customers'] as $customer) {
					foreach ($customer['items'] as $item) {
						$vid = (int) $item['variant_id'];
						$needed[$vid] = ($needed[$vid] ?? 0) + (int) $item['qty'];
					}
				}

				foreach ($needed as $vid => $qty) {
					$available = $this->Inventory_model->get_stock_level($vid, 1);
					if (!$available || $available['qty_on_hand'] < $qty) {
						$this->db->trans_rollback();
						$this->session->set_flashdata('error', t('insufficient_front_desk_stock'));
						redirect('store/approve_sale_batch/' . $batch_id);
					}
				}

				foreach ($data['customers'] as $customer) {
					$subtotal = 0;
					$items = array();
					foreach ($customer['items'] as $item) {
						$variant = $this->Store_model->get_variant_by_id($item['variant_id']);
						$line_total = $item['qty'] * $item['unit_price'];
						$items[] = array(
							'variant_id' => $item['variant_id'],
							'qty' => $item['qty'],
							'unit_price' => $item['unit_price'],
							'line_total' => $line_total,
							'unit_cost_at_sale' => $variant ? $variant['cost_price'] : 0
						);
						$subtotal += $line_total;
					}

					$sale_id = $this->Store_model->create_sale(
						$customer['patient_id'],
						1,
						$this->auth->user_id(),
						$subtotal,
						0,
						0,
						$subtotal,
						$customer['payment_method'],
						$items,
						NULL,
						$customer['patient_id'] ? NULL : $customer['customer_name'],
						$customer['patient_id'] ? NULL : $customer['customer_phone']
					);

					if (!$sale_id) {
						$this->db->trans_rollback();
						$this->session->set_flashdata('error', t('error_creating_sale'));
						redirect('store/approve_sale_batch/' . $batch_id);
					}

					foreach ($items as $item) {
						$stock_recorded = $this->Inventory_model->record_movement(
							$item['variant_id'],
							1,
							'sale_out',
							-$item['qty'],
							$this->auth->user_id(),
							'sale',
							$sale_id
						);

						if (!$stock_recorded) {
							// The aggregate pre-check above can't catch a concurrent sale
							// (e.g. from the walk-in sell() flow) draining stock in between —
							// reject rather than charge/log money for stock that wasn't there.
							$this->db->trans_rollback();
							$this->session->set_flashdata('error', t('insufficient_front_desk_stock'));
							redirect('store/approve_sale_batch/' . $batch_id);
						}
					}

					if ($customer['payment_method'] === 'cash') {
						$this->Safe_model->log_transaction(
							'in',
							'store_sale',
							$subtotal,
							$sale_id,
							'store_sales',
							'Bulk store sale: ' . count($items) . ' item(s)',
							$this->auth->user_id()
						);
					} elseif ($customer['payment_method'] === 'wallet') {
						$this->Wallet_model->deduct($customer['patient_id'], $subtotal, NULL, 'Store purchase (bulk sell)');
						$this->Wallet_model->recalculate_for_patient($customer['patient_id']);
					}
					// 'debt': no Safe/Wallet effect — tracked via store_sales.debt_status.

					$this->Store_model->set_batch_customer_sale_id($customer['id'], $sale_id);
				}

				$this->Store_model->update_sale_batch_status($batch_id, 'approved', $this->auth->user_id());

				$this->db->trans_complete();

				if ($this->db->trans_status()) {
					$this->session->set_flashdata('success', t('sale_batch_approved'));
					redirect('store/sale_batches');
				} else {
					$this->session->set_flashdata('error', t('error_approving_sale_batch'));
				}
			} elseif ($action === 'reject') {
				$reason = trim($this->input->post('reject_reason'));
				if (empty($reason)) {
					$this->session->set_flashdata('error', t('reject_reason_required'));
					redirect('store/approve_sale_batch/' . $batch_id);
				}

				if ($this->Store_model->update_sale_batch_status($batch_id, 'rejected', $this->auth->user_id(), $reason)) {
					$this->session->set_flashdata('success', t('sale_batch_rejected'));
					redirect('store/sale_batches');
				} else {
					$this->session->set_flashdata('error', t('error_rejecting_sale_batch'));
				}
			}
		}

		$this->render('store/approve_sale_batch', $data);
	}

	// ===== Suppliers =====
	public function suppliers()
	{
		$this->require_permission('manage_store');
		$data['suppliers'] = $this->Store_model->get_all_suppliers();
		$data['current_section'] = 'store';
		$this->render('store/suppliers', $data);
	}

	public function create_supplier()
	{
		$this->require_permission('manage_store');

		if ($this->input->method() === 'post') {
			$name = trim($this->input->post('name'));
			if (empty($name)) {
				$this->session->set_flashdata('error', t('supplier_name_required'));
				redirect('store/suppliers');
			}

			$this->Store_model->create_supplier(array(
				'name' => $name,
				'contact' => trim($this->input->post('contact')) ?: NULL,
				'note' => trim($this->input->post('note')) ?: NULL,
				'is_active' => 1
			));

			$this->session->set_flashdata('success', t('supplier_created'));
			redirect('store/suppliers');
		}

		$data['current_section'] = 'store';
		$this->render('store/supplier_form', $data);
	}

	public function edit_supplier($supplier_id)
	{
		$this->require_permission('manage_store');

		$data['supplier'] = $this->Store_model->get_supplier_by_id($supplier_id);
		$data['current_section'] = 'store';

		if (!$data['supplier']) {
			show_404();
		}

		if ($this->input->method() === 'post') {
			$name = trim($this->input->post('name'));
			if (empty($name)) {
				$this->session->set_flashdata('error', t('supplier_name_required'));
				redirect('store/suppliers');
			}

			$this->Store_model->update_supplier($supplier_id, array(
				'name' => $name,
				'contact' => trim($this->input->post('contact')) ?: NULL,
				'note' => trim($this->input->post('note')) ?: NULL
			));

			$this->session->set_flashdata('success', t('supplier_updated'));
			redirect('store/suppliers');
		}

		$this->render('store/supplier_form', $data);
	}

	// ===== Stock Receipts =====
	public function receive_stock()
	{
		$this->require_permission('manage_store');

		$data['suppliers'] = $this->Store_model->get_all_suppliers();
		$data['products'] = $this->Store_model->get_all_products();
		foreach ($data['products'] as &$product) {
			$product['variants'] = $this->Store_model->get_variants_by_product($product['id']);
		}
		unset($product);
		$data['current_section'] = 'store';

		if ($this->input->method() === 'post') {
			$this->load->model('Inventory_model');

			$supplier_id = $this->input->post('supplier_id') ? (int) $this->input->post('supplier_id') : NULL;
			$variants = $this->input->post('variant_id') ?? array();
			$quantities = $this->input->post('qty') ?? array();
			$costs = $this->input->post('unit_cost') ?? array();

			$items = array();
			foreach ($variants as $k => $vid) {
				if (!empty($vid) && isset($quantities[$k], $costs[$k])) {
					$qty = (int) $quantities[$k];
					$cost = round((float) $costs[$k], 2);

					if ($qty > 0 && $cost >= 0) {
						$items[] = array(
							'variant_id' => (int) $vid,
							'qty' => $qty,
							'unit_cost' => $cost
						);
					}
				}
			}

			if (empty($items)) {
				$this->session->set_flashdata('error', t('receipt_empty'));
				redirect('store/receive_stock');
			}

			$receipt_id = $this->Store_model->create_stock_receipt(
				$supplier_id,
				$this->auth->user_id(),
				$items,
				trim($this->input->post('note')) ?: NULL
			);

			if ($receipt_id) {
				foreach ($items as $item) {
					$this->Inventory_model->record_movement(
						$item['variant_id'],
						2,
						'purchase_in',
						$item['qty'],
						$this->auth->user_id(),
						'receipt',
						$receipt_id,
						$item['unit_cost']
					);
				}

				$this->session->set_flashdata('success', t('stock_received'));
				redirect('store/stock_receipts');
			} else {
				$this->session->set_flashdata('error', t('error_receiving_stock'));
			}
		}

		$this->render('store/receive_stock_form', $data);
	}

	public function stock_receipts()
	{
		$data['receipts'] = $this->Store_model->get_stock_receipts();
		$data['current_section'] = 'store';
		$this->render('store/stock_receipts', $data);
	}

	public function view_stock_receipt($receipt_id)
	{
		$data['receipt'] = $this->Store_model->get_receipt_by_id($receipt_id);
		$data['items'] = $this->Store_model->get_receipt_items($receipt_id);
		$data['current_section'] = 'store';

		if (!$data['receipt']) {
			show_404();
		}

		$this->render('store/view_stock_receipt', $data);
	}
}
