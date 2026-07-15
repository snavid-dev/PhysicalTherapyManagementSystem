USE `sql_test_navid_c`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `safe_adjustments`;
DROP TABLE IF EXISTS `safe_transactions`;
DROP TABLE IF EXISTS `staff_salary_payments`;
DROP TABLE IF EXISTS `staff_salary_records`;
DROP TABLE IF EXISTS `expenses`;
DROP TABLE IF EXISTS `expense_categories`;
DROP TABLE IF EXISTS `doctor_leaves`;
DROP TABLE IF EXISTS `patient_debts`;
DROP TABLE IF EXISTS `patient_wallet_transactions`;
DROP TABLE IF EXISTS `patient_wallet`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `patient_discounts`;
DROP TABLE IF EXISTS `turns`;
DROP TABLE IF EXISTS `patient_diagnoses`;
DROP TABLE IF EXISTS `diagnoses`;
DROP TABLE IF EXISTS `staff_sections`;
DROP TABLE IF EXISTS `staff`;
DROP TABLE IF EXISTS `sections`;
DROP TABLE IF EXISTS `staff_types`;
DROP TABLE IF EXISTS `patients`;
DROP TABLE IF EXISTS `reference_doctors`;
DROP TABLE IF EXISTS `role_permissions`;
DROP TABLE IF EXISTS `permissions`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `roles` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(100) NOT NULL,
	`slug` varchar(100) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `permissions` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(100) NOT NULL,
	`module_key` varchar(100) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `role_permissions` (
	`role_id` int unsigned NOT NULL,
	`permission_id` int unsigned NOT NULL,
	PRIMARY KEY (`role_id`, `permission_id`),
	CONSTRAINT `role_permissions_role_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
	CONSTRAINT `role_permissions_permission_fk` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`first_name` varchar(100) NOT NULL,
	`last_name` varchar(100) NOT NULL,
	`username` varchar(100) NOT NULL,
	`email` varchar(150) DEFAULT NULL,
	`phone` varchar(50) DEFAULT NULL,
	`password` varchar(255) NOT NULL,
	`role_id` int unsigned NOT NULL,
	`is_active` tinyint(1) NOT NULL DEFAULT 1,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	UNIQUE KEY `users_username_unique` (`username`),
	CONSTRAINT `users_role_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `safe_transactions` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`type` enum('in','out','adjustment') NOT NULL,
	`source` enum('turn_cash','wallet_topup','patient_payment','patient_debt_payment','patient_refund','other_income','expense','salary_payment','wallet_refund','adjustment','store_sale','store_refund') NOT NULL,
	`amount` decimal(12,2) NOT NULL,
	`balance_after` decimal(12,2) NOT NULL,
	`reference_id` int unsigned DEFAULT NULL,
	`reference_table` varchar(50) DEFAULT NULL,
	`note` varchar(255) DEFAULT NULL,
	`created_by` int unsigned DEFAULT NULL,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	KEY `safe_transactions_type_index` (`type`),
	KEY `safe_transactions_source_index` (`source`),
	KEY `safe_transactions_created_by_index` (`created_by`),
	KEY `safe_transactions_created_at_index` (`created_at`),
	KEY `safe_transactions_source_ref_index` (`source`, `reference_table`, `reference_id`),
	CONSTRAINT `safe_transactions_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `safe_adjustments` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`safe_transaction_id` int unsigned NOT NULL,
	`previous_balance` decimal(12,2) NOT NULL,
	`adjustment_amount` decimal(12,2) NOT NULL,
	`new_balance` decimal(12,2) NOT NULL,
	`reason` text NOT NULL,
	`created_by` int unsigned DEFAULT NULL,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	KEY `safe_adjustments_transaction_id_index` (`safe_transaction_id`),
	KEY `safe_adjustments_created_by_index` (`created_by`),
	CONSTRAINT `safe_adjustments_transaction_fk` FOREIGN KEY (`safe_transaction_id`) REFERENCES `safe_transactions` (`id`) ON DELETE CASCADE,
	CONSTRAINT `safe_adjustments_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `staff_types` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(100) NOT NULL,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `sections` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(100) NOT NULL,
	`default_fee` decimal(12,2) NOT NULL DEFAULT 0.00,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `staff` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`user_id` int unsigned DEFAULT NULL,
	`staff_type_id` int unsigned NOT NULL,
	`first_name` varchar(100) NOT NULL,
	`last_name` varchar(100) NOT NULL,
	`gender` enum('male','female') NOT NULL,
	`section_id` int unsigned DEFAULT NULL,
	`monthly_leave_quota` tinyint unsigned NOT NULL DEFAULT 4,
	`salary` decimal(12,2) NOT NULL DEFAULT 0.00,
	`salary_type` enum('fixed','hourly') NOT NULL DEFAULT 'fixed',
	`status` enum('active','inactive') NOT NULL DEFAULT 'active',
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	KEY `staff_user_id_index` (`user_id`),
	KEY `staff_staff_type_id_index` (`staff_type_id`),
	KEY `staff_section_id_index` (`section_id`),
	CONSTRAINT `staff_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
	CONSTRAINT `staff_staff_type_fk` FOREIGN KEY (`staff_type_id`) REFERENCES `staff_types` (`id`),
	CONSTRAINT `staff_section_fk` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `staff_sections` (
	`staff_id` int unsigned NOT NULL,
	`section_id` int unsigned NOT NULL,
	PRIMARY KEY (`staff_id`, `section_id`),
	CONSTRAINT `staff_sections_staff_fk` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE,
	CONSTRAINT `staff_sections_section_fk` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `reference_doctors` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`first_name` varchar(100) NOT NULL,
	`last_name` varchar(100) NOT NULL,
	`specialty` varchar(150) DEFAULT NULL,
	`phone` varchar(30) DEFAULT NULL,
	`clinic_name` varchar(200) DEFAULT NULL,
	`address` text DEFAULT NULL,
	`notes` text DEFAULT NULL,
	`status` enum('active','inactive') NOT NULL DEFAULT 'active',
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `patients` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`first_name` varchar(100) NOT NULL,
	`last_name` varchar(100) DEFAULT NULL,
	`father_name` varchar(100) DEFAULT NULL,
	`gender` varchar(20) DEFAULT NULL,
	`phone` varchar(30) DEFAULT NULL,
	`phone2` varchar(30) DEFAULT NULL,
	`age` tinyint unsigned DEFAULT NULL,
	`address` varchar(255) DEFAULT NULL,
	`medical_notes` text,
	`referred_by` int unsigned DEFAULT NULL,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	KEY `patients_referred_by_index` (`referred_by`),
	CONSTRAINT `fk_patients_referred_by` FOREIGN KEY (`referred_by`) REFERENCES `reference_doctors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `patients`
	MODIFY COLUMN `last_name` VARCHAR(100) DEFAULT NULL;

CREATE TABLE `diagnoses` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(200) NOT NULL,
	`name_fa` varchar(200) DEFAULT NULL,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `patient_diagnoses` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`patient_id` int unsigned NOT NULL,
	`diagnosis_id` int unsigned NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `uq_patient_diagnosis` (`patient_id`, `diagnosis_id`),
	KEY `patient_diagnoses_diagnosis_id_index` (`diagnosis_id`),
	CONSTRAINT `patient_diagnoses_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
	CONSTRAINT `patient_diagnoses_diagnosis_fk` FOREIGN KEY (`diagnosis_id`) REFERENCES `diagnoses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `patient_discounts` (
	`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`patient_id` INT UNSIGNED NOT NULL,
	`section_id` INT UNSIGNED NOT NULL,
	`discount_percent` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
	`discount_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
	`note` VARCHAR(255) DEFAULT NULL,
	`created_by` INT UNSIGNED DEFAULT NULL,
	`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	KEY `patient_discounts_patient_id_index` (`patient_id`),
	KEY `patient_discounts_section_id_index` (`section_id`),
	KEY `patient_discounts_created_by_index` (`created_by`),
	FOREIGN KEY (`patient_id`)
		REFERENCES `patients`(`id`) ON DELETE CASCADE,
	FOREIGN KEY (`section_id`)
		REFERENCES `sections`(`id`) ON DELETE CASCADE,
	FOREIGN KEY (`created_by`)
		REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `turns` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`patient_id` int unsigned NOT NULL,
	`doctor_id` int unsigned NOT NULL,
	`section_id` int unsigned DEFAULT NULL,
	`staff_id` int unsigned DEFAULT NULL,
	`turn_number` tinyint unsigned DEFAULT NULL,
	`fee` decimal(12,2) NOT NULL DEFAULT 0.00,
	`payment_type` enum('prepaid','cash','deferred','free') NOT NULL DEFAULT 'cash',
	`wallet_deducted` decimal(12,2) NOT NULL DEFAULT 0.00,
	`historical_wallet_deducted` decimal(12,2) NOT NULL DEFAULT 0.00,
	`cash_wallet_deducted` decimal(12,2) NOT NULL DEFAULT 0.00,
	`cash_collected` decimal(12,2) NOT NULL DEFAULT 0.00,
	`topup_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
	`turn_date` date NOT NULL,
	`turn_time` time DEFAULT NULL,
	`status` enum('accepted','scheduled','completed','cancelled') NOT NULL DEFAULT 'accepted',
	`notes` text,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	KEY `turns_patient_id_index` (`patient_id`),
	KEY `turns_doctor_id_index` (`doctor_id`),
	KEY `turns_section_id_index` (`section_id`),
	KEY `turns_staff_id_index` (`staff_id`),
	CONSTRAINT `turns_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
	CONSTRAINT `turns_doctor_fk` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
	CONSTRAINT `turns_section_fk` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL,
	CONSTRAINT `turns_staff_fk` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `turns`
	ADD COLUMN `discount_percent` DECIMAL(5,2) NOT NULL DEFAULT 0.00 AFTER `fee`,
	ADD COLUMN `discount_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00 AFTER `discount_percent`;

CREATE TABLE `patient_wallet` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`patient_id` int unsigned NOT NULL,
	`balance` decimal(12,2) NOT NULL DEFAULT 0.00,
	`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	UNIQUE KEY `patient_wallet_patient_id_unique` (`patient_id`),
	CONSTRAINT `patient_wallet_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `patient_wallet_transactions` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`patient_id` int unsigned NOT NULL,
	`turn_id` int unsigned DEFAULT NULL,
	`type` enum('topup','deduction','auto_debt_settlement','refund') NOT NULL,
	`fund_type` enum('cash_topup','historical_credit') NOT NULL DEFAULT 'cash_topup',
	`amount` decimal(12,2) NOT NULL,
	`note` varchar(255) DEFAULT NULL,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	KEY `patient_wallet_transactions_patient_id_index` (`patient_id`),
	KEY `patient_wallet_transactions_turn_id_index` (`turn_id`),
	CONSTRAINT `patient_wallet_transactions_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
	CONSTRAINT `patient_wallet_transactions_turn_fk` FOREIGN KEY (`turn_id`) REFERENCES `turns` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `patient_debts` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`patient_id` int unsigned NOT NULL,
	`turn_id` int unsigned NOT NULL,
	`amount` decimal(12,2) NOT NULL,
	`status` enum('open','cleared') NOT NULL DEFAULT 'open',
	`debt_type` enum('auto_settleable','manual_only') NOT NULL DEFAULT 'auto_settleable',
	`cleared_at` timestamp NULL DEFAULT NULL,
	`cleared_by_turn_id` int unsigned DEFAULT NULL,
	`note` varchar(255) DEFAULT NULL,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	KEY `patient_debts_patient_id_index` (`patient_id`),
	KEY `patient_debts_turn_id_index` (`turn_id`),
	KEY `patient_debts_cleared_by_turn_id_index` (`cleared_by_turn_id`),
	CONSTRAINT `patient_debts_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
	CONSTRAINT `patient_debts_turn_fk` FOREIGN KEY (`turn_id`) REFERENCES `turns` (`id`) ON DELETE CASCADE,
	CONSTRAINT `patient_debts_cleared_turn_fk` FOREIGN KEY (`cleared_by_turn_id`) REFERENCES `turns` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `payments` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`patient_id` int unsigned NOT NULL,
	`payment_date` date NOT NULL,
	`amount` decimal(12,2) NOT NULL DEFAULT 0.00,
	`payment_method` varchar(50) NOT NULL,
	`reference_number` varchar(100) DEFAULT NULL,
	`notes` text,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	KEY `payments_patient_id_index` (`patient_id`),
	CONSTRAINT `payments_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `doctor_leaves` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`staff_id` int unsigned NOT NULL,
	`start_date` date NOT NULL,
	`end_date` date NOT NULL,
	`status` varchar(30) NOT NULL DEFAULT 'approved',
	`reason` varchar(255) DEFAULT NULL,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	KEY `doctor_leaves_staff_id_index` (`staff_id`),
	CONSTRAINT `doctor_leaves_staff_fk` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `expense_categories` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(150) NOT NULL,
	`name_fa` varchar(150) DEFAULT NULL,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `expenses` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`category_id` int unsigned NOT NULL,
	`staff_id` int unsigned DEFAULT NULL,
	`amount` decimal(12,2) NOT NULL,
	`expense_date` date NOT NULL,
	`description` text DEFAULT NULL,
	`created_by` int unsigned DEFAULT NULL,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	KEY `expenses_category_id_index` (`category_id`),
	KEY `expenses_staff_id_index` (`staff_id`),
	KEY `expenses_created_by_index` (`created_by`),
	CONSTRAINT `expenses_category_fk` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`),
	CONSTRAINT `expenses_staff_fk` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL,
	CONSTRAINT `expenses_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `staff_salary_records` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`staff_id` int unsigned NOT NULL,
	`month` varchar(7) NOT NULL,
	`base_salary` decimal(12,2) NOT NULL DEFAULT 0.00,
	`calculated_deduction` decimal(12,2) NOT NULL DEFAULT 0.00,
	`final_salary` decimal(12,2) NOT NULL DEFAULT 0.00,
	`daily_rate` decimal(12,4) NOT NULL DEFAULT 0.0000,
	`total_paid` decimal(12,2) NOT NULL DEFAULT 0.00,
	`status` enum('unpaid','partial','paid') NOT NULL DEFAULT 'unpaid',
	`settled` tinyint(1) NOT NULL DEFAULT 0,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	UNIQUE KEY `uq_staff_month` (`staff_id`, `month`),
	CONSTRAINT `staff_salary_records_staff_fk` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `staff_salary_payments` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`salary_record_id` int unsigned NOT NULL,
	`staff_id` int unsigned NOT NULL,
	`expense_id` int unsigned DEFAULT NULL,
	`amount` decimal(12,2) NOT NULL,
	`payment_date` date NOT NULL,
	`note` varchar(255) DEFAULT NULL,
	`created_by` int unsigned DEFAULT NULL,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	KEY `staff_salary_payments_record_id_index` (`salary_record_id`),
	KEY `staff_salary_payments_staff_id_index` (`staff_id`),
	KEY `staff_salary_payments_expense_id_index` (`expense_id`),
	KEY `staff_salary_payments_created_by_index` (`created_by`),
	CONSTRAINT `staff_salary_payments_record_fk` FOREIGN KEY (`salary_record_id`) REFERENCES `staff_salary_records` (`id`) ON DELETE CASCADE,
	CONSTRAINT `staff_salary_payments_staff_fk` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE,
	CONSTRAINT `staff_salary_payments_expense_fk` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE SET NULL,
	CONSTRAINT `staff_salary_payments_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `store_product_categories` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(191) NOT NULL,
	`is_active` tinyint(1) NOT NULL DEFAULT 1,
	`created_at` datetime NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `store_products` (
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

CREATE TABLE `store_product_variants` (
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

CREATE TABLE `store_locations` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(100) NOT NULL,
	`type` enum('front_desk','warehouse') NOT NULL,
	`is_default_sales_location` tinyint(1) NOT NULL DEFAULT 0,
	`is_active` tinyint(1) NOT NULL DEFAULT 1,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `stock_levels` (
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

CREATE TABLE `stock_movements` (
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

CREATE TABLE `stock_requisitions` (
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

CREATE TABLE `stock_requisition_items` (
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

CREATE TABLE `store_sales` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`patient_id` int unsigned NULL,
	`customer_name` varchar(191) NULL,
	`customer_phone` varchar(50) NULL,
	`location_id` int unsigned NOT NULL,
	`sold_by` int unsigned NOT NULL,
	`subtotal` decimal(12,2) NOT NULL,
	`discount` decimal(12,2) NOT NULL DEFAULT 0,
	`tax` decimal(12,2) NOT NULL DEFAULT 0,
	`total` decimal(12,2) NOT NULL,
	`payment_method` enum('cash','card','wallet','prepayment','debt') NOT NULL,
	`status` enum('completed','refunded','partially_refunded') NOT NULL DEFAULT 'completed',
	`debt_status` enum('none','open','cleared') NOT NULL DEFAULT 'none',
	`debt_cleared_at` datetime NULL,
	`debt_cleared_by` int unsigned NULL,
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

CREATE TABLE `store_sale_items` (
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

CREATE TABLE `store_sale_batches` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`created_by` int unsigned NOT NULL,
	`status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
	`approved_by` int unsigned NULL,
	`reject_reason` varchar(255) NULL,
	`total_amount` decimal(12,2) NOT NULL DEFAULT 0,
	`note` varchar(255) NULL,
	`created_at` datetime NOT NULL,
	`updated_at` datetime NOT NULL,
	PRIMARY KEY (`id`),
	KEY `store_sale_batches_status_index` (`status`),
	KEY `store_sale_batches_created_by_index` (`created_by`),
	KEY `store_sale_batches_approved_by_index` (`approved_by`),
	CONSTRAINT `store_sale_batches_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
	CONSTRAINT `store_sale_batches_approved_by_fk` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `store_sale_batch_customers` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`batch_id` int unsigned NOT NULL,
	`patient_id` int unsigned NULL,
	`customer_name` varchar(191) NULL,
	`customer_phone` varchar(50) NULL,
	`payment_method` enum('cash','wallet','debt') NOT NULL DEFAULT 'cash',
	`sale_id` int unsigned NULL,
	PRIMARY KEY (`id`),
	KEY `store_sale_batch_customers_batch_id_index` (`batch_id`),
	KEY `store_sale_batch_customers_patient_id_index` (`patient_id`),
	KEY `store_sale_batch_customers_sale_id_index` (`sale_id`),
	CONSTRAINT `store_sale_batch_customers_batch_fk` FOREIGN KEY (`batch_id`) REFERENCES `store_sale_batches` (`id`) ON DELETE CASCADE,
	CONSTRAINT `store_sale_batch_customers_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL,
	CONSTRAINT `store_sale_batch_customers_sale_fk` FOREIGN KEY (`sale_id`) REFERENCES `store_sales` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `store_sale_batch_items` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`batch_customer_id` int unsigned NOT NULL,
	`variant_id` int unsigned NOT NULL,
	`qty` int NOT NULL,
	`unit_price` decimal(12,2) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `store_sale_batch_items_batch_customer_id_index` (`batch_customer_id`),
	KEY `store_sale_batch_items_variant_id_index` (`variant_id`),
	CONSTRAINT `store_sale_batch_items_batch_customer_fk` FOREIGN KEY (`batch_customer_id`) REFERENCES `store_sale_batch_customers` (`id`) ON DELETE CASCADE,
	CONSTRAINT `store_sale_batch_items_variant_fk` FOREIGN KEY (`variant_id`) REFERENCES `store_product_variants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `store_suppliers` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(191) NOT NULL,
	`contact` varchar(191) NULL,
	`note` varchar(255) NULL,
	`is_active` tinyint(1) NOT NULL DEFAULT 1,
	`created_at` datetime NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `store_stock_receipts` (
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

CREATE TABLE `store_stock_receipt_items` (
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

INSERT INTO `roles` (`id`, `name`, `slug`) VALUES
	(1, 'Administrator', 'administrator'),
	(2, 'Therapist', 'therapist'),
	(3, 'Receptionist', 'receptionist');

INSERT INTO `permissions` (`id`, `name`, `module_key`) VALUES
	(1, 'manage_patients', 'patients'),
	(2, 'manage_users', 'users'),
	(3, 'manage_roles', 'roles'),
	(4, 'manage_turns', 'turns'),
	(6, 'view_reports', 'reports'),
	(7, 'manage_leaves', 'leaves'),
	(8, 'manage_staff', 'staff'),
	(9, 'manage_sections', 'sections'),
	(10, 'manage_reference_doctors', 'reference_doctors'),
	(11, 'manage_expenses', 'expenses'),
	(12, 'manage_salaries', 'salaries'),
	(13, 'view_safe', 'safe'),
	(14, 'manage_safe', 'safe'),
	(15, 'edit_processed_payments', 'turns'),
	(16, 'view_store', 'store'),
	(17, 'manage_store', 'store'),
	(18, 'approve_store_requisition', 'store'),
	(19, 'approve_store_sale_batch', 'store');

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
	(1, 1), (1, 2), (1, 3), (1, 4), (1, 6), (1, 7), (1, 8), (1, 9), (1, 10), (1, 11), (1, 12), (1, 13), (1, 14), (1, 15), (1, 16), (1, 17), (1, 18), (1, 19),
	(2, 1), (2, 4), (2, 6), (2, 7),
	(3, 1), (3, 4), (3, 6), (3, 16), (3, 17);

INSERT INTO `expense_categories` (`name`, `name_fa`) VALUES
	('Staff Salary Payment', 'پرداخت معاش کارمند'),
	('Rent / Utilities', 'کرایه و خدمات'),
	('Other', 'سایر'),
	('Inventory Purchase', 'خرید موجودی');

INSERT INTO `store_locations` (`name`, `type`, `is_default_sales_location`, `is_active`) VALUES
	('Front Desk', 'front_desk', 1, 1),
	('Warehouse', 'warehouse', 0, 1);

INSERT INTO `staff_types` (`name`) VALUES
	('Doctor'),
	('Physiotherapist'),
	('Cleaner'),
	('Manager'),
	('Intern'),
	('Helper'),
	('Marketer');

INSERT INTO `sections` (`name`, `default_fee`) VALUES
	('Male Section', 0.00),
	('Female Section', 0.00),
	('Both Sections', 0.00);

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `phone`, `password`, `role_id`, `is_active`) VALUES
	(1, 'System', 'Admin', 'admin', 'admin@clinic.local', '0000000000', '$2y$10$reKyugm60bDFU1/CoGnX..qeEJlYRQyI3e.Cp4LpdaHpVk84GupFS', 1, 1),
	(2, 'Default', 'Therapist', 'therapist', 'therapist@clinic.local', '0000000001', '$2y$10$reKyugm60bDFU1/CoGnX..qeEJlYRQyI3e.Cp4LpdaHpVk84GupFS', 2, 1),
	(3, 'Front', 'Desk', 'reception', 'reception@clinic.local', '0000000002', '$2y$10$reKyugm60bDFU1/CoGnX..qeEJlYRQyI3e.Cp4LpdaHpVk84GupFS', 3, 1);

INSERT INTO `patients` (`id`, `first_name`, `last_name`, `father_name`, `gender`, `phone`, `phone2`, `age`, `address`, `medical_notes`) VALUES
	(1, 'Ahmad', 'Rahimi', 'Karim', 'Male', '0700000001', '0700000101', 36, 'Kabul', 'Post-surgery mobility follow-up.'),
	(2, 'Sara', 'Azizi', 'Jalil', 'Female', '0700000002', NULL, 31, 'Kabul', 'Lower back pain treatment plan.');

INSERT INTO `diagnoses` (`id`, `name`, `name_fa`) VALUES
	(1, 'Low Back Pain', 'کمردرد'),
	(2, 'Post-Surgical Rehabilitation', 'توانبخشی پس از جراحی'),
	(3, 'Knee Osteoarthritis', 'آرتروز زانو');

INSERT INTO `patient_diagnoses` (`patient_id`, `diagnosis_id`) VALUES
	(1, 2),
	(2, 1),
	(2, 3);

INSERT INTO `turns` (`patient_id`, `doctor_id`, `turn_date`, `turn_time`, `status`, `notes`) VALUES
	(1, 2, CURDATE(), '09:00:00', 'scheduled', 'Initial evaluation session.'),
	(2, 2, CURDATE(), '11:00:00', 'completed', 'Range-of-motion follow-up.');

INSERT INTO `patient_wallet` (`patient_id`, `balance`) VALUES
	(1, 100.00),
	(2, 0.00);

INSERT INTO `patient_wallet_transactions` (`patient_id`, `turn_id`, `type`, `fund_type`, `amount`, `note`) VALUES
	(1, NULL, 'topup', 'cash_topup', 100.00, 'Initial wallet top up');

INSERT INTO `patient_debts` (`patient_id`, `turn_id`, `amount`, `status`, `note`) VALUES
	(2, 2, 25.00, 'open', 'Outstanding follow-up fee');

INSERT INTO `payments` (`patient_id`, `payment_date`, `amount`, `payment_method`, `reference_number`, `notes`) VALUES
	(1, CURDATE(), 50.00, 'cash', 'PT-1001', 'Evaluation payment'),
	(2, CURDATE(), 75.00, 'card', 'PT-1002', 'Session payment');

INSERT INTO `doctor_leaves` (`doctor_id`, `start_date`, `end_date`, `status`, `reason`) VALUES
	(2, DATE_ADD(CURDATE(), INTERVAL 7 DAY), DATE_ADD(CURDATE(), INTERVAL 9 DAY), 'approved', 'Conference leave');
