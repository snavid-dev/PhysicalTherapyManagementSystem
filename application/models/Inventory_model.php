<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_model extends CI_Model
{
	public function get_locations()
	{
		return $this->db
			->order_by('type', 'asc')
			->order_by('name', 'asc')
			->get('store_locations')
			->result_array();
	}

	public function get_location_by_id($location_id)
	{
		return $this->db
			->get_where('store_locations', array('id' => (int) $location_id))
			->row_array();
	}

	public function get_stock_level($variant_id, $location_id)
	{
		return $this->db
			->get_where('stock_levels', array(
				'variant_id' => (int) $variant_id,
				'location_id' => (int) $location_id
			))
			->row_array();
	}

	public function get_all_stock_levels($variant_id = NULL)
	{
		$this->db
			->select('stock_levels.*, store_product_variants.variant_label, store_products.name as product_name, store_locations.name as location_name')
			->from('stock_levels')
			->join('store_product_variants', 'stock_levels.variant_id = store_product_variants.id')
			->join('store_products', 'store_product_variants.product_id = store_products.id')
			->join('store_locations', 'stock_levels.location_id = store_locations.id');

		if ($variant_id !== NULL) {
			$this->db->where('stock_levels.variant_id', (int) $variant_id);
		}

		return $this->db
			->order_by('store_locations.type', 'asc')
			->order_by('store_locations.name', 'asc')
			->get()
			->result_array();
	}

	public function recompute_stock_level($variant_id, $location_id)
	{
		$variant_id = (int) $variant_id;
		$location_id = (int) $location_id;

		$sum = $this->db
			->select('COALESCE(SUM(qty), 0) as total')
			->where('variant_id', $variant_id)
			->where('location_id', $location_id)
			->get('stock_movements')
			->row_array();

		$total = (int) $sum['total'];

		if ($total < 0) {
			return FALSE;
		}

		$existing = $this->db
			->get_where('stock_levels', array(
				'variant_id' => $variant_id,
				'location_id' => $location_id
			))
			->row_array();

		if ($existing) {
			return $this->db
				->where('id', $existing['id'])
				->update('stock_levels', array(
					'qty_on_hand' => $total,
					'updated_at' => date('Y-m-d H:i:s')
				));
		} else {
			return $this->db->insert('stock_levels', array(
				'variant_id' => $variant_id,
				'location_id' => $location_id,
				'qty_on_hand' => $total,
				'updated_at' => date('Y-m-d H:i:s')
			));
		}
	}

	public function record_movement($variant_id, $location_id, $type, $qty, $user_id = NULL, $reference_type = NULL, $reference_id = NULL, $unit_cost = NULL, $note = NULL)
	{
		$variant_id = (int) $variant_id;
		$location_id = (int) $location_id;
		$qty = (int) $qty;

		$this->db->trans_start();

		$this->db->insert('stock_movements', array(
			'variant_id' => $variant_id,
			'location_id' => $location_id,
			'type' => trim($type),
			'qty' => $qty,
			'unit_cost' => $unit_cost !== NULL ? round((float) $unit_cost, 2) : NULL,
			'reference_type' => $reference_type ? trim($reference_type) : NULL,
			'reference_id' => $reference_id ? (int) $reference_id : NULL,
			'note' => $note ? trim($note) : NULL,
			'created_by' => $user_id ? (int) $user_id : NULL,
			'created_at' => date('Y-m-d H:i:s')
		));

		$this->recompute_stock_level($variant_id, $location_id);

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	public function get_movements($variant_id = NULL, $location_id = NULL, $limit = 100, $offset = 0)
	{
		$this->db
			->select('stock_movements.*, users.first_name, users.last_name, store_product_variants.variant_label, store_locations.name as location_name')
			->from('stock_movements')
			->join('store_product_variants', 'stock_movements.variant_id = store_product_variants.id')
			->join('store_locations', 'stock_movements.location_id = store_locations.id')
			->join('users', 'stock_movements.created_by = users.id', 'left');

		if ($variant_id !== NULL) {
			$this->db->where('stock_movements.variant_id', (int) $variant_id);
		}

		if ($location_id !== NULL) {
			$this->db->where('stock_movements.location_id', (int) $location_id);
		}

		return $this->db
			->order_by('stock_movements.created_at', 'desc')
			->limit($limit, $offset)
			->get()
			->result_array();
	}

	public function validate_stock_available($variant_id, $location_id, $qty)
	{
		$level = $this->get_stock_level($variant_id, $location_id);
		if (!$level) {
			return FALSE;
		}
		return $level['qty_on_hand'] >= $qty;
	}
}
