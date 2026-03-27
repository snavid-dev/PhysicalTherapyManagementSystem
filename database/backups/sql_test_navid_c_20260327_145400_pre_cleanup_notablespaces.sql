/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.13-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: sql_test_navid_c
-- ------------------------------------------------------
-- Server version	5.7.44-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `diagnoses`
--

DROP TABLE IF EXISTS `diagnoses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `diagnoses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `name_fa` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diagnoses`
--

LOCK TABLES `diagnoses` WRITE;
/*!40000 ALTER TABLE `diagnoses` DISABLE KEYS */;
INSERT INTO `diagnoses` VALUES
(1,'Shoulder pain','شانه درد','2026-03-21 08:32:19'),
(2,'Back pain','کمر درد','2026-03-24 18:13:58'),
(3,'Cervical pain','گردن درد','2026-03-25 06:14:54'),
(4,'C.V.A','سکته مغزی','2026-03-25 06:16:47'),
(5,'Bells palsy','فلج نصف صورت','2026-03-25 06:18:13'),
(6,'Ankle pain','پا درد','2026-03-25 06:19:12'),
(7,'Elbow contracture','محدودیت حرکتی بازو','2026-03-25 06:20:37'),
(8,'M S','اختلال سیستم عصبی','2026-03-25 06:23:15');
/*!40000 ALTER TABLE `diagnoses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor_leaves`
--

DROP TABLE IF EXISTS `doctor_leaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctor_leaves` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` int(10) unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'approved',
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `doctor_leaves_doctor_id_index` (`doctor_id`),
  CONSTRAINT `doctor_leaves_doctor_fk` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_leaves`
--

LOCK TABLES `doctor_leaves` WRITE;
/*!40000 ALTER TABLE `doctor_leaves` DISABLE KEYS */;
INSERT INTO `doctor_leaves` VALUES
(1,2,'2026-03-25','2026-03-27','approved','Conference leave','2026-03-18 12:08:26','2026-03-18 12:08:26'),
(2,2,'2026-03-20','2026-03-20','approved','','2026-03-20 07:32:48','2026-03-20 07:32:48');
/*!40000 ALTER TABLE `doctor_leaves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expense_categories`
--

DROP TABLE IF EXISTS `expense_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `expense_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `name_fa` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expense_categories`
--

LOCK TABLES `expense_categories` WRITE;
/*!40000 ALTER TABLE `expense_categories` DISABLE KEYS */;
INSERT INTO `expense_categories` VALUES
(1,'Staff Salary Payment','پرداخت معاش کارمند','2026-03-25 11:27:48'),
(2,'Rent / Utilities','کرایه و خدمات','2026-03-25 11:27:48'),
(3,'Other','سایر','2026-03-25 11:27:48');
/*!40000 ALTER TABLE `expense_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `staff_id` int(10) unsigned DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `expense_date` date NOT NULL,
  `description` text,
  `reference_month` varchar(7) DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `expenses_category_id_index` (`category_id`),
  KEY `expenses_staff_id_index` (`staff_id`),
  KEY `expenses_created_by_index` (`created_by`),
  CONSTRAINT `expenses_category_fk` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`),
  CONSTRAINT `expenses_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expenses_staff_fk` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_debts`
--

DROP TABLE IF EXISTS `patient_debts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `patient_debts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` int(10) unsigned NOT NULL,
  `turn_id` int(10) unsigned NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` enum('open','cleared') NOT NULL DEFAULT 'open',
  `cleared_at` timestamp NULL DEFAULT NULL,
  `cleared_by_turn_id` int(10) unsigned DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `patient_debts_patient_id_index` (`patient_id`),
  KEY `patient_debts_turn_id_index` (`turn_id`),
  KEY `patient_debts_cleared_by_turn_id_index` (`cleared_by_turn_id`),
  CONSTRAINT `patient_debts_cleared_turn_fk` FOREIGN KEY (`cleared_by_turn_id`) REFERENCES `turns` (`id`) ON DELETE SET NULL,
  CONSTRAINT `patient_debts_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_debts_turn_fk` FOREIGN KEY (`turn_id`) REFERENCES `turns` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_debts`
--

LOCK TABLES `patient_debts` WRITE;
/*!40000 ALTER TABLE `patient_debts` DISABLE KEYS */;
INSERT INTO `patient_debts` VALUES
(4,14,27,200.00,'open',NULL,NULL,NULL,'2026-03-25 08:52:46');
/*!40000 ALTER TABLE `patient_debts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_diagnoses`
--

DROP TABLE IF EXISTS `patient_diagnoses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `patient_diagnoses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` int(10) unsigned NOT NULL,
  `diagnosis_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_patient_diagnosis` (`patient_id`,`diagnosis_id`),
  KEY `patient_diagnoses_diagnosis_id_index` (`diagnosis_id`),
  CONSTRAINT `patient_diagnoses_diagnosis_fk` FOREIGN KEY (`diagnosis_id`) REFERENCES `diagnoses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_diagnoses_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_diagnoses`
--

LOCK TABLES `patient_diagnoses` WRITE;
/*!40000 ALTER TABLE `patient_diagnoses` DISABLE KEYS */;
INSERT INTO `patient_diagnoses` VALUES
(4,11,4),
(5,12,6),
(9,17,7),
(8,19,4);
/*!40000 ALTER TABLE `patient_diagnoses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_discounts`
--

DROP TABLE IF EXISTS `patient_discounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `patient_discounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` int(10) unsigned NOT NULL,
  `section_id` int(10) unsigned NOT NULL,
  `discount_percent` decimal(5,2) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `patient_discounts_patient_id_index` (`patient_id`),
  KEY `patient_discounts_section_id_index` (`section_id`),
  KEY `patient_discounts_created_by_index` (`created_by`),
  CONSTRAINT `patient_discounts_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `patient_discounts_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_discounts_section_fk` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_discounts`
--

LOCK TABLES `patient_discounts` WRITE;
/*!40000 ALTER TABLE `patient_discounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_discounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_wallet`
--

DROP TABLE IF EXISTS `patient_wallet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `patient_wallet` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` int(10) unsigned NOT NULL,
  `balance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patient_wallet_patient_id_unique` (`patient_id`),
  CONSTRAINT `patient_wallet_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_wallet`
--

LOCK TABLES `patient_wallet` WRITE;
/*!40000 ALTER TABLE `patient_wallet` DISABLE KEYS */;
INSERT INTO `patient_wallet` VALUES
(6,17,800.00,'2026-03-27 13:37:15'),
(7,6,0.00,'2026-03-25 08:30:43'),
(8,7,0.00,'2026-03-25 08:31:02'),
(9,8,300.00,'2026-03-25 08:43:59'),
(10,9,0.00,'2026-03-25 08:39:58'),
(11,11,0.00,'2026-03-25 08:40:24'),
(12,13,0.00,'2026-03-25 08:40:55'),
(13,15,0.00,'2026-03-25 08:41:36'),
(14,16,0.00,'2026-03-25 08:41:57'),
(15,18,0.00,'2026-03-25 08:42:53'),
(16,10,0.00,'2026-03-25 08:48:57'),
(17,12,0.00,'2026-03-25 08:50:06'),
(18,14,0.00,'2026-03-25 08:51:01'),
(19,19,0.00,'2026-03-25 08:52:30'),
(20,20,0.00,'2026-03-25 11:01:57'),
(21,21,0.00,'2026-03-25 11:04:27'),
(22,22,0.00,'2026-03-25 11:06:03'),
(23,23,0.00,'2026-03-25 11:39:24'),
(24,24,0.00,'2026-03-25 11:44:42'),
(25,25,0.00,'2026-03-25 11:45:27'),
(26,26,0.00,'2026-03-25 11:46:41'),
(27,27,0.00,'2026-03-25 11:47:10'),
(28,28,0.00,'2026-03-25 11:48:05'),
(29,29,0.00,'2026-03-25 11:48:54');
/*!40000 ALTER TABLE `patient_wallet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_wallet_transactions`
--

DROP TABLE IF EXISTS `patient_wallet_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `patient_wallet_transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` int(10) unsigned NOT NULL,
  `turn_id` int(10) unsigned DEFAULT NULL,
  `type` enum('topup','deduction') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `patient_wallet_transactions_patient_id_index` (`patient_id`),
  KEY `patient_wallet_transactions_turn_id_index` (`turn_id`),
  CONSTRAINT `patient_wallet_transactions_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_wallet_transactions_turn_fk` FOREIGN KEY (`turn_id`) REFERENCES `turns` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_wallet_transactions`
--

LOCK TABLES `patient_wallet_transactions` WRITE;
/*!40000 ALTER TABLE `patient_wallet_transactions` DISABLE KEYS */;
INSERT INTO `patient_wallet_transactions` VALUES
(25,8,NULL,'topup',500.00,NULL,'2026-03-25 08:43:59'),
(26,8,NULL,'deduction',200.00,NULL,'2026-03-25 08:43:59'),
(27,17,22,'topup',1000.00,NULL,'2026-03-25 08:44:00'),
(28,17,22,'deduction',200.00,NULL,'2026-03-25 08:44:00');
/*!40000 ALTER TABLE `patient_wallet_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `patients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `phone2` varchar(30) DEFAULT NULL,
  `age` tinyint(3) unsigned DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `medical_notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `referred_by` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patients_referred_by_index` (`referred_by`),
  CONSTRAINT `fk_patients_referred_by` FOREIGN KEY (`referred_by`) REFERENCES `reference_doctors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
INSERT INTO `patients` VALUES
(6,'محمد زمان','-','محمد ناصر','Male','0792412837',NULL,30,NULL,NULL,'2026-03-25 06:58:51','2026-03-25 08:30:42',1),
(7,'سهیلا','-','سلیمان','Female',NULL,NULL,NULL,NULL,NULL,'2026-03-25 07:02:03','2026-03-25 08:31:02',NULL),
(8,'رضا جان','-','غلام سخی','Male',NULL,NULL,NULL,NULL,NULL,'2026-03-25 07:18:20','2026-03-25 07:18:20',NULL),
(9,'فرهاد','-','عبدالخلیل','Male','0799347932',NULL,NULL,NULL,NULL,'2026-03-25 08:18:17','2026-03-25 08:18:17',1),
(10,'لیلا خانم','-','عبدالحکیم','Female',NULL,NULL,NULL,NULL,NULL,'2026-03-25 08:19:04','2026-03-25 08:19:04',NULL),
(11,'عیسی جان','-','گل خان جان','Male','0793009741',NULL,44,NULL,NULL,'2026-03-25 08:20:41','2026-03-25 08:20:41',1),
(12,'زهرا خانم','-','عبدالسلام','Female',NULL,NULL,60,NULL,NULL,'2026-03-25 08:23:38','2026-03-25 08:23:38',1),
(13,'عبدالعزیز','-','محمد نادر','Male',NULL,NULL,NULL,NULL,NULL,'2026-03-25 08:24:28','2026-03-25 08:24:28',NULL),
(14,'فریحه خانم','-','حاجی امین الله','Female',NULL,NULL,NULL,NULL,NULL,'2026-03-25 08:25:11','2026-03-25 08:25:11',NULL),
(15,'جواد','-','سید مومن','Male',NULL,NULL,NULL,NULL,NULL,'2026-03-25 08:26:24','2026-03-25 08:26:24',NULL),
(16,'داود جان','-','علی حسین','Male',NULL,NULL,NULL,NULL,NULL,'2026-03-25 08:26:55','2026-03-25 08:26:55',NULL),
(17,'اقبال جان',NULL,'حاجی گل احمد جان','Male','0795494746',NULL,22,NULL,NULL,'2026-03-25 08:27:45','2026-03-25 10:49:55',1),
(18,'حامد جان','-','حاجی جمعه خان','Male',NULL,NULL,NULL,NULL,NULL,'2026-03-25 08:29:01','2026-03-25 08:29:01',NULL),
(19,'سارا خانم','-','سید حسین','Female','0794473035',NULL,34,NULL,NULL,'2026-03-25 08:30:00','2026-03-25 08:30:00',1),
(20,'حاجی بسم الله','-',NULL,'Male',NULL,NULL,NULL,NULL,NULL,'2026-03-25 10:48:53','2026-03-25 10:48:53',1),
(21,'محمد انور جان','-',NULL,'Male',NULL,NULL,NULL,NULL,NULL,'2026-03-25 10:50:54','2026-03-25 10:50:54',NULL),
(22,'محمد اکبر جان','-',NULL,'Male',NULL,NULL,NULL,NULL,NULL,'2026-03-25 10:51:14','2026-03-25 10:51:14',NULL),
(23,'محمد داود جان','-',NULL,'Male',NULL,NULL,NULL,NULL,NULL,'2026-03-25 10:51:34','2026-03-25 10:51:34',NULL),
(24,'لطیفه خانم','-',NULL,'Female',NULL,NULL,NULL,NULL,NULL,'2026-03-25 10:52:09','2026-03-25 10:52:09',NULL),
(25,'صالحه جان','-',NULL,'Female',NULL,NULL,NULL,NULL,NULL,'2026-03-25 10:52:36','2026-03-25 10:52:36',NULL),
(26,'مهدی جان','-',NULL,'Male',NULL,NULL,NULL,NULL,NULL,'2026-03-25 10:53:04','2026-03-25 10:53:04',NULL),
(27,'خلیل احمد جان','-',NULL,'Male',NULL,NULL,NULL,NULL,NULL,'2026-03-25 10:53:21','2026-03-25 10:53:21',NULL),
(28,'شیما خانم','-',NULL,'Female',NULL,NULL,NULL,NULL,NULL,'2026-03-25 10:53:47','2026-03-25 10:53:47',NULL),
(29,'عبدالله جان','-',NULL,'Male',NULL,NULL,NULL,NULL,NULL,'2026-03-25 10:54:05','2026-03-25 10:54:05',NULL);
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` int(10) unsigned NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(50) NOT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `payments_patient_id_index` (`patient_id`),
  CONSTRAINT `payments_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `module_key` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES
(1,'manage_patients','patients'),
(2,'manage_users','users'),
(3,'manage_roles','roles'),
(4,'manage_turns','turns'),
(5,'manage_payments','payments'),
(6,'view_reports','reports'),
(7,'manage_leaves','leaves'),
(8,'manage_staff','staff'),
(9,'manage_sections','sections'),
(10,'manage_reference_doctors','reference_doctors'),
(11,'manage_expenses','expenses'),
(12,'manage_salaries','salaries'),
(13,'view_safe','safe'),
(14,'manage_safe','safe');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reference_doctors`
--

DROP TABLE IF EXISTS `reference_doctors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `reference_doctors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `specialty` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `clinic_name` varchar(200) DEFAULT NULL,
  `address` text,
  `notes` text,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reference_doctors`
--

LOCK TABLES `reference_doctors` WRITE;
/*!40000 ALTER TABLE `reference_doctors` DISABLE KEYS */;
INSERT INTO `reference_doctors` VALUES
(1,'خودش','مراجعه نموده',NULL,NULL,NULL,NULL,'خود مریض به کلینیک مراجعه کرده است','active','2026-03-21 07:09:25','2026-03-25 06:55:18'),
(2,'روزبه','لطیف','ارتوپیدی',NULL,'افغان سلامت','چهارراهی آمریت',NULL,'active','2026-03-24 17:48:18','2026-03-24 17:48:18'),
(3,'ناصر','لطیف','ارتوپیدی',NULL,'مارکت حضرت ها','چوک شهرنو',NULL,'active','2026-03-24 17:49:04','2026-03-24 17:49:04'),
(4,'شعیب','حیدرزاده','ارتوپیدی',NULL,NULL,NULL,NULL,'active','2026-03-25 05:02:30','2026-03-25 05:02:54'),
(5,'معرفی','شده است',NULL,NULL,NULL,NULL,'از جانب کسی معرفی و به کلینیک مراجعه نموده است','active','2026-03-25 06:56:55','2026-03-25 06:56:55');
/*!40000 ALTER TABLE `reference_doctors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permissions` (
  `role_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `role_permissions_permission_fk` (`permission_id`),
  CONSTRAINT `role_permissions_permission_fk` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_role_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES
(1,1),
(2,1),
(3,1),
(1,2),
(1,3),
(1,4),
(2,4),
(3,4),
(1,5),
(3,5),
(1,6),
(2,6),
(3,6),
(1,7),
(2,7),
(1,8),
(1,9),
(1,10),
(1,11),
(1,12),
(1,13),
(1,14);
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'Administrator','administrator'),
(2,'Therapist','therapist'),
(3,'Receptionist','receptionist');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_adjustments`
--

DROP TABLE IF EXISTS `safe_adjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `safe_adjustments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `safe_transaction_id` int(10) unsigned NOT NULL,
  `previous_balance` decimal(12,2) NOT NULL,
  `adjustment_amount` decimal(12,2) NOT NULL,
  `new_balance` decimal(12,2) NOT NULL,
  `reason` text NOT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `safe_adjustments_transaction_id_index` (`safe_transaction_id`),
  KEY `safe_adjustments_created_by_index` (`created_by`),
  CONSTRAINT `safe_adjustments_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `safe_adjustments_transaction_fk` FOREIGN KEY (`safe_transaction_id`) REFERENCES `safe_transactions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_adjustments`
--

LOCK TABLES `safe_adjustments` WRITE;
/*!40000 ALTER TABLE `safe_adjustments` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_adjustments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_transactions`
--

DROP TABLE IF EXISTS `safe_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `safe_transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('in','out','adjustment') NOT NULL,
  `source` enum('turn_cash','wallet_topup','patient_payment','other_income','expense','salary_payment','wallet_refund','adjustment') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `balance_after` decimal(12,2) NOT NULL,
  `reference_id` int(10) unsigned DEFAULT NULL,
  `reference_table` varchar(50) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `safe_transactions_type_index` (`type`),
  KEY `safe_transactions_source_index` (`source`),
  KEY `safe_transactions_created_by_index` (`created_by`),
  KEY `safe_transactions_created_at_index` (`created_at`),
  CONSTRAINT `safe_transactions_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_transactions`
--

LOCK TABLES `safe_transactions` WRITE;
/*!40000 ALTER TABLE `safe_transactions` DISABLE KEYS */;
INSERT INTO `safe_transactions` VALUES
(2,'in','turn_cash',200.00,200.00,15,'turns','Cash payment for turn #15',NULL,'2026-03-22 11:00:00'),
(3,'in','turn_cash',200.00,400.00,17,'turns','Cash payment for turn #17',NULL,'2026-03-22 11:00:00'),
(4,'in','turn_cash',200.00,600.00,18,'turns','Cash payment for turn #18',NULL,'2026-03-22 11:00:00'),
(5,'in','turn_cash',100.00,700.00,19,'turns','Cash payment for turn #19',NULL,'2026-03-22 11:00:00'),
(6,'in','turn_cash',180.00,880.00,20,'turns','Cash payment for turn #20',NULL,'2026-03-22 11:00:00'),
(7,'in','turn_cash',200.00,1080.00,21,'turns','Cash payment for turn #21',NULL,'2026-03-22 11:00:00'),
(8,'in','turn_cash',200.00,1280.00,23,'turns','Cash payment for turn #23',NULL,'2026-03-22 11:00:00'),
(9,'in','turn_cash',300.00,1580.00,24,'turns','Cash payment for turn #24',NULL,'2026-03-22 11:00:00'),
(10,'in','turn_cash',200.00,1780.00,25,'turns','Cash payment for turn #25',NULL,'2026-03-22 11:00:00'),
(11,'in','turn_cash',200.00,1980.00,26,'turns','Cash payment for turn #26',NULL,'2026-03-22 11:00:00'),
(12,'in','turn_cash',200.00,2180.00,28,'turns','Cash payment for turn #28',NULL,'2026-03-22 11:00:00'),
(13,'in','turn_cash',250.00,2430.00,29,'turns','Cash payment for turn #29',NULL,'2026-03-22 11:00:00'),
(14,'in','turn_cash',500.00,2930.00,30,'turns','Cash payment for turn #30',NULL,'2026-03-22 11:00:00'),
(15,'in','turn_cash',150.00,3080.00,31,'turns','Cash payment for turn #31',NULL,'2026-03-22 11:00:00'),
(16,'in','turn_cash',300.00,3380.00,32,'turns','Cash payment for turn #32',NULL,'2026-03-22 11:00:00'),
(17,'in','turn_cash',300.00,3680.00,33,'turns','Cash payment for turn #33',NULL,'2026-03-22 11:00:00'),
(18,'in','turn_cash',300.00,3980.00,34,'turns','Cash payment for turn #34',NULL,'2026-03-22 11:00:00'),
(19,'in','turn_cash',300.00,4280.00,35,'turns','Cash payment for turn #35',NULL,'2026-03-22 11:00:00'),
(20,'in','turn_cash',300.00,4580.00,36,'turns','Cash payment for turn #36',NULL,'2026-03-22 11:00:00'),
(21,'in','turn_cash',400.00,4980.00,37,'turns','Cash payment for turn #37',NULL,'2026-03-22 11:00:00'),
(22,'in','turn_cash',300.00,5280.00,38,'turns','Cash payment for turn #38',NULL,'2026-03-22 11:00:00'),
(23,'in','wallet_topup',500.00,5780.00,16,'turns','Wallet top-up for turn #16',NULL,'2026-03-22 11:00:00'),
(24,'in','wallet_topup',1000.00,6780.00,22,'turns','Wallet top-up for turn #22',NULL,'2026-03-22 11:00:00'),
(25,'in','wallet_topup',500.00,7280.00,25,'patient_wallet_transactions','Wallet top-up for patient #8',NULL,'2026-03-25 08:43:59'),
(29,'out','turn_cash',300.00,6980.00,38,'turns','REVERSAL of transaction #22',NULL,'2026-03-27 12:32:28'),
(30,'in','turn_cash',250.00,7230.00,38,'turns','Cash on edit of turn #38',4,'2026-03-27 12:32:28'),
(31,'out','turn_cash',300.00,6930.00,38,'turns','برگشت به دلیل ویرایش نوبت شماره 38. تراکنش اصلی صندوق شماره 22.',NULL,'2026-03-27 12:35:42'),
(32,'in','turn_cash',300.00,7230.00,38,'turns','برگشت به دلیل ویرایش نوبت شماره 38. تراکنش اصلی صندوق شماره 29.',NULL,'2026-03-27 12:35:42'),
(33,'out','turn_cash',250.00,6980.00,38,'turns','برگشت به دلیل ویرایش نوبت شماره 38. تراکنش اصلی صندوق شماره 30.',4,'2026-03-27 12:35:42'),
(34,'in','turn_cash',250.00,7230.00,38,'turns','Cash on edit of turn #38',4,'2026-03-27 12:35:42');
/*!40000 ALTER TABLE `safe_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `default_fee` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES
(6,'Female Section',200.00,'2026-03-20 09:53:12','2026-03-24 17:29:55'),
(7,'Male Section',200.00,'2026-03-20 09:53:21','2026-03-24 17:29:41'),
(9,'ماساژ درمانی مردانه',300.00,'2026-03-20 13:26:25','2026-03-20 13:26:25'),
(10,'ماساژ درمانی زنانه',300.00,'2026-03-24 17:25:19','2026-03-24 17:29:03'),
(11,'VIP مردانه',400.00,'2026-03-24 17:31:12','2026-03-24 17:31:12'),
(12,'VIP زنانه',400.00,'2026-03-24 17:31:40','2026-03-24 17:31:40'),
(13,'ویزیت خانگی مردانه',400.00,'2026-03-24 17:33:01','2026-03-24 17:33:01'),
(14,'ویزیت خانگی زنانه',400.00,'2026-03-24 17:33:28','2026-03-24 17:33:28'),
(15,'بیمه مردانه',180.00,'2026-03-24 17:34:00','2026-03-24 17:34:00'),
(16,'بیمه زنانه',180.00,'2026-03-24 17:34:15','2026-03-24 17:34:15'),
(17,'حجامت مردانه',300.00,'2026-03-24 17:34:43','2026-03-24 17:34:43'),
(18,'حجامت زنانه',300.00,'2026-03-24 17:35:04','2026-03-24 17:35:04'),
(19,'بادکشی مردانه',250.00,'2026-03-24 17:35:31','2026-03-24 17:35:31'),
(20,'بادکشی زنانه',250.00,'2026-03-24 17:35:47','2026-03-24 17:35:47');
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `staff_type_id` int(10) unsigned NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `section_id` int(10) unsigned DEFAULT NULL,
  `section` enum('male','female','both','na') NOT NULL DEFAULT 'na',
  `monthly_leave_quota` tinyint(3) unsigned NOT NULL DEFAULT '4',
  `salary` decimal(12,2) NOT NULL DEFAULT '0.00',
  `salary_type` enum('fixed','hourly') NOT NULL DEFAULT 'fixed',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `staff_user_id_index` (`user_id`),
  KEY `staff_staff_type_id_index` (`staff_type_id`),
  CONSTRAINT `staff_staff_type_fk` FOREIGN KEY (`staff_type_id`) REFERENCES `staff_types` (`id`),
  CONSTRAINT `staff_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES
(1,5,4,'سید سعید','عظیمی','male',7,'male',1,20000.00,'fixed','active','2026-03-20 08:04:35','2026-03-25 08:56:40'),
(2,6,2,'حارث','عزیزی','male',11,'male',2,12000.00,'fixed','active','2026-03-20 08:34:46','2026-03-25 04:16:40'),
(3,7,2,'عبدالجبار','حیدری','male',9,'na',0,20000.00,'fixed','active','2026-03-24 17:40:04','2026-03-24 17:40:04'),
(4,8,2,'غلام دستگیر','اکبری','male',19,'na',1,11000.00,'fixed','active','2026-03-24 17:41:39','2026-03-24 17:41:39'),
(5,9,2,'فرزانه','جمالی','female',20,'na',1,9000.00,'fixed','active','2026-03-24 17:43:17','2026-03-24 17:43:18'),
(6,10,2,'عهدیه','سرحدی','female',12,'na',1,9000.00,'fixed','active','2026-03-24 17:43:57','2026-03-24 17:43:57'),
(7,11,2,'سید صلاح','سادات','male',11,'na',1,0.00,'fixed','active','2026-03-24 17:44:51','2026-03-24 17:44:51'),
(8,12,2,'محمد تائب','محمدی','male',13,'na',1,25000.00,'fixed','active','2026-03-24 17:46:04','2026-03-24 17:46:04'),
(9,13,2,'فریحه','رسا','female',14,'na',1,10000.00,'fixed','active','2026-03-24 17:46:54','2026-03-24 17:46:54'),
(10,14,2,'رحیمه','رحیمی','female',20,'na',1,8500.00,'fixed','active','2026-03-25 05:04:49','2026-03-25 05:04:49'),
(11,15,2,'محمد علی','رضایی','male',11,'na',1,15000.00,'fixed','active','2026-03-25 05:06:36','2026-03-25 05:06:36'),
(12,16,2,'سودابه','رسولی','female',20,'na',1,10000.00,'fixed','active','2026-03-25 05:08:16','2026-03-25 05:08:17'),
(13,17,3,'آصفه','محمدی','female',6,'na',0,6500.00,'fixed','active','2026-03-25 05:11:30','2026-03-25 05:11:31'),
(14,18,3,'بصیر احمد','رحمانی','male',7,'na',0,11000.00,'fixed','active','2026-03-25 05:12:10','2026-03-25 05:12:11'),
(15,19,6,'سعید','حیدری','male',7,'na',0,8500.00,'fixed','active','2026-03-25 05:28:18','2026-03-25 05:28:18'),
(16,20,2,'لیلما','ایوبی','female',6,'na',0,10000.00,'fixed','active','2026-03-25 05:29:30','2026-03-25 05:29:31'),
(17,21,2,'عصمت الله','وزیری','male',7,'na',1,9000.00,'fixed','active','2026-03-25 08:57:15','2026-03-25 08:57:15'),
(18,22,2,'فرزاد','یارزاده','male',7,'na',1,9000.00,'fixed','active','2026-03-25 08:59:33','2026-03-25 08:59:33');
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_salary_payments`
--

DROP TABLE IF EXISTS `staff_salary_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff_salary_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `salary_record_id` int(10) unsigned NOT NULL,
  `staff_id` int(10) unsigned NOT NULL,
  `expense_id` int(10) unsigned DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_date` date NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `staff_salary_payments_record_id_index` (`salary_record_id`),
  KEY `staff_salary_payments_staff_id_index` (`staff_id`),
  KEY `staff_salary_payments_expense_id_index` (`expense_id`),
  KEY `staff_salary_payments_created_by_index` (`created_by`),
  CONSTRAINT `staff_salary_payments_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `staff_salary_payments_expense_fk` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `staff_salary_payments_record_fk` FOREIGN KEY (`salary_record_id`) REFERENCES `staff_salary_records` (`id`) ON DELETE CASCADE,
  CONSTRAINT `staff_salary_payments_staff_fk` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_salary_payments`
--

LOCK TABLES `staff_salary_payments` WRITE;
/*!40000 ALTER TABLE `staff_salary_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `staff_salary_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_salary_records`
--

DROP TABLE IF EXISTS `staff_salary_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff_salary_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(10) unsigned NOT NULL,
  `month` varchar(7) NOT NULL,
  `base_salary` decimal(12,2) NOT NULL DEFAULT '0.00',
  `calculated_deduction` decimal(12,2) NOT NULL DEFAULT '0.00',
  `final_salary` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_paid` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('unpaid','partial','paid') NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_staff_month` (`staff_id`,`month`),
  CONSTRAINT `staff_salary_records_staff_fk` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_salary_records`
--

LOCK TABLES `staff_salary_records` WRITE;
/*!40000 ALTER TABLE `staff_salary_records` DISABLE KEYS */;
INSERT INTO `staff_salary_records` VALUES
(1,13,'2026-03',6500.00,0.00,6500.00,0.00,'unpaid','2026-03-25 11:28:39','2026-03-25 11:28:39'),
(2,14,'2026-03',11000.00,0.00,11000.00,0.00,'unpaid','2026-03-25 11:28:39','2026-03-25 11:28:40'),
(3,2,'2026-03',12000.00,0.00,12000.00,0.00,'unpaid','2026-03-25 11:28:40','2026-03-25 11:28:40'),
(4,10,'2026-03',8500.00,0.00,8500.00,0.00,'unpaid','2026-03-25 11:28:40','2026-03-25 11:28:41'),
(5,15,'2026-03',8500.00,0.00,8500.00,0.00,'unpaid','2026-03-25 11:28:41','2026-03-25 11:28:41'),
(6,12,'2026-03',10000.00,0.00,10000.00,0.00,'unpaid','2026-03-25 11:28:41','2026-03-25 11:28:42'),
(7,1,'2026-03',20000.00,0.00,20000.00,0.00,'unpaid','2026-03-25 11:28:42','2026-03-25 11:28:42'),
(8,7,'2026-03',0.00,0.00,0.00,0.00,'unpaid','2026-03-25 11:28:42','2026-03-25 11:28:42'),
(9,3,'2026-03',20000.00,0.00,20000.00,0.00,'unpaid','2026-03-25 11:28:42','2026-03-25 11:28:43'),
(10,17,'2026-03',9000.00,0.00,9000.00,0.00,'unpaid','2026-03-25 11:28:43','2026-03-25 11:28:43'),
(11,6,'2026-03',9000.00,0.00,9000.00,0.00,'unpaid','2026-03-25 11:28:43','2026-03-25 11:28:43'),
(12,4,'2026-03',11000.00,0.00,11000.00,0.00,'unpaid','2026-03-25 11:28:43','2026-03-25 11:28:43'),
(13,18,'2026-03',9000.00,0.00,9000.00,0.00,'unpaid','2026-03-25 11:28:43','2026-03-25 11:28:44'),
(14,5,'2026-03',9000.00,0.00,9000.00,0.00,'unpaid','2026-03-25 11:28:44','2026-03-25 11:28:44'),
(15,9,'2026-03',10000.00,0.00,10000.00,0.00,'unpaid','2026-03-25 11:28:44','2026-03-25 11:28:44'),
(16,16,'2026-03',10000.00,0.00,10000.00,0.00,'unpaid','2026-03-25 11:28:44','2026-03-25 11:28:45'),
(17,8,'2026-03',25000.00,0.00,25000.00,0.00,'unpaid','2026-03-25 11:28:45','2026-03-25 11:28:46'),
(18,11,'2026-03',15000.00,0.00,15000.00,0.00,'unpaid','2026-03-25 11:28:46','2026-03-25 11:28:46');
/*!40000 ALTER TABLE `staff_salary_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_sections`
--

DROP TABLE IF EXISTS `staff_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff_sections` (
  `staff_id` int(10) unsigned NOT NULL,
  `section_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`staff_id`,`section_id`),
  KEY `staff_sections_section_fk` (`section_id`),
  CONSTRAINT `staff_sections_section_fk` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  CONSTRAINT `staff_sections_staff_fk` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_sections`
--

LOCK TABLES `staff_sections` WRITE;
/*!40000 ALTER TABLE `staff_sections` DISABLE KEYS */;
INSERT INTO `staff_sections` VALUES
(13,6),
(16,6),
(1,7),
(14,7),
(15,7),
(17,7),
(18,7),
(3,9),
(4,9),
(11,9),
(5,10),
(10,10),
(12,10),
(2,11),
(7,11),
(11,11),
(6,12),
(8,13),
(9,14),
(4,17),
(11,17),
(5,18),
(10,18),
(12,18),
(4,19),
(11,19),
(5,20),
(10,20),
(12,20);
/*!40000 ALTER TABLE `staff_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_types`
--

DROP TABLE IF EXISTS `staff_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_types`
--

LOCK TABLES `staff_types` WRITE;
/*!40000 ALTER TABLE `staff_types` DISABLE KEYS */;
INSERT INTO `staff_types` VALUES
(1,'Doctor','2026-03-20 07:50:12'),
(2,'Physiotherapist','2026-03-20 07:50:12'),
(3,'Cleaner','2026-03-20 07:50:12'),
(4,'Manager','2026-03-20 07:50:12'),
(5,'Intern','2026-03-20 08:03:48'),
(6,'Helper','2026-03-20 08:03:48'),
(7,'Marketer','2026-03-20 08:03:48');
/*!40000 ALTER TABLE `staff_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `turns`
--

DROP TABLE IF EXISTS `turns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `turns` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` int(10) unsigned NOT NULL,
  `doctor_id` int(10) unsigned NOT NULL,
  `section_id` int(10) unsigned DEFAULT NULL,
  `staff_id` int(10) unsigned DEFAULT NULL,
  `turn_number` tinyint(3) unsigned DEFAULT NULL,
  `fee` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount_percent` decimal(5,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_type` enum('prepaid','cash','deferred','free') NOT NULL DEFAULT 'cash',
  `wallet_deducted` decimal(12,2) NOT NULL DEFAULT '0.00',
  `cash_collected` decimal(12,2) NOT NULL DEFAULT '0.00',
  `topup_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `turn_date` date NOT NULL,
  `turn_time` time DEFAULT NULL,
  `status` enum('accepted','scheduled','completed','cancelled') NOT NULL DEFAULT 'accepted',
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `turns_patient_id_index` (`patient_id`),
  KEY `turns_doctor_id_index` (`doctor_id`),
  CONSTRAINT `turns_doctor_fk` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`),
  CONSTRAINT `turns_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `turns`
--

LOCK TABLES `turns` WRITE;
/*!40000 ALTER TABLE `turns` DISABLE KEYS */;
INSERT INTO `turns` VALUES
(15,6,22,7,18,1,200.00,0.00,0.00,'cash',0.00,200.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 08:43:58','2026-03-25 09:01:22'),
(16,8,22,7,18,1,200.00,0.00,0.00,'prepaid',200.00,0.00,500.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 08:43:59','2026-03-25 09:01:08'),
(17,9,22,7,18,1,200.00,0.00,0.00,'cash',0.00,200.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 08:43:59','2026-03-25 09:00:53'),
(18,11,22,7,18,1,200.00,0.00,0.00,'cash',0.00,200.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 08:43:59','2026-03-25 09:00:43'),
(19,13,22,7,18,1,100.00,0.00,0.00,'cash',0.00,100.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 08:43:59','2026-03-25 09:00:31'),
(20,15,22,7,18,1,180.00,0.00,0.00,'cash',0.00,180.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 08:43:59','2026-03-25 09:00:20'),
(21,16,22,7,18,1,200.00,0.00,0.00,'cash',0.00,200.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 08:43:59','2026-03-25 09:00:07'),
(22,17,22,7,18,1,200.00,0.00,0.00,'prepaid',200.00,0.00,1000.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 08:44:00','2026-03-25 08:59:48'),
(23,18,21,7,17,1,200.00,0.00,0.00,'cash',0.00,200.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 08:44:00','2026-03-25 08:58:40'),
(24,7,20,6,16,1,300.00,0.00,0.00,'cash',0.00,300.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 08:48:33','2026-03-25 12:41:49'),
(25,10,20,6,16,1,200.00,0.00,0.00,'cash',0.00,200.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 08:49:31','2026-03-25 11:50:40'),
(26,12,20,6,16,1,200.00,0.00,0.00,'cash',0.00,200.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 08:52:45','2026-03-25 12:41:34'),
(27,14,20,6,16,1,200.00,0.00,0.00,'deferred',0.00,0.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 08:52:46','2026-03-25 12:41:20'),
(28,19,20,6,16,1,200.00,0.00,0.00,'cash',0.00,200.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 08:52:46','2026-03-25 11:50:28'),
(29,20,7,9,3,1,250.00,0.00,0.00,'cash',0.00,250.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 11:03:13','2026-03-25 12:40:49'),
(30,21,6,11,2,1,500.00,0.00,0.00,'cash',0.00,500.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 11:05:51','2026-03-25 11:49:53'),
(31,22,7,9,3,1,150.00,0.00,0.00,'cash',0.00,150.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 11:07:20','2026-03-25 11:50:10'),
(32,23,6,11,2,1,300.00,0.00,0.00,'cash',0.00,300.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 11:44:31','2026-03-25 12:40:37'),
(33,24,10,12,6,1,300.00,0.00,0.00,'cash',0.00,300.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 11:45:10','2026-03-25 12:40:27'),
(34,25,10,12,6,1,300.00,0.00,0.00,'cash',0.00,300.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 11:46:24','2026-03-25 11:46:24'),
(35,26,7,9,3,1,300.00,0.00,0.00,'cash',0.00,300.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 11:47:01','2026-03-25 12:40:15'),
(36,27,7,9,3,1,300.00,0.00,0.00,'cash',0.00,300.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 11:47:45','2026-03-25 11:47:45'),
(37,28,10,12,6,1,400.00,0.00,0.00,'cash',0.00,400.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 11:48:32','2026-03-25 11:48:32'),
(38,29,7,9,3,1,250.00,0.00,0.00,'cash',0.00,250.00,0.00,'2026-03-22',NULL,'accepted',NULL,'2026-03-25 11:49:33','2026-03-27 12:29:37');
/*!40000 ALTER TABLE `turns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_role_fk` (`role_id`),
  CONSTRAINT `users_role_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'System','Admin','admin','admin@clinic.local','0000000000','$2y$10$reKyugm60bDFU1/CoGnX..qeEJlYRQyI3e.Cp4LpdaHpVk84GupFS',1,1,'2026-03-18 12:08:26','2026-03-18 12:08:26'),
(2,'Default','Therapist','therapist','therapist@clinic.local','0000000001','$2y$10$reKyugm60bDFU1/CoGnX..qeEJlYRQyI3e.Cp4LpdaHpVk84GupFS',2,1,'2026-03-18 12:08:26','2026-03-18 12:08:26'),
(3,'Front','Desk','reception','reception@clinic.local','0000000002','$2y$10$reKyugm60bDFU1/CoGnX..qeEJlYRQyI3e.Cp4LpdaHpVk84GupFS',3,1,'2026-03-18 12:08:26','2026-03-18 12:08:26'),
(4,'Navid','Admin','navid_admin','navid@clinic.local','0700000099','$2y$10$uBvWb9P6pLTMKJrYvGtK2uxzNwRH1pq13J3nVKjN.qtkXvzOhaSfG',1,1,'2026-03-18 12:11:11','2026-03-18 12:11:11'),
(5,'سید سعید','عظیمی','staff.user',NULL,NULL,'$2y$10$f1DFnYnlyGiaG7uoGNQYL.cJnMvPAh.tftptQxQGydR/ge1Q1uzAe',3,0,'2026-03-20 08:04:35','2026-03-20 08:04:35'),
(6,'حارث','عزیزی','staff.user.1',NULL,NULL,'$2y$10$F7TItiMPsIGeqrJIxTixfO0hmkOrT5OQlvQxmKC8FBrpmY3dYckty',3,0,'2026-03-20 08:34:46','2026-03-20 08:34:46'),
(7,'عبدالجبار','حیدری','staff.user.2',NULL,NULL,'$2y$10$82.j.SiqVFnNeJyKhuq1dO.MYRzCgSwP4ZBfDathmSQIoxjvkzjTi',3,0,'2026-03-24 17:40:04','2026-03-24 17:40:04'),
(8,'غلام دستگیر','اکبری','staff.user.3',NULL,NULL,'$2y$10$8xdbfz/0rkFaIQSA2tHks.4Cu6kRwpw4b1buVavTYjHuK5f0E/BF6',3,0,'2026-03-24 17:41:39','2026-03-24 17:41:39'),
(9,'فرزانه','جمالی','staff.user.4',NULL,NULL,'$2y$10$2BIAB4.XsaH3UaGa4HtPAOnwy1jnyRzoE5SxZQ5sRRZ4rykCslqa.',3,0,'2026-03-24 17:43:17','2026-03-24 17:43:17'),
(10,'عهدیه','سرحدی','staff.user.5',NULL,NULL,'$2y$10$ZqFjypRae5m0n2iy/ROsE.hughpMixiU8tWLctOFrHlfkkcVE6SD2',3,0,'2026-03-24 17:43:57','2026-03-24 17:43:57'),
(11,'سید صلاح','سادات','staff.user.6',NULL,NULL,'$2y$10$rWvCqK/3OFmEkHvCRJ1JR.eVi9luK6JWVJ8IcLLnYaW0Rq0n8Bjhe',3,0,'2026-03-24 17:44:51','2026-03-24 17:44:51'),
(12,'محمد تائب','محمدی','staff.user.7',NULL,NULL,'$2y$10$Gqs6YOGaWF70F0P4MYnAS.p9GLH5BHgl745cCge7K/kD.Dm.Hx.mG',3,0,'2026-03-24 17:46:04','2026-03-24 17:46:04'),
(13,'فریحه','رسا','staff.user.8',NULL,NULL,'$2y$10$a20.Ql/oZHuJYBgYqc1n8ejyohiw896zloI3KCu01di2FFktN1DcC',3,0,'2026-03-24 17:46:54','2026-03-24 17:46:54'),
(14,'رحیمه','رحیمی','staff.user.9',NULL,NULL,'$2y$10$WmaLktkfciQS36FRiORvTeJ7oYxGkn3OSBuNLCWj561d0/d1NPIIa',3,0,'2026-03-25 05:04:49','2026-03-25 05:04:49'),
(15,'محمد علی','رضایی','staff.user.10',NULL,NULL,'$2y$10$ol1gSbLw89LHQ0wmZIcQE.a3ZQYxdW1oX92flfwf5VrVfdbpXz1o.',3,0,'2026-03-25 05:06:36','2026-03-25 05:06:36'),
(16,'سودابه','رسولی','staff.user.11',NULL,NULL,'$2y$10$Tjj6jIjJsb8.zH65misEE.esIPHeOtAEcqGY6o4u.ZvgpEdzrcTQO',3,0,'2026-03-25 05:08:16','2026-03-25 05:08:16'),
(17,'آصفه','محمدی','staff.user.12',NULL,NULL,'$2y$10$7hrjDoKPDkugf7RbuynK8ONrt3jwXkf7wGAWuNZtWhxoAVH.QN356',3,0,'2026-03-25 05:11:30','2026-03-25 05:11:30'),
(18,'بصیر احمد','رحمانی','staff.user.13',NULL,NULL,'$2y$10$2IVUhOtukCiNjuPE.n4.tunuGmLqscqCiPzpEncnLXeiMEX8oIXiq',3,0,'2026-03-25 05:12:10','2026-03-25 05:12:10'),
(19,'سعید','حیدری','staff.user.14',NULL,NULL,'$2y$10$XKyR4yplDaorEWesaP/T/.xcT1TDrEMCuHd0JMkpqi2Um9dv5gRte',3,0,'2026-03-25 05:28:18','2026-03-25 05:28:18'),
(20,'لیلما','ایوبی','staff.user.15',NULL,NULL,'$2y$10$524CVKU9.KfSWD7PO.8Zp.ynC5lEU0INQ1aFgKZEOmxD6mtIxu9cO',3,0,'2026-03-25 05:29:29','2026-03-25 05:29:29'),
(21,'عصمت الله','وزیری','staff.user.16',NULL,NULL,'$2y$10$XtCvUZgCvaFx7VOtxdYZPOdy1ygHeah.dqN5hhh4lcZ0peaEt0AQy',3,0,'2026-03-25 08:57:15','2026-03-25 08:57:15'),
(22,'فرزاد','یارزاده','staff.user.17',NULL,NULL,'$2y$10$V6wg4l1K.1AydOHleSmkl.9NV3w3BVitpi2khESVyZvCQB8XV3CuW',3,0,'2026-03-25 08:59:33','2026-03-25 08:59:33');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'sql_test_navid_c'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-27 14:54:01
