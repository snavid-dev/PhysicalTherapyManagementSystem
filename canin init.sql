-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 09, 2025 at 04:23 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET
SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET
time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `canin`
--

-- --------------------------------------------------------

--
-- Table structure for table `balance_sheet`
--

CREATE TABLE `balance_sheet`
(
	`id`           int(11) NOT NULL,
	`cr` double DEFAULT NULL,
	`dr` double DEFAULT NULL,
	`create`       datetime DEFAULT current_timestamp(),
	`remarks`      text     DEFAULT NULL,
	`shamsi`       varchar(45) NOT NULL,
	`customers_id` int(11) NOT NULL,
	`users_id`     int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `basic_information_teeth`
--

CREATE TABLE `basic_information_teeth`
(
	`id`            int(11) NOT NULL,
	`name`          varchar(200) NOT NULL,
	`department`    varchar(45)  NOT NULL,
	`categories_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `basic_information_teeth`
--

INSERT INTO `basic_information_teeth` (`id`, `name`, `department`, `categories_id`)
VALUES (1, 'Composite', 'restorative', 3),
	   (2, 'Amalgam', 'restorative', 3),
	   (3, 'Class Ionomer', 'restorative', 3),
	   (4, 'Ardant Regular', 'restorative', 5),
	   (5, 'Global Amalgam', 'restorative', 5),
	   (6, 'Amalgam World Work', 'restorative', 5),
	   (7, 'Meridian (Ardent)', 'restorative', 4),
	   (8, 'Zenit', 'restorative', 4),
	   (9, 'Kararay America Composite', 'restorative', 4),
	   (10, 'Shofa', 'restorative', 4),
	   (11, 'Ambar', 'restorative', 9),
	   (12, 'PREBOND universal', 'restorative', 9),
	   (13, 'surface', 'restorative', 1),
	   (14, 'intermediate', 'restorative', 1),
	   (15, 'deep', 'restorative', 1),
	   (16, 'single cone technique', 'endo', 6),
	   (17, 'latral compaction', 'endo', 6),
	   (18, 'warm vertical compaction', 'endo', 6),
	   (19, 'EDTA', 'endo', 8),
	   (20, 'NaOCl', 'endo', 8),
	   (21, 'CHX', 'endo', 8),
	   (22, ' (ZOE) based', 'endo', 7),
	   (23, 'calcium hydroxide based', 'endo', 7),
	   (24, 'glass ionomer-based', 'endo', 7),
	   (25, 'resin-based', 'endo', 7),
	   (26, 'bioceramic', 'endo', 7),
	   (27, 'Varnish', 'restorative', 2),
	   (28, 'calcium hydroxide', 'restorative', 2),
	   (29, 'zinc oxide eugenol', 'restorative', 2),
	   (30, 'zinc phosphate', 'restorative', 2),
	   (31, 'zinc polycarboxylate', 'restorative', 2),
	   (32, 'glass ionomer', 'restorative', 2),
	   (33, 'resin', 'restorative', 2),
	   (34, 'Harvard Restore', 'restorative', 4),
	   (35, '2%', 'Endo', 10),
	   (36, '4%', 'Endo', 10),
	   (37, '6%', 'Endo', 10),
	   (38, 'Noting', 'restorative', 2),
	   (39, 'MTA', 'restorative', 2),
	   (40, 'Full coverage', 'Prosthodontics', 22),
	   (41, 'veneer', 'Prosthodontics', 22),
	   (42, 'inlay', 'Prosthodontics', 22),
	   (43, 'onlay', 'Prosthodontics', 22),
	   (44, 'overlay', 'Prosthodontics', 22),
	   (45, 'Endocrown', 'Prosthodontics', 22),
	   (46, '.Amalgam', 'Prosthodontics', 23),
	   (47, '.Composite', 'Prosthodontics', 23),
	   (48, 'GI', 'Prosthodontics', 23),
	   (50, 'Prefabricated post', 'Prosthodontics', 24),
	   (51, 'Custom post', 'Prosthodontics', 24),
	   (52, 'Metal Screw', 'Prosthodontics', 25),
	   (53, 'Fiber post', 'Prosthodontics', 25),
	   (54, 'Casting post & coee', 'Prosthodontics', 26),
	   (55, 'CAD CAM', 'Prosthodontics', 26),
	   (56, 'full metal', 'Prosthodontics', 27),
	   (57, 'PFM', 'Prosthodontics', 27),
	   (58, 'zirco monolithic', 'Prosthodontics', 27),
	   (59, 'zirco multilithic', 'Prosthodontics', 27),
	   (60, 'PFZ (Layer )', 'Prosthodontics', 27),
	   (61, 'A1', 'Prosthodontics', 28),
	   (62, 'A2', 'Prosthodontics', 28),
	   (63, 'A3', 'Prosthodontics', 28),
	   (64, 'A4', 'Prosthodontics', 28),
	   (65, 'A3.5', 'Prosthodontics', 28),
	   (66, 'B1', 'Prosthodontics', 28),
	   (67, 'B2', 'Prosthodontics', 28),
	   (68, 'B3', 'Prosthodontics', 28),
	   (69, 'B4', 'Prosthodontics', 28),
	   (70, 'C1', 'Prosthodontics', 28),
	   (71, 'c2', 'Prosthodontics', 28),
	   (72, 'C3', 'Prosthodontics', 28),
	   (73, 'C4', 'Prosthodontics', 28),
	   (74, 'D1', 'Prosthodontics', 28),
	   (75, 'D2', 'Prosthodontics', 28),
	   (76, 'D3', 'Prosthodontics', 28),
	   (77, 'D4', 'Prosthodontics', 28),
	   (78, 'Hygienic', 'Prosthodontics', 29),
	   (79, 'Resin Modified  GI', 'Prosthodontics', 23),
	   (81, 'Ridge Lab', 'Prosthodontics', 29),
	   (82, 'Modified  Ridge Lab', 'Prosthodontics', 29),
	   (83, 'Conical', 'Prosthodontics', 29),
	   (84, 'ovate', 'Prosthodontics', 29),
	   (85, 'Conventional', 'Prosthodontics', 30),
	   (86, 'Digital scan', 'Prosthodontics', 30),
	   (87, 'Alginate', 'Prosthodontics', 31),
	   (88, 'C-silicone', 'Prosthodontics', 31),
	   (89, 'A-silocone', 'Prosthodontics', 31),
	   (90, 'zinc phophate', 'Prosthodontics', 33),
	   (91, '.zinc polycarboxylate', 'Prosthodontics', 33),
	   (92, 'zinc oxide euginol', 'Prosthodontics', 33),
	   (93, 'GI.', 'Prosthodontics', 33),
	   (94, 'modified GI', 'Prosthodontics', 33),
	   (95, 'Resin cement', 'Prosthodontics', 33);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories`
(
	`id`   int(11) NOT NULL,
	`name` varchar(200) NOT NULL,
	`type` varchar(45)  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `type`)
VALUES (1, 'عمق پوسیدگی', 'teeth'),
	   (2, 'ماده بیس یا لاینر', 'teeth'),
	   (3, 'ماده ترمیم', 'teeth'),
	   (4, 'برند کامپوزیت', 'teeth'),
	   (5, 'برند امالگام', 'teeth'),
	   (6, 'نوع آبجوریشن', 'teeth'),
	   (7, 'نوع سیلر', 'teeth'),
	   (8, 'نوع اریگیشن', 'teeth'),
	   (9, 'برند باندینگ', 'teeth'),
	   (10, 'تیپر گوتاه', 'teeth'),
	   (11, 'نوع روکش', 'teeth'),
	   (12, 'نوع فینیش لاین', 'teeth'),
	   (13, 'رنگ روکش', 'teeth'),
	   (14, 'ماده قالب گیری', 'teeth'),
	   (15, 'تعداد اباتمنت ها', 'teeth'),
	   (16, 'تعداد پانتیک ها', 'teeth'),
	   (17, 'تعداد مجموع روکش ها', 'teeth'),
	   (18, 'نوع سمنت', 'teeth'),
	   (19, 'برند سمنت', 'teeth'),
	   (20, 'لابراتوار', 'teeth'),
	   (21, 'اکسری', 'files'),
	   (22, 'Type of restoration', 'teeth'),
	   (23, 'Core material', 'teeth'),
	   (24, 'Post', 'teeth'),
	   (25, 'Prefabricated post', 'teeth'),
	   (26, 'Custom post', 'teeth'),
	   (27, 'Material of crown', 'teeth'),
	   (28, 'color', 'teeth'),
	   (29, 'pontic design', 'teeth'),
	   (30, 'Impression Technique', 'teeth'),
	   (31, 'Impression materials', 'teeth'),
	   (32, 'Cement', 'teeth'),
	   (33, 'Cement Material', 'teeth');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers`
(
	`id`       int(11) NOT NULL,
	`name`     varchar(45) NOT NULL,
	`lname`    varchar(45) DEFAULT NULL,
	`phone`    varchar(45) DEFAULT NULL,
	`type`     char(1)     DEFAULT 'm',
	`users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `diagnose`
--

CREATE TABLE `diagnose`
(
	`id`   int(11) NOT NULL,
	`name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `diagnose`
--

INSERT INTO `diagnose` (`id`, `name`)
VALUES (1, 'Dental Caries'),
	   (2, 'Reversible Palpitis'),
	   (3, 'Irreversible Palpitis'),
	   (4, 'HyperSensitivity Tooth'),
	   (5, 'TMJ Disorders'),
	   (6, 'Pericoronititis'),
	   (7, 'Bruxism'),
	   (8, 'Tooth Fractnres'),
	   (9, 'Malcclusion'),
	   (10, 'Peridontal Diseese'),
	   (11, 'hopeless teeth'),
	   (12, 'nicros pulp');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_leave`
--

CREATE TABLE `doctor_leave`
(
	`id`               int(11) NOT NULL,
	`doctor_id`        int(11) NOT NULL,
	`leave_start_date` varchar(45)         DEFAULT NULL,
	`leave_end_date`   varchar(45)         DEFAULT NULL,
	`reason`           varchar(255)        DEFAULT NULL,
	`status`           varchar(5) NOT NULL DEFAULT 'a'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `endo`
--

CREATE TABLE `endo`
(
	`id`          int(11) NOT NULL,
	`r_name1`     varchar(45) DEFAULT NULL,
	`r_width1`    varchar(45) DEFAULT NULL,
	`r_name2`     varchar(45) DEFAULT NULL,
	`r_width2`    varchar(45) DEFAULT NULL,
	`r_name3`     varchar(45) DEFAULT NULL,
	`r_width3`    varchar(45) DEFAULT NULL,
	`r_name4`     varchar(45) DEFAULT NULL,
	`r_width4`    varchar(45) DEFAULT NULL,
	`r_name5`     varchar(45) DEFAULT NULL,
	`r_width5`    varchar(45) DEFAULT NULL,
	`services`    text        DEFAULT NULL,
	`price`       varchar(45) DEFAULT NULL,
	`details`     text        DEFAULT NULL,
	`root_number` int(4) DEFAULT NULL,
	`modify_date` varchar(45) DEFAULT NULL,
	`tooth_id`    int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `endo_has_basic_information_teeth`
--

CREATE TABLE `endo_has_basic_information_teeth`
(
	`endo_id`                    int(11) NOT NULL,
	`basic_information_teeth_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `endo_has_services`
--

CREATE TABLE `endo_has_services`
(
	`endo_id`     int(11) NOT NULL,
	`services_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files`
(
	`id`            int(10) UNSIGNED NOT NULL,
	`filename`      varchar(200) DEFAULT NULL,
	`date`          varchar(25)  DEFAULT NULL,
	`title`         varchar(100) DEFAULT NULL,
	`desc`          text         DEFAULT NULL,
	`patient_id`    int(11) NOT NULL,
	`categories_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `labs`
--

CREATE TABLE `labs`
(
	`id`                  int(11) NOT NULL,
	`teeth`               text,
	`type`                text,
	`dr`                  int(11) DEFAULT NULL,
	`remarks`             text,
	`patient_id`          int(11) NOT NULL,
	`customers_id`        int(11) NOT NULL,
	`give_date`           varchar(50) NOT NULL,
	`hour`                text        NOT NULL,
	`color`               varchar(11) NOT NULL,
	`status`              varchar(1)  NOT NULL DEFAULT 'p',
	`teeth_id`            text        NOT NULL,
	`unit`                varchar(45)          DEFAULT NULL,
	`first_try_status`    varchar(1)  NOT NULL DEFAULT 'p',
	`first_try_datetime`  varchar(20) NOT NULL,
	`first_try_message`   text        NOT NULL,
	`second_try_status`   varchar(1)  NOT NULL DEFAULT 'p',
	`second_try_datetime` varchar(20) NOT NULL,
	`second_try_message`  text        NOT NULL,
	`pay_datetime`        varchar(20) NOT NULL,
	`receive_datetime`    varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE `medicine`
(
	`id`        int(11) NOT NULL,
	`name`      varchar(100) NOT NULL,
	`type`      varchar(100) NOT NULL,
	`doze`      varchar(45) DEFAULT NULL,
	`day`       varchar(45) DEFAULT NULL,
	`times`     varchar(45) DEFAULT NULL,
	`amount`    varchar(45) DEFAULT NULL,
	`usageType` varchar(45) DEFAULT NULL,
	`unit`      text         NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `medicine`
--

INSERT INTO `medicine` (`id`, `name`, `type`, `doze`, `day`, `times`, `amount`, `usageType`, `unit`)
VALUES (9, 'Amoxicilin', 'Cap', '500', '1', '3', '10', '', 'mg'),
	   (10, 'Omeprazole', 'Cap', '40', '1', '3', '10', 'ORAL/قبل از غذا', 'mg'),
	   (11, 'Omeprazole', 'Cap', '20', '1', '3', '10', 'ORAL/قبل از غذا', 'mg'),
	   (12, 'Amoxicilin', 'Tab', '1', '1', '3', '10', '', 'g'),
	   (13, 'Parecetamol', 'Tab', '500', '1', '3', '10', '', 'mg'),
	   (14, 'Ibuprofen', 'Tab', '600', '1', '3', '10', '', 'mg'),
	   (15, 'Ibuprofen', 'Tab', '400', '1', '3', '10', '', 'mg'),
	   (16, 'Ibuprofen', 'Tab', '200', '1', '3', '10', '', 'mg'),
	   (17, 'Metronidazole', 'Tab', '400', '1', '3', '10', '', 'mg'),
	   (19, 'Metronidazole', 'Tab', '200', '1', '3', '10', '', 'mg'),
	   (20, 'Mefenamic-acid', 'Tab', '500', '1', '3', '500', '', 'mg'),
	   (21, 'Mefenamic-acid', 'Tab', '250', '1', '3', '10', '', 'mg'),
	   (22, 'Cheymoral-F', 'Tab', '100', '1', '3', '10', '', 'mg'),
	   (23, 'Flurbrprofen', 'Tab', '100', '1', '3', '100', '', 'mg'),
	   (24, 'Co-amoxiclav', 'Tab', '1', '1', '2', '6', '', 'g'),
	   (25, 'Co-amoxiclav', 'Tab', '625', '1', '3', '6', '', 'mg'),
	   (26, 'Co-amoxiclav', 'Tab', '375', '1', '3', '6', '', 'mg'),
	   (27, 'Diazepam', 'Tab', '5', '1', '1', '10', '', 'mg'),
	   (29, 'Ceftriexone', 'Amp', '1', '1', '2', '6', 'IV', 'g'),
	   (30, 'Ceftriaxone', 'Amp', '500', '1', '2', '6', 'IV', 'mg'),
	   (31, 'Paracetamol', 'Amp', '750', '1', '2', '6', 'IV', 'mg'),
	   (32, 'Paracetamol', 'Amp', '300', '1', '2', '6', 'IV', 'mg'),
	   (34, 'Amoxicilin', 'Cap', '250', '1', '4', '10', 'ORAL', 'mg'),
	   (35, 'Somogel', 'Jel', '20', '1', '3', '1', '', 'g'),
	   (37, 'co-amoxiclav', 'Syr', '60', '1', '3', '', '', 'ml'),
	   (38, 'Brufen', 'Syr', '100', '1', '3', '', '', 'mg'),
	   (39, 'falgyl', 'Syr', '100', '1', '3', '', '', ''),
	   (40, 'Fresh Maouth', 'Solution', '', '1', '3', '', '', 'Bottle');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient`
(
	`id`          int(11) NOT NULL,
	`name`        varchar(45) NOT NULL,
	`lname`       varchar(45)          DEFAULT NULL,
	`phone1`      int(20) NOT NULL,
	`phone2`      int(20) DEFAULT NULL,
	`age`         int(11) DEFAULT NULL,
	`address`     text                 DEFAULT NULL,
	`pains`       text                 DEFAULT NULL,
	`gender`      char(1)              DEFAULT 'm',
	`other_pains` text                 DEFAULT NULL,
	`serial_id`   varchar(11)          DEFAULT NULL,
	`create`      varchar(45) NOT NULL,
	`users_id`    int(11) NOT NULL,
	`status`      varchar(1)  NOT NULL DEFAULT 'p',
	`remarks`     text        NOT NULL,
	`doctor_id`   int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions`
(
	`id`              int(11) NOT NULL,
	`permission_name` varchar(50) NOT NULL,
	`category_id`     int(11) DEFAULT NULL,
	`created_at`      timestamp   NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `permission_name`, `category_id`, `created_at`)
VALUES (1, 'Create Patient', 1, '2024-11-17 11:18:16'),
	   (2, 'Read Patients', 1, '2024-11-17 11:18:16'),
	   (3, 'Read Patient Profile', 1, '2024-11-17 11:18:16'),
	   (4, 'Update Patient Acceptance', 1, '2024-11-17 11:18:16'),
	   (5, 'Update Blocked Patient', 1, '2024-11-17 11:18:16'),
	   (6, 'Delete Patient', 1, '2024-11-17 11:18:16'),
	   (7, 'Create New Patient', 1, '2024-11-17 11:18:16'),
	   (8, 'Update Personal Information', 1, '2024-11-17 11:18:16'),
	   (9, 'Delete Teeth', 1, '2024-11-17 11:18:16'),
	   (10, 'Delete Personal Turn', 1, '2024-11-17 11:18:16'),
	   (11, 'Create Turn', 2, '2024-11-17 11:18:16'),
	   (12, 'Read Today\'s Turn List', 2, '2024-11-17 11:18:16'),
(13, 'Read Turns Index', 2, '2024-11-17 11:18:16'),
(14, 'Create New Turn', 2, '2024-11-17 11:18:16'),
(15, 'Read Sent SMS', 2, '2024-11-17 11:18:16'),
(16, 'Update Turn Acceptance', 2, '2024-11-17 11:18:16'),
(17, 'Read Printed Turns', 2, '2024-11-17 11:18:16'),
(18, 'Update Personal Turn', 2, '2024-11-17 11:18:16'),
(19, 'Create Expenses', 3, '2024-11-17 11:18:16'),
(20, 'Read Today\'s Balance Sheet', 3, '2024-11-17 11:18:16'),
	   (21, 'Create Payment', 3, '2024-11-17 11:18:16'),
	   (22, 'Read Printed Payment', 3, '2024-11-17 11:18:16'),
	   (23, 'Create New Account', 3, '2024-11-17 11:18:16'),
	   (24, 'Update Account', 3, '2024-11-17 11:18:16'),
	   (25, 'Delete Account', 3, '2024-11-17 11:18:16'),
	   (26, 'Delete Receipt', 3, '2024-11-17 11:18:16'),
	   (27, 'Update Receipt', 3, '2024-11-17 11:18:16'),
	   (28, 'Read Filtered Receipts by Date', 3, '2024-11-17 11:18:16'),
	   (29, 'Read Financial Accounts Index', 3, '2024-11-17 11:18:16'),
	   (30, 'Create New Receipt', 3, '2024-11-17 11:18:16'),
	   (31, 'Read Balance', 3, '2024-11-17 11:18:16'),
	   (32, 'Read Financials of Patient', 4, '2024-11-17 11:18:16'),
	   (33, 'Read Paid', 4, '2024-11-17 11:18:16'),
	   (34, 'Read Revenue', 4, '2024-11-17 11:18:16'),
	   (35, 'Read Expenses', 4, '2024-11-17 11:18:16'),
	   (36, 'Read Balance', 4, '2024-11-17 11:18:16'),
	   (37, 'Read Printed Report', 4, '2024-11-17 11:18:16'),
	   (38, 'Read Report Receipts Index', 4, '2024-11-17 11:18:16'),
	   (39, 'Read Printed Receipts', 4, '2024-11-17 11:18:16'),
	   (40, 'Read Call Log Index', 5, '2024-11-17 11:18:16'),
	   (41, 'Read Calls', 5, '2024-11-17 11:18:16'),
	   (42, 'Create Services', 6, '2024-11-17 11:18:16'),
	   (43, 'Read Services', 6, '2024-11-17 11:18:16'),
	   (44, 'Update Services', 6, '2024-11-17 11:18:16'),
	   (45, 'Delete Services', 6, '2024-11-17 11:18:16'),
	   (46, 'Create Medicine', 6, '2024-11-17 11:18:16'),
	   (47, 'Read Medicine', 6, '2024-11-17 11:18:16'),
	   (48, 'Update Medicine', 6, '2024-11-17 11:18:16'),
	   (49, 'Delete Medicine', 6, '2024-11-17 11:18:16'),
	   (50, 'Create Diagnoses', 6, '2024-11-17 11:18:16'),
	   (51, 'Read Diagnoses', 6, '2024-11-17 11:18:16'),
	   (52, 'Update Diagnoses', 6, '2024-11-17 11:18:16'),
	   (53, 'Delete Diagnoses', 6, '2024-11-17 11:18:16'),
	   (54, 'Read Users Index', 7, '2024-11-17 11:18:16'),
	   (55, 'Create User', 7, '2024-11-17 11:18:16'),
	   (56, 'Update User Block', 7, '2024-11-17 11:18:16'),
	   (57, 'Delete User', 7, '2024-11-17 11:18:16'),
	   (58, 'Update User Acceptance', 7, '2024-11-17 11:18:16'),
	   (59, 'Update User', 7, '2024-11-17 11:18:16'),
	   (60, 'Read Payment Information', 8, '2024-11-17 11:18:16'),
	   (61, 'Read Personal Information', 8, '2024-11-17 11:18:16'),
	   (62, 'Create Teeth Entry', 8, '2024-11-17 11:18:16'),
	   (63, 'Create Restorative Entry', 8, '2024-11-17 11:18:16'),
	   (64, 'Create Endodontic Entry', 8, '2024-11-17 11:18:16'),
	   (65, 'Create Prosthetic Entry', 8, '2024-11-17 11:18:16'),
	   (66, 'Update Restorative Entry', 8, '2024-11-17 11:18:16'),
	   (67, 'Update Endodontic Entry', 8, '2024-11-17 11:18:16'),
	   (68, 'Update Prosthetic Entry', 8, '2024-11-17 11:18:16'),
	   (69, 'Update Teeth Entry', 8, '2024-11-17 11:18:16'),
	   (70, 'Create Lab Entry', 8, '2024-11-17 11:18:16'),
	   (71, 'Read Lab Entry', 8, '2024-11-17 11:18:16'),
	   (72, 'Update Lab Entry', 8, '2024-11-17 11:18:16'),
	   (73, 'Delete Lab Entry', 8, '2024-11-17 11:18:16');

-- --------------------------------------------------------

--
-- Table structure for table `permission_categories`
--

CREATE TABLE `permission_categories`
(
	`id`            int(11) NOT NULL,
	`category_name` varchar(50) NOT NULL,
	`created_at`    timestamp   NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `permission_categories`
--

INSERT INTO `permission_categories` (`id`, `category_name`, `created_at`)
VALUES (1, 'Patient Management', '2024-11-17 11:15:13'),
	   (2, 'Turn Management', '2024-11-17 11:15:13'),
	   (3, 'Financial Management', '2024-11-17 11:15:13'),
	   (4, 'Reporting', '2024-11-17 11:15:13'),
	   (5, 'Communication Management', '2024-11-17 11:15:13'),
	   (6, 'Service Management', '2024-11-17 11:15:13'),
	   (7, 'User Management', '2024-11-17 11:15:13'),
	   (8, 'Patient Profile Details', '2024-11-17 11:15:13');

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription`
(
	`id`           int(11) NOT NULL,
	`medicine_1`   int(11) DEFAULT NULL,
	`usageType_1`  varchar(45) DEFAULT NULL,
	`day_1`        varchar(2)  DEFAULT NULL,
	`time_1`       varchar(2)  DEFAULT NULL,
	`doze_1`       varchar(4)  DEFAULT NULL,
	`unit_1`       varchar(10) DEFAULT NULL,
	`amount_1`     varchar(3)  DEFAULT NULL,
	`medicine_2`   int(11) DEFAULT NULL,
	`usageType_2`  varchar(45) DEFAULT NULL,
	`day_2`        varchar(2)  DEFAULT NULL,
	`time_2`       varchar(2)  DEFAULT NULL,
	`doze_2`       varchar(4)  DEFAULT NULL,
	`unit_2`       varchar(10) DEFAULT NULL,
	`amount_2`     varchar(3)  DEFAULT NULL,
	`medicine_3`   int(11) DEFAULT NULL,
	`usageType_3`  varchar(45) DEFAULT NULL,
	`day_3`        varchar(2)  DEFAULT NULL,
	`time_3`       varchar(2)  DEFAULT NULL,
	`doze_3`       varchar(4)  DEFAULT NULL,
	`unit_3`       varchar(10) DEFAULT NULL,
	`amount_3`     varchar(3)  DEFAULT NULL,
	`medicine_4`   int(11) DEFAULT NULL,
	`usageType_4`  varchar(45) DEFAULT NULL,
	`day_4`        varchar(2)  DEFAULT NULL,
	`time_4`       varchar(2)  DEFAULT NULL,
	`doze_4`       varchar(4)  DEFAULT NULL,
	`unit_4`       varchar(10) DEFAULT NULL,
	`amount_4`     varchar(3)  DEFAULT NULL,
	`medicine_5`   int(11) DEFAULT NULL,
	`usageType_5`  varchar(45) DEFAULT NULL,
	`day_5`        varchar(2)  DEFAULT NULL,
	`time_5`       varchar(2)  DEFAULT NULL,
	`doze_5`       varchar(4)  DEFAULT NULL,
	`unit_5`       varchar(10) DEFAULT NULL,
	`amount_5`     varchar(3)  DEFAULT NULL,
	`medicine_6`   int(11) DEFAULT NULL,
	`usageType_6`  varchar(45) DEFAULT NULL,
	`day_6`        varchar(2)  DEFAULT NULL,
	`time_6`       varchar(2)  DEFAULT NULL,
	`doze_6`       varchar(4)  DEFAULT NULL,
	`unit_6`       varchar(10) DEFAULT NULL,
	`amount_6`     varchar(3)  DEFAULT NULL,
	`medicine_7`   int(11) DEFAULT NULL,
	`usageType_7`  varchar(45) DEFAULT NULL,
	`day_7`        varchar(2)  DEFAULT NULL,
	`time_7`       varchar(2)  DEFAULT NULL,
	`doze_7`       varchar(4)  DEFAULT NULL,
	`unit_7`       varchar(10) DEFAULT NULL,
	`amount_7`     varchar(3)  DEFAULT NULL,
	`medicine_8`   int(11) DEFAULT NULL,
	`usageType_8`  varchar(45) DEFAULT NULL,
	`day_8`        varchar(2)  DEFAULT NULL,
	`time_8`       varchar(2)  DEFAULT NULL,
	`doze_8`       varchar(4)  DEFAULT NULL,
	`unit_8`       varchar(10) DEFAULT NULL,
	`amount_8`     varchar(3)  DEFAULT NULL,
	`medicine_9`   int(11) DEFAULT NULL,
	`usageType_9`  varchar(45) DEFAULT NULL,
	`day_9`        varchar(2)  DEFAULT NULL,
	`time_9`       varchar(2)  DEFAULT NULL,
	`doze_9`       varchar(4)  DEFAULT NULL,
	`unit_9`       varchar(10) DEFAULT NULL,
	`amount_9`     varchar(3)  DEFAULT NULL,
	`medicine_10`  int(11) DEFAULT NULL,
	`usageType_10` varchar(45) DEFAULT NULL,
	`day_10`       varchar(2)  DEFAULT NULL,
	`time_10`      varchar(2)  DEFAULT NULL,
	`doze_10`      varchar(4)  DEFAULT NULL,
	`unit_10`      varchar(10) DEFAULT NULL,
	`amount_10`    varchar(3)  DEFAULT NULL,
	`patient_id`   int(11) NOT NULL,
	`users_id`     int(11) NOT NULL,
	`date_time`    varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `prosthodontics`
--

CREATE TABLE `prosthodontics`
(
	`id`          int(11) NOT NULL,
	`details`     text        DEFAULT NULL,
	`price`       int(11) DEFAULT NULL,
	`modify_date` varchar(45) DEFAULT NULL,
	`services`    varchar(80) DEFAULT NULL,
	`tooth_id`    int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `prosthodontics_has_basic_information_teeth`
--

CREATE TABLE `prosthodontics_has_basic_information_teeth`
(
	`Prosthodontics_id`          int(11) NOT NULL,
	`basic_information_teeth_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `prosthodontics_has_services`
--

CREATE TABLE `prosthodontics_has_services`
(
	`Prosthodontics_id` int(11) NOT NULL,
	`services_id`       int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `restorative`
--

CREATE TABLE `restorative`
(
	`id`          int(11) NOT NULL,
	`details`     text        DEFAULT NULL,
	`services`    text        DEFAULT NULL,
	`price`       varchar(45) DEFAULT NULL,
	`modify_date` varchar(45) DEFAULT NULL,
	`tooth_id`    int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `restorative_has_basic_information_teeth`
--

CREATE TABLE `restorative_has_basic_information_teeth`
(
	`restorative_id`             int(11) NOT NULL,
	`basic_information_teeth_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `restorative_has_services`
--

CREATE TABLE `restorative_has_services`
(
	`restorative_id` int(11) NOT NULL,
	`services_id`    int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles`
(
	`id`         int(11) NOT NULL,
	`role_name`  varchar(50) NOT NULL,
	`created_at` timestamp   NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `created_at`)
VALUES (7, 'Doctor', '2024-11-19 07:07:05'),
	   (10, 'Navid', '2024-11-19 07:10:15'),
	   (11, 'Arsalan', '2024-11-19 07:12:42'),
	   (12, 'Admin', '2024-11-20 00:37:04'),
	   (13, 'taha', '2024-11-21 04:03:18'),
	   (14, 'سیبسب', '2024-11-23 06:01:32'),
	   (15, 'delta', '2024-12-07 06:50:57');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions`
(
	`role_id`       int(11) NOT NULL,
	`permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`)
VALUES (7, 1),
	   (7, 2),
	   (7, 3),
	   (7, 4),
	   (10, 1),
	   (10, 2),
	   (10, 3),
	   (10, 4),
	   (10, 5),
	   (10, 6),
	   (10, 7),
	   (10, 8),
	   (10, 9),
	   (10, 10),
	   (11, 1),
	   (11, 2),
	   (11, 3),
	   (11, 4),
	   (11, 5),
	   (11, 6),
	   (11, 7),
	   (11, 8),
	   (11, 9),
	   (11, 10),
	   (11, 11),
	   (11, 12),
	   (11, 13),
	   (11, 14),
	   (11, 15),
	   (11, 16),
	   (11, 17),
	   (11, 18),
	   (11, 19),
	   (11, 20),
	   (11, 21),
	   (11, 22),
	   (11, 23),
	   (11, 24),
	   (11, 25),
	   (11, 26),
	   (11, 27),
	   (11, 28),
	   (11, 29),
	   (11, 30),
	   (11, 31),
	   (11, 32),
	   (11, 33),
	   (11, 34),
	   (11, 35),
	   (11, 36),
	   (11, 37),
	   (11, 38),
	   (11, 39),
	   (11, 40),
	   (11, 41),
	   (11, 42),
	   (11, 43),
	   (11, 44),
	   (11, 45),
	   (11, 46),
	   (11, 47),
	   (11, 48),
	   (11, 49),
	   (11, 50),
	   (11, 51),
	   (11, 52),
	   (11, 53),
	   (11, 54),
	   (11, 55),
	   (11, 56),
	   (11, 57),
	   (11, 58),
	   (11, 59),
	   (11, 60),
	   (11, 61),
	   (11, 62),
	   (11, 63),
	   (11, 64),
	   (11, 65),
	   (11, 66),
	   (11, 67),
	   (11, 68),
	   (11, 69),
	   (11, 70),
	   (11, 71),
	   (11, 72),
	   (11, 73),
	   (12, 1),
	   (12, 2),
	   (12, 3),
	   (12, 4),
	   (12, 5),
	   (12, 6),
	   (12, 7),
	   (12, 8),
	   (12, 9),
	   (12, 10),
	   (12, 11),
	   (12, 12),
	   (12, 13),
	   (12, 14),
	   (12, 15),
	   (12, 16),
	   (12, 17),
	   (12, 18),
	   (12, 19),
	   (12, 20),
	   (12, 21),
	   (12, 22),
	   (12, 23),
	   (12, 24),
	   (12, 25),
	   (12, 26),
	   (12, 27),
	   (12, 28),
	   (12, 29),
	   (12, 30),
	   (12, 31),
	   (12, 32),
	   (12, 33),
	   (12, 34),
	   (12, 35),
	   (12, 36),
	   (12, 37),
	   (12, 38),
	   (12, 39),
	   (12, 40),
	   (12, 41),
	   (12, 42),
	   (12, 43),
	   (12, 44),
	   (12, 45),
	   (12, 46),
	   (12, 47),
	   (12, 48),
	   (12, 49),
	   (12, 50),
	   (12, 51),
	   (12, 52),
	   (12, 53),
	   (12, 54),
	   (12, 55),
	   (12, 56),
	   (12, 57),
	   (12, 58),
	   (12, 59),
	   (12, 60),
	   (12, 61),
	   (12, 62),
	   (12, 63),
	   (12, 64),
	   (12, 65),
	   (12, 66),
	   (12, 67),
	   (12, 68),
	   (12, 69),
	   (12, 70),
	   (12, 71),
	   (12, 72),
	   (12, 73),
	   (13, 1),
	   (13, 4),
	   (13, 5),
	   (13, 6),
	   (13, 9),
	   (15, 1),
	   (15, 2),
	   (15, 3),
	   (15, 4),
	   (15, 5),
	   (15, 6),
	   (15, 7),
	   (15, 8),
	   (15, 9),
	   (15, 10);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services`
(
	`id`         int(10) UNSIGNED NOT NULL,
	`name`       varchar(100)         DEFAULT NULL,
	`price`      int(11) DEFAULT NULL,
	`department` varchar(70) NOT NULL DEFAULT 'Endodantic'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `temp_patient`
--

CREATE TABLE `temp_patient`
(
	`id`          int(11) NOT NULL,
	`name`        varchar(45) NOT NULL,
	`lname`       varchar(45)          DEFAULT NULL,
	`phone1`      int(20) NOT NULL,
	`phone2`      int(20) DEFAULT NULL,
	`age`         int(11) DEFAULT NULL,
	`address`     text                 DEFAULT NULL,
	`pains`       text                 DEFAULT NULL,
	`gender`      char(1)              DEFAULT 'm',
	`other_pains` text                 DEFAULT NULL,
	`create`      varchar(45) NOT NULL,
	`users_id`    int(11) NOT NULL,
	`status`      varchar(1)  NOT NULL DEFAULT 'p',
	`remarks`     text        NOT NULL,
	`doctor_id`   int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `temp_turn`
--

CREATE TABLE `temp_turn`
(
	`id`              int(11) NOT NULL,
	`temp_patient_id` int(11) NOT NULL,
	`date`            varchar(25) DEFAULT NULL,
	`hour`            varchar(45) DEFAULT NULL,
	`cr`              int(11) NOT NULL DEFAULT 0,
	`pay_date`        varchar(50) NOT NULL,
	`doctor_id`       int(11) NOT NULL,
	`in_time`         varchar(45) DEFAULT NULL,
	`out_time`        varchar(45) DEFAULT NULL,
	`exam_time`       varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tooth`
--

CREATE TABLE `tooth`
(
	`id`          int(10) UNSIGNED NOT NULL,
	`name`        varchar(45) DEFAULT NULL,
	`location`    varchar(45) NOT NULL,
	`create_date` varchar(50) NOT NULL,
	`imgAddress`  text        NOT NULL,
	`price`       varchar(45) NOT NULL,
	`users_id`    int(11) NOT NULL,
	`patient_id`  int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tooth_has_diagnose`
--

CREATE TABLE `tooth_has_diagnose`
(
	`tooth_id`    int(10) UNSIGNED NOT NULL,
	`diagnose_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `turn`
--

CREATE TABLE `turn`
(
	`id`             int(11) NOT NULL,
	`patient_id`     int(11) NOT NULL,
	`date`           varchar(25)          DEFAULT NULL,
	`from_time`      varchar(50) NOT NULL,
	`to_time`        varchar(50) NOT NULL,
	`status`         char(1)     NOT NULL DEFAULT 'p',
	`cr`             int(11) NOT NULL DEFAULT 0,
	`pay_date`       varchar(50) NOT NULL,
	`doctor_id`      int(11) NOT NULL,
	`date_phone1`    varchar(10) NOT NULL,
	`hour_phone1`    varchar(20) NOT NULL,
	`remarks_phone1` text        NOT NULL,
	`date_phone2`    varchar(10) NOT NULL,
	`hour_phone2`    varchar(20) NOT NULL,
	`remarks_phone2` text        NOT NULL,
	`date_phone3`    varchar(10) NOT NULL,
	`hour_phone3`    varchar(20) NOT NULL,
	`remarks_phone3` text        NOT NULL,
	`in_time`        varchar(45)          DEFAULT NULL,
	`out_time`       varchar(45)          DEFAULT NULL,
	`exam_time`      varchar(45)          DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users`
(
	`id`                 int(11) NOT NULL,
	`fname`              varchar(45)  NOT NULL,
	`lname`              varchar(45)           DEFAULT NULL,
	`role`               varchar(45)  NOT NULL DEFAULT 'admin',
	`username`           varchar(45)  NOT NULL,
	`status`             varchar(1)            DEFAULT 'A',
	`password`           varchar(120) NOT NULL,
	`photo`              varchar(100) NOT NULL DEFAULT 'default.png',
	`uniqid`             varchar(45)           DEFAULT NULL,
	`role_id`            int(11) DEFAULT NULL,
	`working_start_time` varchar(30)           DEFAULT '08:00',
	`working_end_time`   varchar(30)           DEFAULT '17:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `users` (`id`, `fname`, `lname`, `role`, `username`, `status`, `password`, `photo`, `uniqid`, `role_id`, `working_start_time`, `working_end_time`) VALUES (1, 'سید نوید', 'عظیمی', 'admin', 'Navid', 'A', 'b8a1ebd0836b58bca6fe44de5f2ac969', 'default.png', 'sdfsd', '12', '08:00', '17:00');
-- --------------------------------------------------------

--
-- Table structure for table `waiting_room`
--

CREATE TABLE `waiting_room`
(
	`id`              int(11) NOT NULL,
	`patient_id`      int(11) DEFAULT NULL,
	`turn_id`         int(11) DEFAULT NULL,
	`temp_turn_id`    int(11) DEFAULT NULL,
	`temp_patient_id` int(11) DEFAULT NULL,
	`in_time`         varchar(45) DEFAULT NULL,
	`examine_time`    varchar(45) DEFAULT NULL,
	`out_time`        varchar(45) DEFAULT NULL,
	`status`          varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `balance_sheet`
--
ALTER TABLE `balance_sheet`
	ADD PRIMARY KEY (`id`),
  ADD KEY `fk_balance_sheet_customer1_idx` (`customers_id`),
  ADD KEY `fk_balance_sheet_users1_idx` (`users_id`);

--
-- Indexes for table `basic_information_teeth`
--
ALTER TABLE `basic_information_teeth`
	ADD PRIMARY KEY (`id`),
  ADD KEY `fk_basic_information_teeth_categories1_idx` (`categories_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
	ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
	ADD PRIMARY KEY (`id`),
  ADD KEY `fk_customer_users1_idx` (`users_id`);

--
-- Indexes for table `diagnose`
--
ALTER TABLE `diagnose`
	ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_leave`
--
ALTER TABLE `doctor_leave`
	ADD PRIMARY KEY (`id`),
  ADD KEY `fk_doctor_leave_users1_idx` (`doctor_id`);

--
-- Indexes for table `endo`
--
ALTER TABLE `endo`
	ADD PRIMARY KEY (`id`),
  ADD KEY `fk_endo_tooth1_idx` (`tooth_id`);

--
-- Indexes for table `endo_has_basic_information_teeth`
--
ALTER TABLE `endo_has_basic_information_teeth`
	ADD PRIMARY KEY (`endo_id`, `basic_information_teeth_id`),
  ADD KEY `fk_endo_has_basic_information_teeth_basic_information_teeth_idx` (`basic_information_teeth_id`),
  ADD KEY `fk_endo_has_basic_information_teeth_endo1_idx` (`endo_id`);

--
-- Indexes for table `endo_has_services`
--
ALTER TABLE `endo_has_services`
	ADD PRIMARY KEY (`endo_id`, `services_id`),
  ADD KEY `fk_endo_has_services_services1_idx` (`services_id`),
  ADD KEY `fk_endo_has_services_endo1_idx` (`endo_id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
	ADD PRIMARY KEY (`id`),
  ADD KEY `fk_files_patient1_idx` (`patient_id`),
  ADD KEY `fk_files_categories1_idx` (`categories_id`);

--
-- Indexes for table `labs`
--
ALTER TABLE `labs`
	ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicine`
--
ALTER TABLE `medicine`
	ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
	ADD PRIMARY KEY (`id`),
  ADD KEY `fk_patient_users1_idx` (`users_id`),
  ADD KEY `fk_patient_users2_idx` (`doctor_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
	ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `permission_categories`
--
ALTER TABLE `permission_categories`
	ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
	ADD PRIMARY KEY (`id`),
  ADD KEY `fk_prescription_patient1_idx` (`patient_id`),
  ADD KEY `fk_prescription_users1_idx` (`users_id`);

--
-- Indexes for table `prosthodontics`
--
ALTER TABLE `prosthodontics`
	ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Prosthodontics_tooth1_idx` (`tooth_id`);

--
-- Indexes for table `prosthodontics_has_basic_information_teeth`
--
ALTER TABLE `prosthodontics_has_basic_information_teeth`
	ADD PRIMARY KEY (`Prosthodontics_id`, `basic_information_teeth_id`),
  ADD KEY `fk_Prosthodontics_has_basic_information_teeth_basic_informa_idx` (`basic_information_teeth_id`),
  ADD KEY `fk_Prosthodontics_has_basic_information_teeth_Prosthodontic_idx` (`Prosthodontics_id`);

--
-- Indexes for table `prosthodontics_has_services`
--
ALTER TABLE `prosthodontics_has_services`
	ADD PRIMARY KEY (`Prosthodontics_id`, `services_id`),
  ADD KEY `fk_Prosthodontics_has_services_services1_idx` (`services_id`),
  ADD KEY `fk_Prosthodontics_has_services_Prosthodontics1_idx` (`Prosthodontics_id`);

--
-- Indexes for table `restorative`
--
ALTER TABLE `restorative`
	ADD PRIMARY KEY (`id`),
  ADD KEY `fk_restorative_tooth1_idx` (`tooth_id`);

--
-- Indexes for table `restorative_has_basic_information_teeth`
--
ALTER TABLE `restorative_has_basic_information_teeth`
	ADD PRIMARY KEY (`restorative_id`, `basic_information_teeth_id`),
  ADD KEY `fk_restorative_has_basic_information_teeth_basic_informatio_idx` (`basic_information_teeth_id`),
  ADD KEY `fk_restorative_has_basic_information_teeth_restorative1_idx` (`restorative_id`);

--
-- Indexes for table `restorative_has_services`
--
ALTER TABLE `restorative_has_services`
	ADD PRIMARY KEY (`restorative_id`, `services_id`),
  ADD KEY `fk_restorative_has_services_services1_idx` (`services_id`),
  ADD KEY `fk_restorative_has_services_restorative1_idx` (`restorative_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
	ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
	ADD PRIMARY KEY (`role_id`, `permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
	ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp_patient`
--
ALTER TABLE `temp_patient`
	ADD PRIMARY KEY (`id`),
  ADD KEY `fk_temp_patient_users1` (`users_id`),
  ADD KEY `fk_temp_patient_users2` (`doctor_id`);

--
-- Indexes for table `temp_turn`
--
ALTER TABLE `temp_turn`
	ADD PRIMARY KEY (`id`),
  ADD KEY `fk_turn_users1_idx` (`doctor_id`),
  ADD KEY `fk_temp_turn_temp_patient1_idx` (`temp_patient_id`);

--
-- Indexes for table `tooth`
--
ALTER TABLE `tooth`
	ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tooth_patient1_idx` (`patient_id`),
  ADD KEY `fk_tooth_users1_idx` (`users_id`);

--
-- Indexes for table `tooth_has_diagnose`
--
ALTER TABLE `tooth_has_diagnose`
	ADD PRIMARY KEY (`tooth_id`, `diagnose_id`),
  ADD KEY `fk_tooth_has_diagnose_diagnose1_idx` (`diagnose_id`),
  ADD KEY `fk_tooth_has_diagnose_tooth1_idx` (`tooth_id`);

--
-- Indexes for table `turn`
--
ALTER TABLE `turn`
	ADD PRIMARY KEY (`id`),
  ADD KEY `fk_turn_patient1_idx` (`patient_id`),
  ADD KEY `fk_turn_users1_idx` (`doctor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
	ADD PRIMARY KEY (`id`),
  ADD KEY `fk_role_id` (`role_id`);

--
-- Indexes for table `waiting_room`
--
ALTER TABLE `waiting_room`
	ADD PRIMARY KEY (`id`),
  ADD KEY `fk_waiting_room_patient1_idx` (`patient_id`),
  ADD KEY `fk_waiting_room_turn1_idx` (`turn_id`),
  ADD KEY `fk_waiting_room_temp_turn1_idx` (`temp_turn_id`),
  ADD KEY `fk_waiting_room_temp_patient1_idx` (`temp_patient_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `balance_sheet`
--
ALTER TABLE `balance_sheet`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `basic_information_teeth`
--
ALTER TABLE `basic_information_teeth`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `diagnose`
--
ALTER TABLE `diagnose`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `doctor_leave`
--
ALTER TABLE `doctor_leave`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `endo`
--
ALTER TABLE `endo`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=750;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
	MODIFY `id` int (10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `labs`
--
ALTER TABLE `labs`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `medicine`
--
ALTER TABLE `medicine`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1124;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `permission_categories`
--
ALTER TABLE `permission_categories`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `prosthodontics`
--
ALTER TABLE `prosthodontics`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `restorative`
--
ALTER TABLE `restorative`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=771;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
	MODIFY `id` int (10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `temp_patient`
--
ALTER TABLE `temp_patient`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `temp_turn`
--
ALTER TABLE `temp_turn`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2857;

--
-- AUTO_INCREMENT for table `tooth`
--
ALTER TABLE `tooth`
	MODIFY `id` int (10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1327;

--
-- AUTO_INCREMENT for table `turn`
--
ALTER TABLE `turn`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2883;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `waiting_room`
--
ALTER TABLE `waiting_room`
	MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `balance_sheet`
--
ALTER TABLE `balance_sheet`
	ADD CONSTRAINT `fk_balance_sheet_customer1` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_balance_sheet_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON
DELETE
NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `basic_information_teeth`
--
ALTER TABLE `basic_information_teeth`
	ADD CONSTRAINT `fk_basic_information_teeth_categories1` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
	ADD CONSTRAINT `fk_customer_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `doctor_leave`
--
ALTER TABLE `doctor_leave`
	ADD CONSTRAINT `fk_doctor_leave_users1` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `endo`
--
ALTER TABLE `endo`
	ADD CONSTRAINT `fk_endo_tooth1` FOREIGN KEY (`tooth_id`) REFERENCES `tooth` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `endo_has_basic_information_teeth`
--
ALTER TABLE `endo_has_basic_information_teeth`
	ADD CONSTRAINT `fk_endo_has_basic_information_teeth_basic_information_teeth1` FOREIGN KEY (`basic_information_teeth_id`) REFERENCES `basic_information_teeth` (`id`),
  ADD CONSTRAINT `fk_endo_has_basic_information_teeth_endo1` FOREIGN KEY (`endo_id`) REFERENCES `endo` (`id`) ON
DELETE
CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `endo_has_services`
--
ALTER TABLE `endo_has_services`
	ADD CONSTRAINT `fk_endo_has_services_endo1` FOREIGN KEY (`endo_id`) REFERENCES `endo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_endo_has_services_services1` FOREIGN KEY (`services_id`) REFERENCES `services` (`id`) ON
DELETE
CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
	ADD CONSTRAINT `fk_files_categories1` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_files_patient1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON
DELETE
NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
	ADD CONSTRAINT `fk_patient_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_patient_users2` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON
DELETE
NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
	ADD CONSTRAINT `permissions_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `permission_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prosthodontics`
--
ALTER TABLE `prosthodontics`
	ADD CONSTRAINT `fk_Prosthodontics_tooth1` FOREIGN KEY (`tooth_id`) REFERENCES `tooth` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prosthodontics_has_basic_information_teeth`
--
ALTER TABLE `prosthodontics_has_basic_information_teeth`
	ADD CONSTRAINT `fk_Prosthodontics_has_basic_information_teeth_Prosthodontics1` FOREIGN KEY (`Prosthodontics_id`) REFERENCES `prosthodontics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Prosthodontics_has_basic_information_teeth_basic_informati1` FOREIGN KEY (`basic_information_teeth_id`) REFERENCES `basic_information_teeth` (`id`) ON
DELETE
NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `prosthodontics_has_services`
--
ALTER TABLE `prosthodontics_has_services`
	ADD CONSTRAINT `fk_Prosthodontics_has_services_Prosthodontics1` FOREIGN KEY (`Prosthodontics_id`) REFERENCES `prosthodontics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Prosthodontics_has_services_services1` FOREIGN KEY (`services_id`) REFERENCES `services` (`id`) ON
DELETE
NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `restorative`
--
ALTER TABLE `restorative`
	ADD CONSTRAINT `fk_restorative_tooth1` FOREIGN KEY (`tooth_id`) REFERENCES `tooth` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `restorative_has_basic_information_teeth`
--
ALTER TABLE `restorative_has_basic_information_teeth`
	ADD CONSTRAINT `fk_restorative_has_basic_information_teeth_basic_information_1` FOREIGN KEY (`basic_information_teeth_id`) REFERENCES `basic_information_teeth` (`id`),
  ADD CONSTRAINT `fk_restorative_has_basic_information_teeth_restorative1` FOREIGN KEY (`restorative_id`) REFERENCES `restorative` (`id`) ON
DELETE
CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `restorative_has_services`
--
ALTER TABLE `restorative_has_services`
	ADD CONSTRAINT `fk_restorative_has_services_restorative1` FOREIGN KEY (`restorative_id`) REFERENCES `restorative` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_restorative_has_services_services1` FOREIGN KEY (`services_id`) REFERENCES `services` (`id`) ON
DELETE
CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
	ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON
DELETE
CASCADE;

--
-- Constraints for table `temp_patient`
--
ALTER TABLE `temp_patient`
	ADD CONSTRAINT `fk_temp_patient_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_temp_patient_users2` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON
DELETE
NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `temp_turn`
--
ALTER TABLE `temp_turn`
	ADD CONSTRAINT `fk_temp_turn_temp_patient1` FOREIGN KEY (`temp_patient_id`) REFERENCES `temp_patient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_turn_users10` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON
DELETE
NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tooth`
--
ALTER TABLE `tooth`
	ADD CONSTRAINT `fk_tooth_patient1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tooth_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON
DELETE
NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tooth_has_diagnose`
--
ALTER TABLE `tooth_has_diagnose`
	ADD CONSTRAINT `fk_tooth_has_diagnose_diagnose1` FOREIGN KEY (`diagnose_id`) REFERENCES `diagnose` (`id`),
  ADD CONSTRAINT `fk_tooth_has_diagnose_tooth1` FOREIGN KEY (`tooth_id`) REFERENCES `tooth` (`id`) ON
DELETE
CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `turn`
--
ALTER TABLE `turn`
	ADD CONSTRAINT `fk_turn_patient1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_turn_users1` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON
DELETE
NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
	ADD CONSTRAINT `fk_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `waiting_room`
--
ALTER TABLE `waiting_room`
	ADD CONSTRAINT `fk_waiting_room_patient1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_waiting_room_temp_patient1` FOREIGN KEY (`temp_patient_id`) REFERENCES `temp_patient` (`id`) ON
DELETE
CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_waiting_room_temp_turn1` FOREIGN KEY (`temp_turn_id`) REFERENCES `temp_turn` (`id`) ON DELETE
CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_waiting_room_turn1` FOREIGN KEY (`turn_id`) REFERENCES `turn` (`id`) ON DELETE
CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
