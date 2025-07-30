-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2025 at 06:44 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pig`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8 '),
(2, 'admin1', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8'),
(3, 'admin3', 'f865b53623b121fd34ee5426c792e5c33af8c227'),
(4, 'admin5', 'd4e8e6deaa7b1f8381e09e3e6b83e36f0b681c5c');

-- --------------------------------------------------------

--
-- Table structure for table `breed`
--

CREATE TABLE `breed` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `breed`
--

INSERT INTO `breed` (`id`, `name`) VALUES
(3, 'Duroc'),
(1, 'Hampshire'),
(5, 'Landrace'),
(4, 'Large White'),
(2, 'Native');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `rfid_uid` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_logs`
--

CREATE TABLE `employee_logs` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `check_in` datetime NOT NULL DEFAULT current_timestamp(),
  `check_out` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_and_supplies`
--

CREATE TABLE `feed_and_supplies` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feed_and_supplies`
--

INSERT INTO `feed_and_supplies` (`id`, `name`, `type`, `quantity`, `unit`, `date_added`, `description`) VALUES
(1, 'B-meg', 'Feed', 10, 'Bags', '2024-12-10 03:37:10', 'for fattenings'),
(2, 'Uno', 'Feed', 8, 'Bags', '2024-12-10 03:38:31', 'Piglets');

-- --------------------------------------------------------

--
-- Table structure for table `financial_records`
--

CREATE TABLE `financial_records` (
  `id` int(11) NOT NULL,
  `record_date` date NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `category` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `related_batch` varchar(20) DEFAULT NULL,
  `recorded_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `growth_tracking`
--

CREATE TABLE `growth_tracking` (
  `id` int(11) NOT NULL,
  `batch_id` varchar(20) NOT NULL,
  `track_date` date NOT NULL,
  `avg_weight` decimal(5,2) NOT NULL,
  `feed_amount` decimal(5,2) DEFAULT NULL COMMENT 'in kg',
  `feed_type` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medication`
--

CREATE TABLE `medication` (
  `id` int(11) NOT NULL,
  `batch_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `med_type` enum('vaccine','antibiotic','vitamin','deworming','other') NOT NULL,
  `dosage` varchar(50) NOT NULL,
  `admin_date` date NOT NULL,
  `next_date` date DEFAULT NULL,
  `administered_by` varchar(100) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medication`
--

INSERT INTO `medication` (`id`, `batch_id`, `name`, `med_type`, `dosage`, `admin_date`, `next_date`, `administered_by`, `notes`, `created_at`) VALUES
(1, 'BATCH-20250508150734', 'norovit', 'vitamin', '1ml', '2025-05-08', '2025-05-17', 'jade', 'test', '2025-05-08 13:44:19');

-- --------------------------------------------------------

--
-- Table structure for table `pigs`
--

CREATE TABLE `pigs` (
  `id` int(11) NOT NULL,
  `pigno` varchar(255) NOT NULL,
  `breed_id` int(11) NOT NULL,
  `weight` varchar(10) NOT NULL,
  `img` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `arrived` varchar(10) NOT NULL,
  `remark` text NOT NULL,
  `health_status` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pigs`
--

INSERT INTO `pigs` (`id`, `pigno`, `breed_id`, `weight`, `img`, `gender`, `arrived`, `remark`, `health_status`) VALUES
(2, 'pig-fms-938', 4, '50kg', 'uploadfolder/Koala.jpg', 'female', '2017-11-02', 'This is the content', 'active'),
(3, 'pig-fms-5949', 3, '100kg', 'uploadfolder/306621780_115855024588333_8608901741252533686_n (1).jpg', 'male', '2024-12-02', 'ni jimmy', 'on treatment'),
(4, 'pig-fms-1402', 1, '200', 'uploadfolder/462284065_1199602134604880_213243662245234560_n.jpg', 'male', '02-23-2024', 'boar from', 'on treatment'),
(5, 'pig-fms-1883', 5, '80', 'uploadfolder/67ef5eebf131f_images.jpg', 'female', '2025-05-03', 'ui', 'active'),
(6, 'pig-fms-2490', 1, '90', 'uploadfolder/67ef5e6fb7b3b_IMG_6105.jpg', 'female', '2025-05-01', 'yu', 'sick');

-- --------------------------------------------------------

--
-- Table structure for table `pig_batches`
--

CREATE TABLE `pig_batches` (
  `id` int(11) NOT NULL,
  `batch_id` varchar(20) NOT NULL,
  `photo` varchar(255) DEFAULT 'assets/default_batch.jpg',
  `source` enum('farm','external') NOT NULL,
  `sow_id` varchar(20) DEFAULT NULL,
  `breed_id` int(11) DEFAULT NULL,
  `birth_date` date NOT NULL,
  `total_pigs` int(11) NOT NULL,
  `male_count` int(11) DEFAULT 0,
  `female_count` int(11) DEFAULT 0,
  `deceased_count` int(11) DEFAULT 0,
  `weight_avg` decimal(5,2) DEFAULT NULL COMMENT 'Average weight in kg',
  `status` enum('active','quarantined','sold','deceased') DEFAULT 'active',
  `location` varchar(50) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `batch_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pig_batches`
--

INSERT INTO `pig_batches` (`id`, `batch_id`, `photo`, `source`, `sow_id`, `breed_id`, `birth_date`, `total_pigs`, `male_count`, `female_count`, `deceased_count`, `weight_avg`, `status`, `location`, `remark`, `created_at`, `batch_date`) VALUES
(1, 'BATCH-20250508150734', 'uploads/batches/BATCH-20250508150734.jpg', 'farm', 'SOW-89', 1, '2025-03-07', 12, 7, 5, 0, 0.92, 'active', '4', 'TEST', '2025-05-08 13:08:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quarantine`
--

CREATE TABLE `quarantine` (
  `id` int(11) NOT NULL,
  `batch_id` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `reason` text NOT NULL,
  `diagnosis` varchar(100) DEFAULT NULL,
  `treatment` text DEFAULT NULL,
  `status` enum('active','resolved','terminated') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sold_batches`
--

CREATE TABLE `sold_batches` (
  `id` int(11) NOT NULL,
  `batch_id` varchar(20) NOT NULL,
  `sale_date` date NOT NULL,
  `buyer_name` varchar(100) DEFAULT NULL,
  `buyer_contact` varchar(50) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `total_cost` decimal(10,2) DEFAULT NULL COMMENT 'Production cost',
  `profit` decimal(10,2) GENERATED ALWAYS AS (`total_price` - `total_cost`) STORED,
  `payment_method` enum('cash','check','bank_transfer','other') DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sow`
--

CREATE TABLE `sow` (
  `id` int(11) NOT NULL,
  `sow_id` varchar(20) NOT NULL,
  `breed_id` int(11) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `acquisition_date` date DEFAULT NULL,
  `status` enum('active','retired','deceased') DEFAULT 'active',
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sow`
--

INSERT INTO `sow` (`id`, `sow_id`, `breed_id`, `birth_date`, `acquisition_date`, `status`, `notes`) VALUES
(1, 'SOW-89', 1, '2023-01-31', '2024-12-20', 'active', 'TEST');

-- --------------------------------------------------------

--
-- Table structure for table `sow_gilt_records`
--

CREATE TABLE `sow_gilt_records` (
  `id` int(11) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `breed_id` int(11) NOT NULL,
  `age` int(11) NOT NULL,
  `mating_date` date NOT NULL,
  `labor_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sow_gilt_records`
--

INSERT INTO `sow_gilt_records` (`id`, `picture`, `breed_id`, `age`, `mating_date`, `labor_date`, `description`, `qr_code`) VALUES
(20, 'images.jpg', 1, 2, '2025-04-01', '2025-07-24', 'jk', 'qrcodes/sow_gilt_20.png'),
(21, 'uploadfolder/67ef5e6fb7b3b_IMG_6105.jpg', 3, 3, '2025-02-01', '2025-05-26', 'parity5 ', 'qrcodes/sow_gilt_21.png'),
(22, 'uploadfolder/67ef5eebf131f_images.jpg', 1, 2, '2025-02-05', '2025-05-30', 'parity 3', 'qrcodes/sow_gilt_22.png'),
(23, 'uploadfolder/67ef5f0bcb2ff_360_F_2921173_yl8sF7MsOGme2unABCi5yqDjczMsk8.jpg', 1, 2, '2025-02-06', '2025-05-31', 'parity 3', 'qrcodes/sow_gilt_23.png'),
(24, 'uploadfolder/67ef5f3eed72c_images (2).jpg', 4, 3, '2025-02-07', '2025-06-01', 'parity 2', 'qrcodes/sow_gilt_24.png'),
(25, 'uploadfolder/67f228783bfa8_images (1).jpg', 1, 2, '2025-02-13', '2025-06-07', 'test', 'qrcodes/sow_gilt_25.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','employee','veterinarian','owner') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) DEFAULT 1,
  `name` varchar(255) NOT NULL,
  `sex` enum('male','female') NOT NULL,
  `age` int(11) NOT NULL,
  `address` text NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `birthday` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `status`, `name`, `sex`, `age`, `address`, `contact_number`, `birthday`) VALUES
(1, 'admin5', 'd4e8e6deaa7b1f8381e09e3e6b83e36f0b681c5c', 'admin', '2025-04-03 11:50:28', 1, '', 'male', 0, '', '', NULL),
(2, 'employee1', 'd4e8e6deaa7b1f8381e09e3e6b83e36f0b681c5c', 'employee', '2025-04-03 11:51:09', 1, 'cinnamoroll', 'male', 22, 'cabugao, ilocos sur', '0198390235', NULL),
(3, 'admin6', 'd4e8e6deaa7b1f8381e09e3e6b83e36f0b681c5c', 'veterinarian', '2025-04-03 11:58:47', 1, '', 'male', 0, '', '', NULL),
(4, 'owner1', 'd4e8e6deaa7b1f8381e09e3e6b83e36f0b681c5c', 'owner', '2025-04-03 12:12:56', 0, '', 'male', 0, '', '', NULL),
(6, 'vet4', 'd4e8e6deaa7b1f8381e09e3e6b83e36f0b681c5c', 'veterinarian', '2025-04-03 15:44:38', 0, '', 'male', 0, '', '', NULL),
(9, 'vet6', 'd4e8e6deaa7b1f8381e09e3e6b83e36f0b681c5c', 'veterinarian', '2025-04-06 07:07:41', 1, 'pikachu', 'male', 20, 'cabugao, ilocos sur', '0198390235', '2004-06-09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `breed`
--
ALTER TABLE `breed`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rfid_uid` (`rfid_uid`);

--
-- Indexes for table `employee_logs`
--
ALTER TABLE `employee_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `feed_and_supplies`
--
ALTER TABLE `feed_and_supplies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `financial_records`
--
ALTER TABLE `financial_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `related_batch` (`related_batch`);

--
-- Indexes for table `growth_tracking`
--
ALTER TABLE `growth_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `batch_id` (`batch_id`);

--
-- Indexes for table `medication`
--
ALTER TABLE `medication`
  ADD PRIMARY KEY (`id`),
  ADD KEY `batch_id` (`batch_id`);

--
-- Indexes for table `pigs`
--
ALTER TABLE `pigs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pig_batches`
--
ALTER TABLE `pig_batches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `batch_id` (`batch_id`),
  ADD KEY `breed_id` (`breed_id`),
  ADD KEY `sow_id` (`sow_id`);

--
-- Indexes for table `quarantine`
--
ALTER TABLE `quarantine`
  ADD PRIMARY KEY (`id`),
  ADD KEY `batch_id` (`batch_id`);

--
-- Indexes for table `sold_batches`
--
ALTER TABLE `sold_batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `batch_id` (`batch_id`);

--
-- Indexes for table `sow`
--
ALTER TABLE `sow`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sow_id` (`sow_id`),
  ADD KEY `breed_id` (`breed_id`);

--
-- Indexes for table `sow_gilt_records`
--
ALTER TABLE `sow_gilt_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `breed_id` (`breed_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `breed`
--
ALTER TABLE `breed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_logs`
--
ALTER TABLE `employee_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feed_and_supplies`
--
ALTER TABLE `feed_and_supplies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `financial_records`
--
ALTER TABLE `financial_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `growth_tracking`
--
ALTER TABLE `growth_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medication`
--
ALTER TABLE `medication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pigs`
--
ALTER TABLE `pigs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pig_batches`
--
ALTER TABLE `pig_batches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quarantine`
--
ALTER TABLE `quarantine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sold_batches`
--
ALTER TABLE `sold_batches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sow`
--
ALTER TABLE `sow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sow_gilt_records`
--
ALTER TABLE `sow_gilt_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee_logs`
--
ALTER TABLE `employee_logs`
  ADD CONSTRAINT `employee_logs_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);

--
-- Constraints for table `financial_records`
--
ALTER TABLE `financial_records`
  ADD CONSTRAINT `financial_records_ibfk_1` FOREIGN KEY (`related_batch`) REFERENCES `pig_batches` (`batch_id`);

--
-- Constraints for table `growth_tracking`
--
ALTER TABLE `growth_tracking`
  ADD CONSTRAINT `growth_tracking_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `pig_batches` (`batch_id`);

--
-- Constraints for table `medication`
--
ALTER TABLE `medication`
  ADD CONSTRAINT `medication_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `pig_batches` (`batch_id`);

--
-- Constraints for table `pig_batches`
--
ALTER TABLE `pig_batches`
  ADD CONSTRAINT `pig_batches_ibfk_1` FOREIGN KEY (`breed_id`) REFERENCES `breed` (`id`),
  ADD CONSTRAINT `pig_batches_ibfk_2` FOREIGN KEY (`sow_id`) REFERENCES `sow` (`sow_id`);

--
-- Constraints for table `quarantine`
--
ALTER TABLE `quarantine`
  ADD CONSTRAINT `quarantine_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `pig_batches` (`batch_id`);

--
-- Constraints for table `sold_batches`
--
ALTER TABLE `sold_batches`
  ADD CONSTRAINT `sold_batches_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `pig_batches` (`batch_id`);

--
-- Constraints for table `sow`
--
ALTER TABLE `sow`
  ADD CONSTRAINT `sow_ibfk_1` FOREIGN KEY (`breed_id`) REFERENCES `breed` (`id`);

--
-- Constraints for table `sow_gilt_records`
--
ALTER TABLE `sow_gilt_records`
  ADD CONSTRAINT `sow_gilt_records_ibfk_1` FOREIGN KEY (`breed_id`) REFERENCES `breed` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
