USE `sql_test_navid_c`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `doctor_leaves`;
DROP TABLE IF EXISTS `patient_debts`;
DROP TABLE IF EXISTS `patient_wallet_transactions`;
DROP TABLE IF EXISTS `patient_wallet`;
DROP TABLE IF EXISTS `payments`;
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
	`type` enum('topup','deduction') NOT NULL,
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
	`doctor_id` int unsigned NOT NULL,
	`start_date` date NOT NULL,
	`end_date` date NOT NULL,
	`status` varchar(30) NOT NULL DEFAULT 'approved',
	`reason` varchar(255) DEFAULT NULL,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	KEY `doctor_leaves_doctor_id_index` (`doctor_id`),
	CONSTRAINT `doctor_leaves_doctor_fk` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
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
	(5, 'manage_payments', 'payments'),
	(6, 'view_reports', 'reports'),
	(7, 'manage_leaves', 'leaves'),
	(8, 'manage_staff', 'staff'),
	(9, 'manage_sections', 'sections'),
	(10, 'manage_reference_doctors', 'reference_doctors');

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
	(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 8), (1, 9), (1, 10),
	(2, 1), (2, 4), (2, 6), (2, 7),
	(3, 1), (3, 4), (3, 5), (3, 6);

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

INSERT INTO `patient_wallet_transactions` (`patient_id`, `turn_id`, `type`, `amount`, `note`) VALUES
	(1, NULL, 'topup', 100.00, 'Initial wallet top up');

INSERT INTO `patient_debts` (`patient_id`, `turn_id`, `amount`, `status`, `note`) VALUES
	(2, 2, 25.00, 'open', 'Outstanding follow-up fee');

INSERT INTO `payments` (`patient_id`, `payment_date`, `amount`, `payment_method`, `reference_number`, `notes`) VALUES
	(1, CURDATE(), 50.00, 'cash', 'PT-1001', 'Evaluation payment'),
	(2, CURDATE(), 75.00, 'card', 'PT-1002', 'Session payment');

INSERT INTO `doctor_leaves` (`doctor_id`, `start_date`, `end_date`, `status`, `reason`) VALUES
	(2, DATE_ADD(CURDATE(), INTERVAL 7 DAY), DATE_ADD(CURDATE(), INTERVAL 9 DAY), 'approved', 'Conference leave');
