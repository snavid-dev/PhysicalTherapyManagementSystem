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
}
