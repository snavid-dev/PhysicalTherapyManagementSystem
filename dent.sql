-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2023 at 04:26 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dent`
--

-- --------------------------------------------------------

--
-- Table structure for table `balance_sheet`
--

CREATE TABLE `balance_sheet` (
  `id` int(11) NOT NULL,
  `cr` double DEFAULT NULL,
  `dr` double DEFAULT NULL,
  `create` datetime DEFAULT current_timestamp(),
  `remarks` text DEFAULT NULL,
  `shamsi` varchar(45) NOT NULL,
  `customers_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `balance_sheet`
--

INSERT INTO `balance_sheet` (`id`, `cr`, `dr`, `create`, `remarks`, `shamsi`, `customers_id`, `users_id`) VALUES
(1, 200, NULL, '2023-12-23 13:49:36', '', '1402/10/02', 17, 7);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `lname` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `type` char(1) DEFAULT 'm',
  `users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `lname`, `phone`, `type`, `users_id`) VALUES
(17, 'لابراتوار', 'خیری', '', 'l', 7);

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(10) UNSIGNED NOT NULL,
  `filename` varchar(200) DEFAULT NULL,
  `date` varchar(25) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `desc` text DEFAULT NULL,
  `patient_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `filename`, `date`, `title`, `desc`, `patient_id`) VALUES
(1, '6587d51f0a1dd77ea1cccc1dd4952554c0e6debc48eb46587d51f0a1e0.pdf', '1402/10/03 11:22:15', 'hi', 'textarea', 1707);

-- --------------------------------------------------------

--
-- Table structure for table `labs`
--

CREATE TABLE `labs` (
  `id` int(11) NOT NULL,
  `teeth` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `dr` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `patient_id` int(11) NOT NULL,
  `customers_id` int(11) NOT NULL,
  `give_date` varchar(50) NOT NULL,
  `hour` text NOT NULL,
  `color` varchar(11) NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'p',
  `teeth_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `labs`
--

INSERT INTO `labs` (`id`, `teeth`, `type`, `dr`, `remarks`, `patient_id`, `customers_id`, `give_date`, `hour`, `color`, `status`, `teeth_id`) VALUES
(694, '3244_(1 - بالا - راست)', 'پارشیل متحرک,متحرک کامل', 5, '', 1695, 16, '1402/09/24', '8:00 - 8:30 ق.ظ', 'A3', 'p', '');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `lname` varchar(45) DEFAULT NULL,
  `phone1` int(13) NOT NULL,
  `phone2` int(13) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `pains` text DEFAULT NULL,
  `gender` char(1) DEFAULT 'm',
  `other_pains` text DEFAULT NULL,
  `serial_id` varchar(11) DEFAULT NULL,
  `create` varchar(45) NOT NULL,
  `users_id` int(11) NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'p',
  `remarks` text NOT NULL,
  `doctor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `name`, `lname`, `phone1`, `phone2`, `age`, `address`, `pains`, `gender`, `other_pains`, `serial_id`, `create`, `users_id`, `status`, `remarks`, `doctor_id`) VALUES
(1695, 'سید نوید', 'عظیمی', 792978400, 0, 20, 'سالم', 'هپاتیت,دیابت,فشار خون بالا/پایین', 'm', 'فعلا که نیست', '02_09_01', '1402/09/24 09:23:29', 7, 'p', '', 9),
(1705, 'hamed', 'haidari', 6874, 0, 52, 'herat', 'Hepatitis,Diabetes', 'm', '', '02_09_11', '1402/09/24 22:56:15', 7, 'p', '', 8),
(1706, 'سید نوید', 'عظیمی', 792978400, 0, 20, 'هرات', 'کلیوی,هپاتیت', 'm', '', '02_09_12', '1402/09/26 21:17:23', 7, 'p', '', 9),
(1707, 'مصطفی', 'ایوبی', 792770401, 0, 24, 'هرات', 'تب روماتسمی,کلیوی', 'm', '', '02_10_01', '1402/10/03 10:44:02', 7, 'p', '', 9);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `price`) VALUES
(26, 'RCT', 2500),
(27, 'RCT &amp; Crown', 6000),
(28, 'کشیدن دندان', 500);

-- --------------------------------------------------------

--
-- Table structure for table `tooth`
--

CREATE TABLE `tooth` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `patient_id` int(11) NOT NULL,
  `r_name1` varchar(45) NOT NULL,
  `r_width1` varchar(45) NOT NULL,
  `r_name2` varchar(45) DEFAULT NULL,
  `r_width2` varchar(45) DEFAULT NULL,
  `r_name3` varchar(45) DEFAULT NULL,
  `r_width3` varchar(45) DEFAULT NULL,
  `r_name4` varchar(45) DEFAULT NULL,
  `r_width4` varchar(45) DEFAULT NULL,
  `r_name5` varchar(45) DEFAULT NULL,
  `r_width5` varchar(45) DEFAULT NULL,
  `services` text DEFAULT NULL,
  `price` varchar(45) DEFAULT NULL,
  `location` varchar(45) NOT NULL,
  `details` text NOT NULL,
  `root_number` int(4) NOT NULL,
  `create_date` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tooth`
--

INSERT INTO `tooth` (`id`, `name`, `patient_id`, `r_name1`, `r_width1`, `r_name2`, `r_width2`, `r_name3`, `r_width3`, `r_name4`, `r_width4`, `r_name5`, `r_width5`, `services`, `price`, `location`, `details`, `root_number`, `create_date`) VALUES
(3244, '1', 1695, '', '', '', '', '', '', '', '', '', '', 'RCT,RCT &amp;amp; Crown', '8500', '1', '', 0, '1402/09/24'),
(3245, '1', 1707, '', '', '', '', '', '', '', '', '', '', 'RCT,کشیدن دندان', '3000', '1', '', 0, '1402/10/03');

-- --------------------------------------------------------

--
-- Table structure for table `turn`
--

CREATE TABLE `turn` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `date` varchar(25) DEFAULT NULL,
  `hour` varchar(45) DEFAULT NULL,
  `status` char(1) NOT NULL DEFAULT 'p',
  `cr` int(11) NOT NULL DEFAULT 0,
  `doctor` varchar(200) NOT NULL DEFAULT 'آقای حیدری',
  `pay_date` varchar(50) NOT NULL,
  `doctor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `turn`
--

INSERT INTO `turn` (`id`, `patient_id`, `date`, `hour`, `status`, `cr`, `doctor`, `pay_date`, `doctor_id`) VALUES
(8711, 1705, '1402/09/24', '22:00,23:00', 'a', 2800, 'آقای حیدری', '', 8),
(8739, 1695, '1402/09/26', '11:00,12:00', 'p', 0, 'آقای حیدری', '', 8),
(8740, 1695, '1402/09/26', '12:00,13:00', 'p', 0, 'آقای حیدری', '', 9),
(8741, 1706, '1402/09/26', '21:00,22:00', 'p', 0, 'آقای حیدری', '', 9),
(8742, 1705, '1402/10/03', '8:00,9:00', 'a', 5000, 'آقای حیدری', '', 8),
(8753, 1707, '1402/10/02', '9:00,10:00', 'a', 2200, 'آقای حیدری', '', 9),
(8754, 1707, '1402/10/05', '10:00,11:00', 'p', 400, 'آقای حیدری', '', 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(45) NOT NULL,
  `lname` varchar(45) DEFAULT NULL,
  `role` varchar(45) NOT NULL DEFAULT 'admin',
  `username` varchar(45) NOT NULL,
  `status` varchar(1) DEFAULT 'A',
  `password` varchar(120) NOT NULL,
  `photo` varchar(100) NOT NULL DEFAULT 'default.png',
  `uniqid` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `role`, `username`, `status`, `password`, `photo`, `uniqid`) VALUES
(7, 'سید نوید', 'عظیمی', 'a', 'navid', 'A', 'b8a1ebd0836b58bca6fe44de5f2ac969', 'default.png', 'dsfds'),
(8, 'صبغت الله', 'حیدری', 'D', 'haidary', 'A', 'c7f036c7e90e44c48d79f3966dd06c31', 'default.png', '657c0cdc2ed42'),
(9, 'طه', 'محمودی', 'D', 'طه', 'A', 'c7f036c7e90e44c48d79f3966dd06c31', 'default.png', '657c1247270b6');

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
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_customer_users1_idx` (`users_id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_files_patient1_idx` (`patient_id`);

--
-- Indexes for table `labs`
--
ALTER TABLE `labs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_patient_users1_idx` (`users_id`),
  ADD KEY `fk_patient_users2_idx` (`doctor_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tooth`
--
ALTER TABLE `tooth`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tooth_patient1_idx` (`patient_id`);

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
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `balance_sheet`
--
ALTER TABLE `balance_sheet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `labs`
--
ALTER TABLE `labs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=695;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1708;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tooth`
--
ALTER TABLE `tooth`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3246;

--
-- AUTO_INCREMENT for table `turn`
--
ALTER TABLE `turn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8755;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `balance_sheet`
--
ALTER TABLE `balance_sheet`
  ADD CONSTRAINT `fk_balance_sheet_customer1` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_balance_sheet_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `fk_customer_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `fk_files_patient1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `fk_patient_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_patient_users2` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tooth`
--
ALTER TABLE `tooth`
  ADD CONSTRAINT `fk_tooth_patient1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `turn`
--
ALTER TABLE `turn`
  ADD CONSTRAINT `fk_turn_patient1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_turn_users1` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
