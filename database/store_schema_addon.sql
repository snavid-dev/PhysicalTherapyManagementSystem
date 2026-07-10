-- Store module addon — safe to run on the restored DB.
-- Every statement is idempotent: CREATE TABLE IF NOT EXISTS, INSERT IGNORE,
-- or a NOT EXISTS guard. No DROP, no data loss, safe to re-run.

CREATE TABLE IF NOT EXISTS `store_product_categories` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(191) NOT NULL,
	`is_active` tinyint(1) NOT NULL DEFAULT 1,
	`created_at` datetime NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `store_products` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`category_id` int unsigned NOT NULL,
	`name` varchar(191) NOT NULL,
	`brand` varchar(191) NULL,
	`unit` varchar(50) NOT NULL DEFAULT 'piece',
	`is_consumable` tinyint(1) NOT NULL DEFAULT 0,
	`image` varchar(255) NULL,
	`is_active` tinyint(1) NOT NULL DEFAULT 1,
	`created_at` datetime NOT NULL,
	PRIMARY KEY (`id`),
	KEY `store_products_category_id_index` (`category_id`),
	CONSTRAINT `store_products_category_fk` FOREIGN KEY (`category_id`) REFERENCES `store_product_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `store_product_variants` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`product_id` int unsigned NOT NULL,
	`variant_label` varchar(191) NOT NULL,
	`attributes` json NULL,
	`sku` varchar(100) NULL,
	`barcode` varchar(100) NULL,
	`cost_price` decimal(12,2) NOT NULL DEFAULT 0,
	`sell_price` decimal(12,2) NOT NULL DEFAULT 0,
	`reorder_level` int NOT NULL DEFAULT 0,
	`is_active` tinyint(1) NOT NULL DEFAULT 1,
	`created_at` datetime NOT NULL,
	PRIMARY KEY (`id`),
	KEY `store_product_variants_product_id_index` (`product_id`),
	CONSTRAINT `store_product_variants_product_fk` FOREIGN KEY (`product_id`) REFERENCES `store_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `store_locations` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(100) NOT NULL,
	`type` enum('front_desk','warehouse') NOT NULL,
	`is_default_sales_location` tinyint(1) NOT NULL DEFAULT 0,
	`is_active` tinyint(1) NOT NULL DEFAULT 1,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `stock_levels` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`variant_id` int unsigned NOT NULL,
	`location_id` int unsigned NOT NULL,
	`qty_on_hand` int NOT NULL DEFAULT 0,
	`qty_reserved` int NOT NULL DEFAULT 0,
	`updated_at` datetime NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `uq_variant_location` (`variant_id`, `location_id`),
	KEY `stock_levels_location_id_index` (`location_id`),
	CONSTRAINT `stock_levels_variant_fk` FOREIGN KEY (`variant_id`) REFERENCES `store_product_variants` (`id`) ON DELETE CASCADE,
	CONSTRAINT `stock_levels_location_fk` FOREIGN KEY (`location_id`) REFERENCES `store_locations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `stock_movements` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`variant_id` int unsigned NOT NULL,
	`location_id` int unsigned NOT NULL,
	`type` enum('purchase_in','transfer_out','transfer_in','sale_out','return_in','adjustment','consumption_out') NOT NULL,
	`qty` int NOT NULL,
	`unit_cost` decimal(12,2) NULL,
	`reference_type` varchar(40) NULL,
	`reference_id` int unsigned NULL,
	`note` varchar(255) NULL,
	`created_by` int unsigned NULL,
	`created_at` datetime NOT NULL,
	PRIMARY KEY (`id`),
	KEY `stock_movements_variant_id_index` (`variant_id`),
	KEY `stock_movements_location_id_index` (`location_id`),
	KEY `stock_movements_type_index` (`type`),
	KEY `stock_movements_created_by_index` (`created_by`),
	KEY `stock_movements_created_at_index` (`created_at`),
	CONSTRAINT `stock_movements_variant_fk` FOREIGN KEY (`variant_id`) REFERENCES `store_product_variants` (`id`) ON DELETE CASCADE,
	CONSTRAINT `stock_movements_location_fk` FOREIGN KEY (`location_id`) REFERENCES `store_locations` (`id`) ON DELETE CASCADE,
	CONSTRAINT `stock_movements_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `stock_requisitions` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`from_location_id` int unsigned NOT NULL,
	`to_location_id` int unsigned NOT NULL,
	`requested_by` int unsigned NOT NULL,
	`status` enum('pending','approved','rejected','in_transit','received','cancelled') NOT NULL DEFAULT 'pending',
	`approved_by` int unsigned NULL,
	`reject_reason` varchar(255) NULL,
	`note` varchar(255) NULL,
	`created_at` datetime NOT NULL,
	`updated_at` datetime NOT NULL,
	PRIMARY KEY (`id`),
	KEY `stock_requisitions_status_index` (`status`),
	KEY `stock_requisitions_requested_by_index` (`requested_by`),
	KEY `stock_requisitions_approved_by_index` (`approved_by`),
	KEY `stock_requisitions_from_location_index` (`from_location_id`),
	KEY `stock_requisitions_to_location_index` (`to_location_id`),
	CONSTRAINT `stock_requisitions_from_location_fk` FOREIGN KEY (`from_location_id`) REFERENCES `store_locations` (`id`),
	CONSTRAINT `stock_requisitions_to_location_fk` FOREIGN KEY (`to_location_id`) REFERENCES `store_locations` (`id`),
	CONSTRAINT `stock_requisitions_requested_by_fk` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
	CONSTRAINT `stock_requisitions_approved_by_fk` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `stock_requisition_items` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`requisition_id` int unsigned NOT NULL,
	`variant_id` int unsigned NOT NULL,
	`qty_requested` int NOT NULL,
	`qty_approved` int NULL,
	`qty_received` int NULL,
	PRIMARY KEY (`id`),
	KEY `stock_requisition_items_requisition_id_index` (`requisition_id`),
	KEY `stock_requisition_items_variant_id_index` (`variant_id`),
	CONSTRAINT `stock_requisition_items_requisition_fk` FOREIGN KEY (`requisition_id`) REFERENCES `stock_requisitions` (`id`) ON DELETE CASCADE,
	CONSTRAINT `stock_requisition_items_variant_fk` FOREIGN KEY (`variant_id`) REFERENCES `store_product_variants` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `store_sales` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`patient_id` int unsigned NULL,
	`location_id` int unsigned NOT NULL,
	`sold_by` int unsigned NOT NULL,
	`subtotal` decimal(12,2) NOT NULL,
	`discount` decimal(12,2) NOT NULL DEFAULT 0,
	`tax` decimal(12,2) NOT NULL DEFAULT 0,
	`total` decimal(12,2) NOT NULL,
	`payment_method` enum('cash','card','wallet','prepayment') NOT NULL,
	`status` enum('completed','refunded','partially_refunded') NOT NULL DEFAULT 'completed',
	`payment_id` int unsigned NULL,
	`created_at` datetime NOT NULL,
	PRIMARY KEY (`id`),
	KEY `store_sales_patient_id_index` (`patient_id`),
	KEY `store_sales_location_id_index` (`location_id`),
	KEY `store_sales_sold_by_index` (`sold_by`),
	KEY `store_sales_created_at_index` (`created_at`),
	CONSTRAINT `store_sales_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL,
	CONSTRAINT `store_sales_location_fk` FOREIGN KEY (`location_id`) REFERENCES `store_locations` (`id`),
	CONSTRAINT `store_sales_sold_by_fk` FOREIGN KEY (`sold_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `store_sale_items` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`sale_id` int unsigned NOT NULL,
	`variant_id` int unsigned NOT NULL,
	`qty` int NOT NULL,
	`unit_price` decimal(12,2) NOT NULL,
	`discount` decimal(12,2) NOT NULL DEFAULT 0,
	`line_total` decimal(12,2) NOT NULL,
	`unit_cost_at_sale` decimal(12,2) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `store_sale_items_sale_id_index` (`sale_id`),
	KEY `store_sale_items_variant_id_index` (`variant_id`),
	CONSTRAINT `store_sale_items_sale_fk` FOREIGN KEY (`sale_id`) REFERENCES `store_sales` (`id`) ON DELETE CASCADE,
	CONSTRAINT `store_sale_items_variant_fk` FOREIGN KEY (`variant_id`) REFERENCES `store_product_variants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `store_suppliers` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(191) NOT NULL,
	`contact` varchar(191) NULL,
	`note` varchar(255) NULL,
	`is_active` tinyint(1) NOT NULL DEFAULT 1,
	`created_at` datetime NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `store_stock_receipts` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`supplier_id` int unsigned NULL,
	`received_by` int unsigned NOT NULL,
	`expense_id` int unsigned NULL,
	`note` varchar(255) NULL,
	`created_at` datetime NOT NULL,
	PRIMARY KEY (`id`),
	KEY `store_stock_receipts_supplier_id_index` (`supplier_id`),
	KEY `store_stock_receipts_received_by_index` (`received_by`),
	KEY `store_stock_receipts_expense_id_index` (`expense_id`),
	CONSTRAINT `store_stock_receipts_supplier_fk` FOREIGN KEY (`supplier_id`) REFERENCES `store_suppliers` (`id`) ON DELETE SET NULL,
	CONSTRAINT `store_stock_receipts_received_by_fk` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`),
	CONSTRAINT `store_stock_receipts_expense_fk` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `store_stock_receipt_items` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`receipt_id` int unsigned NOT NULL,
	`variant_id` int unsigned NOT NULL,
	`qty` int NOT NULL,
	`unit_cost` decimal(12,2) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `store_stock_receipt_items_receipt_id_index` (`receipt_id`),
	KEY `store_stock_receipt_items_variant_id_index` (`variant_id`),
	CONSTRAINT `store_stock_receipt_items_receipt_fk` FOREIGN KEY (`receipt_id`) REFERENCES `store_stock_receipts` (`id`) ON DELETE CASCADE,
	CONSTRAINT `store_stock_receipt_items_variant_fk` FOREIGN KEY (`variant_id`) REFERENCES `store_product_variants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed the two locations. Store.php hardcodes location_id 1 = Front Desk, 2 = Warehouse
-- (see sell(), receive_stock(), create_requisition()) — insert order matters here.
INSERT INTO `store_locations` (`name`, `type`, `is_default_sales_location`, `is_active`)
SELECT 'Front Desk', 'front_desk', 1, 1
WHERE NOT EXISTS (SELECT 1 FROM `store_locations` WHERE `type` = 'front_desk');

INSERT INTO `store_locations` (`name`, `type`, `is_default_sales_location`, `is_active`)
SELECT 'Warehouse', 'warehouse', 0, 1
WHERE NOT EXISTS (SELECT 1 FROM `store_locations` WHERE `type` = 'warehouse');

-- Store permissions (permissions.name is UNIQUE, so INSERT IGNORE is safe here)
INSERT IGNORE INTO `permissions` (`name`, `module_key`) VALUES
	('view_store', 'store'),
	('manage_store', 'store'),
	('approve_store_requisition', 'store');

-- Grant to Administrator (role 1) and Receptionist (role 3) by name, not by
-- guessed ID — role_permissions has a composite PK so INSERT IGNORE dedupes.
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 1, id FROM `permissions` WHERE `name` IN ('view_store', 'manage_store', 'approve_store_requisition');

INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 3, id FROM `permissions` WHERE `name` IN ('view_store', 'manage_store');

-- Extend safe_transactions.source enum for store sales/refunds (SM3/SM5).
-- MODIFY COLUMN to the same definition is a safe no-op if already applied.
ALTER TABLE `safe_transactions`
	MODIFY COLUMN `source` enum('turn_cash','wallet_topup','patient_payment','patient_debt_payment','patient_refund','other_income','expense','salary_payment','wallet_refund','adjustment','store_sale','store_refund') NOT NULL;

-- Inventory Purchase expense category for SM4 restock accounting.
-- expense_categories.name has no UNIQUE key, so guard manually.
INSERT INTO `expense_categories` (`name`, `name_fa`)
SELECT 'Inventory Purchase', 'خرید موجودی'
WHERE NOT EXISTS (SELECT 1 FROM `expense_categories` WHERE `name` = 'Inventory Purchase');
