-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2025 at 08:31 PM
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
-- Database: `gaming_cafe_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `branch_id` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `branch_id`, `created_at`) VALUES
(1, 1, 'login', 'User logged in', '::1', 1, '2025-10-11 09:23:15'),
(2, 1, 'console_create', 'Created console: test', '::1', 1, '2025-10-11 09:24:34'),
(3, 1, 'game_create', 'Created game: qwer', '::1', 1, '2025-10-11 09:34:26'),
(4, 1, 'console_create', 'Created console: xfcgfq', '::1', 1, '2025-10-11 11:41:01'),
(5, 1, 'console_update', 'Updated console: test', '::1', 1, '2025-10-11 12:02:26'),
(6, 1, 'session_management', 'Started gaming session for shan on console test', NULL, 1, '2025-10-11 12:09:08'),
(7, 1, 'session_management', 'Paused gaming session on console 2', NULL, 1, '2025-10-11 12:23:24'),
(8, 1, 'session_management', 'Resumed gaming session on console 2', NULL, 1, '2025-10-11 12:24:37'),
(9, 1, 'session_management', 'Paused gaming session on console 2', NULL, 1, '2025-10-11 12:24:45'),
(10, 1, 'session_management', 'Resumed gaming session on console 2', NULL, 1, '2025-10-11 12:26:29'),
(11, 1, 'session_management', 'Paused gaming session on console 2', NULL, 1, '2025-10-11 12:26:32'),
(12, 1, 'session_management', 'Resumed gaming session on console 2', NULL, 1, '2025-10-11 12:26:36'),
(13, 1, 'session_management', 'Ended gaming session on console 2. Total: $2.85', NULL, 1, '2025-10-11 12:38:31'),
(14, 1, 'session_management', 'Ended gaming session on console 1. Total: ₹106.20', NULL, 1, '2025-10-11 13:04:53'),
(15, 1, 'session_management', 'Started gaming session for azlan on console test', NULL, 1, '2025-10-11 13:07:30'),
(16, 1, 'session_management', 'Transferred session from console 1 to console 2', NULL, 1, '2025-10-11 13:07:50'),
(17, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹35.40', NULL, 1, '2025-10-11 13:08:17'),
(18, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-11 13:13:40'),
(19, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹35.40', NULL, 1, '2025-10-11 13:26:46'),
(20, 1, 'session_management', 'Started gaming session for ajju on console xfcgfq', NULL, 1, '2025-10-11 13:27:07'),
(21, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹47.20', NULL, 1, '2025-10-11 13:27:16'),
(22, 1, 'session_management', 'Started gaming session for aju on console xfcgfq', NULL, 1, '2025-10-11 13:35:56'),
(23, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹35.40', NULL, 1, '2025-10-11 13:37:53'),
(24, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-11 14:04:35'),
(25, 1, 'session_management', 'Started gaming session for azlan on console test', NULL, 1, '2025-10-11 14:04:47'),
(26, 1, 'session_management', 'Changed player count to 2 on console 1', NULL, 1, '2025-10-11 14:04:57'),
(27, 1, 'session_management', 'Changed player count to 3 on console 2', NULL, 1, '2025-10-11 14:39:36'),
(28, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹224.20', NULL, 1, '2025-10-11 15:04:39'),
(29, 1, 'session_management', 'Changed player count from 1 to 4 on console 1', NULL, 1, '2025-10-11 15:15:13'),
(30, 1, 'session_management', 'Started gaming session for ajlan on console xfcgfq', NULL, 1, '2025-10-11 15:15:54'),
(31, 1, 'session_management', 'Changed player count from 1 to 2 on console 2', NULL, 1, '2025-10-11 15:16:10'),
(32, 1, 'session_management', 'Changed player count to 2 on console 2', NULL, 1, '2025-10-11 15:18:56'),
(33, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹59.00', NULL, 1, '2025-10-11 15:19:04'),
(34, 1, 'session_management', 'Ended gaming session on console 1. Total: ₹280.00', NULL, 1, '2025-10-11 15:58:30'),
(35, 1, 'session_management', 'Ended gaming session on console 1. Total: ₹2,430.00', NULL, 1, '2025-10-12 17:18:37'),
(36, 1, 'session_management', 'Ended gaming session on console 1. Total: ₹2,430.00', NULL, 1, '2025-10-12 17:23:26'),
(37, 1, 'session_management', 'Ended gaming session on console 1. Total: ₹3,060.00', NULL, 1, '2025-10-13 01:10:24'),
(38, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-13 01:14:41'),
(39, 1, 'console_create', 'Created console: test console', '::1', 1, '2025-10-13 01:19:36'),
(40, 1, 'session_management', 'Added 1x Coca Cola to session on console 2', NULL, 1, '2025-10-13 01:20:47'),
(41, 1, 'console_update', 'Updated console: test console', '::1', 1, '2025-10-13 01:22:26'),
(42, 1, 'console_update', 'Updated console: test', '::1', 1, '2025-10-13 01:27:27'),
(43, 1, 'session_management', 'Added 1x Coca Cola to session on console 2', NULL, 1, '2025-10-13 01:29:15'),
(44, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹55.00', NULL, 1, '2025-10-13 01:40:23'),
(45, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-13 01:41:03'),
(46, 1, 'session_management', 'Started gaming session for ajju on console test', NULL, 1, '2025-10-13 01:41:13'),
(47, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹30.00', NULL, 1, '2025-10-13 01:41:16'),
(48, 1, 'session_management', 'Ended gaming session on console 1. Total: ₹30.00', NULL, 1, '2025-10-13 01:44:13'),
(49, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-13 01:45:59'),
(50, 1, 'session_management', 'Started gaming session for azlan on console test', NULL, 1, '2025-10-13 01:46:07'),
(51, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹30.00', NULL, 1, '2025-10-13 01:46:11'),
(52, 1, 'session_management', 'Started gaming session for q on console xfcgfq', NULL, 1, '2025-10-13 01:47:44'),
(53, 1, 'session_management', 'Ended gaming session on console 1. Total: ₹30.00', NULL, 1, '2025-10-13 01:47:48'),
(54, 1, 'session_management', 'Started gaming session for azlan on console test', NULL, 1, '2025-10-13 01:48:40'),
(55, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹30.00', NULL, 1, '2025-10-13 01:49:08'),
(56, 1, 'session_management', 'Payment processed for session 18. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-13 01:49:14'),
(57, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-13 01:52:12'),
(58, 1, 'session_management', 'Ended gaming session on console 1. Total: ₹30.00', NULL, 1, '2025-10-13 01:52:25'),
(59, 1, 'session_management', 'Payment processed for session 19. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-13 01:52:32'),
(60, 1, 'coupon_create', 'Created coupon: test', '::1', 1, '2025-10-13 01:53:34'),
(61, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹30.00', NULL, 1, '2025-10-13 01:53:43'),
(62, 1, 'session_management', 'Payment processed for session 20. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-13 01:53:52'),
(63, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-13 01:55:17'),
(64, 1, 'session_management', 'Started gaming session for ajju on console test', NULL, 1, '2025-10-13 01:55:53'),
(65, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹30.00', NULL, 1, '2025-10-13 01:56:40'),
(66, 1, 'session_management', 'Payment processed for session 21. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-13 01:56:45'),
(67, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-13 01:57:05'),
(68, 1, 'session_management', 'Ended gaming session on console 1. Total: ₹30.00', NULL, 1, '2025-10-13 01:57:08'),
(69, 1, 'session_management', 'Payment processed for session 22. Method: cash, Amount: ₹15.00', NULL, 1, '2025-10-13 01:57:22'),
(70, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹30.00', NULL, 1, '2025-10-13 01:57:29'),
(71, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-13 01:58:51'),
(72, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹30.00', NULL, 1, '2025-10-13 01:58:58'),
(73, 1, 'session_management', 'Payment processed for session 24. Method: upi, Amount: ₹15.00', NULL, 1, '2025-10-13 01:59:32'),
(74, 1, 'login', 'User logged in', '::1', 1, '2025-10-13 15:58:43'),
(75, 1, 'coupon_create', 'Created coupon: test', '::1', 1, '2025-10-14 17:19:07'),
(76, 1, 'session_management', 'Started gaming session for rifaz on console xfcgfq', NULL, 1, '2025-10-14 17:19:19'),
(77, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹30.00', NULL, 1, '2025-10-14 17:20:47'),
(78, 1, 'session_management', 'Payment processed for session 25. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-14 17:20:58'),
(79, 1, 'session_management', 'Started gaming session for thameem on console xfcgfq', NULL, 1, '2025-10-14 17:31:54'),
(80, 1, 'coupon_update', 'Updated coupon: test', '::1', 1, '2025-10-14 17:35:09'),
(81, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹180.00', NULL, 1, '2025-10-14 17:36:08'),
(82, 1, 'coupon_update', 'Updated coupon: test', '::1', 1, '2025-10-14 17:38:00'),
(83, 1, 'logout', 'User logged out', '::1', 1, '2025-10-14 17:42:24'),
(84, 2, 'login', 'User logged in', '::1', 1, '2025-10-14 17:42:37'),
(85, 2, 'session_management', 'Started gaming session for ajju on console xfcgfq', NULL, 1, '2025-10-14 17:52:28'),
(86, 2, 'session_management', 'Added 2x Coca Cola to session on console 2', NULL, 1, '2025-10-14 17:52:35'),
(87, 2, 'session_management', 'Added 2x Coca Cola to session on console 2', NULL, 1, '2025-10-14 17:52:49'),
(88, 2, 'session_management', 'Added 3x Coca Cola to session on console 2', NULL, 1, '2025-10-14 17:53:08'),
(89, 2, 'session_management', 'Added 2x Coca Cola to session on console 2. Stock reduced by 2', NULL, 1, '2025-10-14 17:58:27'),
(90, 2, 'logout', 'User logged out', '::1', 1, '2025-10-14 18:07:04'),
(91, 1, 'login', 'User logged in', '::1', 1, '2025-10-14 18:07:20'),
(92, 1, 'game_update', 'Updated game: qwer', '::1', 1, '2025-10-14 18:09:01'),
(93, 1, 'game_update', 'Updated game: qwer', '::1', 1, '2025-10-14 18:10:33'),
(94, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹92.50', NULL, 1, '2025-10-14 18:14:50'),
(95, 1, 'session_management', 'Payment processed for session 27. Method: cash, Amount: ₹66.25', NULL, 1, '2025-10-14 18:15:10'),
(96, 1, 'game_create', 'Created game: c', '::1', 1, '2025-10-14 18:17:21'),
(97, 1, 'session_management', 'Started gaming session for test on console xfcgfq', NULL, 1, '2025-10-14 18:17:44'),
(98, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹30.00', NULL, 1, '2025-10-14 18:17:48'),
(99, 1, 'session_management', 'Started gaming session for hb on console xfcgfq', NULL, 1, '2025-10-14 18:21:38'),
(100, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹30.00', NULL, 1, '2025-10-14 18:21:46'),
(101, 1, 'session_management', 'Payment processed for session 30. Method: cash_upi, Amount: ₹30.00', NULL, 1, '2025-10-14 18:21:53'),
(102, 1, 'session_management', 'Started gaming session for a on console xfcgfq', NULL, 1, '2025-10-14 18:26:05'),
(103, 1, 'session_management', 'Started gaming session for azlan on console test', NULL, 1, '2025-10-14 18:26:31'),
(104, 1, 'logout', 'User logged out', '::1', 1, '2025-10-14 18:35:35'),
(105, 1, 'login', 'User logged in', '::1', 1, '2025-10-14 18:35:47'),
(106, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹50.00', NULL, 1, '2025-10-14 18:46:21'),
(107, 1, 'session_management', 'Payment processed for session 31. Method: cash_upi, Amount: ₹50.00', NULL, 1, '2025-10-14 18:46:37'),
(108, 1, 'session_management', 'Ended gaming session on console 1. Total: ₹50.00', NULL, 1, '2025-10-14 18:46:46'),
(109, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-15 15:52:57'),
(110, 1, 'session_management', 'Added 2x Coca Cola to session on console 2. Stock reduced by 2', NULL, 1, '2025-10-15 15:53:08'),
(111, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹35.00', NULL, 1, '2025-10-15 15:53:18'),
(112, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-15 15:54:22'),
(113, 1, 'session_management', 'Added 8x Coca Cola to session on console 2. Stock reduced by 8', NULL, 1, '2025-10-15 15:55:20'),
(114, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹200.00', NULL, 1, '2025-10-15 15:55:24'),
(115, 1, 'login', 'User logged in', '::1', 1, '2025-10-15 17:15:16'),
(116, 1, 'session_management', 'Started gaming session for ajju on console xfcgfq', NULL, 1, '2025-10-15 17:15:24'),
(117, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹270.00', NULL, 1, '2025-10-15 17:17:11'),
(118, 1, 'coupon_update', 'Updated coupon: test', '::1', 1, '2025-10-15 17:17:39'),
(119, 1, 'session_management', 'Started gaming session for ajjuu on console xfcgfq', NULL, 1, '2025-10-15 17:17:48'),
(120, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹180.00', NULL, 1, '2025-10-15 17:18:07'),
(121, 1, 'session_management', 'Payment processed for session 36. Method: cash, Amount: ₹90.00', NULL, 1, '2025-10-15 17:18:18'),
(122, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-15 17:18:27'),
(123, 1, 'session_management', 'Added 4x Coca Cola to session on console 2. Stock reduced by 4', NULL, 1, '2025-10-15 17:18:34'),
(124, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹190.00', NULL, 1, '2025-10-15 17:18:51'),
(125, 1, 'session_management', 'Started gaming session for q on console xfcgfq', NULL, 1, '2025-10-15 17:25:23'),
(126, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹270.00', NULL, 1, '2025-10-15 17:25:53'),
(127, 1, 'session_management', 'Payment processed for session 38. Method: cash, Amount: ₹270.00', NULL, 1, '2025-10-15 17:26:04'),
(128, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-15 17:26:14'),
(129, 1, 'session_management', 'Added 5x Tea to session on console 2. Stock reduced by 5', NULL, 1, '2025-10-15 17:26:37'),
(130, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹190.00', NULL, 1, '2025-10-15 17:26:40'),
(131, 1, 'session_management', 'Payment processed for session 39. Method: cash, Amount: ₹100.00', NULL, 1, '2025-10-15 17:27:34'),
(132, 1, 'session_management', 'Started gaming session for az on console xfcgfq', NULL, 1, '2025-10-15 17:29:20'),
(133, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹90.00', NULL, 1, '2025-10-15 17:29:40'),
(134, 1, 'session_management', 'Started gaming session for az on console xfcgfq', NULL, 1, '2025-10-15 17:29:50'),
(135, 1, 'session_management', 'Added 4x Coca Cola to session on console 2. Stock reduced by 4', NULL, 1, '2025-10-15 17:30:14'),
(136, 1, 'session_management', 'Added 5x Tea to session on console 2. Stock reduced by 5', NULL, 1, '2025-10-15 17:30:14'),
(137, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹200.00', NULL, 1, '2025-10-15 17:30:22'),
(138, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-15 17:33:30'),
(139, 1, 'session_management', 'Added 4x Coca Cola to session on console 2. Stock reduced by 4', NULL, 1, '2025-10-15 17:33:42'),
(140, 1, 'session_management', 'Removed 4x Coca Cola from session on console 2. Stock restored by 4', NULL, 1, '2025-10-15 17:33:51'),
(141, 1, 'session_management', 'Added 4x Coca Cola to session on console 2. Stock reduced by 4', NULL, 1, '2025-10-15 17:33:58'),
(142, 1, 'session_management', 'Removed 4x Coca Cola from session on console 2. Stock restored by 4', NULL, 1, '2025-10-15 17:34:02'),
(143, 1, 'session_management', 'Added 4x Coca Cola to session on console 2. Stock reduced by 4', NULL, 1, '2025-10-15 17:34:07'),
(144, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹280.00', NULL, 1, '2025-10-15 17:34:31'),
(145, 1, 'session_management', 'Payment processed for session 42. Method: cash_upi, Amount: ₹145.00', NULL, 1, '2025-10-15 17:34:55'),
(146, 1, 'session_management', 'Started gaming session for g on console xfcgfq', NULL, 1, '2025-10-15 17:39:43'),
(147, 1, 'session_management', 'Added 4x Coca Cola to session on console 2. Stock reduced by 4', NULL, 1, '2025-10-15 17:40:10'),
(148, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹280.00', NULL, 1, '2025-10-15 17:40:16'),
(149, 1, 'session_management', 'Payment processed for session 43. Method: cash_upi, Amount: ₹145.00', NULL, 1, '2025-10-15 17:40:33'),
(150, 1, 'login', 'User logged in', '::1', 1, '2025-10-18 06:44:09'),
(151, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-18 06:49:00'),
(152, 1, 'session_management', 'Payment processed and session ended for session 44. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-18 06:49:18'),
(153, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-18 06:51:04'),
(154, 1, 'session_management', 'Payment processed and session ended for session 45. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-18 06:51:31'),
(155, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-18 06:53:58'),
(156, 1, 'session_management', 'Added 10x Coca Cola to session on console 2. Stock reduced by 10', NULL, 1, '2025-10-18 06:54:05'),
(157, 1, 'session_management', 'Payment processed and session ended for session 46. Method: cash, Amount: ₹95.00', NULL, 1, '2025-10-18 07:29:37'),
(158, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-18 07:30:21'),
(159, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹30.00', NULL, 1, '2025-10-18 07:30:25'),
(160, 1, 'session_management', 'Payment processed for session 47. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-18 07:30:28'),
(161, 1, 'session_management', 'Started gaming session for ajju on console xfcgfq', NULL, 1, '2025-10-18 07:49:40'),
(162, 1, 'session_management', 'Added 5x Coca Cola to session on console 2. Stock reduced by 5', NULL, 1, '2025-10-18 07:49:48'),
(163, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹42.50', NULL, 1, '2025-10-18 07:49:55'),
(164, 1, 'session_management', 'Payment processed for session 48. Method: cash, Amount: ₹27.50', NULL, 1, '2025-10-18 07:50:10'),
(165, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-18 08:24:45'),
(166, 1, 'console_create', 'Created console: xbox', '::1', 1, '2025-10-18 08:29:13'),
(167, 1, 'session_management', 'Added 2x Coca Cola to session on console 2. Stock reduced by 2', NULL, 1, '2025-10-18 08:33:41'),
(168, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹815.00', NULL, 1, '2025-10-18 16:51:56'),
(169, 1, 'session_management', 'Payment processed for session 49. Method: cash, Amount: ₹815.00', NULL, 1, '2025-10-18 16:52:00'),
(170, 1, 'session_management', 'Started gaming session for azlan on console xfcgfq', NULL, 1, '2025-10-18 16:52:16'),
(171, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹30.00', NULL, 1, '2025-10-18 16:52:27'),
(172, 1, 'session_management', 'Payment processed for session 50. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-18 16:52:55'),
(173, 1, 'session_management', 'Started gaming session for a on console xfcgfq', NULL, 1, '2025-10-18 16:53:07'),
(174, 1, 'session_management', 'Ended gaming session on console 2. Total: ₹30.00', NULL, 1, '2025-10-18 16:53:13'),
(175, 1, 'session_management', 'Payment processed for session 51. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-18 16:53:16'),
(176, 1, 'session_management', 'Started gaming session for Azlan on console xbox', NULL, 1, '2025-10-20 01:17:59'),
(177, 1, 'session_management', 'Added 15x Coca Cola to session on console 4. Stock reduced by 15', NULL, 1, '2025-10-20 01:38:53'),
(178, 1, 'session_management', 'Added 2x Coffee to session on console 4. Stock reduced by 2', NULL, 1, '2025-10-20 01:50:39'),
(179, 1, 'session_management', 'Added 2x Ice Cream to session on console 4. Stock reduced by 2', NULL, 1, '2025-10-20 01:53:06'),
(180, 1, 'session_management', 'Added 16x Coffee to session on console 4. Stock reduced by 16', NULL, 1, '2025-10-20 02:07:31'),
(181, 1, 'session_management', 'Ended gaming session on console 4. Total: ₹200.48', NULL, 1, '2025-10-20 02:16:33'),
(182, 1, 'session_management', 'Payment processed for session 52. Method: cash, Amount: ₹200.48', NULL, 1, '2025-10-20 02:16:36'),
(183, 1, 'session_management', 'Started gaming session for ajju on console xbox', NULL, 1, '2025-10-20 02:20:47'),
(184, 1, 'session_management', 'Added 6x Energy Drink to session on console 4. Stock reduced by 6', NULL, 1, '2025-10-20 02:29:28'),
(185, 1, 'session_management', 'Ended gaming session on console 4. Total: ₹53.94', NULL, 1, '2025-10-20 02:29:32'),
(186, 1, 'session_management', 'Payment processed for session 53. Method: cash_upi, Amount: ₹38.94', NULL, 1, '2025-10-20 02:29:51'),
(187, 1, 'login', 'User logged in', '::1', 1, '2025-10-20 15:40:07'),
(188, 1, 'session_management', 'Started gaming session for Azlan on console xbox', NULL, 1, '2025-10-20 15:55:57'),
(189, 1, 'session_management', 'Initiated session end for console 4. Billing info generated. Session ID: 54', NULL, 1, '2025-10-20 16:02:24'),
(190, 1, 'session_management', 'Started gaming session for ajlan on console xfcgfq', NULL, 1, '2025-10-20 16:03:32'),
(191, 1, 'session_management', 'Initiated session end for console 2. Billing info generated. Session ID: 55', NULL, 1, '2025-10-20 16:03:37'),
(192, 1, 'session_management', 'Payment processed for session 55. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-20 16:03:39'),
(193, 1, 'session_management', 'Payment processed for session 55. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-20 16:03:49'),
(194, 1, 'session_management', 'Started gaming session for ajlan on console test', NULL, 1, '2025-10-20 16:04:09'),
(195, 1, 'session_management', 'Initiated session end for console 1. Billing info generated. Session ID: 56', NULL, 1, '2025-10-20 16:04:16'),
(196, 1, 'session_management', 'Started gaming session for aa on console xfcgfq', NULL, 1, '2025-10-20 16:06:16'),
(197, 1, 'session_management', 'Session 57 ended and billing details generated for console 2.', NULL, 1, '2025-10-20 16:09:56'),
(198, 1, 'session_management', 'Payment processed and session finalized for session 57. Method: cash, Amount: ₹0.00', NULL, 1, '2025-10-20 16:09:56'),
(199, 1, 'session_management', 'Started gaming session for az on console xfcgfq', NULL, 1, '2025-10-20 16:10:07'),
(200, 1, 'session_management', 'Added 5x Energy Drink to session on console 2. Stock reduced by 5', NULL, 1, '2025-10-20 16:10:32'),
(201, 1, 'session_management', 'Session 58 ended and billing details generated for console 2.', NULL, 1, '2025-10-20 16:13:51'),
(202, 1, 'session_management', 'Payment processed and session finalized for session 58. Method: cash_upi, Amount: ₹34.95', NULL, 1, '2025-10-20 16:13:51'),
(203, 1, 'console_delete', 'Deleted console ID: 1', '::1', 1, '2025-10-20 16:19:32'),
(204, 1, 'console_delete', 'Deleted console ID: 4', '::1', 1, '2025-10-20 16:19:37'),
(205, 1, 'console_delete', 'Deleted console ID: 3', '::1', 1, '2025-10-20 16:19:41'),
(206, 1, 'console_create', 'Created console: test console', '::1', 1, '2025-10-20 16:20:03'),
(207, 1, 'session_management', 'Started gaming session for azlan on console test console', NULL, 1, '2025-10-20 16:21:26'),
(208, 1, 'session_management', 'Paused gaming session on console 5', NULL, 1, '2025-10-20 16:22:03'),
(209, 1, 'session_management', 'Resumed gaming session on console 5', NULL, 1, '2025-10-20 16:22:25'),
(210, 1, 'session_management', 'Paused gaming session on console 5', NULL, 1, '2025-10-20 16:22:28'),
(211, 1, 'session_management', 'Resumed gaming session on console 5', NULL, 1, '2025-10-20 16:22:32'),
(212, 1, 'session_management', 'Session 59 ended and billing details generated for console 5.', NULL, 1, '2025-10-20 16:29:50'),
(213, 1, 'session_management', 'Payment processed and session finalized for session 59. Method: cash, Amount: ₹0.00', NULL, 1, '2025-10-20 16:29:50'),
(214, 1, 'session_management', 'Session 60 ended and billing details generated for console 5.', NULL, 1, '2025-10-20 16:32:07'),
(215, 1, 'session_management', 'Payment processed and session finalized for session 60. Method: cash, Amount: ₹0.00', NULL, 1, '2025-10-20 16:32:07'),
(216, 1, 'session_management', 'Session 61 ended and billing details generated for console 5.', NULL, 1, '2025-10-20 16:35:15'),
(217, 1, 'session_management', 'Payment processed and session finalized for session 61. Method: cash, Amount: ₹0.00', NULL, 1, '2025-10-20 16:35:16'),
(218, 1, 'session_management', 'Started gaming session for azlan on console test console', NULL, 1, '2025-10-20 16:36:04'),
(219, 1, 'session_management', 'Session 63 ended and billing details generated for console 5.', NULL, 1, '2025-10-20 16:41:20'),
(220, 1, 'session_management', 'Payment processed and session finalized for session 63. Method: cash, Amount: ₹0.00', NULL, 1, '2025-10-20 16:41:20'),
(221, 1, 'session_management', 'Changed player count from 1 to 4 on console 5', NULL, 1, '2025-10-20 16:42:18'),
(222, 1, 'session_management', 'Changed player count from 4 to 3 on console 5', NULL, 1, '2025-10-20 16:42:46'),
(223, 1, 'session_management', 'Added 3x Coca Cola to session on console 5. Stock reduced by 3', NULL, 1, '2025-10-20 16:42:55'),
(224, 1, 'session_management', 'Session 64 ended and billing details generated for console 5.', NULL, 1, '2025-10-20 16:43:35'),
(225, 1, 'session_management', 'Payment processed and session finalized for session 64. Method: cash, Amount: ₹102.50', NULL, 1, '2025-10-20 16:43:35'),
(226, 1, 'session_management', 'Started gaming session for thasleem on console test console', NULL, 1, '2025-10-20 16:43:48'),
(227, 1, 'session_management', 'Added 10x Tea to session on console 5. Stock reduced by 10', NULL, 1, '2025-10-20 16:44:03'),
(228, 1, 'session_management', 'Changed player count from 1 to 3 on console 5', NULL, 1, '2025-10-20 16:44:13'),
(229, 1, 'session_management', 'Session 65 ended and billing details generated for console 5.', NULL, 1, '2025-10-20 16:45:16'),
(230, 1, 'session_management', 'Payment processed and session finalized for session 65. Method: cash_upi, Amount: ₹70.00', NULL, 1, '2025-10-20 16:45:16'),
(231, 1, 'session_management', 'Started gaming session for thacchi on console test console', NULL, 1, '2025-10-20 16:46:41'),
(232, 1, 'session_management', 'Changed player count from 1 to 2 on console 5', NULL, 1, '2025-10-20 16:49:14'),
(233, 1, 'session_management', 'Added 4x Coca Cola to session on console 5. Stock reduced by 4', NULL, 1, '2025-10-20 16:49:27'),
(234, 1, 'session_management', 'Session 66 ended and billing details generated for console 5.', NULL, 1, '2025-10-20 16:49:51'),
(235, 1, 'session_management', 'Payment processed and session finalized for session 66. Method: cash, Amount: ₹50.00', NULL, 1, '2025-10-20 16:49:51'),
(236, 1, 'session_management', 'Started gaming session for ajju on console test console', NULL, 1, '2025-10-20 17:00:51'),
(237, 1, 'session_management', 'Session 62 ended and billing details generated for console 2.', NULL, 1, '2025-10-20 17:02:24'),
(238, 1, 'session_management', 'Payment processed and session finalized for session 62. Method: cash, Amount: ₹0.00', NULL, 1, '2025-10-20 17:02:24'),
(239, 1, 'session_management', 'Paused gaming session on console 5', NULL, 1, '2025-10-21 14:00:17'),
(240, 1, 'session_management', 'Resumed gaming session on console 5', NULL, 1, '2025-10-21 14:00:21'),
(241, 1, 'session_management', 'Changed player count from 2 to 3 on console 5', NULL, 1, '2025-10-21 14:00:38'),
(242, 1, 'session_management', 'Changed player count from 3 to 2 on console 5', NULL, 1, '2025-10-21 14:02:24'),
(243, 1, 'session_management', 'Added 7x Energy Drink to session on console 5. Stock reduced by 7', NULL, 1, '2025-10-21 14:03:53'),
(244, 1, 'session_management', 'Started gaming session for thameem on console xfcgfq', NULL, 1, '2025-10-21 14:05:50'),
(245, 1, 'session_management', 'Resumed gaming session on console 2', NULL, 1, '2025-10-21 14:06:45'),
(246, 1, 'session_management', 'Paused gaming session on console 2', NULL, 1, '2025-10-21 14:09:11'),
(247, 1, 'session_management', 'Resumed gaming session on console 2', NULL, 1, '2025-10-21 14:09:17'),
(248, 1, 'session_management', 'Resumed gaming session on console 5', NULL, 1, '2025-10-21 14:14:30'),
(249, 1, 'session_management', 'Paused gaming session on console 5 - Reason: Customer Break', NULL, 1, '2025-10-21 14:21:36'),
(250, 1, 'session_management', 'Resumed gaming session on console 5', NULL, 1, '2025-10-21 14:21:44'),
(251, 1, 'session_management', 'Session 67 ended and billing details generated for console 5.', NULL, 1, '2025-10-21 14:22:16'),
(252, 1, 'session_management', 'Payment processed and session finalized for session 67. Method: cash, Amount: ₹3,117.93', NULL, 1, '2025-10-21 14:22:16'),
(253, 1, 'session_management', 'Started gaming session for azlan on console test console', NULL, 1, '2025-10-21 14:22:32'),
(254, 1, 'session_management', 'Paused gaming session on console 5 - Reason: Customer Break', NULL, 1, '2025-10-21 14:23:27'),
(255, 1, 'session_management', 'Resumed gaming session on console 5', NULL, 1, '2025-10-21 14:24:24'),
(256, 1, 'session_management', 'Session 69 ended and billing details generated for console 5.', NULL, 1, '2025-10-21 14:27:05'),
(257, 1, 'session_management', 'Payment processed and session finalized for session 69. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-21 14:27:05'),
(258, 1, 'session_management', 'Started gaming session for azlan on console test console', NULL, 1, '2025-10-21 14:27:36'),
(259, 1, 'session_management', 'Session 70 ended and billing details generated for console 5.', NULL, 1, '2025-10-21 14:31:38'),
(260, 1, 'session_management', 'Payment processed and session finalized for session 70. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-21 14:31:38'),
(261, 1, 'session_management', 'Started gaming session for thasleem on console test console', NULL, 1, '2025-10-21 14:31:49'),
(262, 1, 'session_management', 'Paused gaming session on console 2 - Reason: Bathroom Break', NULL, 1, '2025-10-21 14:35:40'),
(263, 1, 'session_management', 'Resumed gaming session on console 2', NULL, 1, '2025-10-21 15:05:30'),
(264, 1, 'session_management', 'Paused gaming session on console 2 - Reason: Phone Call', NULL, 1, '2025-10-21 15:14:34'),
(265, 1, 'session_management', 'Resumed gaming session on console 2', NULL, 1, '2025-10-21 15:15:40'),
(266, 1, 'session_management', 'Changed player count from 1 to 2 on console 2', NULL, 1, '2025-10-21 15:18:17'),
(267, 1, 'session_management', 'Paused gaming session on console 2 - Reason: chor unnog poyre', NULL, 1, '2025-10-21 15:21:39'),
(268, 1, 'session_management', 'Resumed gaming session on console 2', NULL, 1, '2025-10-21 15:21:54'),
(269, 1, 'coupon_update', 'Updated coupon: test', '::1', 1, '2025-10-21 15:23:40'),
(270, 1, 'session_management', 'Paused gaming session on console 2 - Reason: Technical Issue', NULL, 1, '2025-10-21 15:23:53'),
(271, 1, 'session_management', 'Resumed gaming session on console 2', NULL, 1, '2025-10-21 15:24:01'),
(272, 1, 'coupon_update', 'Updated coupon: test', '::1', 1, '2025-10-21 15:24:38'),
(273, 1, 'coupon_update', 'Updated coupon: test', '::1', 1, '2025-10-21 15:25:43'),
(274, 1, 'session_management', 'Session 68 ended and billing details generated for console 2.', NULL, 1, '2025-10-21 15:29:21'),
(275, 1, 'session_management', 'Payment processed and session finalized for session 68. Method: cash, Amount: ₹96.92', NULL, 1, '2025-10-21 15:29:21'),
(276, 1, 'session_management', 'Added 3x Energy Drink to session on console 5. Stock reduced by 3', NULL, 1, '2025-10-21 15:30:01'),
(277, 1, 'coupon_update', 'Updated coupon: test', '::1', 1, '2025-10-21 15:30:58'),
(278, 1, 'session_management', 'Changed player count from 1 to 2 on console 5', NULL, 1, '2025-10-21 15:36:16'),
(279, 1, 'session_management', 'Paused gaming session on console 5 - Reason: Technical Issue', NULL, 1, '2025-10-21 15:46:06'),
(280, 1, 'session_management', 'Resumed gaming session on console 5', NULL, 1, '2025-10-21 15:47:40'),
(281, 1, 'session_management', 'Session 71 ended and billing details generated for console 5.', NULL, 1, '2025-10-21 15:48:42'),
(282, 1, 'session_management', 'Payment processed and session finalized for session 71. Method: cash, Amount: ₹126.97', NULL, 1, '2025-10-21 15:48:42'),
(283, 1, 'session_management', 'Started gaming session for azlan on console test console', NULL, 1, '2025-10-21 15:49:10'),
(284, 1, 'session_management', 'Session 72 ended and billing details generated for console 5.', NULL, 1, '2025-10-21 15:55:11'),
(285, 1, 'session_management', 'Payment processed and session finalized for session 72. Method: cash, Amount: ₹15.00', NULL, 1, '2025-10-21 15:55:11'),
(286, 1, 'session_management', 'Started gaming session for sq on console test console', NULL, 1, '2025-10-21 16:00:40'),
(287, 1, 'user_create', 'Created user: manager', '::1', 1, '2025-10-22 15:11:37'),
(288, 1, 'branch_create', 'Created branch: mangalore branch', '::1', 1, '2025-10-22 15:21:34'),
(289, 1, 'session_management', 'Session 73 ended and billing details generated for console 5.', NULL, 1, '2025-10-22 15:22:00'),
(290, 1, 'session_management', 'Payment processed and session finalized for session 73. Method: cash, Amount: ₹2,160.00', NULL, 1, '2025-10-22 15:22:00'),
(291, 1, 'console_update', 'Updated console: test console', '::1', 1, '2025-10-22 15:24:07'),
(292, 1, 'user_update', 'Updated user: admin', '::1', 1, '2025-10-22 15:24:27'),
(293, 1, 'user_update', 'Updated user: manager', '::1', 1, '2025-10-22 15:24:36'),
(294, 1, 'console_update', 'Updated console: xfcgfq', '::1', 1, '2025-10-22 15:25:02'),
(295, 1, 'session_management', 'Started gaming session for ajju on console test console', NULL, 1, '2025-10-22 15:34:45'),
(296, 1, 'session_management', 'Session 74 ended and billing details generated for console 5.', NULL, 1, '2025-10-22 15:34:52'),
(297, 1, 'session_management', 'Payment processed and session finalized for session 74. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-22 15:34:52'),
(298, 1, 'session_management', 'Started gaming session for ajju on console test console', NULL, 1, '2025-10-22 15:37:58'),
(299, 1, 'session_management', 'Session 75 ended and billing details generated for console 5.', NULL, 1, '2025-10-22 15:38:03'),
(300, 1, 'session_management', 'Payment processed and session finalized for session 75. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-22 15:38:03'),
(301, 1, 'branch_create', 'Created branch: test', '::1', 1, '2025-10-22 16:14:27'),
(302, 1, 'logout', 'User logged out', '::1', 1, '2025-10-22 16:15:51'),
(303, 3, 'login', 'User logged in', '::1', 1, '2025-10-22 16:16:04'),
(304, 3, 'session_management', 'Started gaming session for azlanajju on console test console', NULL, 1, '2025-10-22 16:17:24'),
(305, 3, 'logout', 'User logged out', '::1', 1, '2025-10-22 18:15:45'),
(306, 3, 'login', 'User logged in', '::1', 1, '2025-10-22 18:15:55'),
(307, 3, 'coupon_create', 'Created coupon: test', '::1', 1, '2025-10-22 18:20:04'),
(308, 3, 'session_management', 'Session 76 ended and billing details generated for console 5.', NULL, 1, '2025-10-22 18:23:21'),
(309, 3, 'session_management', 'Payment processed and session finalized for session 76. Method: cash, Amount: ₹243.00', NULL, 1, '2025-10-22 18:23:21'),
(310, 1, 'login', 'User logged in', '::1', 1, '2025-10-24 16:33:22'),
(311, 3, 'login', 'User logged in', '::1', 1, '2025-10-24 16:34:04'),
(312, 1, 'branch_delete', 'Deleted branch ID: 3', '::1', 1, '2025-10-24 16:35:48'),
(313, 3, 'console_create', 'Created console: hello', '::1', 1, '2025-10-24 16:58:11'),
(314, 1, 'user_create', 'Created user: manager2', '::1', 1, '2025-10-24 16:59:56'),
(315, 3, 'logout', 'User logged out', '::1', 1, '2025-10-24 17:00:41'),
(316, 4, 'login', 'User logged in', '::1', 1, '2025-10-24 17:00:47'),
(317, 3, 'login', 'User logged in', '::1', 1, '2025-10-24 17:06:45'),
(318, 3, 'session_management', 'Started gaming session for a on console hello', NULL, 1, '2025-10-24 17:08:18'),
(319, 4, 'session_management', 'Started gaming session for ad on console xfcgfq', NULL, 1, '2025-10-24 17:08:43'),
(320, 4, 'session_management', 'Session 78 ended and billing details generated for console 2.', NULL, 1, '2025-10-24 17:08:58'),
(321, 4, 'session_management', 'Payment processed and session finalized for session 78. Method: cash, Amount: ₹35.00', NULL, 1, '2025-10-24 17:08:58'),
(322, 4, 'session_management', 'Started gaming session for a on console xfcgfq', NULL, 1, '2025-10-24 17:13:13'),
(323, 1, 'session_management', 'Session 77 ended and billing details generated for console 6.', NULL, 1, '2025-10-24 17:13:30'),
(324, 1, 'session_management', 'Payment processed and session finalized for session 77. Method: cash, Amount: ₹35.00', NULL, 1, '2025-10-24 17:13:30'),
(325, 4, 'session_management', 'Session 79 ended and billing details generated for console 2.', NULL, 1, '2025-10-24 17:13:48'),
(326, 4, 'session_management', 'Payment processed and session finalized for session 79. Method: cash, Amount: ₹35.00', NULL, 1, '2025-10-24 17:13:48'),
(327, 3, 'session_management', 'Started gaming session for e on console hello', NULL, 1, '2025-10-24 17:14:15'),
(328, 3, 'session_management', 'Changed player count from 1 to 2 on console 6', NULL, 1, '2025-10-24 17:14:24'),
(329, 3, 'session_management', 'Changed player count from 2 to 3 on console 6', NULL, 1, '2025-10-24 17:14:31'),
(330, 3, 'session_management', 'Changed player count from 3 to 4 on console 6', NULL, 1, '2025-10-24 17:15:20'),
(331, 3, 'session_management', 'Added 3x azlan to session on console 6. Stock reduced by 3', NULL, 1, '2025-10-24 17:19:51'),
(332, 3, 'session_management', 'Added 2x Coffee to session on console 6. Stock reduced by 2', NULL, 1, '2025-10-24 17:20:02'),
(333, 3, 'session_management', 'Started gaming session for 1 on console test console', NULL, 1, '2025-10-24 17:22:48'),
(334, 3, 'console_create', 'Created console: test', '::1', 1, '2025-10-24 17:24:07'),
(335, 3, 'session_management', 'Added 1x azlan to session on console 6. Stock reduced by 1', NULL, 1, '2025-10-24 17:25:46'),
(336, 3, 'session_management', 'Removed 3x azlan from session on console 6. Stock restored by 3', NULL, 1, '2025-10-24 17:26:43'),
(337, 3, 'session_management', 'Removed 1x azlan from session on console 6. Stock restored by 1', NULL, 1, '2025-10-24 17:26:52'),
(338, 3, 'session_management', 'Session 80 ended and billing details generated for console 6.', NULL, 1, '2025-10-24 17:32:55'),
(339, 3, 'session_management', 'Payment processed and session finalized for session 80. Method: cash, Amount: ₹285.00', NULL, 1, '2025-10-24 17:32:55'),
(340, 1, 'session_management', 'Started gaming session for ajju on console test', NULL, 1, '2025-10-24 17:33:46'),
(341, 1, 'session_management', 'Paused gaming session on console 7 - Reason: Technical Issue', NULL, 1, '2025-10-24 17:33:55'),
(342, 1, 'session_management', 'Resumed gaming session on console 7', NULL, 1, '2025-10-24 17:35:11'),
(343, 1, 'session_management', 'Changed player count from 1 to 2 on console 7', NULL, 1, '2025-10-24 17:35:19'),
(344, 1, 'session_management', 'Session 82 ended and billing details generated for console 7.', NULL, 1, '2025-10-24 17:35:31'),
(345, 1, 'session_management', 'Payment processed and session finalized for session 82. Method: cash, Amount: ₹80.00', NULL, 1, '2025-10-24 17:35:31'),
(346, 1, 'session_management', 'Paused gaming session on console 5 - Reason: Customer Break', NULL, 1, '2025-10-24 17:35:57'),
(347, 1, 'session_management', 'Resumed gaming session on console 5', NULL, 1, '2025-10-24 17:36:00'),
(348, 1, 'session_management', 'Session 81 ended and billing details generated for console 5.', NULL, 1, '2025-10-24 17:36:12'),
(349, 1, 'session_management', 'Payment processed and session finalized for session 81. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-24 17:36:12'),
(350, 1, 'session_management', 'Started gaming session for knk on console test', NULL, 1, '2025-10-24 17:37:34'),
(351, 1, 'session_management', 'Started gaming session for j on console hello', NULL, 1, '2025-10-24 17:37:41'),
(352, 1, 'session_management', 'Started gaming session for kj on console test console', NULL, 1, '2025-10-24 17:37:47'),
(353, 1, 'session_management', 'Paused gaming session on console 7 - Reason: Bathroom Break', NULL, 1, '2025-10-24 17:37:58'),
(354, 1, 'session_management', 'Resumed gaming session on console 7', NULL, 1, '2025-10-24 17:38:04'),
(355, 1, 'session_management', 'Session 83 ended and billing details generated for console 7.', NULL, 1, '2025-10-24 17:38:15'),
(356, 1, 'session_management', 'Payment processed and session finalized for session 83. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-24 17:38:15'),
(357, 1, 'session_management', 'Changed player count from 1 to 2 on console 6', NULL, 1, '2025-10-24 17:43:07'),
(358, 1, 'session_management', 'Changed player count from 2 to 3 on console 6', NULL, 1, '2025-10-24 17:43:13'),
(359, 1, 'session_management', 'Session 84 ended and billing details generated for console 6.', NULL, 1, '2025-10-24 17:43:20'),
(360, 1, 'session_management', 'Payment processed and session finalized for session 84. Method: cash, Amount: ₹150.00', NULL, 1, '2025-10-24 17:43:20'),
(361, 1, 'session_management', 'Changed player count from 1 to 2 on console 5', NULL, 1, '2025-10-24 17:46:59'),
(362, 1, 'session_management', 'Changed player count from 2 to 3 on console 5', NULL, 1, '2025-10-24 17:47:07'),
(363, 1, 'session_management', 'Changed player count from 3 to 4 on console 5', NULL, 1, '2025-10-24 17:47:24'),
(364, 1, 'session_management', 'Session 85 ended and billing details generated for console 5.', NULL, 1, '2025-10-24 17:47:38'),
(365, 1, 'session_management', 'Payment processed and session finalized for session 85. Method: cash, Amount: ₹240.00', NULL, 1, '2025-10-24 17:47:38'),
(366, 1, 'session_management', 'Started gaming session for ds on console test', NULL, 1, '2025-10-24 17:48:19'),
(367, 1, 'session_management', 'Started gaming session for a on console hello', NULL, 1, '2025-10-24 17:48:25'),
(368, 1, 'session_management', 'Started gaming session for a on console test console', NULL, 1, '2025-10-24 17:48:30'),
(369, 1, 'session_management', 'Changed player count from 1 to 2 on console 7', NULL, 1, '2025-10-24 17:48:43'),
(370, 1, 'session_management', 'Changed player count from 2 to 4 on console 7', NULL, 1, '2025-10-24 17:48:58'),
(371, 4, 'session_management', 'Started gaming session for a on console xfcgfq', NULL, 1, '2025-10-24 17:50:23'),
(372, 4, 'session_management', 'Session 89 ended and billing details generated for console 2.', NULL, 1, '2025-10-24 17:50:39'),
(373, 4, 'session_management', 'Payment processed and session finalized for session 89. Method: cash, Amount: ₹30.00', NULL, 1, '2025-10-24 17:50:39'),
(374, 3, 'session_management', 'Session 86 ended and billing details generated for console 7.', NULL, 1, '2025-10-24 17:51:41'),
(375, 3, 'session_management', 'Payment processed and session finalized for session 86. Method: cash, Amount: ₹170.00', NULL, 1, '2025-10-24 17:51:41'),
(376, 3, 'session_management', 'Added 1x Coffee to session on console 6. Stock reduced by 1', NULL, 1, '2025-10-24 18:21:30'),
(377, 3, 'session_management', 'Paused gaming session on console 6 - Reason: Customer Break', NULL, 1, '2025-10-24 18:21:40'),
(378, 4, 'session_management', 'Started gaming session for jjnj on console xfcgfq', NULL, 1, '2025-10-24 18:25:14'),
(379, 4, 'session_management', 'Changed player count from 1 to 2 on console 2', NULL, 1, '2025-10-24 18:25:23'),
(380, 4, 'session_management', 'Paused gaming session on console 2 - Reason: Phone Call', NULL, 1, '2025-10-24 18:25:31'),
(381, 4, 'session_management', 'Resumed gaming session on console 2', NULL, 1, '2025-10-24 18:25:41'),
(382, 4, 'session_management', 'Session 90 ended and billing details generated for console 2.', NULL, 1, '2025-10-24 18:25:53'),
(383, 4, 'session_management', 'Payment processed and session finalized for session 90. Method: cash, Amount: ₹80.00', NULL, 1, '2025-10-24 18:25:53'),
(384, 3, 'logout', 'User logged out', '::1', 1, '2025-10-24 18:27:18'),
(385, 2, 'login', 'User logged in', '::1', 1, '2025-10-24 18:27:25');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `contact` varchar(20) NOT NULL,
  `manager_name` varchar(255) NOT NULL,
  `timing` varchar(100) NOT NULL,
  `console_count` int(11) DEFAULT 0,
  `established_year` int(4) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `manager_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `location`, `address`, `contact`, `manager_name`, `timing`, `console_count`, `established_year`, `status`, `created_at`, `updated_at`, `manager_id`) VALUES
(1, 'Main Branch', 'Downtown', '123 Gaming Street, City Center', '+1234567890', 'muhammad azlan', '10:00 AM - 12:00 AM', 10, 2023, 'Active', '2025-10-11 09:02:17', '2025-10-24 17:01:14', 4),
(2, 'mangalore branch', 'mangalore', 'address', '6361557581', 'manager', '10', 15, 2023, 'Active', '2025-10-22 15:21:34', '2025-10-24 16:51:46', 3);

-- --------------------------------------------------------

--
-- Table structure for table `consoles`
--

CREATE TABLE `consoles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `specifications` text DEFAULT NULL,
  `purchase_year` int(4) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `primary_user` varchar(255) DEFAULT NULL,
  `location` varchar(100) NOT NULL,
  `has_plus_account` tinyint(1) DEFAULT 0,
  `under_maintenance` tinyint(1) DEFAULT 0,
  `status` enum('Available','In Use','Maintenance') NOT NULL DEFAULT 'Available',
  `branch_id` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `consoles`
--

INSERT INTO `consoles` (`id`, `name`, `type`, `specifications`, `purchase_year`, `email`, `primary_user`, `location`, `has_plus_account`, `under_maintenance`, `status`, `branch_id`, `created_at`, `updated_at`) VALUES
(2, 'xfcgfq', 'PC', 'RTX 4060, i5-12600K, 16GB RAM', 2025, 'muhammedazlan11@gmail.com', 'azlan', '0', 1, 0, 'Available', 1, '2025-10-11 11:41:01', '2025-10-24 18:25:53'),
(5, 'test console', 'PC', 'gtx 3050', 2025, 'muhammedazlan11@gmail.com', 'azlan', '0', 1, 0, '', 2, '2025-10-20 16:20:03', '2025-10-24 17:48:30'),
(6, 'hello', 'PS5', 'hg', 2025, 'mu@gmai.com', 'azan', '0', 1, 0, '', 2, '2025-10-24 16:58:11', '2025-10-24 17:48:25'),
(7, 'test', 'PC', '80', 2025, 'm@gmai.com', 'azlan', '0', 1, 0, 'Available', 2, '2025-10-24 17:24:07', '2025-10-24 17:51:41');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_type` enum('percentage','flat','time_bonus') NOT NULL,
  `discount_value` decimal(10,2) DEFAULT NULL,
  `base_minutes` int(11) DEFAULT NULL,
  `bonus_minutes` int(11) DEFAULT NULL,
  `loop_bonus` tinyint(1) DEFAULT 0,
  `usage_limit` int(11) DEFAULT 0,
  `times_used` int(11) DEFAULT 0,
  `usage_count` int(11) DEFAULT 0,
  `last_used_at` datetime DEFAULT NULL,
  `min_order_amount` decimal(10,2) DEFAULT 0.00,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `branch_id` int(11) DEFAULT 1,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `name`, `code`, `description`, `discount_type`, `discount_value`, `base_minutes`, `bonus_minutes`, `loop_bonus`, `usage_limit`, `times_used`, `usage_count`, `last_used_at`, `min_order_amount`, `valid_from`, `valid_to`, `branch_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'test', '123', 'couper', 'percentage', 50.00, NULL, NULL, 0, 0, 0, 14, '2025-10-21 21:25:11', 0.00, '2025-10-13', '2025-10-31', 1, 'Active', '2025-10-13 01:53:34', '2025-10-21 15:55:11'),
(3, 'test', '1234', 'desc', 'time_bonus', NULL, 30, 15, 1, 0, 0, 3, '2025-10-21 20:59:21', 0.00, '2025-10-14', '2025-10-31', 1, 'Active', '2025-10-14 17:19:07', '2025-10-21 15:30:58'),
(4, 'test', '1122', 'yghb', 'percentage', 10.00, NULL, NULL, 0, 0, 0, 1, '2025-10-22 23:53:21', 0.00, NULL, NULL, 2, 'Active', '2025-10-22 18:20:04', '2025-10-22 18:23:21');

-- --------------------------------------------------------

--
-- Table structure for table `food_and_drinks`
--

CREATE TABLE `food_and_drinks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `category` enum('beverages','snacks','meals','desserts','other') NOT NULL DEFAULT 'other',
  `description` text DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `image_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `branch_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `food_and_drinks`
--

INSERT INTO `food_and_drinks` (`id`, `name`, `price`, `stock`, `category`, `description`, `is_available`, `image_url`, `created_at`, `updated_at`, `branch_id`) VALUES
(1, 'Coca Cola', 2.50, 3, 'beverages', 'Refreshing carbonated soft drink', 1, NULL, '2025-10-11 12:16:36', '2025-10-20 16:49:27', 1),
(2, 'Pizza Slice', 8.99, 15, 'meals', 'Delicious cheese pizza slice', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36', 1),
(3, 'Coffee', 3.50, 7, 'beverages', 'Freshly brewed coffee', 0, NULL, '2025-10-11 12:16:36', '2025-10-20 16:16:31', 1),
(4, 'Sandwich', 6.99, 20, 'meals', 'Fresh sandwich with your choice of filling', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36', 1),
(5, 'French Fries', 4.50, 30, 'snacks', 'Crispy golden french fries', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36', 1),
(6, 'Burger', 12.99, 18, 'meals', 'Juicy beef burger with fries', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36', 1),
(7, 'Energy Drink', 3.99, 19, 'beverages', 'High-energy drink for gamers', 1, NULL, '2025-10-11 12:16:36', '2025-10-21 15:30:01', 1),
(8, 'Chocolate Bar', 2.99, 50, 'desserts', 'Rich chocolate bar', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36', 1),
(9, 'Nachos', 5.99, 25, 'snacks', 'Loaded nachos with cheese', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36', 1),
(10, 'Tea', 2.00, 15, 'beverages', 'Hot or iced tea', 1, NULL, '2025-10-11 12:16:36', '2025-10-20 16:44:03', 1),
(11, 'Ice Cream', 4.99, 18, 'desserts', 'Creamy vanilla ice cream', 1, NULL, '2025-10-11 12:16:36', '2025-10-20 01:53:06', 1),
(12, 'Hot Dog', 7.50, 22, 'meals', 'Classic hot dog with condiments', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36', 1),
(13, 'Coffee', 20.00, 17, 'beverages', 'desc', 1, '', '2025-10-22 16:09:39', '2025-10-24 18:21:30', 2),
(14, 'azlan', 10.00, 10, 'beverages', 'azlan', 1, '0', '2025-10-22 18:17:24', '2025-10-24 17:26:52', 2),
(15, 'Test Item', 10.50, 5, 'beverages', 'Test description', 1, '0', '2025-10-22 18:17:44', '2025-10-22 18:17:44', 1);

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `developer` varchar(255) NOT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `branch_id` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `category`, `developer`, `rating`, `release_date`, `branch_id`, `created_at`, `updated_at`) VALUES
(1, 'qwer', 'FPS', 'qwr', 5.0, '2025-10-17', 1, '2025-10-11 09:34:26', '2025-10-14 18:10:33'),
(2, 'c', 'FPS', 'tyhuj', 5.0, '2025-10-10', 1, '2025-10-14 18:17:21', '2025-10-14 18:17:21');

-- --------------------------------------------------------

--
-- Table structure for table `game_console_assignments`
--

CREATE TABLE `game_console_assignments` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `console_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `game_console_assignments`
--

INSERT INTO `game_console_assignments` (`id`, `game_id`, `console_id`) VALUES
(5, 1, 2),
(8, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `gaming_sessions`
--

CREATE TABLE `gaming_sessions` (
  `id` int(11) NOT NULL,
  `console_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_number` varchar(20) DEFAULT NULL,
  `player_count` int(11) NOT NULL DEFAULT 1,
  `rate_type` enum('regular','vip') NOT NULL DEFAULT 'regular',
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `total_fandd_amount` decimal(10,2) DEFAULT 0.00,
  `status` enum('active','paused','completed','cancelled') NOT NULL DEFAULT 'active',
  `timezone_offset` varchar(10) DEFAULT '+05:30',
  `pause_start_time` datetime DEFAULT NULL,
  `total_pause_duration` int(11) DEFAULT 0 COMMENT 'in seconds',
  `last_resume_time` datetime DEFAULT NULL,
  `total_duration_seconds` int(11) DEFAULT 0,
  `payment_method` varchar(50) DEFAULT NULL,
  `coupon_code` varchar(50) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `final_amount` decimal(10,2) DEFAULT 0.00,
  `payment_status` enum('pending','completed','refunded') DEFAULT 'pending',
  `payment_date` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gaming_sessions`
--

INSERT INTO `gaming_sessions` (`id`, `console_id`, `customer_name`, `customer_number`, `player_count`, `rate_type`, `start_time`, `end_time`, `total_amount`, `total_fandd_amount`, `status`, `timezone_offset`, `pause_start_time`, `total_pause_duration`, `last_resume_time`, `total_duration_seconds`, `payment_method`, `coupon_code`, `discount_amount`, `final_amount`, `payment_status`, `payment_date`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 2, 'azlan', '122', 1, 'regular', '2025-10-11 17:36:58', '2025-10-11 18:08:31', 2.85, 0.00, 'completed', '+05:30', NULL, 181, '2025-10-11 17:56:36', 1712, NULL, NULL, 0.00, 0.00, 'pending', NULL, 0, '2025-10-11 12:06:58', '2025-10-11 12:38:31'),
(3, 2, 'azlan', '6361557581', 1, 'regular', '2025-10-11 18:37:30', '2025-10-11 18:38:17', 35.40, 0.00, 'completed', '+05:30', NULL, 0, NULL, 47, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-11 13:07:30', '2025-10-11 13:08:17'),
(4, 2, 'azlan', '6361557581', 1, 'regular', '2025-10-11 18:43:40', '2025-10-11 18:56:46', 35.40, 0.00, 'completed', '+05:30', NULL, 0, NULL, 786, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-11 13:13:40', '2025-10-11 13:26:46'),
(5, 2, 'ajju', '1234567890', 1, 'vip', '2025-10-11 18:57:07', '2025-10-11 18:57:16', 47.20, 0.00, 'completed', '+05:30', NULL, 0, NULL, 9, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-11 13:27:07', '2025-10-11 13:27:16'),
(6, 2, 'aju', '6361557581', 1, 'regular', '2025-10-11 19:05:56', '2025-10-11 19:07:53', 35.40, 0.00, 'completed', '+05:30', NULL, 0, NULL, 117, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-11 13:35:56', '2025-10-11 13:37:53'),
(7, 2, 'azlan', '6361557581', 3, 'regular', '2025-10-11 19:34:35', '2025-10-11 20:34:39', 224.20, 0.00, 'completed', '+05:30', NULL, 0, NULL, 3604, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-11 14:04:35', '2025-10-11 15:04:39'),
(12, 2, 'ajlan', '6361557581', 2, 'regular', '2025-10-11 20:45:54', '2025-10-11 20:49:04', 59.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 190, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-11 15:15:54', '2025-10-11 15:19:04'),
(13, 2, 'azlan', '6361557581', 1, 'regular', '2025-10-13 06:44:41', '2025-10-13 07:10:23', 55.00, 5.00, 'completed', '+05:30', NULL, 0, NULL, 1542, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-13 01:14:41', '2025-10-13 01:40:23'),
(14, 2, 'azlan', '6361557581', 1, 'regular', '2025-10-13 07:11:03', '2025-10-13 07:11:16', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 13, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-13 01:41:03', '2025-10-13 01:41:16'),
(16, 2, 'azlan', '', 1, 'regular', '2025-10-13 07:15:59', '2025-10-13 07:16:11', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 12, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-13 01:45:59', '2025-10-13 01:46:11'),
(18, 2, 'q', '', 1, 'regular', '2025-10-13 07:17:44', '2025-10-13 07:19:08', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 84, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-13 07:19:14', 1, '2025-10-13 01:47:44', '2025-10-13 01:49:14'),
(20, 2, 'azlan', '', 1, 'regular', '2025-10-13 07:22:12', '2025-10-13 07:23:43', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 91, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-13 07:23:52', 1, '2025-10-13 01:52:12', '2025-10-13 01:53:52'),
(21, 2, 'azlan', '', 1, 'regular', '2025-10-13 07:25:17', '2025-10-13 07:26:40', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 83, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-13 07:26:45', 1, '2025-10-13 01:55:17', '2025-10-13 01:56:45'),
(23, 2, 'azlan', '', 1, 'regular', '2025-10-13 07:27:05', '2025-10-13 07:27:29', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 24, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-13 01:57:05', '2025-10-13 01:57:29'),
(24, 2, 'azlan', '', 1, 'regular', '2025-10-13 07:28:51', '2025-10-13 07:28:58', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 7, 'upi', '123', 15.00, 15.00, 'completed', '2025-10-13 07:29:32', 1, '2025-10-13 01:58:51', '2025-10-13 01:59:32'),
(25, 2, 'rifaz', '098765431', 1, 'regular', '2025-10-14 22:49:19', '2025-10-14 22:50:47', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 88, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-14 22:50:58', 1, '2025-10-14 17:19:19', '2025-10-14 17:20:58'),
(26, 2, 'thameem', '09876', 1, 'regular', '2025-10-14 21:45:54', '2025-10-14 23:06:08', 180.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 4814, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-14 17:31:54', '2025-10-14 17:36:08'),
(27, 2, 'ajju', '12', 1, 'regular', '2025-10-14 23:04:28', '2025-10-14 23:44:50', 92.50, 22.50, 'completed', '+05:30', NULL, 0, NULL, 2422, 'cash', '1234', 26.25, 66.25, 'completed', '2025-10-14 23:45:10', 2, '2025-10-14 17:52:28', '2025-10-14 18:15:10'),
(29, 2, 'test', 'azk', 1, 'regular', '2025-10-14 23:47:44', '2025-10-14 23:47:48', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 4, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-14 18:17:44', '2025-10-14 18:17:48'),
(30, 2, 'hb', '', 1, 'regular', '2025-10-14 23:51:38', '2025-10-14 23:51:46', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 8, 'cash_upi', NULL, 0.00, 30.00, 'completed', '2025-10-14 23:51:53', 1, '2025-10-14 18:21:38', '2025-10-14 18:21:53'),
(31, 2, 'a', '', 1, 'regular', '2025-10-14 23:56:05', '2025-10-15 00:16:21', 50.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 1216, 'cash_upi', '1234', 0.00, 50.00, 'completed', '2025-10-15 00:16:37', 1, '2025-10-14 18:26:05', '2025-10-14 18:46:37'),
(33, 2, 'azlan', '', 1, 'regular', '2025-10-15 21:22:57', '2025-10-15 21:23:18', 35.00, 5.00, 'completed', '+05:30', NULL, 0, NULL, 21, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-15 15:52:57', '2025-10-15 15:53:18'),
(34, 2, 'azlan', '', 1, 'regular', '2025-10-15 20:24:22', '2025-10-15 21:25:24', 200.00, 20.00, 'completed', '+05:30', NULL, 0, NULL, 3662, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-15 15:54:22', '2025-10-15 15:55:24'),
(35, 2, 'ajju', '', 1, 'regular', '2025-10-15 20:45:24', '2025-10-15 22:47:11', 270.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 7307, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-15 17:15:24', '2025-10-15 17:17:11'),
(36, 2, 'ajjuu', '', 1, 'regular', '2025-10-15 20:47:48', '2025-10-15 22:48:07', 180.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 7219, 'cash', '123', 90.00, 90.00, 'completed', '2025-10-15 22:48:18', 1, '2025-10-15 17:17:48', '2025-10-15 17:18:18'),
(37, 2, 'azlan', '', 1, 'regular', '2025-10-15 20:48:27', '2025-10-15 22:48:51', 190.00, 10.00, 'completed', '+05:30', NULL, 0, NULL, 7224, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-15 17:18:27', '2025-10-15 17:18:51'),
(38, 2, 'q', '', 1, 'regular', '2025-10-15 20:55:23', '2025-10-15 22:55:53', 270.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 7230, 'cash', NULL, 0.00, 270.00, 'completed', '2025-10-15 22:56:04', 1, '2025-10-15 17:25:23', '2025-10-15 17:26:04'),
(39, 2, 'azlan', '', 1, 'regular', '2025-10-15 20:56:14', '2025-10-15 22:56:40', 190.00, 10.00, 'completed', '+05:30', NULL, 0, NULL, 7226, 'cash', '123', 90.00, 100.00, 'completed', '2025-10-15 22:57:34', 1, '2025-10-15 17:26:14', '2025-10-15 17:27:34'),
(40, 2, 'az', '', 1, 'regular', '2025-10-15 21:59:20', '2025-10-15 22:59:40', 90.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 3620, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-15 17:29:20', '2025-10-15 17:29:40'),
(41, 2, 'az', '', 1, 'regular', '2025-10-15 21:59:50', '2025-10-15 23:00:22', 200.00, 20.00, 'completed', '+05:30', NULL, 0, NULL, 3632, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-15 17:29:50', '2025-10-15 17:30:22'),
(42, 2, 'azlan', '', 1, 'regular', '2025-10-15 21:03:30', '2025-10-15 23:04:31', 280.00, 10.00, 'completed', '+05:30', NULL, 0, NULL, 7261, 'cash_upi', '123', 135.00, 145.00, 'completed', '2025-10-15 23:04:55', 1, '2025-10-15 17:33:30', '2025-10-15 17:34:55'),
(43, 2, 'g', '', 1, 'regular', '2025-10-15 21:09:43', '2025-10-15 23:10:16', 280.00, 10.00, 'completed', '+05:30', NULL, 0, NULL, 7233, 'cash_upi', '123', 135.00, 145.00, 'completed', '2025-10-15 23:10:33', 1, '2025-10-15 17:39:43', '2025-10-15 17:40:33'),
(44, 2, 'azlan', '', 1, 'regular', '2025-10-18 12:19:00', '2025-10-18 12:19:18', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 18, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-18 12:19:18', 1, '2025-10-18 06:49:00', '2025-10-18 06:49:18'),
(45, 2, 'azlan', '', 1, 'regular', '2025-10-18 12:21:04', '2025-10-18 12:21:31', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 27, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-18 12:21:31', 1, '2025-10-18 06:51:04', '2025-10-18 06:51:31'),
(46, 2, 'azlan', '', 1, 'regular', '2025-10-18 12:23:58', '2025-10-18 12:59:37', 95.00, 25.00, 'completed', '+05:30', NULL, 0, NULL, 2139, 'cash', NULL, 0.00, 95.00, 'completed', '2025-10-18 12:59:37', 1, '2025-10-18 06:53:58', '2025-10-18 07:29:37'),
(47, 2, 'azlan', '', 1, 'regular', '2025-10-18 13:00:21', '2025-10-18 13:00:25', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 4, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-18 13:00:28', 1, '2025-10-18 07:30:21', '2025-10-18 07:30:28'),
(48, 2, 'ajju', '', 1, 'regular', '2025-10-18 13:19:40', '2025-10-18 13:19:55', 42.50, 12.50, 'completed', '+05:30', NULL, 0, NULL, 15, 'cash', '123', 15.00, 27.50, 'completed', '2025-10-18 13:20:10', 1, '2025-10-18 07:49:40', '2025-10-18 07:50:10'),
(49, 2, 'azlan', '', 1, 'regular', '2025-10-18 13:54:45', '2025-10-18 22:21:56', 815.00, 5.00, 'completed', '+05:30', NULL, 0, NULL, 30431, 'cash', NULL, 0.00, 815.00, 'completed', '2025-10-18 22:22:00', 1, '2025-10-18 08:24:45', '2025-10-18 16:52:00'),
(50, 2, 'azlan', '', 1, 'regular', '2025-10-18 22:22:16', '2025-10-18 22:22:27', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 11, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-18 22:22:55', 1, '2025-10-18 16:52:16', '2025-10-18 16:52:55'),
(51, 2, 'a', '', 1, 'regular', '2025-10-18 22:23:07', '2025-10-18 22:23:13', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 6, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-18 22:23:16', 1, '2025-10-18 16:53:07', '2025-10-18 16:53:16'),
(55, 2, 'ajlan', '', 1, 'regular', '2025-10-20 21:33:32', '2025-10-20 21:33:37', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 0, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-20 21:33:49', 1, '2025-10-20 16:03:32', '2025-10-20 16:03:49'),
(57, 2, 'aa', '', 1, 'regular', '2025-10-20 21:36:16', '2025-10-20 21:39:56', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 220, 'cash', NULL, 0.00, 0.00, 'completed', '2025-10-20 21:39:56', 1, '2025-10-20 16:06:16', '2025-10-20 16:09:56'),
(58, 2, 'az', '', 1, 'regular', '2025-10-20 21:40:07', '2025-10-20 21:43:51', 49.95, 19.95, 'completed', '+05:30', NULL, 0, NULL, 224, 'cash_upi', '123', 15.00, 34.95, 'completed', '2025-10-20 21:43:51', 1, '2025-10-20 16:10:07', '2025-10-20 16:13:51'),
(59, 5, 'azlan', '', 1, 'regular', '2025-10-20 21:51:26', '2025-10-20 21:59:50', 30.00, 0.00, 'completed', '+05:30', NULL, 27, '2025-10-20 21:52:32', 477, 'cash', '123', 0.00, 0.00, 'completed', '2025-10-20 21:59:50', 1, '2025-10-20 16:21:26', '2025-10-20 16:29:50'),
(60, 5, 'hj', '', 1, 'regular', '2025-10-20 22:00:05', '2025-10-20 22:02:07', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 122, 'cash', NULL, 0.00, 0.00, 'completed', '2025-10-20 22:02:07', 1, '2025-10-20 16:30:05', '2025-10-20 16:32:07'),
(61, 5, 'hj', '', 1, 'regular', '2025-10-20 22:00:11', '2025-10-20 22:05:15', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 304, 'cash', NULL, 0.00, 0.00, 'completed', '2025-10-20 22:05:15', 1, '2025-10-20 16:30:11', '2025-10-20 16:35:15'),
(62, 2, 'ahaj', '', 1, 'regular', '2025-10-20 22:00:25', '2025-10-20 22:32:24', 70.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 1919, 'cash', NULL, 0.00, 0.00, 'completed', '2025-10-20 22:32:24', 1, '2025-10-20 16:30:25', '2025-10-20 17:02:24'),
(63, 5, 'azlan', '', 1, 'regular', '2025-10-20 22:05:27', '2025-10-20 22:11:20', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 353, 'cash', NULL, 0.00, 0.00, 'completed', '2025-10-20 22:11:20', 1, '2025-10-20 16:35:27', '2025-10-20 16:41:20'),
(64, 5, 'azlan', '', 3, 'regular', '2025-10-20 22:06:04', '2025-10-20 22:13:35', 77.50, 7.50, 'completed', '+05:30', NULL, 0, NULL, 451, 'cash', '123', 95.00, 102.50, 'completed', '2025-10-20 22:13:35', 1, '2025-10-20 16:36:04', '2025-10-20 16:43:35'),
(65, 5, 'thasleem', '', 3, 'regular', '2025-10-20 22:13:48', '2025-10-20 22:15:16', 90.00, 20.00, 'completed', '+05:30', NULL, 0, NULL, 88, 'cash_upi', '123', 50.00, 70.00, 'completed', '2025-10-20 22:15:16', 1, '2025-10-20 16:43:48', '2025-10-20 16:45:16'),
(66, 5, 'thacchi', '', 2, 'regular', '2025-10-20 22:16:41', '2025-10-20 22:19:51', 60.00, 10.00, 'completed', '+05:30', NULL, 0, NULL, 190, 'cash', '123', 40.00, 50.00, 'completed', '2025-10-20 22:19:51', 1, '2025-10-20 16:46:41', '2025-10-20 16:49:51'),
(67, 5, 'ajju', '', 2, 'regular', '2025-10-20 22:30:51', '2025-10-21 19:52:16', 3107.93, 27.93, 'completed', '+05:30', NULL, 553, '2025-10-21 19:51:44', 76332, 'cash', NULL, 0.00, 3117.93, 'completed', '2025-10-21 19:52:16', 1, '2025-10-20 17:00:51', '2025-10-21 14:22:16'),
(68, 2, 'thameem', '', 2, 'regular', '2025-10-21 19:35:50', '2025-10-21 20:59:21', 120.00, 0.00, 'completed', '+05:30', NULL, 1932, '2025-10-21 20:54:01', 3180, 'cash', '1234', 23.08, 96.92, 'completed', '2025-10-21 20:59:21', 1, '2025-10-21 14:05:50', '2025-10-21 15:29:21'),
(69, 5, 'azlan', '', 1, 'regular', '2025-10-21 17:52:32', '2025-10-21 19:57:05', 180.00, 0.00, 'completed', '+05:30', NULL, 3657, '2025-10-21 19:54:24', 3816, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-21 19:57:05', 1, '2025-10-21 14:22:32', '2025-10-21 14:27:05'),
(70, 5, 'azlan', '', 1, 'regular', '2025-10-21 14:57:36', '2025-10-21 20:01:38', 540.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 18242, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-21 20:01:38', 1, '2025-10-21 14:27:36', '2025-10-21 14:31:38'),
(71, 5, 'thasleem', '', 2, 'regular', '2025-10-21 20:01:49', '2025-10-21 21:18:42', 241.97, 11.97, 'completed', '+05:30', NULL, 94, '2025-10-21 21:17:40', 4500, 'cash', '123', 115.00, 126.97, 'completed', '2025-10-21 21:18:42', 1, '2025-10-21 14:31:49', '2025-10-21 15:48:42'),
(72, 5, 'azlan', '', 1, 'regular', '2025-10-21 21:19:10', '2025-10-21 21:25:11', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 360, 'cash', '123', 15.00, 15.00, 'completed', '2025-10-21 21:25:11', 1, '2025-10-21 15:49:10', '2025-10-21 15:55:11'),
(73, 5, 'sq', '', 1, 'regular', '2025-10-21 21:30:40', '2025-10-22 20:52:00', 2160.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 84060, 'cash', NULL, 0.00, 2160.00, 'completed', '2025-10-22 20:52:00', 1, '2025-10-21 16:00:40', '2025-10-22 15:22:00'),
(74, 5, 'ajju', '', 1, 'regular', '2025-10-22 21:04:45', '2025-10-22 21:04:52', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 0, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-22 21:04:52', 1, '2025-10-22 15:34:45', '2025-10-22 15:34:52'),
(75, 5, 'ajju', '', 1, 'regular', '2025-10-22 21:07:58', '2025-10-22 21:08:03', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 0, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-22 21:08:03', 1, '2025-10-22 15:37:58', '2025-10-22 15:38:03'),
(76, 5, 'azlanajju', '', 1, 'regular', '2025-10-22 21:47:24', '2025-10-22 23:53:21', 270.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 7560, 'cash', '1122', 27.00, 243.00, 'completed', '2025-10-22 23:53:21', 3, '2025-10-22 16:17:24', '2025-10-22 18:23:21'),
(77, 6, 'a', '', 1, 'regular', '2025-10-24 22:38:18', '2025-10-24 22:43:30', 35.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 300, 'cash', NULL, 0.00, 35.00, 'completed', '2025-10-24 22:43:30', 3, '2025-10-24 17:08:18', '2025-10-24 17:13:30'),
(78, 2, 'ad', '', 1, 'regular', '2025-10-24 22:38:43', '2025-10-24 22:38:58', 35.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 0, 'cash', '1234', 0.00, 35.00, 'completed', '2025-10-24 22:38:58', 4, '2025-10-24 17:08:43', '2025-10-24 17:08:58'),
(79, 2, 'a', '', 1, 'regular', '2025-10-24 22:43:13', '2025-10-24 22:43:48', 35.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 60, 'cash', NULL, 0.00, 35.00, 'completed', '2025-10-24 22:43:48', 4, '2025-10-24 17:13:13', '2025-10-24 17:13:48'),
(80, 6, 'e', '', 4, 'regular', '2025-10-24 22:44:15', '2025-10-24 23:02:55', 330.00, 40.00, 'completed', '+05:30', NULL, 0, NULL, 1140, 'cash', NULL, 0.00, 285.00, 'completed', '2025-10-24 23:02:55', 3, '2025-10-24 17:14:15', '2025-10-24 17:32:55'),
(81, 5, '1', '', 1, 'regular', '2025-10-24 22:52:48', '2025-10-24 23:06:12', 30.00, 0.00, 'completed', '+05:30', NULL, 3, '2025-10-24 23:06:00', 780, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-24 23:06:12', 3, '2025-10-24 17:22:48', '2025-10-24 17:36:12'),
(82, 7, 'ajju', '', 2, 'regular', '2025-10-24 23:03:46', '2025-10-24 23:05:31', 80.00, 0.00, 'completed', '+05:30', NULL, 76, '2025-10-24 23:05:11', 60, 'cash', NULL, 0.00, 80.00, 'completed', '2025-10-24 23:05:31', 1, '2025-10-24 17:33:46', '2025-10-24 17:35:31'),
(83, 7, 'knk', '', 1, 'regular', '2025-10-24 23:07:34', '2025-10-24 23:08:15', 30.00, 0.00, 'completed', '+05:30', NULL, 6, '2025-10-24 23:08:04', 60, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-24 23:08:15', 1, '2025-10-24 17:37:34', '2025-10-24 17:38:15'),
(84, 6, 'j', '', 3, 'regular', '2025-10-24 23:07:41', '2025-10-24 23:13:20', 150.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 360, 'cash', NULL, 0.00, 150.00, 'completed', '2025-10-24 23:13:20', 1, '2025-10-24 17:37:41', '2025-10-24 17:43:20'),
(85, 5, 'kj', '', 4, 'regular', '2025-10-24 23:07:47', '2025-10-24 23:17:38', 240.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 600, 'cash', NULL, 0.00, 240.00, 'completed', '2025-10-24 23:17:38', 1, '2025-10-24 17:37:47', '2025-10-24 17:47:38'),
(86, 7, 'ds', '', 4, 'regular', '2025-10-24 23:18:19', '2025-10-24 23:21:41', 170.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 180, 'cash', NULL, 0.00, 170.00, 'completed', '2025-10-24 23:21:41', 1, '2025-10-24 17:48:19', '2025-10-24 17:51:41'),
(87, 6, 'a', '', 1, 'regular', '2025-10-24 23:18:25', NULL, 0.00, 20.00, 'paused', '+05:30', '2025-10-24 23:51:40', 0, NULL, 0, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-24 17:48:25', '2025-10-24 18:21:40'),
(88, 5, 'a', '', 1, 'regular', '2025-10-24 23:18:30', NULL, 0.00, 0.00, 'active', '+05:30', NULL, 0, NULL, 0, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-24 17:48:30', '2025-10-24 17:48:30'),
(89, 2, 'a', '', 1, 'regular', '2025-10-24 23:20:23', '2025-10-24 23:20:39', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 0, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-24 23:20:39', 4, '2025-10-24 17:50:23', '2025-10-24 17:50:39'),
(90, 2, 'jjnj', '', 2, 'regular', '2025-10-24 23:55:14', '2025-10-24 23:55:53', 80.00, 0.00, 'completed', '+05:30', NULL, 10, '2025-10-24 23:55:41', 60, 'cash', NULL, 0.00, 80.00, 'completed', '2025-10-24 23:55:53', 4, '2025-10-24 18:25:14', '2025-10-24 18:25:53');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `expiry_date` date DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `branch_id` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `name`, `category`, `cost_price`, `selling_price`, `stock_quantity`, `expiry_date`, `supplier`, `branch_id`, `created_at`, `updated_at`) VALUES
(1, 'Coffee', 'beverages', 10.00, 15.00, 20, '2025-10-31', 'qa', 1, '2025-10-22 15:53:14', '2025-10-22 15:53:14'),
(2, 'azlan', 'snacks', 10.00, 10.00, 10, '2025-10-21', 'ajjju', 1, '2025-10-22 18:08:18', '2025-10-22 18:08:18');

-- --------------------------------------------------------

--
-- Table structure for table `pricing`
--

CREATE TABLE `pricing` (
  `id` int(11) NOT NULL,
  `rate_type` enum('regular','vip') NOT NULL,
  `player_count` int(11) NOT NULL,
  `duration_15` decimal(10,2) DEFAULT NULL,
  `duration_30` decimal(10,2) DEFAULT NULL,
  `duration_45` decimal(10,2) DEFAULT NULL,
  `duration_60` decimal(10,2) DEFAULT NULL,
  `branch_id` int(11) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pricing`
--

INSERT INTO `pricing` (`id`, `rate_type`, `player_count`, `duration_15`, `duration_30`, `duration_45`, `duration_60`, `branch_id`, `updated_at`) VALUES
(1, 'regular', 1, 30.00, 52.00, 70.00, 90.00, 1, '2025-10-24 17:32:24'),
(2, 'regular', 2, 50.00, 80.00, 110.00, 140.00, 1, '2025-10-11 09:02:17'),
(3, 'regular', 3, 70.00, 110.00, 150.00, 190.00, 1, '2025-10-11 09:02:17'),
(4, 'regular', 4, 90.00, 140.00, 190.00, 240.00, 1, '2025-10-11 09:02:17'),
(5, 'vip', 1, 40.00, 70.00, 100.00, 130.00, 1, '2025-10-11 09:02:17'),
(6, 'vip', 2, 70.00, 110.00, 150.00, 190.00, 1, '2025-10-11 09:02:17'),
(7, 'vip', 3, 100.00, 150.00, 200.00, 250.00, 1, '2025-10-11 09:02:17'),
(8, 'vip', 4, 130.00, 190.00, 250.00, 310.00, 1, '2025-10-11 09:02:17');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `console_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `player_count` int(11) NOT NULL,
  `rate_type` enum('regular','vip') NOT NULL DEFAULT 'regular',
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `total_duration` int(11) DEFAULT 0 COMMENT 'in minutes',
  `pause_time` int(11) DEFAULT 0 COMMENT 'in minutes',
  `status` enum('Active','Paused','Completed') NOT NULL DEFAULT 'Active',
  `created_by` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `session_items`
--

CREATE TABLE `session_items` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `item_type` enum('food_drinks','gaming','other') NOT NULL DEFAULT 'food_drinks',
  `fandd_item_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `session_items`
--

INSERT INTO `session_items` (`id`, `session_id`, `item_name`, `quantity`, `unit_price`, `total_price`, `item_type`, `fandd_item_id`, `created_at`) VALUES
(1, 13, 'Coca Cola', 1, 2.50, 2.50, 'food_drinks', NULL, '2025-10-13 01:20:47'),
(2, 13, 'Coca Cola', 1, 2.50, 2.50, 'food_drinks', NULL, '2025-10-13 01:29:15'),
(3, 27, 'Coca Cola', 2, 2.50, 5.00, 'food_drinks', NULL, '2025-10-14 17:52:35'),
(4, 27, 'Coca Cola', 2, 2.50, 5.00, 'food_drinks', NULL, '2025-10-14 17:52:49'),
(5, 27, 'Coca Cola', 3, 2.50, 7.50, 'food_drinks', NULL, '2025-10-14 17:53:08'),
(7, 27, 'Coca Cola', 2, 2.50, 5.00, 'food_drinks', 1, '2025-10-14 17:58:27'),
(8, 33, 'Coca Cola', 2, 2.50, 5.00, 'food_drinks', 1, '2025-10-15 15:53:08'),
(9, 34, 'Coca Cola', 8, 2.50, 20.00, 'food_drinks', 1, '2025-10-15 15:55:20'),
(10, 37, 'Coca Cola', 4, 2.50, 10.00, 'food_drinks', 1, '2025-10-15 17:18:34'),
(11, 39, 'Tea', 5, 2.00, 10.00, 'food_drinks', 10, '2025-10-15 17:26:37'),
(12, 41, 'Coca Cola', 4, 2.50, 10.00, 'food_drinks', 1, '2025-10-15 17:30:14'),
(13, 41, 'Tea', 5, 2.00, 10.00, 'food_drinks', 10, '2025-10-15 17:30:14'),
(16, 42, 'Coca Cola', 4, 2.50, 10.00, 'food_drinks', 1, '2025-10-15 17:34:07'),
(17, 43, 'Coca Cola', 4, 2.50, 10.00, 'food_drinks', 1, '2025-10-15 17:40:10'),
(18, 46, 'Coca Cola', 10, 2.50, 25.00, 'food_drinks', 1, '2025-10-18 06:54:05'),
(19, 48, 'Coca Cola', 5, 2.50, 12.50, 'food_drinks', 1, '2025-10-18 07:49:48'),
(20, 49, 'Coca Cola', 2, 2.50, 5.00, 'food_drinks', 1, '2025-10-18 08:33:41'),
(26, 58, 'Energy Drink', 5, 3.99, 19.95, 'food_drinks', 7, '2025-10-20 16:10:32'),
(27, 64, 'Coca Cola', 3, 2.50, 7.50, 'food_drinks', 1, '2025-10-20 16:42:55'),
(28, 65, 'Tea', 10, 2.00, 20.00, 'food_drinks', 10, '2025-10-20 16:44:03'),
(29, 66, 'Coca Cola', 4, 2.50, 10.00, 'food_drinks', 1, '2025-10-20 16:49:27'),
(30, 67, 'Energy Drink', 7, 3.99, 27.93, 'food_drinks', 7, '2025-10-21 14:03:53'),
(31, 71, 'Energy Drink', 3, 3.99, 11.97, 'food_drinks', 7, '2025-10-21 15:30:01'),
(33, 80, 'Coffee', 2, 20.00, 40.00, 'food_drinks', 13, '2025-10-24 17:20:02'),
(35, 87, 'Coffee', 1, 20.00, 20.00, 'food_drinks', 13, '2025-10-24 18:21:30');

-- --------------------------------------------------------

--
-- Table structure for table `session_pauses`
--

CREATE TABLE `session_pauses` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `pause_start` datetime NOT NULL,
  `pause_end` datetime DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `duration` int(11) DEFAULT 0 COMMENT 'in minutes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `session_pauses`
--

INSERT INTO `session_pauses` (`id`, `session_id`, `pause_start`, `pause_end`, `reason`, `duration`) VALUES
(4, 67, '2025-10-21 19:51:36', '2025-10-21 19:51:44', 'Customer Break', 0),
(5, 69, '2025-10-21 18:53:27', '2025-10-21 19:54:24', 'Customer Break', 61),
(6, 68, '2025-10-21 20:05:40', '2025-10-21 20:35:30', 'Bathroom Break', 30),
(7, 68, '2025-10-21 20:44:34', '2025-10-21 20:45:40', 'Phone Call', 1),
(8, 68, '2025-10-21 20:51:39', '2025-10-21 20:51:54', 'chor unnog poyre', 0),
(9, 68, '2025-10-21 20:53:53', '2025-10-21 20:54:01', 'Technical Issue', 0),
(10, 71, '2025-10-21 21:16:06', '2025-10-21 21:17:40', 'Technical Issue', 2),
(11, 82, '2025-10-24 23:03:55', '2025-10-24 23:05:11', 'Technical Issue', 1),
(12, 81, '2025-10-24 23:05:57', '2025-10-24 23:06:00', 'Customer Break', 0),
(13, 83, '2025-10-24 23:07:58', '2025-10-24 23:08:04', 'Bathroom Break', 0),
(14, 87, '2025-10-24 23:51:40', NULL, 'Customer Break', 0),
(15, 90, '2025-10-24 23:55:31', '2025-10-24 23:55:41', 'Phone Call', 0);

-- --------------------------------------------------------

--
-- Table structure for table `session_segments`
--

CREATE TABLE `session_segments` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `player_count` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `duration` int(11) DEFAULT 0 COMMENT 'in minutes',
  `amount` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `session_segments`
--

INSERT INTO `session_segments` (`id`, `session_id`, `player_count`, `start_time`, `end_time`, `duration`, `amount`) VALUES
(1, 64, 1, '2025-10-20 22:06:04', '2025-10-20 22:12:17', 6, 30.00),
(2, 63, 1, '2025-10-20 22:05:27', NULL, 0, 0.00),
(3, 64, 4, '2025-10-20 22:12:17', '2025-10-20 22:12:46', 0, 90.00),
(4, 64, 3, '2025-10-20 22:12:46', '2025-10-20 22:13:35', 0, 70.00),
(5, 65, 1, '2025-10-20 22:13:48', '2025-10-20 22:14:13', 0, 30.00),
(6, 65, 3, '2025-10-20 22:14:13', '2025-10-20 22:15:16', 1, 70.00),
(7, 66, 1, '2025-10-20 22:16:41', '2025-10-20 22:19:14', 2, 30.00),
(8, 66, 2, '2025-10-20 22:19:14', '2025-10-20 22:19:51', 0, 50.00),
(9, 67, 2, '2025-10-20 22:30:51', '2025-10-21 19:30:38', 1259, 2940.00),
(10, 67, 3, '2025-10-21 19:30:38', '2025-10-21 19:32:24', 1, 70.00),
(11, 67, 2, '2025-10-21 19:32:24', '2025-10-21 19:52:16', 19, 80.00),
(12, 68, 1, '2025-10-21 19:35:50', '2025-10-21 20:48:17', 72, 180.00),
(13, 69, 1, '2025-10-21 19:52:32', '2025-10-21 19:57:05', 4, 30.00),
(14, 70, 1, '2025-10-21 19:57:36', '2025-10-21 20:01:38', 4, 30.00),
(15, 71, 1, '2025-10-21 20:01:49', '2025-10-21 21:06:16', 64, 180.00),
(16, 68, 2, '2025-10-21 20:48:17', '2025-10-21 20:59:21', 11, 50.00),
(17, 71, 2, '2025-10-21 21:06:16', '2025-10-21 21:18:42', 12, 50.00),
(18, 72, 1, '2025-10-21 21:19:10', '2025-10-21 21:25:11', 6, 30.00),
(19, 73, 1, '2025-10-21 21:30:40', '2025-10-22 20:52:00', 1401, 2160.00),
(20, 74, 1, '2025-10-22 21:04:45', '2025-10-22 21:04:52', 0, 30.00),
(21, 75, 1, '2025-10-22 21:07:58', '2025-10-22 21:08:03', 0, 30.00),
(22, 76, 1, '2025-10-22 21:47:24', '2025-10-22 23:53:21', 125, 270.00),
(23, 77, 1, '2025-10-24 22:38:18', '2025-10-24 22:43:30', 5, 35.00),
(24, 78, 1, '2025-10-24 22:38:43', '2025-10-24 22:38:58', 0, 35.00),
(25, 79, 1, '2025-10-24 22:43:13', '2025-10-24 22:43:48', 0, 35.00),
(26, 80, 1, '2025-10-24 22:44:15', '2025-10-24 22:44:24', 0, 35.00),
(27, 80, 2, '2025-10-24 22:44:24', '2025-10-24 22:44:31', 0, 50.00),
(28, 80, 3, '2025-10-24 22:44:31', '2025-10-24 22:45:20', 0, 70.00),
(29, 80, 4, '2025-10-24 22:45:20', '2025-10-24 23:02:55', 17, 140.00),
(30, 81, 1, '2025-10-24 22:52:48', '2025-10-24 23:06:12', 13, 30.00),
(31, 82, 1, '2025-10-24 23:03:46', '2025-10-24 23:05:19', 1, 30.00),
(32, 82, 2, '2025-10-24 23:05:19', '2025-10-24 23:05:31', 0, 50.00),
(33, 83, 1, '2025-10-24 23:07:34', '2025-10-24 23:08:15', 0, 30.00),
(34, 84, 1, '2025-10-24 23:07:41', '2025-10-24 23:13:07', 5, 30.00),
(35, 85, 1, '2025-10-24 23:07:47', '2025-10-24 23:16:59', 9, 30.00),
(36, 84, 2, '2025-10-24 23:13:07', '2025-10-24 23:13:13', 0, 50.00),
(37, 84, 3, '2025-10-24 23:13:13', '2025-10-24 23:13:20', 0, 70.00),
(38, 85, 2, '2025-10-24 23:16:59', '2025-10-24 23:17:07', 0, 50.00),
(39, 85, 3, '2025-10-24 23:17:07', '2025-10-24 23:17:24', 0, 70.00),
(40, 85, 4, '2025-10-24 23:17:24', '2025-10-24 23:17:38', 0, 90.00),
(41, 86, 1, '2025-10-24 23:18:19', '2025-10-24 23:18:43', 0, 30.00),
(42, 87, 1, '2025-10-24 23:18:25', NULL, 0, 0.00),
(43, 88, 1, '2025-10-24 23:18:30', NULL, 0, 0.00),
(44, 86, 2, '2025-10-24 23:18:43', '2025-10-24 23:18:58', 0, 50.00),
(45, 86, 4, '2025-10-24 23:18:58', '2025-10-24 23:21:41', 2, 90.00),
(46, 89, 1, '2025-10-24 23:20:23', '2025-10-24 23:20:39', 0, 30.00),
(47, 90, 1, '2025-10-24 23:55:14', '2025-10-24 23:55:23', 0, 30.00),
(48, 90, 2, '2025-10-24 23:55:23', '2025-10-24 23:55:53', 0, 50.00);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  `branch_id` int(11) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `branch_id`, `updated_at`) VALUES
(1, 'peak_hours', '18,19,20,21,22', 1, '2025-10-11 09:02:17'),
(2, 'peak_multiplier', '1.5', 1, '2025-10-11 14:58:36'),
(3, 'weekend_multiplier', '1.1', 1, '2025-10-11 09:02:17'),
(5, 'tax_rate', '0.00', 1, '2025-10-11 15:57:20');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_number` varchar(20) DEFAULT NULL,
  `player_count` int(11) NOT NULL DEFAULT 1,
  `rate_type` enum('regular','vip') NOT NULL DEFAULT 'regular',
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `total_duration_minutes` int(11) NOT NULL DEFAULT 0,
  `console_id` int(11) DEFAULT NULL,
  `console_name` varchar(255) DEFAULT NULL,
  `duration` int(11) DEFAULT 0 COMMENT 'in minutes',
  `gaming_amount` decimal(10,2) DEFAULT 0.00,
  `fandd_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `food_amount` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `final_amount` decimal(10,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` enum('pending','paid','refunded') NOT NULL DEFAULT 'pending',
  `payment_date` datetime DEFAULT NULL,
  `payment_details` text DEFAULT NULL,
  `coupon_code` varchar(50) DEFAULT NULL,
  `transaction_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `session_id`, `customer_name`, `customer_number`, `player_count`, `rate_type`, `start_time`, `end_time`, `total_duration_minutes`, `console_id`, `console_name`, `duration`, `gaming_amount`, `fandd_amount`, `subtotal`, `tax_amount`, `food_amount`, `total_amount`, `final_amount`, `discount_amount`, `payment_method`, `payment_status`, `payment_date`, `payment_details`, `coupon_code`, `transaction_date`, `created_by`, `branch_id`, `created_at`) VALUES
(3, 3, 'azlan', '6361557581', 1, '', '2025-10-11 18:37:30', '2025-10-11 18:38:17', 1, 2, NULL, 1, 30.00, 0.00, 30.00, 5.40, 0.00, 35.40, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-11 13:08:17'),
(4, 4, 'azlan', '6361557581', 1, 'regular', '2025-10-11 18:43:40', '0000-00-00 00:00:00', 13, 2, NULL, 13, 30.00, 0.00, 30.00, 5.40, 0.00, 35.40, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-11 13:26:46'),
(5, 5, 'ajju', '1234567890', 1, 'vip', '2025-10-11 18:57:07', '0000-00-00 00:00:00', 0, 2, NULL, 0, 40.00, 0.00, 40.00, 7.20, 0.00, 47.20, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-11 13:27:16'),
(6, 6, 'aju', '6361557581', 1, 'regular', '2025-10-11 19:05:56', '0000-00-00 00:00:00', 2, 2, NULL, 2, 30.00, 0.00, 30.00, 5.40, 0.00, 35.40, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-11 13:37:53'),
(7, 7, 'azlan', '6361557581', 3, 'regular', '2025-10-11 19:34:35', '0000-00-00 00:00:00', 60, 2, NULL, 60, 190.00, 0.00, 190.00, 34.20, 0.00, 224.20, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-11 15:04:39'),
(8, 12, 'ajlan', '6361557581', 2, 'regular', '2025-10-11 20:45:54', '0000-00-00 00:00:00', 3, 2, NULL, 3, 50.00, 0.00, 50.00, 9.00, 0.00, 59.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-11 15:19:04'),
(13, 13, 'azlan', '6361557581', 1, 'regular', '2025-10-13 06:44:41', '0000-00-00 00:00:00', 26, 2, NULL, 26, 50.00, 5.00, 55.00, 0.00, 0.00, 55.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:40:23'),
(14, 14, 'azlan', '6361557581', 1, 'regular', '2025-10-13 07:11:03', '0000-00-00 00:00:00', 0, 2, NULL, 0, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:41:16'),
(16, 16, 'azlan', '', 1, 'regular', '2025-10-13 07:15:59', '0000-00-00 00:00:00', 0, 2, NULL, 0, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:46:11'),
(18, 18, 'q', '', 1, 'regular', '2025-10-13 07:17:44', '0000-00-00 00:00:00', 1, 2, NULL, 1, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', '', '2025-10-13 07:19:14', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:49:08'),
(20, 20, 'azlan', '', 1, 'regular', '2025-10-13 07:22:12', '0000-00-00 00:00:00', 2, 2, NULL, 2, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', '', '2025-10-13 07:23:52', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:53:43'),
(21, 21, 'azlan', '', 1, 'regular', '2025-10-13 07:25:17', '0000-00-00 00:00:00', 1, 2, NULL, 1, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', '', '2025-10-13 07:26:45', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:56:40'),
(23, 23, 'azlan', '', 1, 'regular', '2025-10-13 07:27:05', '0000-00-00 00:00:00', 0, 2, NULL, 0, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:57:29'),
(24, 24, 'azlan', '', 1, 'regular', '2025-10-13 07:28:51', '0000-00-00 00:00:00', 0, 2, NULL, 0, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 15.00, 15.00, 'upi', '', '2025-10-13 07:29:32', NULL, '123', '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:58:58'),
(25, 25, 'rifaz', '098765431', 1, 'regular', '2025-10-14 22:49:19', '0000-00-00 00:00:00', 1, 2, NULL, 1, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', '', '2025-10-14 22:50:58', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-14 17:20:47'),
(26, 26, 'thameem', '09876', 1, 'regular', '2025-10-14 21:45:54', '0000-00-00 00:00:00', 80, 2, NULL, 80, 180.00, 0.00, 180.00, 0.00, 0.00, 180.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-14 17:36:08'),
(27, 27, 'ajju', '12', 1, 'regular', '2025-10-14 23:04:28', '0000-00-00 00:00:00', 40, 2, NULL, 40, 70.00, 22.50, 92.50, 0.00, 0.00, 92.50, 66.25, 26.25, 'cash', '', '2025-10-14 23:45:10', NULL, '1234', '0000-00-00 00:00:00', 1, 1, '2025-10-14 18:14:50'),
(28, 29, 'test', 'azk', 1, 'regular', '2025-10-14 23:47:44', '0000-00-00 00:00:00', 0, 2, NULL, 0, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-14 18:17:48'),
(29, 30, 'hb', '', 1, 'regular', '2025-10-14 23:51:38', '0000-00-00 00:00:00', 0, 2, NULL, 0, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash_upi', '', '2025-10-14 23:51:53', '{\"cash_amount\":15,\"upi_amount\":15,\"total_amount\":30}', NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-14 18:21:46'),
(30, 31, 'a', '', 1, 'regular', '2025-10-14 23:56:05', '2025-10-15 00:16:21', 20, 2, NULL, 20, 50.00, 0.00, 50.00, 0.00, 0.00, 50.00, 50.00, 0.00, 'cash_upi', '', '2025-10-15 00:16:37', NULL, '1234', '0000-00-00 00:00:00', 1, 1, '2025-10-14 18:46:21'),
(32, 33, 'azlan', '', 1, 'regular', '2025-10-15 21:22:57', '2025-10-15 21:23:18', 0, 2, NULL, 0, 30.00, 5.00, 35.00, 0.00, 0.00, 35.00, 0.00, 0.00, 'pending', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-15 15:53:18'),
(33, 34, 'azlan', '', 1, 'regular', '2025-10-15 20:24:22', '2025-10-15 21:25:24', 61, 2, NULL, 61, 180.00, 20.00, 200.00, 0.00, 0.00, 200.00, 0.00, 0.00, 'pending', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-15 15:55:24'),
(34, 35, 'ajju', '', 1, 'regular', '2025-10-15 20:45:24', '2025-10-15 22:47:11', 122, 2, NULL, 122, 270.00, 0.00, 270.00, 0.00, 0.00, 270.00, 0.00, 0.00, 'pending', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-15 17:17:11'),
(35, 36, 'ajjuu', '', 1, 'regular', '2025-10-15 20:47:48', '2025-10-15 22:48:07', 120, 2, NULL, 120, 180.00, 0.00, 180.00, 0.00, 0.00, 180.00, 90.00, 90.00, 'cash', '', '2025-10-15 22:48:18', NULL, '123', '0000-00-00 00:00:00', 1, 1, '2025-10-15 17:18:07'),
(36, 37, 'azlan', '', 1, 'regular', '2025-10-15 20:48:27', '2025-10-15 22:48:51', 120, 2, NULL, 120, 180.00, 10.00, 190.00, 0.00, 0.00, 190.00, 0.00, 0.00, 'pending', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-15 17:18:51'),
(37, 38, 'q', '', 1, 'regular', '2025-10-15 20:55:23', '2025-10-15 22:55:53', 121, 2, NULL, 121, 270.00, 0.00, 270.00, 0.00, 0.00, 270.00, 270.00, 0.00, 'cash', '', '2025-10-15 22:56:04', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-15 17:25:53'),
(38, 39, 'azlan', '', 1, 'regular', '2025-10-15 20:56:14', '2025-10-15 22:56:40', 120, 2, NULL, 120, 180.00, 10.00, 190.00, 0.00, 0.00, 190.00, 100.00, 90.00, 'cash', '', '2025-10-15 22:57:34', NULL, '123', '0000-00-00 00:00:00', 1, 1, '2025-10-15 17:26:40'),
(39, 40, 'az', '', 1, 'regular', '2025-10-15 21:59:20', '2025-10-15 22:59:40', 60, 2, NULL, 60, 90.00, 0.00, 90.00, 0.00, 0.00, 90.00, 0.00, 0.00, 'pending', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-15 17:29:40'),
(40, 41, 'az', '', 1, 'regular', '2025-10-15 21:59:50', '2025-10-15 23:00:22', 61, 2, NULL, 61, 180.00, 20.00, 200.00, 0.00, 0.00, 200.00, 0.00, 0.00, 'pending', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-15 17:30:22'),
(41, 42, 'azlan', '', 1, 'regular', '2025-10-15 21:03:30', '2025-10-15 23:04:31', 121, 2, NULL, 121, 270.00, 10.00, 280.00, 0.00, 0.00, 280.00, 145.00, 135.00, 'cash_upi', '', '2025-10-15 23:04:55', '{\"cash_amount\":120,\"upi_amount\":25}', '123', '0000-00-00 00:00:00', 1, 1, '2025-10-15 17:34:31'),
(42, 43, 'g', '', 1, 'regular', '2025-10-15 21:09:43', '2025-10-15 23:10:16', 121, 2, NULL, 121, 270.00, 10.00, 280.00, 0.00, 0.00, 145.00, 145.00, 135.00, 'cash_upi', '', '2025-10-15 23:10:33', '{\"cash_amount\":100,\"upi_amount\":45}', '123', '0000-00-00 00:00:00', 1, 1, '2025-10-15 17:40:16'),
(43, 47, 'azlan', '', 1, 'regular', '2025-10-18 13:00:21', '2025-10-18 13:00:25', 0, 2, NULL, 0, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', '', '2025-10-18 13:00:28', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-18 07:30:25'),
(44, 48, 'ajju', '', 1, 'regular', '2025-10-18 13:19:40', '2025-10-18 13:19:55', 0, 2, NULL, 0, 30.00, 12.50, 42.50, 0.00, 0.00, 27.50, 27.50, 15.00, 'cash', '', '2025-10-18 13:20:10', NULL, '123', '0000-00-00 00:00:00', 1, 1, '2025-10-18 07:49:55'),
(45, 49, 'azlan', '', 1, 'regular', '2025-10-18 13:54:45', '2025-10-18 22:21:56', 507, 2, NULL, 507, 810.00, 5.00, 815.00, 0.00, 0.00, 815.00, 815.00, 0.00, 'cash', '', '2025-10-18 22:22:00', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-18 16:51:56'),
(46, 50, 'azlan', '', 1, 'regular', '2025-10-18 22:22:16', '2025-10-18 22:22:27', 0, 2, NULL, 0, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', '', '2025-10-18 22:22:55', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-18 16:52:27'),
(47, 51, 'a', '', 1, 'regular', '2025-10-18 22:23:07', '2025-10-18 22:23:13', 0, 2, NULL, 0, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', '', '2025-10-20 21:33:49', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-18 16:53:13'),
(51, 55, 'ajlan', '', 1, 'regular', '2025-10-20 21:33:32', '2025-10-20 21:33:37', 0, 2, NULL, 0, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-20 16:03:37'),
(53, 57, 'aa', '', 1, 'regular', '2025-10-20 21:36:16', '2025-10-20 21:39:56', 4, 2, NULL, 4, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cash', '', '2025-10-20 21:39:56', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-20 16:09:56'),
(54, 58, 'az', '', 1, 'regular', '2025-10-20 21:40:07', '2025-10-20 21:43:51', 4, 2, NULL, 4, 30.00, 19.95, 49.95, 0.00, 0.00, 49.95, 34.00, 15.00, 'cash_upi', '', '2025-10-20 21:43:51', '{\"cash_amount\":20,\"upi_amount\":14.95}', '123', '0000-00-00 00:00:00', 1, 1, '2025-10-20 16:13:51'),
(55, 59, 'azlan', '', 1, 'regular', '2025-10-20 21:51:26', '2025-10-20 21:59:50', 8, 5, NULL, 8, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cash', '', '2025-10-20 21:59:50', NULL, '123', '0000-00-00 00:00:00', 1, 1, '2025-10-20 16:29:50'),
(56, 60, 'hj', '', 1, 'regular', '2025-10-20 22:00:05', '2025-10-20 22:02:07', 2, 5, NULL, 2, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cash', '', '2025-10-20 22:02:07', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-20 16:32:07'),
(57, 61, 'hj', '', 1, 'regular', '2025-10-20 22:00:11', '2025-10-20 22:05:15', 5, 5, NULL, 5, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cash', '', '2025-10-20 22:05:15', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-20 16:35:15'),
(58, 63, 'azlan', '', 1, 'regular', '2025-10-20 22:05:27', '2025-10-20 22:11:20', 6, 5, NULL, 6, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cash', '', '2025-10-20 22:11:20', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-20 16:41:20'),
(59, 64, 'azlan', '', 3, 'regular', '2025-10-20 22:06:04', '2025-10-20 22:13:35', 8, 5, NULL, 8, 190.00, 7.50, 197.50, 0.00, 0.00, 197.50, 102.00, 95.00, 'cash', '', '2025-10-20 22:13:35', NULL, '123', '0000-00-00 00:00:00', 1, 1, '2025-10-20 16:43:35'),
(60, 65, 'thasleem', '', 3, 'regular', '2025-10-20 22:13:48', '2025-10-20 22:15:16', 1, 5, NULL, 1, 100.00, 20.00, 120.00, 0.00, 0.00, 120.00, 70.00, 50.00, 'cash_upi', '', '2025-10-20 22:15:16', '{\"cash_amount\":20,\"upi_amount\":50}', '123', '0000-00-00 00:00:00', 1, 1, '2025-10-20 16:45:16'),
(61, 66, 'thacchi', '', 2, 'regular', '2025-10-20 22:16:41', '2025-10-20 22:19:51', 3, 5, NULL, 3, 80.00, 10.00, 90.00, 0.00, 0.00, 90.00, 50.00, 40.00, 'cash', '', '2025-10-20 22:19:51', NULL, '123', '0000-00-00 00:00:00', 1, 1, '2025-10-20 16:49:51'),
(62, 62, 'ahaj', '', 1, 'regular', '2025-10-20 22:00:25', '2025-10-20 22:32:24', 32, 2, NULL, 32, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cash', '', '2025-10-20 22:32:24', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-20 17:02:24'),
(63, 67, 'ajju', '', 2, 'regular', '2025-10-20 22:30:51', '2025-10-21 19:52:16', 1272, 5, NULL, 1272, 3090.00, 27.93, 3117.93, 0.00, 0.00, 3117.93, 3117.00, 0.00, 'cash', '', '2025-10-21 19:52:16', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-21 14:22:16'),
(64, 69, 'azlan', '', 1, 'regular', '2025-10-21 17:52:32', '2025-10-21 19:57:05', 64, 5, NULL, 64, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', '', '2025-10-21 19:57:05', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-21 14:27:05'),
(65, 70, 'azlan', '', 1, 'regular', '2025-10-21 14:57:36', '2025-10-21 20:01:38', 304, 5, NULL, 304, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', '', '2025-10-21 20:01:38', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-21 14:31:38'),
(66, 68, 'thameem', '', 2, 'regular', '2025-10-21 19:35:50', '2025-10-21 20:59:21', 53, 2, NULL, 53, 120.00, 0.00, 120.00, 0.00, 0.00, 120.00, 96.00, 23.08, 'cash', '', '2025-10-21 20:59:21', NULL, '1234', '0000-00-00 00:00:00', 1, 1, '2025-10-21 15:29:21'),
(67, 71, 'thasleem', '', 2, 'regular', '2025-10-21 20:01:49', '2025-10-21 21:18:42', 75, 5, NULL, 75, 230.00, 11.97, 241.97, 0.00, 0.00, 241.97, 126.00, 115.00, 'cash', '', '2025-10-21 21:18:42', NULL, '123', '0000-00-00 00:00:00', 1, 1, '2025-10-21 15:48:42'),
(68, 72, 'azlan', '', 1, 'regular', '2025-10-21 21:19:10', '2025-10-21 21:25:11', 6, 5, NULL, 6, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 15.00, 15.00, 'cash', '', '2025-10-21 21:25:11', NULL, '123', '0000-00-00 00:00:00', 1, 1, '2025-10-21 15:55:11'),
(69, 73, 'sq', '', 1, 'regular', '2025-10-21 21:30:40', '2025-10-22 20:52:00', 1401, 5, NULL, 1401, 2160.00, 0.00, 2160.00, 0.00, 0.00, 2160.00, 2160.00, 0.00, 'cash', '', '2025-10-22 20:52:00', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-22 15:22:00'),
(70, 74, 'ajju', '', 1, 'regular', '2025-10-22 21:04:45', '2025-10-22 21:04:52', 0, 5, NULL, 0, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', '', '2025-10-22 21:04:52', NULL, NULL, '0000-00-00 00:00:00', 1, 3, '2025-10-22 15:34:52'),
(71, 75, 'ajju', '', 1, 'regular', '2025-10-22 21:07:58', '2025-10-22 21:08:03', 0, 5, NULL, 0, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', '', '2025-10-22 21:08:03', NULL, NULL, '0000-00-00 00:00:00', 1, 2, '2025-10-22 15:38:03'),
(72, 76, 'azlanajju', '', 1, 'regular', '2025-10-22 21:47:24', '2025-10-22 23:53:21', 126, 5, NULL, 126, 270.00, 0.00, 270.00, 0.00, 0.00, 270.00, 243.00, 27.00, 'cash', '', '2025-10-22 23:53:21', NULL, '1122', '0000-00-00 00:00:00', 3, 2, '2025-10-22 18:23:21'),
(73, 78, 'ad', '', 1, 'regular', '2025-10-24 22:38:43', '2025-10-24 22:38:58', 0, 2, NULL, 0, 35.00, 0.00, 35.00, 0.00, 0.00, 35.00, 35.00, 0.00, 'cash', '', '2025-10-24 22:38:58', NULL, '1234', '0000-00-00 00:00:00', 4, 1, '2025-10-24 17:08:58'),
(74, 77, 'a', '', 1, 'regular', '2025-10-24 22:38:18', '2025-10-24 22:43:30', 5, 6, NULL, 5, 35.00, 0.00, 35.00, 0.00, 0.00, 35.00, 35.00, 0.00, 'cash', '', '2025-10-24 22:43:30', NULL, NULL, '0000-00-00 00:00:00', 1, 2, '2025-10-24 17:13:30'),
(75, 79, 'a', '', 1, 'regular', '2025-10-24 22:43:13', '2025-10-24 22:43:48', 1, 2, NULL, 1, 35.00, 0.00, 35.00, 0.00, 0.00, 35.00, 35.00, 0.00, 'cash', '', '2025-10-24 22:43:48', NULL, NULL, '0000-00-00 00:00:00', 4, 1, '2025-10-24 17:13:48'),
(76, 80, 'e', '', 4, 'regular', '2025-10-24 22:44:15', '2025-10-24 23:02:55', 19, 6, NULL, 19, 245.00, 40.00, 285.00, 0.00, 0.00, 285.00, 285.00, 0.00, 'cash', '', '2025-10-24 23:02:55', NULL, NULL, '0000-00-00 00:00:00', 3, 2, '2025-10-24 17:32:55'),
(77, 82, 'ajju', '', 2, 'regular', '2025-10-24 23:03:46', '2025-10-24 23:05:31', 1, 7, NULL, 1, 80.00, 0.00, 80.00, 0.00, 0.00, 80.00, 80.00, 0.00, 'cash', '', '2025-10-24 23:05:31', NULL, NULL, '0000-00-00 00:00:00', 1, 2, '2025-10-24 17:35:31'),
(78, 81, '1', '', 1, 'regular', '2025-10-24 22:52:48', '2025-10-24 23:06:12', 13, 5, NULL, 13, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', '', '2025-10-24 23:06:12', NULL, NULL, '0000-00-00 00:00:00', 1, 2, '2025-10-24 17:36:12'),
(79, 83, 'knk', '', 1, 'regular', '2025-10-24 23:07:34', '2025-10-24 23:08:15', 1, 7, NULL, 1, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', '', '2025-10-24 23:08:15', NULL, NULL, '0000-00-00 00:00:00', 1, 2, '2025-10-24 17:38:15'),
(80, 84, 'j', '', 3, 'regular', '2025-10-24 23:07:41', '2025-10-24 23:13:20', 6, 6, NULL, 6, 150.00, 0.00, 150.00, 0.00, 0.00, 150.00, 150.00, 0.00, 'cash', '', '2025-10-24 23:13:20', NULL, NULL, '0000-00-00 00:00:00', 1, 2, '2025-10-24 17:43:20'),
(81, 85, 'kj', '', 4, 'regular', '2025-10-24 23:07:47', '2025-10-24 23:17:38', 10, 5, NULL, 10, 240.00, 0.00, 240.00, 0.00, 0.00, 240.00, 240.00, 0.00, 'cash', '', '2025-10-24 23:17:38', NULL, NULL, '0000-00-00 00:00:00', 1, 2, '2025-10-24 17:47:38'),
(82, 89, 'a', '', 1, 'regular', '2025-10-24 23:20:23', '2025-10-24 23:20:39', 0, 2, NULL, 0, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', '', '2025-10-24 23:20:39', NULL, NULL, '0000-00-00 00:00:00', 4, 1, '2025-10-24 17:50:39'),
(83, 86, 'ds', '', 4, 'regular', '2025-10-24 23:18:19', '2025-10-24 23:21:41', 3, 7, NULL, 3, 170.00, 0.00, 170.00, 0.00, 0.00, 170.00, 170.00, 0.00, 'cash', '', '2025-10-24 23:21:41', NULL, NULL, '0000-00-00 00:00:00', 3, 2, '2025-10-24 17:51:41'),
(84, 90, 'jjnj', '', 2, 'regular', '2025-10-24 23:55:14', '2025-10-24 23:55:53', 1, 2, NULL, 1, 80.00, 0.00, 80.00, 0.00, 0.00, 80.00, 80.00, 0.00, 'cash', '', '2025-10-24 23:55:53', NULL, NULL, '0000-00-00 00:00:00', 4, 1, '2025-10-24 18:25:53');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_items`
--

CREATE TABLE `transaction_items` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `item_type` enum('food','gaming') NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Manager','Staff') NOT NULL DEFAULT 'Staff',
  `branch_id` int(11) DEFAULT 1,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `email`, `phone`, `password`, `role`, `branch_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'System Administrator', 'admin', 'admin@gamebot.com', '1234567890', '$2y$10$.O3ZrRfBqTyzZVxqOmw8Ke0.ChN22Z.HK.RMjWLG6ZJPDiyjSAhbq', 'Admin', 1, 'Active', '2025-10-11 09:02:17', '2025-10-22 15:24:27'),
(2, 'staff', 'staff', 'staff@gmail.com', '0987654', '$2y$10$zvTuKoLj3YLzmTljiG16IuF6XQMVHWmrI.26KmXqOyfftqfG/g8VW', 'Staff', 1, 'Active', '2025-10-14 17:40:09', '2025-10-14 17:40:18'),
(3, 'manager', 'manager', 'manager@gmail.com', '6361557581', '$2y$10$46irQUAswHQziqb5sLUG4.nSGGBq4H4l3hj4Y.Az2hGlqpGVyPax.', 'Manager', 2, 'Active', '2025-10-22 15:11:37', '2025-10-22 18:15:35'),
(4, 'muhammad azlan', 'manager2', 'muhammedazlan@gmail.com', '06361557581', '$2y$10$zTWndoXYdRUb/02MwXfnz.Hu/bjLCSj9KYy5T1o.30A6tOuLPwAMO', 'Manager', 1, 'Active', '2025-10-24 16:59:56', '2025-10-24 16:59:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_manager` (`manager_id`);

--
-- Indexes for table `consoles`
--
ALTER TABLE `consoles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `food_and_drinks`
--
ALTER TABLE `food_and_drinks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`),
  ADD KEY `is_available` (`is_available`),
  ADD KEY `fk_branch` (`branch_id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `game_console_assignments`
--
ALTER TABLE `game_console_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`),
  ADD KEY `console_id` (`console_id`);

--
-- Indexes for table `gaming_sessions`
--
ALTER TABLE `gaming_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `console_id` (`console_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `start_time` (`start_time`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pricing`
--
ALTER TABLE `pricing`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_pricing` (`rate_type`,`player_count`,`branch_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `console_id` (`console_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `session_items`
--
ALTER TABLE `session_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `fandd_item_id` (`fandd_item_id`);

--
-- Indexes for table `session_pauses`
--
ALTER TABLE `session_pauses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `session_segments`
--
ALTER TABLE `session_segments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_setting` (`setting_key`,`branch_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `console_id` (`console_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `transaction_date` (`transaction_date`);

--
-- Indexes for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=386;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `consoles`
--
ALTER TABLE `consoles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `food_and_drinks`
--
ALTER TABLE `food_and_drinks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `game_console_assignments`
--
ALTER TABLE `game_console_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `gaming_sessions`
--
ALTER TABLE `gaming_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pricing`
--
ALTER TABLE `pricing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `session_items`
--
ALTER TABLE `session_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `session_pauses`
--
ALTER TABLE `session_pauses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `session_segments`
--
ALTER TABLE `session_segments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `fk_manager` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `food_and_drinks`
--
ALTER TABLE `food_and_drinks`
  ADD CONSTRAINT `fk_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `game_console_assignments`
--
ALTER TABLE `game_console_assignments`
  ADD CONSTRAINT `fk_game_console_console` FOREIGN KEY (`console_id`) REFERENCES `consoles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_game_console_game` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gaming_sessions`
--
ALTER TABLE `gaming_sessions`
  ADD CONSTRAINT `fk_gaming_session_console` FOREIGN KEY (`console_id`) REFERENCES `consoles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `fk_session_console` FOREIGN KEY (`console_id`) REFERENCES `consoles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_session_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `session_items`
--
ALTER TABLE `session_items`
  ADD CONSTRAINT `fk_session_item_session` FOREIGN KEY (`session_id`) REFERENCES `gaming_sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `session_pauses`
--
ALTER TABLE `session_pauses`
  ADD CONSTRAINT `fk_pause_session` FOREIGN KEY (`session_id`) REFERENCES `gaming_sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `session_segments`
--
ALTER TABLE `session_segments`
  ADD CONSTRAINT `fk_segment_session` FOREIGN KEY (`session_id`) REFERENCES `gaming_sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transaction_console` FOREIGN KEY (`console_id`) REFERENCES `consoles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_transaction_gaming_session` FOREIGN KEY (`session_id`) REFERENCES `gaming_sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_transaction_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `fk_item_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
