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
}
