-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2025 at 03:42 PM
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
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Success',
  `log_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `status`, `log_time`) VALUES
(1, 1, 'login_success', 'User admin5 logged in successfully.', '127.0.0.1', 'Success', '2025-05-18 23:21:19'),
(2, 1, 'login_success', 'User admin5 logged in successfully.', '127.0.0.1', 'Success', '2025-05-18 23:25:43'),
(3, 1, 'login_success', 'User admin5 logged in successfully.', '127.0.0.1', 'Success', '2025-05-18 23:27:29'),
(4, 1, 'Logout', 'User has logged out.', '127.0.0.1', 'Success', '2025-05-18 23:28:53'),
(5, 1, 'login_success', 'User admin5 logged in successfully.', '127.0.0.1', 'Success', '2025-05-18 23:29:05'),
(6, 1, 'Logout', 'User \'admin5\' has logged out.', '127.0.0.1', 'Success', '2025-05-18 23:31:07'),
(7, 1, 'login_success', 'User admin5 logged in successfully.', '127.0.0.1', 'Success', '2025-05-18 23:31:16'),
(8, 1, 'Logout', 'User \'admin5\' has logged out.', '127.0.0.1', 'Success', '2025-05-18 23:38:32'),
(9, 0, 'login_failed', 'Failed login attempt for username: admin5', '127.0.0.1', 'Success', '2025-05-18 23:38:36'),
(10, 0, 'login_failed', 'Failed login attempt for username: admin5', '127.0.0.1', 'Success', '2025-05-18 23:38:43'),
(11, 1, 'login_success', 'User admin5 logged in successfully.', '127.0.0.1', 'Success', '2025-05-18 23:38:48'),
(12, 1, 'login_success', 'User admin5 logged in successfully.', '127.0.0.1', 'Success', '2025-05-19 02:20:30'),
(13, 1, 'Logout', 'User \'admin5\' has logged out.', NULL, 'Success', '2025-05-19 02:37:35'),
(14, 0, 'login_failed', 'Failed login attempt for username: admin5', NULL, 'Success', '2025-05-19 02:37:39'),
(15, 0, 'login_failed', 'Failed login attempt for username: admin5', NULL, 'Success', '2025-05-19 02:37:44'),
(16, 1, 'login_success', 'User admin5 logged in successfully.', NULL, 'Success', '2025-05-19 02:37:49'),
(17, 1, 'Logout', 'User \'admin5\' has logged out.', NULL, 'Success', '2025-05-19 02:53:44'),
(18, 1, 'login_success', 'User admin5 logged in successfully.', NULL, 'Success', '2025-05-19 02:54:44'),
(19, 1, 'Logout', 'User \'admin5\' has logged out.', NULL, 'Success', '2025-05-19 03:00:53'),
(20, 1, 'login_success', 'User logged in successfully', '::1', 'success', '2025-05-19 03:00:55'),
(21, 1, 'Logout', 'User \'admin5\' has logged out.', NULL, 'Success', '2025-05-19 03:03:13'),
(22, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-19 03:03:14'),
(23, 1, 'logout', 'User logged out', '::1', 'success', '2025-05-19 03:04:45'),
(24, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-19 03:04:55'),
(25, 1, 'logout', 'User logged out', '::1', 'success', '2025-05-19 03:05:35'),
(26, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-19 03:05:37'),
(27, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-19 03:09:38'),
(28, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-19 03:09:41'),
(29, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-19 03:21:50'),
(30, 1, 'page_access', 'Accessed Add New Batch page - External Purchase Batch', '127.0.0.1', 'Success', '2025-05-19 03:27:26'),
(31, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-19 03:27:29'),
(32, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-19 03:38:05'),
(33, 1, 'sow_selection', 'Selected sow ID: 22', '127.0.0.1', 'Success', '2025-05-19 03:38:08'),
(34, 1, 'breed_selection', 'Selected breed: Duroc', '127.0.0.1', 'Success', '2025-05-19 03:38:09'),
(35, 1, 'pig_count_change', 'Updated total count to 10', '127.0.0.1', 'Success', '2025-05-19 03:38:12'),
(36, 1, 'pig_count_change', 'Updated male count to 3', '127.0.0.1', 'Success', '2025-05-19 03:38:13'),
(37, 1, 'pig_count_change', 'Updated female count to 7', '127.0.0.1', 'Success', '2025-05-19 03:38:15'),
(38, 1, 'weight_input', 'Entered average weight: 2 kg', '127.0.0.1', 'Success', '2025-05-19 03:38:15'),
(39, 1, 'file_upload_attempt', 'Attempted to upload batch photo', '127.0.0.1', 'Success', '2025-05-19 03:38:24'),
(40, 1, 'pen_selection', 'Selected pen: nursery', '127.0.0.1', 'Success', '2025-05-19 03:38:27'),
(41, 1, 'note_entry', 'Added note: re...', '127.0.0.1', 'Success', '2025-05-19 03:38:32'),
(42, 1, 'batch_submission_attempt', 'Attempting to submit batch data', '127.0.0.1', 'Success', '2025-05-19 03:38:32'),
(43, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-19 08:43:18'),
(44, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-19 12:11:05'),
(45, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-19 12:14:58'),
(46, 0, 'login_failed', 'Failed login attempt for username: Yza', '127.0.0.1', 'failed', '2025-05-19 12:15:16'),
(47, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-19 12:15:49'),
(48, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-19 12:16:01'),
(49, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-19 12:16:41'),
(50, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-19 12:17:00'),
(51, 0, 'login_failed', 'Failed login attempt for username: Yza', '127.0.0.1', 'failed', '2025-05-19 12:17:22'),
(52, 10, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-19 12:17:45'),
(53, 10, 'logout', 'User Yza logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-19 12:18:51'),
(54, 10, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-19 12:18:54'),
(55, 10, 'logout', 'User Yza logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-19 12:18:56'),
(56, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-19 12:19:00'),
(57, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-19 12:20:22'),
(58, 1, 'pen_selection', 'Selected pen: nursery', '127.0.0.1', 'Success', '2025-05-19 12:20:27'),
(59, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-19 12:28:33'),
(60, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-19 13:43:09'),
(61, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-19 13:44:34'),
(62, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-20 13:35:33'),
(63, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-20 15:06:57'),
(64, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-20 15:07:27'),
(65, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-20 15:07:27'),
(66, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-20 15:07:30'),
(67, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-20 15:07:32'),
(68, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-20 15:07:36'),
(69, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-20 15:34:42'),
(70, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-20 15:35:00'),
(71, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-20 19:01:00'),
(72, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-20 19:01:48'),
(73, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-20 19:05:22'),
(74, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-20 19:12:09'),
(75, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-20 19:17:03'),
(76, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-20 19:22:52'),
(77, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-20 19:22:58'),
(78, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-20 19:25:57'),
(79, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-20 19:31:27'),
(80, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-20 19:33:17'),
(81, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-20 19:39:19'),
(82, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-20 19:40:22'),
(83, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-20 19:43:34'),
(84, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-20 19:49:17'),
(85, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-20 20:24:29'),
(86, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-21 00:38:11'),
(87, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-21 01:37:05'),
(88, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-21 01:39:08'),
(89, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-21 02:16:05'),
(90, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-21 14:13:49'),
(91, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-21 14:15:36'),
(92, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-21 16:49:18'),
(93, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-21 16:50:10'),
(94, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-21 16:58:15'),
(95, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-21 19:53:18'),
(96, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-21 19:53:31'),
(97, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-21 23:56:42'),
(98, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-22 13:56:39'),
(99, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-22 14:01:47'),
(100, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-22 14:01:50'),
(101, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-22 14:32:43'),
(102, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-22 14:32:48'),
(103, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-22 20:24:14'),
(104, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-22 20:27:32'),
(105, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-22 20:27:46'),
(106, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-22 20:45:06'),
(107, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-22 20:45:18'),
(108, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-22 20:48:43'),
(109, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-22 20:48:46'),
(110, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-22 21:27:26'),
(111, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-22 21:28:51'),
(112, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-22 21:29:23'),
(113, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-22 21:29:57'),
(114, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-22 21:37:03'),
(115, 1, 'page_access', 'Accessed Add New Batch page - External Purchase Batch', '127.0.0.1', 'Success', '2025-05-22 21:37:16'),
(116, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-22 21:38:16'),
(117, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 00:03:20'),
(118, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 00:04:00'),
(119, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 00:05:52'),
(120, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 00:16:18'),
(121, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 00:16:27'),
(122, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 00:23:50'),
(123, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 00:25:55'),
(124, 1, 'sow_selection', 'Selected sow ID: 21', '127.0.0.1', 'Success', '2025-05-23 00:26:05'),
(125, 1, 'breed_selection', 'Selected breed: Duroc', '127.0.0.1', 'Success', '2025-05-23 00:26:07'),
(126, 1, 'pig_count_change', 'Updated total count to 15', '127.0.0.1', 'Success', '2025-05-23 00:26:11'),
(127, 1, 'pig_count_change', 'Updated male count to 10', '127.0.0.1', 'Success', '2025-05-23 00:26:12'),
(128, 1, 'pig_count_change', 'Updated female count to 5', '127.0.0.1', 'Success', '2025-05-23 00:26:14'),
(129, 1, 'weight_input', 'Entered average weight: 2 kg', '127.0.0.1', 'Success', '2025-05-23 00:26:17'),
(130, 1, 'file_upload_attempt', 'Attempted to upload batch photo', '127.0.0.1', 'Success', '2025-05-23 00:26:33'),
(131, 1, 'batch_submission_attempt', 'Attempting to submit batch data', '127.0.0.1', 'Success', '2025-05-23 00:26:43'),
(132, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 00:27:44'),
(133, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 00:30:59'),
(134, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 00:50:30'),
(135, 1, 'breed_selection', 'Selected breed: Hampshire', '127.0.0.1', 'Success', '2025-05-23 00:51:34'),
(136, 1, 'pig_count_change', 'Updated total count to 5', '127.0.0.1', 'Success', '2025-05-23 00:51:37'),
(137, 1, 'pig_count_change', 'Updated total count to 10', '127.0.0.1', 'Success', '2025-05-23 00:51:40'),
(138, 1, 'pig_count_change', 'Updated male count to 5', '127.0.0.1', 'Success', '2025-05-23 00:51:43'),
(139, 1, 'pig_count_change', 'Updated female count to 5', '127.0.0.1', 'Success', '2025-05-23 00:51:46'),
(140, 1, 'sow_selection', 'Selected sow ID: 20', '127.0.0.1', 'Success', '2025-05-23 00:51:47'),
(141, 1, 'weight_input', 'Entered average weight: 3 kg', '127.0.0.1', 'Success', '2025-05-23 00:51:50'),
(142, 1, 'batch_submission_attempt', 'Attempting to submit batch data', '127.0.0.1', 'Success', '2025-05-23 00:52:01'),
(143, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 00:55:44'),
(144, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 00:55:59'),
(145, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 00:59:48'),
(146, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 01:01:32'),
(147, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 01:01:32'),
(148, 1, 'batch_creation_canceled', 'Canceled new batch creation', '127.0.0.1', 'Success', '2025-05-23 01:02:06'),
(149, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 01:10:50'),
(150, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 01:10:50'),
(151, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 01:11:20'),
(152, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 01:11:21'),
(153, 1, 'file_upload_attempt', 'Attempted to upload batch photo', '127.0.0.1', 'Success', '2025-05-23 01:11:27'),
(154, 1, 'sow_selection', 'Selected sow ID: 21', '127.0.0.1', 'Success', '2025-05-23 01:11:41'),
(155, 1, 'pig_count_change', 'Updated total count to 10', '127.0.0.1', 'Success', '2025-05-23 01:11:49'),
(156, 1, 'pig_count_change', 'Updated male count to 5', '127.0.0.1', 'Success', '2025-05-23 01:11:53'),
(157, 1, 'pig_count_change', 'Updated female count to 5', '127.0.0.1', 'Success', '2025-05-23 01:11:55'),
(158, 1, 'weight_input', 'Entered average weight: 3 kg', '127.0.0.1', 'Success', '2025-05-23 01:11:57'),
(159, 1, 'breed_selection', 'Selected breed: Duroc', '127.0.0.1', 'Success', '2025-05-23 01:11:58'),
(160, 1, 'batch_submission_attempt', 'Attempting to submit batch data', '127.0.0.1', 'Success', '2025-05-23 01:12:12'),
(161, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 01:12:46'),
(162, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 01:12:47'),
(163, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 01:18:52'),
(164, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 01:18:53'),
(165, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 01:24:44'),
(166, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 01:24:44'),
(167, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 01:33:50'),
(168, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-23 01:33:50'),
(169, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 03:17:13'),
(170, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 03:18:13'),
(171, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 03:24:38'),
(172, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 03:25:18'),
(173, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 03:29:50'),
(174, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 03:31:02'),
(175, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 03:31:18'),
(176, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 03:31:34'),
(177, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 03:32:20'),
(178, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 03:37:07'),
(179, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 03:38:29'),
(180, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 03:39:22'),
(181, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 03:42:55'),
(182, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 03:43:12'),
(183, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 03:45:59'),
(184, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 03:46:02'),
(185, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 03:48:24'),
(186, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 03:50:04'),
(187, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 03:58:05'),
(188, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:03:55'),
(189, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:05:23'),
(190, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 04:05:28'),
(191, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:05:40'),
(192, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 04:05:44'),
(193, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:06:41'),
(194, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 04:08:14'),
(195, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:08:20'),
(196, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 04:08:35'),
(197, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:08:40'),
(198, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 04:08:53'),
(199, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:08:57'),
(200, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 04:09:38'),
(201, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:09:41'),
(202, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 04:10:33'),
(203, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:10:35'),
(204, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 04:10:49'),
(205, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:10:53'),
(206, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:15:02'),
(207, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:15:09'),
(208, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:15:50'),
(209, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 04:16:58'),
(210, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:17:05'),
(211, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 04:17:15'),
(212, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:17:47'),
(213, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 04:18:01'),
(214, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:18:32'),
(215, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 04:18:51'),
(216, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:18:55'),
(217, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 04:19:09'),
(218, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:19:12'),
(219, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 04:19:41'),
(220, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 04:20:00'),
(221, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 20:55:56'),
(222, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 20:56:47'),
(223, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 21:16:06'),
(224, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 21:16:10'),
(225, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 22:04:32'),
(226, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 22:07:47'),
(227, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 23:16:18'),
(228, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 23:17:49'),
(229, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 23:25:44'),
(230, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-23 23:27:35'),
(231, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-23 23:37:29'),
(232, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-24 01:28:56'),
(233, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-24 01:28:56'),
(234, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-24 01:31:10'),
(235, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-24 01:31:11'),
(236, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-24 01:32:45'),
(237, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-24 01:32:45'),
(238, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-24 01:32:55'),
(239, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-24 01:32:55'),
(240, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-24 17:41:10'),
(241, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-24 21:26:54'),
(242, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-24 21:27:03'),
(243, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-24 22:32:28'),
(244, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-24 22:42:03'),
(245, 0, 'login_failed', 'Failed login attempt for username: sayaan', '127.0.0.1', 'failed', '2025-05-24 22:45:23'),
(246, 0, 'login_failed', 'Failed login attempt for username: sayaan', '127.0.0.1', 'failed', '2025-05-24 22:49:03'),
(247, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-24 23:01:46'),
(248, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-24 23:07:04'),
(249, 12, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-24 23:07:57'),
(250, 12, 'logout', 'User sayaan logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-24 23:10:42'),
(251, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-24 23:10:45'),
(252, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-26 05:02:57'),
(253, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-05-26 09:26:03'),
(254, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-05-26 09:29:55'),
(255, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-26 09:42:20'),
(256, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-26 09:42:20'),
(257, 1, 'sow_selection', 'Selected sow ID: 23', '127.0.0.1', 'Success', '2025-05-26 09:42:30'),
(258, 1, 'breed_selection', 'Selected breed: Hampshire', '127.0.0.1', 'Success', '2025-05-26 09:42:57'),
(259, 1, 'pig_count_change', 'Updated total count to 12', '127.0.0.1', 'Success', '2025-05-26 09:43:04'),
(260, 1, 'pig_count_change', 'Updated male count to 6', '127.0.0.1', 'Success', '2025-05-26 09:43:09'),
(261, 1, 'pig_count_change', 'Updated female count to 6', '127.0.0.1', 'Success', '2025-05-26 09:43:31'),
(262, 1, 'weight_input', 'Entered average weight: 2 kg', '127.0.0.1', 'Success', '2025-05-26 09:44:06'),
(263, 1, 'note_entry', 'Added note: test...', '127.0.0.1', 'Success', '2025-05-26 09:45:00'),
(264, 1, 'batch_submission_attempt', 'Attempting to submit batch data', '127.0.0.1', 'Success', '2025-05-26 09:45:18'),
(265, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-26 09:48:41'),
(266, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-26 09:48:41'),
(267, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-26 09:48:59'),
(268, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-05-26 09:48:59'),
(269, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-06-10 16:27:40'),
(270, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-06-11 16:52:45'),
(271, 0, 'login_failed', 'Failed login attempt for username: admin5', '127.0.0.1', 'failed', '2025-06-17 01:58:48'),
(272, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-06-17 01:58:59'),
(273, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-06-25 15:14:58'),
(274, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-06-25 15:20:19'),
(275, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-06-25 15:32:46'),
(276, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-06-25 15:34:33'),
(277, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-06-25 15:38:09'),
(278, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-06-25 15:38:53'),
(279, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-06-25 15:40:43'),
(280, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-06-25 15:43:16'),
(281, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-06-25 15:53:48'),
(282, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-06-25 16:00:21'),
(283, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-06-25 16:00:21'),
(284, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-06-25 16:01:03'),
(285, 0, 'login_failed', 'Failed login attempt for username: admin5', '127.0.0.1', 'failed', '2025-07-07 20:43:54'),
(286, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-07 21:47:30'),
(287, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:49:24'),
(288, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:50:52'),
(289, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:51:41'),
(290, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:29'),
(291, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:29'),
(292, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:29'),
(293, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:29'),
(294, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:29'),
(295, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:30'),
(296, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:30'),
(297, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:30'),
(298, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:31'),
(299, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:31'),
(300, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:32'),
(301, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:32'),
(302, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:33'),
(303, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:37'),
(304, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:38'),
(305, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:39'),
(306, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:39'),
(307, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:40'),
(308, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:41'),
(309, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:42'),
(310, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:43'),
(311, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:44'),
(312, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:45'),
(313, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:46'),
(314, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:48'),
(315, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:52'),
(316, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:53'),
(317, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:54'),
(318, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:56'),
(319, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:57'),
(320, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:55:59'),
(321, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:00'),
(322, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:02'),
(323, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:03'),
(324, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:05'),
(325, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-07 21:56:06'),
(326, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:07'),
(327, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:08'),
(328, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:10'),
(329, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:12'),
(330, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:13'),
(331, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:15'),
(332, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:17'),
(333, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:19'),
(334, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:21'),
(335, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:23'),
(336, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:25'),
(337, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:27'),
(338, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:29'),
(339, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:31'),
(340, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:33'),
(341, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:35'),
(342, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:38'),
(343, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:40'),
(344, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:42'),
(345, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:44'),
(346, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:50'),
(347, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:53'),
(348, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:55'),
(349, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:56:57'),
(350, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:00'),
(351, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:03'),
(352, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:05'),
(353, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:08'),
(354, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:11'),
(355, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:13'),
(356, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:16'),
(357, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:19'),
(358, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:22'),
(359, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:25'),
(360, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:28'),
(361, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:30'),
(362, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:33'),
(363, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:36'),
(364, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:40'),
(365, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:43'),
(366, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:46'),
(367, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:49'),
(368, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:52'),
(369, 0, 'login_failed', 'Failed login attempt for username: admin5', '192.168.100.91', 'failed', '2025-07-07 21:57:55'),
(370, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 21:59:33'),
(371, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:00:17'),
(372, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:01:28'),
(373, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:16'),
(374, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:16'),
(375, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:16'),
(376, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:16'),
(377, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:16'),
(378, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:17'),
(379, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:17'),
(380, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:17'),
(381, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:18'),
(382, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:18'),
(383, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:19'),
(384, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:19'),
(385, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:20'),
(386, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:20'),
(387, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:21'),
(388, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:22'),
(389, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:22'),
(390, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:25'),
(391, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:26'),
(392, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:27'),
(393, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:28'),
(394, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:29'),
(395, 14, 'login_success', 'User logged in successfully', '192.168.100.91', 'success', '2025-07-07 22:02:30'),
(396, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:31'),
(397, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:32'),
(398, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:33'),
(399, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:34'),
(400, 0, 'login_failed', 'Failed login attempt for username: unpadmin', '192.168.100.91', 'failed', '2025-07-07 22:02:35'),
(401, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-07-07 22:04:20'),
(402, 14, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-07 22:04:33'),
(403, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-08 08:47:13'),
(404, 0, 'login_failed', '1', '127.0.0.1', 'failed', '2025-07-08 08:47:33'),
(405, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-08 08:47:39'),
(406, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-07-08 08:47:49'),
(407, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-08 08:47:56'),
(408, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-07-08 08:47:59'),
(409, 0, 'login_failed', '1', '127.0.0.1', 'failed', '2025-07-08 08:48:08'),
(410, 0, 'login_failed', '1', '127.0.0.1', 'failed', '2025-07-08 08:49:22'),
(411, 0, 'login_failed', '1', '127.0.0.1', 'failed', '2025-07-08 09:23:49'),
(412, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-28 17:31:07'),
(413, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-29 00:33:29'),
(414, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-07-29 00:36:04'),
(415, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-29 00:36:19'),
(416, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:39:24'),
(417, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:39:24'),
(418, 1, 'page_access', 'Accessed Add New Batch page - External Purchase Batch', '127.0.0.1', 'Success', '2025-07-29 01:39:32'),
(419, 1, 'page_access', 'Accessed Add New Batch page - External Purchase Batch', '127.0.0.1', 'Success', '2025-07-29 01:39:32'),
(420, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:42:38'),
(421, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:42:38'),
(422, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:43:02'),
(423, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:43:02'),
(424, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:43:09'),
(425, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:43:09'),
(426, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:43:31'),
(427, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:43:31'),
(428, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:43:33'),
(429, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:43:33'),
(430, 1, 'page_access', 'Accessed Add New Batch page - External Purchase Batch', '127.0.0.1', 'Success', '2025-07-29 01:43:45'),
(431, 1, 'page_access', 'Accessed Add New Batch page - External Purchase Batch', '127.0.0.1', 'Success', '2025-07-29 01:43:46'),
(432, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:47:43'),
(433, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:47:43'),
(434, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:48:40'),
(435, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:48:41'),
(436, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:48:56'),
(437, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:48:56'),
(438, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:49:17'),
(439, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:49:17'),
(440, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:50:12'),
(441, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 01:50:12'),
(442, 1, 'batch_creation_canceled', 'Canceled new batch creation', '127.0.0.1', 'Success', '2025-07-29 01:50:26'),
(443, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 02:15:27'),
(444, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 02:15:27'),
(445, 1, 'sow_selection', 'Selected sow ID: 21', '127.0.0.1', 'Success', '2025-07-29 02:15:30'),
(446, 1, 'weight_input', 'Entered average weight: 2 kg', '127.0.0.1', 'Success', '2025-07-29 02:15:43');
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `status`, `log_time`) VALUES
(447, 1, 'pig_count_change', 'Updated total count to 10', '127.0.0.1', 'Success', '2025-07-29 02:15:46'),
(448, 1, 'breed_selection', 'Selected breed: Native', '127.0.0.1', 'Success', '2025-07-29 02:15:48'),
(449, 1, 'pig_count_change', 'Updated male count to 5', '127.0.0.1', 'Success', '2025-07-29 02:15:52'),
(450, 1, 'pig_count_change', 'Updated female count to 5', '127.0.0.1', 'Success', '2025-07-29 02:15:54'),
(451, 1, 'note_entry', 'Added note: test...', '127.0.0.1', 'Success', '2025-07-29 02:16:09'),
(452, 1, 'batch_submission_attempt', 'Attempting to submit batch data', '127.0.0.1', 'Success', '2025-07-29 02:16:10'),
(453, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 02:23:45'),
(454, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 02:23:45'),
(455, 1, 'batch_creation_canceled', 'Canceled new batch creation', '127.0.0.1', 'Success', '2025-07-29 02:23:52'),
(456, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 03:05:14'),
(457, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 03:05:15'),
(458, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 03:13:51'),
(459, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 03:13:51'),
(460, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 03:14:00'),
(461, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 03:14:00'),
(462, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 03:14:22'),
(463, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 03:14:22'),
(464, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 03:14:23'),
(465, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 03:14:23'),
(466, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 03:14:51'),
(467, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 03:14:51'),
(468, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 03:14:58'),
(469, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 03:14:58'),
(470, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 03:38:49'),
(471, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 03:38:49'),
(472, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-29 19:31:32'),
(473, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 19:35:41'),
(474, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 19:35:42'),
(475, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 19:38:08'),
(476, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 19:38:08'),
(477, 1, 'batch_creation_canceled', 'Canceled new batch creation', '127.0.0.1', 'Success', '2025-07-29 19:38:32'),
(478, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 19:54:41'),
(479, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 19:54:41'),
(480, 1, 'file_upload_attempt', 'Attempted to upload batch photo', '127.0.0.1', 'Success', '2025-07-29 19:55:16'),
(481, 1, 'file_upload_attempt', 'Attempted to upload batch photo', '127.0.0.1', 'Success', '2025-07-29 19:55:19'),
(482, 1, 'pig_count_change', 'Updated total count to 10', '127.0.0.1', 'Success', '2025-07-29 19:55:25'),
(483, 1, 'pig_count_change', 'Updated male count to 3', '127.0.0.1', 'Success', '2025-07-29 19:55:26'),
(484, 1, 'pig_count_change', 'Updated female count to 7', '127.0.0.1', 'Success', '2025-07-29 19:55:28'),
(485, 1, 'breed_selection', 'Selected breed: Crossbreed', '127.0.0.1', 'Success', '2025-07-29 19:55:29'),
(486, 1, 'weight_input', 'Entered average weight: 5 kg', '127.0.0.1', 'Success', '2025-07-29 19:55:36'),
(487, 1, 'sow_selection', 'Selected sow ID: 20', '127.0.0.1', 'Success', '2025-07-29 19:55:43'),
(488, 1, 'batch_submission_attempt', 'Attempting to submit batch data', '127.0.0.1', 'Success', '2025-07-29 19:55:46'),
(489, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 20:04:10'),
(490, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 20:04:10'),
(491, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 21:35:37'),
(492, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 21:35:37'),
(493, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 21:40:09'),
(494, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 21:40:09'),
(495, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 21:40:18'),
(496, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 21:40:18'),
(497, 1, 'sow_selection', 'Selected sow ID: 25', '127.0.0.1', 'Success', '2025-07-29 21:40:31'),
(498, 1, 'file_upload_attempt', 'Attempted to upload batch photo', '127.0.0.1', 'Success', '2025-07-29 21:40:39'),
(499, 1, 'weight_input', 'Entered average weight: 3 kg', '127.0.0.1', 'Success', '2025-07-29 21:40:44'),
(500, 1, 'pig_count_change', 'Updated total count to 5', '127.0.0.1', 'Success', '2025-07-29 21:40:46'),
(501, 1, 'pig_count_change', 'Updated male count to 2', '127.0.0.1', 'Success', '2025-07-29 21:40:47'),
(502, 1, 'pig_count_change', 'Updated female count to 3', '127.0.0.1', 'Success', '2025-07-29 21:40:49'),
(503, 1, 'breed_selection', 'Selected breed: Crossbreed', '127.0.0.1', 'Success', '2025-07-29 21:40:50'),
(504, 1, 'batch_submission_attempt', 'Attempting to submit batch data', '127.0.0.1', 'Success', '2025-07-29 21:41:01'),
(505, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 21:42:07'),
(506, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 21:42:07'),
(507, 1, 'file_upload_attempt', 'Attempted to upload batch photo', '127.0.0.1', 'Success', '2025-07-29 21:42:11'),
(508, 1, 'sow_selection', 'Selected sow ID: 21', '127.0.0.1', 'Success', '2025-07-29 21:42:13'),
(509, 1, 'breed_selection', 'Selected breed: Crossbreed', '127.0.0.1', 'Success', '2025-07-29 21:42:18'),
(510, 1, 'pig_count_change', 'Updated total count to 3', '127.0.0.1', 'Success', '2025-07-29 21:42:22'),
(511, 1, 'weight_input', 'Entered average weight: 3 kg', '127.0.0.1', 'Success', '2025-07-29 21:42:25'),
(512, 1, 'pig_count_change', 'Updated male count to 1', '127.0.0.1', 'Success', '2025-07-29 21:42:25'),
(513, 1, 'pig_count_change', 'Updated female count to 2', '127.0.0.1', 'Success', '2025-07-29 21:42:30'),
(514, 1, 'batch_submission_attempt', 'Attempting to submit batch data', '127.0.0.1', 'Success', '2025-07-29 21:42:38'),
(515, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 21:56:36'),
(516, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-29 21:56:36'),
(517, 1, 'file_upload_attempt', 'Attempted to upload batch photo', '127.0.0.1', 'Success', '2025-07-29 21:56:43'),
(518, 1, 'sow_selection', 'Selected sow ID: 21', '127.0.0.1', 'Success', '2025-07-29 21:56:45'),
(519, 1, 'breed_selection', 'Selected breed: Crossbreed', '127.0.0.1', 'Success', '2025-07-29 21:56:52'),
(520, 1, 'pig_count_change', 'Updated total count to 5', '127.0.0.1', 'Success', '2025-07-29 21:56:54'),
(521, 1, 'pig_count_change', 'Updated male count to 2', '127.0.0.1', 'Success', '2025-07-29 21:56:55'),
(522, 1, 'pig_count_change', 'Updated female count to 3', '127.0.0.1', 'Success', '2025-07-29 21:56:57'),
(523, 1, 'weight_input', 'Entered average weight: 3 kg', '127.0.0.1', 'Success', '2025-07-29 21:57:01'),
(524, 1, 'batch_submission_attempt', 'Attempting to submit batch data', '127.0.0.1', 'Success', '2025-07-29 21:57:05'),
(525, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-07-29 22:01:48'),
(526, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-29 22:17:11'),
(527, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-07-29 22:22:50'),
(528, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-29 22:22:59'),
(529, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-07-29 22:23:27'),
(530, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-29 22:23:41'),
(531, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-07-29 22:23:54'),
(532, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-29 22:24:06'),
(533, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-07-29 22:24:23'),
(534, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-29 22:24:52'),
(535, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-07-29 22:25:15'),
(536, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-29 22:26:18'),
(537, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-07-29 22:26:33'),
(538, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-29 22:26:39'),
(539, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-07-29 22:27:05'),
(540, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-29 22:28:14'),
(541, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-07-29 22:43:48'),
(542, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-29 22:44:02'),
(543, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-30 01:30:00'),
(544, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-30 01:39:44'),
(545, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-30 02:03:13'),
(546, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-30 13:47:33'),
(547, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-07-30 16:12:08'),
(548, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-30 16:14:30'),
(549, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-30 16:17:24'),
(550, 1, 'page_access', 'Accessed Add New Batch page - Farm Production Batch', '127.0.0.1', 'Success', '2025-07-30 16:17:24'),
(551, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-30 16:25:24'),
(552, 1, 'logout', 'User admin5 logged out from 127.0.0.1', '127.0.0.1', 'success', '2025-07-30 16:28:24'),
(553, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-30 19:35:27'),
(554, 1, 'login_success', 'User logged in successfully', '127.0.0.1', 'success', '2025-07-30 19:58:51');

-- --------------------------------------------------------

--
-- Table structure for table `batch_pens`
--

CREATE TABLE `batch_pens` (
  `id` int(11) NOT NULL,
  `batch_id` varchar(64) NOT NULL,
  `pen_id` int(11) NOT NULL,
  `pigs_assigned` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `batch_pens`
--

INSERT INTO `batch_pens` (`id`, `batch_id`, `pen_id`, `pigs_assigned`) VALUES
(1, 'BATCH-20250522182555', 1, 15),
(5, 'BATCH-20250522185030', 3, 10),
(6, 'BATCH-20250518162911', 6, 9),
(8, 'BATCH-20250526034220', 6, 12),
(9, 'BATCH-20250728201527', 11, 10),
(10, 'BATCH-20250522191120', 12, 10),
(12, 'BATCH-20250729135441', 10, 10),
(15, 'BATCH-20250729154018', 9, 5),
(16, 'BATCH-20250729154207', 9, 3),
(17, 'BATCH-20250729155636', 11, 5),
(19, 'BATCH-20250508150734', 12, 12);

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
(7, 'Crossbreed'),
(3, 'Duroc'),
(6, 'F1'),
(1, 'Hampshire'),
(5, 'Landrace'),
(4, 'Large White'),
(2, 'Native'),
(8, 'other');

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
-- Table structure for table `feed_inventory`
--

CREATE TABLE `feed_inventory` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `quantity_kg` float DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `supplier` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feed_inventory`
--

INSERT INTO `feed_inventory` (`id`, `name`, `type`, `quantity_kg`, `expiry_date`, `supplier`) VALUES
(3, 'b-meg', 'starter', 100, NULL, 'jlc farm suupplies');

-- --------------------------------------------------------

--
-- Table structure for table `feed_usage`
--

CREATE TABLE `feed_usage` (
  `id` int(11) NOT NULL,
  `feed_id` int(11) DEFAULT NULL,
  `amount_kg` float DEFAULT NULL,
  `usage_date` date DEFAULT curdate(),
  `used_for` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `user` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Dumping data for table `financial_records`
--

INSERT INTO `financial_records` (`id`, `record_date`, `type`, `category`, `amount`, `description`, `related_batch`, `recorded_by`) VALUES
(1, '2025-05-18', 'expense', 'pig_purchase', 100.00, 'Purchase of 10 pigs from jmc farm', 'BATCH-20250518154138', NULL);

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
-- Table structure for table `info_sections`
--

CREATE TABLE `info_sections` (
  `id` int(11) NOT NULL,
  `about_us` text NOT NULL,
  `features` text NOT NULL,
  `farming_tips` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `info_sections`
--

INSERT INTO `info_sections` (`id`, `about_us`, `features`, `farming_tips`, `created_at`, `updated_at`) VALUES
(1, 'PIGGERY MANAGEMENT SYSTEM is a comprehensive hog farming management system developed by agricultural technology experts with decades of combined experience in piggery operations.\r\n\r\nOur mission is to empower farmers with technology that simplifies complex farming operations while improving productivity and profitability through data-driven insights.\r\n\r\nThe system has been field-tested on commercial farms and smallholder operations to ensure it meets the diverse needs of the hog farming community.', '', '', '2025-07-29 22:15:31', '2025-07-29 22:27:03');

-- --------------------------------------------------------

--
-- Table structure for table `liveweight_trends`
--

CREATE TABLE `liveweight_trends` (
  `id` int(11) NOT NULL,
  `trend_date` date DEFAULT NULL,
  `price` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `liveweight_trends`
--

INSERT INTO `liveweight_trends` (`id`, `trend_date`, `price`) VALUES
(1, '2025-07-29', 220),
(2, '2025-07-29', 230),
(3, '2025-07-29', 250),
(4, '2025-07-29', 230),
(5, '2025-07-29', 220);

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
(1, 'BATCH-20250508150734', 'norovit', 'vitamin', '1ml', '2025-05-08', '2025-05-17', 'jade', 'test', '2025-05-08 13:44:19'),
(3, 'BATCH-20250518154138', 'amocicilin', 'antibiotic', '1ml', '2025-05-18', '2025-05-24', 'jade', 'test', '2025-05-18 13:50:16');

-- --------------------------------------------------------

--
-- Table structure for table `pens`
--

CREATE TABLE `pens` (
  `id` int(11) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `label` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `length_m` float DEFAULT NULL,
  `width_m` float DEFAULT NULL,
  `area_sqm` float DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `current_pigs` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pens`
--

INSERT INTO `pens` (`id`, `x`, `y`, `width`, `height`, `label`, `created_at`, `length_m`, `width_m`, `area_sqm`, `capacity`, `current_pigs`) VALUES
(1, 50, 50, 100, 100, 'Pen 1', '2025-05-18 14:28:15', 4, 5, 20, 20, 0),
(2, 200, 50, 100, 100, 'Pen 2', '2025-05-18 14:28:15', 4, 4, 16, 20, 0),
(3, 350, 50, 100, 100, 'Pen 3', '2025-05-18 14:28:15', 4, 5, 20, 20, 0),
(5, 28, 175, 189, 97, 'farrowing pen', '2025-05-18 14:45:21', 2, 3, 6, 1, 0),
(6, 258, 166, 188, 104, 'nursery', '2025-05-18 14:45:21', 4, 5, 20, 60, 0),
(7, 652, 403, 139, 84, 'quarantine', '2025-05-18 19:17:00', 4, 5, 20, 20, 0),
(8, 650, 305, 138, 88, 'quarantine', '2025-05-18 19:17:00', 4, 5, 20, 20, 0),
(9, 50, 308, 51, 102, 'fattening', '2025-05-18 19:17:00', 4, 5, 20, 20, 0),
(10, 108, 308, 72, 102, 'fattening', '2025-05-18 19:17:00', 4, 5, 20, 20, 0),
(11, 190, 308, 74, 103, 'fattening', '2025-05-18 19:17:00', 4, 5, 20, 20, 0),
(12, 51, 415, 218, 84, 'fattening', '2025-05-18 19:17:00', 10, 10, 100, 50, 0),
(13, 302, 307, 83, 191, 'farrowing pen 2', '2025-05-19 04:20:12', NULL, NULL, NULL, NULL, 0),
(14, 412, 308, 82, 190, 'farrowing pen 3', '2025-05-19 04:20:12', NULL, NULL, NULL, NULL, 0),
(15, 648, 193, 98, 96, 'nursery', '2025-05-22 13:38:03', NULL, NULL, NULL, NULL, 0),
(16, 526, 409, 102, 69, 'fattening', '2025-05-22 13:38:54', 5, 5, 25, 30, 0),
(17, 506, 162, 102, 106, 'Pen 4', '2025-05-22 16:05:18', 4, 5, 20, 10, 0);

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
  `sow_id` int(11) DEFAULT NULL,
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
  `batch_date` date DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `nursery_transfer_type` varchar(16) DEFAULT NULL,
  `nursery_transfer_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pig_batches`
--

INSERT INTO `pig_batches` (`id`, `batch_id`, `photo`, `source`, `sow_id`, `breed_id`, `birth_date`, `total_pigs`, `male_count`, `female_count`, `deceased_count`, `weight_avg`, `status`, `location`, `remark`, `created_at`, `batch_date`, `qr_code`, `nursery_transfer_type`, `nursery_transfer_date`) VALUES
(1, 'BATCH-20250508150734', 'uploads/batches/BATCH-20250508150734.jpg', 'farm', 25, 1, '2025-03-07', 7, 6, 4, 0, 0.92, 'active', '', 'TEST', '2025-05-08 13:08:22', NULL, 'qrcodes/batch_BATCH-20250508150734.png', NULL, NULL),
(2, 'BATCH-20250518151927', 'uploads/360_F_645220217_IyiCC4Rdh3eVjaThrAxLWQhV9CrQIphk.jpg', 'farm', 24, 3, '2025-05-10', 10, 5, 5, 0, 2.00, 'active', '5', 'test', '2025-05-18 13:20:50', NULL, NULL, NULL, NULL),
(3, 'BATCH-20250518152329', 'uploads/batches/BATCH-20250518152329.jpg', 'farm', 24, 1, '2025-05-03', 8, 3, 5, 0, 3.00, 'active', '5', 'rer', '2025-05-18 13:24:16', NULL, NULL, NULL, NULL),
(4, 'BATCH-20250518154138', 'uploads/batches/BATCH-20250518154138.jpg', 'external', NULL, 1, '2025-04-12', 0, 0, 0, 0, 2.00, 'sold', '2', 'texs', '2025-05-18 13:42:44', NULL, NULL, NULL, NULL),
(5, 'BATCH-20250518162911', 'uploads/batches/BATCH-20250518162911.jpg', 'farm', 22, 5, '2025-05-07', 9, 2, 7, 0, 0.00, 'active', '', '', '2025-05-18 14:30:12', NULL, NULL, NULL, NULL),
(6, 'BATCH-20250518212150', 'uploads/batches/BATCH-20250518212150.jpg', 'farm', 22, 3, '2025-05-05', 10, 7, 3, 0, 2.00, 'active', 'nursery', 'tr', '2025-05-18 19:22:52', NULL, NULL, NULL, NULL),
(7, 'BATCH-20250518212729', 'uploads/batches/BATCH-20250518212729.jpg', 'farm', 22, 1, '2025-04-23', 12, 4, 8, 0, 2.00, 'active', 'fattening', 're', '2025-05-18 19:28:08', NULL, NULL, NULL, NULL),
(8, 'BATCH-20250518213805', 'uploads/batches/BATCH-20250518213805.jpg', 'farm', 22, 3, '2025-05-10', 10, 3, 7, 0, 2.00, 'active', 'nursery', 're', '2025-05-18 19:38:32', NULL, NULL, NULL, NULL),
(9, 'BATCH-20250522180552', 'uploads/67f228783bfa8_images (1).jpg', 'farm', 20, 3, '2025-05-17', 12, 6, 6, 0, 2.00, 'active', NULL, '', '2025-05-22 16:06:39', NULL, NULL, NULL, NULL),
(10, 'BATCH-20250522182555', 'uploads/batches/BATCH-20250522182555.jpg', 'farm', 21, 3, '2025-05-17', 15, 10, 5, 0, 2.00, 'active', NULL, '', '2025-05-22 16:26:43', NULL, NULL, NULL, NULL),
(11, 'BATCH-20250522185030', 'uploads/360_F_645220217_IyiCC4Rdh3eVjaThrAxLWQhV9CrQIphk.jpg', 'farm', 20, 1, '2025-05-01', 10, 5, 5, 0, 3.00, 'active', '', '', '2025-05-22 16:52:01', NULL, NULL, NULL, NULL),
(12, 'BATCH-20250522191120', 'uploads/batches/BATCH-20250522191120.jpg', 'farm', 21, 3, '2025-03-15', 10, 5, 5, 0, 3.00, 'quarantined', '', '', '2025-05-22 17:12:12', NULL, NULL, NULL, NULL),
(13, 'BATCH-20250526034220', 'uploads/images.jpg', 'farm', 23, 1, '2025-05-10', 6, 3, 3, 0, 2.00, 'active', '', 'test', '2025-05-26 01:45:18', NULL, NULL, NULL, NULL),
(14, 'BATCH-20250728201527', 'assets/default_batch.jpg', 'farm', 21, 2, '2025-04-04', 10, 5, 5, 0, 2.00, 'active', NULL, 'test', '2025-07-28 18:16:10', NULL, NULL, NULL, NULL),
(15, 'BATCH-20250729135441', 'uploads/batches/BATCH-20250729135441.jpg', 'farm', 20, 7, '2025-07-12', 10, 3, 7, 0, 5.00, 'active', NULL, '', '2025-07-29 11:55:46', NULL, NULL, 'late', '2025-07-29'),
(16, 'BATCH-20250729154018', 'uploads/batches/BATCH-20250729154018.jpg', 'farm', 25, 7, '2025-07-12', 5, 2, 3, 0, 3.00, 'active', NULL, '', '2025-07-29 13:41:01', NULL, 'qrcodes/batch_BATCH-20250729154018.png', NULL, NULL),
(17, 'BATCH-20250729154207', 'uploads/batches/BATCH-20250729154207.jpg', 'farm', 21, 7, '2025-07-18', 3, 1, 2, 0, 3.00, 'active', NULL, '', '2025-07-29 13:42:38', NULL, NULL, NULL, NULL),
(18, 'BATCH-20250729155636', 'uploads/batches/BATCH-20250729155636.jpg', 'farm', 21, 7, '2025-07-12', 5, 2, 3, 0, 3.00, 'active', NULL, '', '2025-07-29 13:57:05', NULL, 'qrcodes/batch_BATCH-20250729155636.png', 'early', '2025-07-30');

-- --------------------------------------------------------

--
-- Table structure for table `pig_batch_health_records`
--

CREATE TABLE `pig_batch_health_records` (
  `id` int(11) NOT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `record_date` date DEFAULT NULL,
  `history` text DEFAULT NULL,
  `mortality_rate` float DEFAULT NULL,
  `deformities` int(11) DEFAULT NULL,
  `deformity_kind` varchar(255) DEFAULT NULL,
  `unhealthy_pigs` int(11) DEFAULT NULL,
  `symptoms` text DEFAULT NULL,
  `cured` tinyint(1) DEFAULT NULL,
  `cure_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pig_sales`
--

CREATE TABLE `pig_sales` (
  `id` int(11) NOT NULL,
  `sale_date` date DEFAULT NULL,
  `buyer` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `total_pigs` int(11) DEFAULT NULL,
  `total_liveweight` float DEFAULT NULL,
  `total_income` float DEFAULT NULL,
  `total_feed_cost` float DEFAULT NULL,
  `total_med_cost` float DEFAULT NULL,
  `total_expenses` float DEFAULT NULL,
  `total_profit` float DEFAULT NULL,
  `batch_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pig_sales`
--

INSERT INTO `pig_sales` (`id`, `sale_date`, `buyer`, `remarks`, `total_pigs`, `total_liveweight`, `total_income`, `total_feed_cost`, `total_med_cost`, `total_expenses`, `total_profit`, `batch_details`) VALUES
(1, '2025-07-29', 'xmall', 'test', 3, 290, 63800, 16800, 2000, 18800, 45000, '[{\"batch_id\":\"BATCH-20250728201527\",\"pigs_sold\":3,\"weights\":[100,90,100],\"liveweight_price\":220,\"feed_sacks\":12,\"feed_price\":1400,\"med_expenses\":2000,\"income\":63800,\"feed_cost\":16800,\"expenses\":18800,\"profit\":45000}]'),
(2, '2025-07-29', 'xmall', 'tr', 5, 449, 103270, 30000, 3000, 33000, 70270, '[{\"batch_id\":\"BATCH-20250518162911\",\"pigs_sold\":5,\"weights\":[90,100,70,89,100],\"liveweight_price\":230,\"feed_sacks\":20,\"feed_price\":1500,\"med_expenses\":3000,\"income\":103270,\"feed_cost\":30000,\"expenses\":33000,\"profit\":70270}]'),
(3, '2025-07-29', 're', 'few', 2, 200, 50000, 12000, 2000, 14000, 36000, '[{\"batch_id\":\"BATCH-20250728201527\",\"pigs_sold\":2,\"weights\":[100,100],\"liveweight_price\":250,\"feed_sacks\":8,\"feed_price\":1500,\"med_expenses\":2000,\"income\":50000,\"feed_cost\":12000,\"expenses\":14000,\"profit\":36000}]'),
(4, '2025-07-29', 'xmall', 'sax', 1, 100, 23000, 5200, 999.99, 6199.99, 16800, '[{\"batch_id\":\"BATCH-20250728201527\",\"pigs_sold\":1,\"weights\":[100],\"liveweight_price\":230,\"feed_sacks\":4,\"feed_price\":1300,\"med_expenses\":999.99,\"income\":23000,\"feed_cost\":5200,\"expenses\":6199.99,\"profit\":16800.010000000002}]'),
(5, '2025-07-29', 'dsld', 'sdk', 3, 294, 64680, NULL, NULL, NULL, NULL, '[{\"batch_id\":\"BATCH-20250508150734\",\"pigs_sold\":3,\"weights\":[100,100,94],\"liveweight_price\":220,\"feed_sacks\":12,\"feed_price\":1300,\"med_expenses\":2300,\"income\":64680}]');

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
-- Table structure for table `quarantine_batches`
--

CREATE TABLE `quarantine_batches` (
  `id` int(11) NOT NULL,
  `batch_id` varchar(20) NOT NULL,
  `date_quarantined` date NOT NULL,
  `num_quarantined` int(11) NOT NULL,
  `num_male` int(11) NOT NULL,
  `num_female` int(11) NOT NULL,
  `symptoms` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `pen_id` int(11) DEFAULT NULL,
  `is_full_batch` tinyint(1) DEFAULT 0,
  `reported_by` varchar(50) DEFAULT NULL,
  `time_reported` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quarantine_batches`
--

INSERT INTO `quarantine_batches` (`id`, `batch_id`, `date_quarantined`, `num_quarantined`, `num_male`, `num_female`, `symptoms`, `notes`, `pen_id`, `is_full_batch`, `reported_by`, `time_reported`) VALUES
(1, 'BATCH-20250522191120', '2025-05-23', 0, 1, 0, 'isc', 'ijcds', 7, 0, NULL, '2025-05-23 19:01:22'),
(2, 'BATCH-20250522191120', '2025-05-23', 0, 1, 1, 'tets', 'ysu', 15, 0, NULL, '2025-05-23 19:08:02'),
(3, 'BATCH-20250522191120', '2025-05-23', 4, 0, 1, 'tet', 't', 17, 1, NULL, '2025-05-23 19:09:03');

-- --------------------------------------------------------

--
-- Table structure for table `sold_batches`
--

CREATE TABLE `sold_batches` (
  `id` int(11) NOT NULL,
  `batch_id` varchar(255) NOT NULL,
  `buyer_name` varchar(255) DEFAULT NULL,
  `buyer_contact` varchar(255) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(10,2) DEFAULT NULL COMMENT 'Production cost',
  `payment_method` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `num_pigs` int(11) NOT NULL,
  `num_male` int(11) NOT NULL,
  `num_female` int(11) NOT NULL,
  `weight_per_head` text DEFAULT NULL,
  `weights` text DEFAULT NULL,
  `live_weight_price` decimal(10,2) DEFAULT NULL,
  `feed_sacks` int(11) DEFAULT NULL,
  `feed_price` decimal(10,2) DEFAULT NULL,
  `medication_price` decimal(10,2) DEFAULT NULL,
  `total_sale_price` decimal(10,2) DEFAULT NULL,
  `profit` decimal(10,2) DEFAULT NULL,
  `sale_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sold_batches`
--

INSERT INTO `sold_batches` (`id`, `batch_id`, `buyer_name`, `buyer_contact`, `total_price`, `total_cost`, `payment_method`, `notes`, `num_pigs`, `num_male`, `num_female`, `weight_per_head`, `weights`, `live_weight_price`, `feed_sacks`, `feed_price`, `medication_price`, `total_sale_price`, `profit`, `sale_date`) VALUES
(1, 'BATCH-20250508150734', 'christan', '09614889820', 44000.00, 13500.00, 'cash', 'uy', 2, 1, 1, '100,100', NULL, 220.00, 8, 1500.00, 1500.00, NULL, NULL, '2025-07-28 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `sow_gilt_records`
--

CREATE TABLE `sow_gilt_records` (
  `id` int(11) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `breed_id` int(11) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `acquired_date` date DEFAULT NULL,
  `age` int(11) NOT NULL,
  `mating_date` date NOT NULL,
  `labor_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `parity` int(11) DEFAULT 0,
  `status` varchar(50) DEFAULT 'active',
  `qr_code` varchar(255) DEFAULT NULL,
  `type` enum('sow','gilt') NOT NULL DEFAULT 'gilt'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sow_gilt_records`
--

INSERT INTO `sow_gilt_records` (`id`, `picture`, `breed_id`, `birth_date`, `acquired_date`, `age`, `mating_date`, `labor_date`, `description`, `parity`, `status`, `qr_code`, `type`) VALUES
(20, 'images.jpg', 1, NULL, NULL, 2, '2025-04-01', '2025-07-24', 'jk', 0, 'active', 'qrcodes/sow_gilt_20.png', 'sow'),
(21, 'uploadfolder/67ef5e6fb7b3b_IMG_6105.jpg', 3, NULL, NULL, 3, '2025-02-01', '2025-05-26', 'parity5 ', 1, 'active', 'qrcodes/sow_gilt_21.png', 'sow'),
(22, 'uploadfolder/67ef5eebf131f_images.jpg', 1, NULL, NULL, 2, '2025-02-05', '2025-05-30', 'parity 3', 0, 'active', 'qrcodes/sow_gilt_22.png', 'sow'),
(23, 'uploadfolder/67ef5f0bcb2ff_360_F_2921173_yl8sF7MsOGme2unABCi5yqDjczMsk8.jpg', 1, NULL, NULL, 2, '2025-02-06', '2025-05-31', 'parity 3', 0, 'active', 'qrcodes/sow_gilt_23.png', 'sow'),
(24, 'uploadfolder/67ef5f3eed72c_images (2).jpg', 4, NULL, NULL, 3, '2025-02-07', '2025-06-01', 'parity 2', 1, 'active', 'qrcodes/sow_gilt_24.png', 'sow'),
(25, 'uploadfolder/67f228783bfa8_images (1).jpg', 1, '2024-07-11', '2025-05-03', 10, '2025-05-22', '2025-09-13', 'test', 0, 'active', 'qrcodes/sow_gilt_25.png', 'sow'),
(27, 'uploadfolder/682f3a2a244f3_67ef5e6fb7b3b_IMG_6105.jpg', 2, '2024-06-22', '2025-05-03', 13, '2025-05-11', '2025-09-02', 'test', 0, 'active', NULL, 'sow'),
(28, 'uploadfolder/682f3bee7d493_67ef5eebf131f_images.jpg', 1, '2025-05-10', '2025-05-10', 3, '2025-05-10', '2025-09-01', 'test', 0, 'active', 'qrcodes/sow_gilt_28.png', 'gilt'),
(29, 'uploadfolder/682f3dba756c8_67f228783bfa8_images (1).jpg', 3, '2025-05-10', '2025-05-17', 2, '0000-00-00', '0000-00-00', 'test', 0, 'active', 'qrcodes/sow_gilt_29.png', 'sow');

-- --------------------------------------------------------

--
-- Table structure for table `sow_gilt_repro_history`
--

CREATE TABLE `sow_gilt_repro_history` (
  `id` int(11) NOT NULL,
  `sow_gilt_id` int(11) NOT NULL,
  `event_type` enum('heat','mating','pregnancy_start','pregnancy_cancel','farrowing') NOT NULL,
  `event_date` date NOT NULL,
  `notes` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sow_gilt_repro_history`
--

INSERT INTO `sow_gilt_repro_history` (`id`, `sow_gilt_id`, `event_type`, `event_date`, `notes`) VALUES
(1, 28, '', '2025-05-22', 'Initial record'),
(2, 28, 'mating', '2025-05-10', 'First mating recorded on add'),
(3, 28, 'pregnancy_start', '2025-05-10', 'Pregnancy cycle started on add'),
(4, 29, '', '2025-05-22', 'Initial record'),
(5, 29, 'mating', '2025-05-21', 'First mating recorded on add'),
(6, 29, 'pregnancy_start', '2025-05-21', 'Pregnancy cycle started on add'),
(7, 29, 'pregnancy_cancel', '2025-05-22', 'in heat'),
(8, 25, 'pregnancy_cancel', '2025-05-22', 'in heat'),
(9, 25, 'mating', '2025-05-22', ''),
(10, 21, 'farrowing', '2025-05-26', 'Automatic increment after labor date'),
(11, 24, 'heat', '2025-07-29', 'in heat'),
(12, 24, 'farrowing', '2025-07-29', '');

-- --------------------------------------------------------

--
-- Table structure for table `supply_inventory`
--

CREATE TABLE `supply_inventory` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `supplier` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supply_inventory`
--

INSERT INTO `supply_inventory` (`id`, `name`, `category`, `quantity`, `unit`, `expiry_date`, `supplier`) VALUES
(1, 'Respesure', 'Vaccine', 10, '10ml', '2025-10-28', 'jlc farm supplies');

-- --------------------------------------------------------

--
-- Table structure for table `supply_usage`
--

CREATE TABLE `supply_usage` (
  `id` int(11) NOT NULL,
  `supply_id` int(11) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `usage_date` date DEFAULT curdate(),
  `used_for` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `user` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','employee','veterinarian','owner') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) DEFAULT 1,
  `sex` enum('male','female') NOT NULL,
  `age` int(11) NOT NULL,
  `address` text NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `birthday` date DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  `province` varchar(80) DEFAULT NULL,
  `municipality` varchar(80) DEFAULT NULL,
  `barangay` varchar(80) DEFAULT NULL,
  `street_address` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `suffix`, `username`, `password`, `role`, `created_at`, `status`, `sex`, `age`, `address`, `contact_number`, `birthday`, `last_login`, `profile_img`, `province`, `municipality`, `barangay`, `street_address`) VALUES
(1, NULL, NULL, NULL, NULL, 'admin5', 'd4e8e6deaa7b1f8381e09e3e6b83e36f0b681c5c', 'admin', '2025-04-03 11:50:28', 1, 'male', 19, 'cabugao, ilocos sur', '09929591452', '2005-06-07', '2025-07-30 11:58:51', 'uploads/profiles/profile_68309a6b080500.83751210.png', NULL, NULL, NULL, NULL),
(2, NULL, NULL, NULL, NULL, 'employee1', 'd4e8e6deaa7b1f8381e09e3e6b83e36f0b681c5c', 'employee', '2025-04-03 11:51:09', 1, 'male', 22, 'cabugao, ilocos sur', '0198390235', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, NULL, NULL, NULL, NULL, 'admin6', 'd4e8e6deaa7b1f8381e09e3e6b83e36f0b681c5c', 'veterinarian', '2025-04-03 11:58:47', 1, 'male', 0, '', '09778324038', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, NULL, NULL, NULL, NULL, 'owner1', 'd4e8e6deaa7b1f8381e09e3e6b83e36f0b681c5c', 'owner', '2025-04-03 12:12:56', 0, 'male', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, NULL, NULL, NULL, NULL, 'Yza', 'fa7b462bab58afae1f822889452d9d825ed5fac7', 'veterinarian', '2025-05-19 04:14:16', 1, '', 20, 'Bangued, Abra', '09066352613', '2004-09-23', NULL, NULL, NULL, NULL, NULL, NULL),
(11, NULL, NULL, NULL, NULL, 'test', 'd4e8e6deaa7b1f8381e09e3e6b83e36f0b681c5c', 'admin', '2025-05-22 19:35:45', 1, 'male', 22, 'cabugao, ilocos sur', '09929591452', '2003-01-22', NULL, 'uploads/profiles/profile_682f7c911b8e74.63635523.jpg', NULL, NULL, NULL, NULL),
(12, NULL, NULL, NULL, NULL, 'sayaan', '006e4e24b52755dcf602bf1bd18c076be9a5776e', 'admin', '2025-05-23 12:56:45', 1, 'male', 0, 'Bangued, Abra', '09614889820', '2025-05-03', '2025-05-24 15:07:57', 'uploads/profiles/profile_6830708d508836.02993352.jpg', NULL, NULL, NULL, NULL),
(13, NULL, NULL, NULL, NULL, 'christian', 'd16d33e9c4548928adde0ce490072be1d94f67e1', 'employee', '2025-05-26 01:36:05', 1, 'male', 20, 'quezon, cabugao, ilocos sur', '0198390235', '2004-07-07', NULL, 'uploads/profiles/profile_685ba63bae7ab7.18090812.png', NULL, NULL, NULL, NULL),
(14, NULL, NULL, NULL, NULL, 'unpadmin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'admin', '2025-07-07 13:57:50', 0, 'male', 21, 'quezon, cabugao, ilocos sur', '0198390235', '2004-02-04', '2025-07-07 14:04:33', NULL, NULL, NULL, NULL, NULL),
(15, 'tests', 'yests', 'say', 'sabh', 'tian', '$2y$10$t3S4XsAhpRelfcaa5wmQTe.3MgvV7h6O.eupd7E3MNQSK9iAfzbYi', 'employee', '2025-07-28 10:07:51', 0, 'female', 6, 'quezon, cabugao, ilocos sur', '097765849766666', '2019-02-12', NULL, 'uploads/profiles/profile_68874c11a63032.32980833.png', 'Metro Manila', 'Quezon City', 'Bagong Pag-asa', 'quezon, cabugao, ilocos sur');

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `theme_mode` varchar(20) NOT NULL DEFAULT 'light',
  `language` varchar(5) NOT NULL DEFAULT 'en',
  `font_style` varchar(50) NOT NULL DEFAULT 'Arial',
  `notifications` tinyint(1) NOT NULL DEFAULT 1,
  `font_size` smallint(6) NOT NULL DEFAULT 16,
  `timezone` varchar(50) NOT NULL DEFAULT 'UTC',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_settings`
--

INSERT INTO `user_settings` (`id`, `user_id`, `theme_mode`, `language`, `font_style`, `notifications`, `font_size`, `timezone`, `created_at`, `updated_at`) VALUES
(1, 1, 'light', 'en', 'Arial', 0, 18, 'UTC', '2025-05-20 18:55:15', '2025-07-29 22:27:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `batch_pens`
--
ALTER TABLE `batch_pens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pen_id` (`pen_id`);

--
-- Indexes for table `breed`
--
ALTER TABLE `breed`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `feed_and_supplies`
--
ALTER TABLE `feed_and_supplies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feed_inventory`
--
ALTER TABLE `feed_inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feed_usage`
--
ALTER TABLE `feed_usage`
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
-- Indexes for table `info_sections`
--
ALTER TABLE `info_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `liveweight_trends`
--
ALTER TABLE `liveweight_trends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medication`
--
ALTER TABLE `medication`
  ADD PRIMARY KEY (`id`),
  ADD KEY `batch_id` (`batch_id`);

--
-- Indexes for table `pens`
--
ALTER TABLE `pens`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `pig_batch_health_records`
--
ALTER TABLE `pig_batch_health_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pig_sales`
--
ALTER TABLE `pig_sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quarantine`
--
ALTER TABLE `quarantine`
  ADD PRIMARY KEY (`id`),
  ADD KEY `batch_id` (`batch_id`);

--
-- Indexes for table `quarantine_batches`
--
ALTER TABLE `quarantine_batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `batch_id` (`batch_id`),
  ADD KEY `pen_id` (`pen_id`);

--
-- Indexes for table `sold_batches`
--
ALTER TABLE `sold_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sow_gilt_records`
--
ALTER TABLE `sow_gilt_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `breed_id` (`breed_id`);

--
-- Indexes for table `sow_gilt_repro_history`
--
ALTER TABLE `sow_gilt_repro_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sow_gilt_id` (`sow_gilt_id`);

--
-- Indexes for table `supply_inventory`
--
ALTER TABLE `supply_inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supply_usage`
--
ALTER TABLE `supply_usage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=555;

--
-- AUTO_INCREMENT for table `batch_pens`
--
ALTER TABLE `batch_pens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `breed`
--
ALTER TABLE `breed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `feed_and_supplies`
--
ALTER TABLE `feed_and_supplies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `feed_inventory`
--
ALTER TABLE `feed_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `feed_usage`
--
ALTER TABLE `feed_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_records`
--
ALTER TABLE `financial_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `growth_tracking`
--
ALTER TABLE `growth_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `liveweight_trends`
--
ALTER TABLE `liveweight_trends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `medication`
--
ALTER TABLE `medication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pens`
--
ALTER TABLE `pens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pigs`
--
ALTER TABLE `pigs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pig_batches`
--
ALTER TABLE `pig_batches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `pig_batch_health_records`
--
ALTER TABLE `pig_batch_health_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pig_sales`
--
ALTER TABLE `pig_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `quarantine`
--
ALTER TABLE `quarantine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quarantine_batches`
--
ALTER TABLE `quarantine_batches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sold_batches`
--
ALTER TABLE `sold_batches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sow_gilt_records`
--
ALTER TABLE `sow_gilt_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `sow_gilt_repro_history`
--
ALTER TABLE `sow_gilt_repro_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `supply_inventory`
--
ALTER TABLE `supply_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supply_usage`
--
ALTER TABLE `supply_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_settings`
--
ALTER TABLE `user_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `batch_pens`
--
ALTER TABLE `batch_pens`
  ADD CONSTRAINT `batch_pens_ibfk_1` FOREIGN KEY (`pen_id`) REFERENCES `pens` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `fk_pig_batches_sow_gilt` FOREIGN KEY (`sow_id`) REFERENCES `sow_gilt_records` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pig_batches_ibfk_1` FOREIGN KEY (`breed_id`) REFERENCES `breed` (`id`);

--
-- Constraints for table `quarantine`
--
ALTER TABLE `quarantine`
  ADD CONSTRAINT `quarantine_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `pig_batches` (`batch_id`);

--
-- Constraints for table `quarantine_batches`
--
ALTER TABLE `quarantine_batches`
  ADD CONSTRAINT `quarantine_batches_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `pig_batches` (`batch_id`),
  ADD CONSTRAINT `quarantine_batches_ibfk_2` FOREIGN KEY (`pen_id`) REFERENCES `pens` (`id`);

--
-- Constraints for table `sow_gilt_records`
--
ALTER TABLE `sow_gilt_records`
  ADD CONSTRAINT `sow_gilt_records_ibfk_1` FOREIGN KEY (`breed_id`) REFERENCES `breed` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sow_gilt_repro_history`
--
ALTER TABLE `sow_gilt_repro_history`
  ADD CONSTRAINT `sow_gilt_repro_history_ibfk_1` FOREIGN KEY (`sow_gilt_id`) REFERENCES `sow_gilt_records` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD CONSTRAINT `user_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
