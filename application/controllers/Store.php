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
		$this->require_permission('manage_store');
		$data['products'] = $this->Store_model->get_all_products();
		$data['current_section'] = 'store';
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

			if ($qty < 0) {
				$this->session->set_flashdata('error', t('qty_cannot_be_negative'));
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
				'Opening stock'
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

	public function create_requisition()
	{
		$this->require_permission('manage_store');

		$data['locations'] = $this->Inventory_model->get_locations();
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

		$data['patients'] = array();
		$data['current_section'] = 'store';

		if ($this->input->method() === 'post') {
			$this->load->model('Safe_model');
			$this->load->model('Wallet_model');

			$this->db->trans_start();

			$patient_id = $this->input->post('patient_id') ? (int) $this->input->post('patient_id') : NULL;
			$payment_method = trim($this->input->post('payment_method'));
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

			if ($payment_method === 'cash' || $payment_method === 'card') {
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
					$items
				);

				if ($sale_id) {
					foreach ($items as $item) {
						$this->Inventory_model->record_movement(
							$item['variant_id'],
							1,
							'sale_out',
							-$item['qty'],
							$this->auth->user_id(),
							'sale',
							$sale_id
						);
					}

					$this->Safe_model->log_transaction(
						'in',
						'store_sale',
						$total,
						$sale_id,
						'store_sales',
						'Store sale: ' . count($items) . ' item(s)',
						$this->auth->user_id()
					);

					redirect('store/receipt/' . $sale_id);
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', t('error_creating_sale'));
				}
			} elseif ($payment_method === 'wallet' || $payment_method === 'prepayment') {
				if (!$patient_id) {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', t('patient_required_for_wallet'));
					redirect('store/sell');
				}

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
					$items
				);

				if ($sale_id) {
					foreach ($items as $item) {
						$this->Inventory_model->record_movement(
							$item['variant_id'],
							1,
							'sale_out',
							-$item['qty'],
							$this->auth->user_id(),
							'sale',
							$sale_id
						);
					}

					$this->Wallet_model->deduct($patient_id, $total, NULL, 'Store purchase');
					$this->Wallet_model->recalculate_for_patient($patient_id);

					redirect('store/receipt/' . $sale_id);
				} else {
					$this->db->trans_rollback();
					$this->session->set_flashdata('error', t('error_creating_sale'));
				}
			}

			redirect('store/sell');
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
}
