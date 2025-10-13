-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2025 at 04:42 AM
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
(73, 1, 'session_management', 'Payment processed for session 24. Method: upi, Amount: ₹15.00', NULL, 1, '2025-10-13 01:59:32');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `location`, `address`, `contact`, `manager_name`, `timing`, `console_count`, `established_year`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Main Branch', 'Downtown', '123 Gaming Street, City Center', '+1234567890', 'John Manager', '10:00 AM - 12:00 AM', 10, 2023, 'Active', '2025-10-11 09:02:17', '2025-10-11 09:02:17');

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
(1, 'test', 'PC', 'RTX 4060, i5-12600K, 16GB RAM', 2025, 'hello@gmail.com', 'azlan', '0', 1, 0, 'Available', 1, '2025-10-11 09:24:34', '2025-10-13 01:57:08'),
(2, 'xfcgfq', 'PC', 'RTX 4060, i5-12600K, 16GB RAM', 2025, 'muhammedazlan11@gmail.com', 'azlan', '0', 1, 0, 'Available', 1, '2025-10-11 11:41:01', '2025-10-13 01:58:58'),
(3, 'test console', 'PC', 'gtx 3050', 2025, 'azlan@gmail.com', 'azlan', '0', 0, 1, 'Available', 1, '2025-10-13 01:19:36', '2025-10-13 01:22:26');

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
(1, 'test', '123', 'couper', 'percentage', 50.00, NULL, NULL, 0, 0, 0, 2, '2025-10-13 07:29:32', 0.00, '2025-10-13', '2025-10-14', 1, 'Active', '2025-10-13 01:53:34', '2025-10-13 01:59:32');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `food_and_drinks`
--

INSERT INTO `food_and_drinks` (`id`, `name`, `price`, `stock`, `category`, `description`, `is_available`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 'Coca Cola', 2.50, 60, 'beverages', 'Refreshing carbonated soft drink', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36'),
(2, 'Pizza Slice', 8.99, 15, 'meals', 'Delicious cheese pizza slice', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36'),
(3, 'Coffee', 3.50, 25, 'beverages', 'Freshly brewed coffee', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36'),
(4, 'Sandwich', 6.99, 20, 'meals', 'Fresh sandwich with your choice of filling', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36'),
(5, 'French Fries', 4.50, 30, 'snacks', 'Crispy golden french fries', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36'),
(6, 'Burger', 12.99, 18, 'meals', 'Juicy beef burger with fries', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36'),
(7, 'Energy Drink', 3.99, 40, 'beverages', 'High-energy drink for gamers', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36'),
(8, 'Chocolate Bar', 2.99, 50, 'desserts', 'Rich chocolate bar', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36'),
(9, 'Nachos', 5.99, 25, 'snacks', 'Loaded nachos with cheese', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36'),
(10, 'Tea', 2.00, 35, 'beverages', 'Hot or iced tea', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36'),
(11, 'Ice Cream', 4.99, 20, 'desserts', 'Creamy vanilla ice cream', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36'),
(12, 'Hot Dog', 7.50, 22, 'meals', 'Classic hot dog with condiments', 1, NULL, '2025-10-11 12:16:36', '2025-10-11 12:16:36');

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
(1, 'qwer', 'FPS', 'qwr', 5.0, '0000-00-00', 1, '2025-10-11 09:34:26', '2025-10-11 09:34:26');

-- --------------------------------------------------------

--
-- Table structure for table `game_console_assignments`
--

CREATE TABLE `game_console_assignments` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `console_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(2, 1, 'shan', '123', 1, 'regular', '2025-10-11 17:39:08', '2025-10-11 18:34:53', 106.20, 0.00, 'completed', '+05:30', NULL, 0, NULL, 3345, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-11 12:09:08', '2025-10-11 13:04:53'),
(3, 2, 'azlan', '6361557581', 1, 'regular', '2025-10-11 18:37:30', '2025-10-11 18:38:17', 35.40, 0.00, 'completed', '+05:30', NULL, 0, NULL, 47, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-11 13:07:30', '2025-10-11 13:08:17'),
(4, 2, 'azlan', '6361557581', 1, 'regular', '2025-10-11 18:43:40', '2025-10-11 18:56:46', 35.40, 0.00, 'completed', '+05:30', NULL, 0, NULL, 786, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-11 13:13:40', '2025-10-11 13:26:46'),
(5, 2, 'ajju', '1234567890', 1, 'vip', '2025-10-11 18:57:07', '2025-10-11 18:57:16', 47.20, 0.00, 'completed', '+05:30', NULL, 0, NULL, 9, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-11 13:27:07', '2025-10-11 13:27:16'),
(6, 2, 'aju', '6361557581', 1, 'regular', '2025-10-11 19:05:56', '2025-10-11 19:07:53', 35.40, 0.00, 'completed', '+05:30', NULL, 0, NULL, 117, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-11 13:35:56', '2025-10-11 13:37:53'),
(7, 2, 'azlan', '6361557581', 3, 'regular', '2025-10-11 19:34:35', '2025-10-11 20:34:39', 224.20, 0.00, 'completed', '+05:30', NULL, 0, NULL, 3604, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-11 14:04:35', '2025-10-11 15:04:39'),
(8, 1, 'azlan', '6361557581', 2, 'regular', '2025-10-11 19:34:47', '2025-10-11 21:28:30', 280.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 6823, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-11 14:04:47', '2025-10-11 15:58:30'),
(9, 1, 'Test Customer', '1234567890', 1, 'regular', '2025-10-11 20:42:05', '2025-10-12 22:48:37', 2430.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 93992, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-11 15:12:05', '2025-10-12 17:18:37'),
(10, 1, 'Test Customer', '1234567890', 1, 'regular', '2025-10-11 20:42:16', '2025-10-12 22:53:26', 2430.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 94270, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-11 15:12:16', '2025-10-12 17:23:26'),
(11, 1, 'Test Customer', '1234567890', 1, 'regular', '2025-10-11 20:42:26', '2025-10-13 06:40:24', 3060.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 122278, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-11 15:12:26', '2025-10-13 01:10:24'),
(12, 2, 'ajlan', '6361557581', 2, 'regular', '2025-10-11 20:45:54', '2025-10-11 20:49:04', 59.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 190, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-11 15:15:54', '2025-10-11 15:19:04'),
(13, 2, 'azlan', '6361557581', 1, 'regular', '2025-10-13 06:44:41', '2025-10-13 07:10:23', 55.00, 5.00, 'completed', '+05:30', NULL, 0, NULL, 1542, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-13 01:14:41', '2025-10-13 01:40:23'),
(14, 2, 'azlan', '6361557581', 1, 'regular', '2025-10-13 07:11:03', '2025-10-13 07:11:16', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 13, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-13 01:41:03', '2025-10-13 01:41:16'),
(15, 1, 'ajju', '6361557581', 1, 'regular', '2025-10-13 07:11:13', '2025-10-13 07:14:13', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 180, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-13 01:41:13', '2025-10-13 01:44:13'),
(16, 2, 'azlan', '', 1, 'regular', '2025-10-13 07:15:59', '2025-10-13 07:16:11', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 12, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-13 01:45:59', '2025-10-13 01:46:11'),
(17, 1, 'azlan', '', 1, 'regular', '2025-10-13 07:16:07', '2025-10-13 07:17:48', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 101, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-13 01:46:07', '2025-10-13 01:47:48'),
(18, 2, 'q', '', 1, 'regular', '2025-10-13 07:17:44', '2025-10-13 07:19:08', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 84, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-13 07:19:14', 1, '2025-10-13 01:47:44', '2025-10-13 01:49:14'),
(19, 1, 'azlan', '', 1, 'regular', '2025-10-13 07:18:40', '2025-10-13 07:22:25', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 225, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-13 07:22:32', 1, '2025-10-13 01:48:40', '2025-10-13 01:52:32'),
(20, 2, 'azlan', '', 1, 'regular', '2025-10-13 07:22:12', '2025-10-13 07:23:43', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 91, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-13 07:23:52', 1, '2025-10-13 01:52:12', '2025-10-13 01:53:52'),
(21, 2, 'azlan', '', 1, 'regular', '2025-10-13 07:25:17', '2025-10-13 07:26:40', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 83, 'cash', NULL, 0.00, 30.00, 'completed', '2025-10-13 07:26:45', 1, '2025-10-13 01:55:17', '2025-10-13 01:56:45'),
(22, 1, 'ajju', '', 1, 'regular', '2025-10-13 07:25:53', '2025-10-13 07:27:08', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 75, 'cash', '123', 15.00, 15.00, 'completed', '2025-10-13 07:27:22', 1, '2025-10-13 01:55:53', '2025-10-13 01:57:22'),
(23, 2, 'azlan', '', 1, 'regular', '2025-10-13 07:27:05', '2025-10-13 07:27:29', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 24, NULL, NULL, 0.00, 0.00, 'pending', NULL, 1, '2025-10-13 01:57:05', '2025-10-13 01:57:29'),
(24, 2, 'azlan', '', 1, 'regular', '2025-10-13 07:28:51', '2025-10-13 07:28:58', 30.00, 0.00, 'completed', '+05:30', NULL, 0, NULL, 7, 'upi', '123', 15.00, 15.00, 'completed', '2025-10-13 07:29:32', 1, '2025-10-13 01:58:51', '2025-10-13 01:59:32');

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
(1, 'regular', 1, 30.00, 50.00, 70.00, 90.00, 1, '2025-10-11 09:02:17'),
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `session_items`
--

INSERT INTO `session_items` (`id`, `session_id`, `item_name`, `quantity`, `unit_price`, `total_price`, `item_type`, `created_at`) VALUES
(1, 13, 'Coca Cola', 1, 2.50, 2.50, 'food_drinks', '2025-10-13 01:20:47'),
(2, 13, 'Coca Cola', 1, 2.50, 2.50, 'food_drinks', '2025-10-13 01:29:15');

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
(2, 2, 'shan', '123', 1, '', '2025-10-11 17:39:08', '2025-10-11 18:34:53', 56, 1, NULL, 56, 90.00, 0.00, 90.00, 16.20, 0.00, 106.20, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-11 13:04:53'),
(3, 3, 'azlan', '6361557581', 1, '', '2025-10-11 18:37:30', '2025-10-11 18:38:17', 1, 2, NULL, 1, 30.00, 0.00, 30.00, 5.40, 0.00, 35.40, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-11 13:08:17'),
(4, 4, 'azlan', '6361557581', 1, 'regular', '2025-10-11 18:43:40', '0000-00-00 00:00:00', 13, 2, NULL, 13, 30.00, 0.00, 30.00, 5.40, 0.00, 35.40, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-11 13:26:46'),
(5, 5, 'ajju', '1234567890', 1, 'vip', '2025-10-11 18:57:07', '0000-00-00 00:00:00', 0, 2, NULL, 0, 40.00, 0.00, 40.00, 7.20, 0.00, 47.20, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-11 13:27:16'),
(6, 6, 'aju', '6361557581', 1, 'regular', '2025-10-11 19:05:56', '0000-00-00 00:00:00', 2, 2, NULL, 2, 30.00, 0.00, 30.00, 5.40, 0.00, 35.40, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-11 13:37:53'),
(7, 7, 'azlan', '6361557581', 3, 'regular', '2025-10-11 19:34:35', '0000-00-00 00:00:00', 60, 2, NULL, 60, 190.00, 0.00, 190.00, 34.20, 0.00, 224.20, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-11 15:04:39'),
(8, 12, 'ajlan', '6361557581', 2, 'regular', '2025-10-11 20:45:54', '0000-00-00 00:00:00', 3, 2, NULL, 3, 50.00, 0.00, 50.00, 9.00, 0.00, 59.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-11 15:19:04'),
(9, 8, 'azlan', '6361557581', 2, 'regular', '2025-10-11 19:34:47', '0000-00-00 00:00:00', 114, 1, NULL, 114, 280.00, 0.00, 280.00, 0.00, 0.00, 450.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-11 15:58:30'),
(10, 9, 'Test Customer', '1234567890', 1, 'regular', '2025-10-11 20:42:05', '0000-00-00 00:00:00', 1567, 1, NULL, 1567, 2430.00, 0.00, 2430.00, 0.00, 0.00, 2430.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-12 17:18:37'),
(11, 10, 'Test Customer', '1234567890', 1, 'regular', '2025-10-11 20:42:16', '0000-00-00 00:00:00', 1571, 1, NULL, 1571, 2430.00, 0.00, 2430.00, 0.00, 0.00, 2430.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-12 17:23:26'),
(12, 11, 'Test Customer', '1234567890', 1, 'regular', '2025-10-11 20:42:26', '0000-00-00 00:00:00', 2038, 1, NULL, 2038, 3060.00, 0.00, 3060.00, 0.00, 0.00, 3060.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:10:24'),
(13, 13, 'azlan', '6361557581', 1, 'regular', '2025-10-13 06:44:41', '0000-00-00 00:00:00', 26, 2, NULL, 26, 50.00, 5.00, 55.00, 0.00, 0.00, 55.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:40:23'),
(14, 14, 'azlan', '6361557581', 1, 'regular', '2025-10-13 07:11:03', '0000-00-00 00:00:00', 0, 2, NULL, 0, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:41:16'),
(15, 15, 'ajju', '6361557581', 1, 'regular', '2025-10-13 07:11:13', '0000-00-00 00:00:00', 3, 1, NULL, 3, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:44:13'),
(16, 16, 'azlan', '', 1, 'regular', '2025-10-13 07:15:59', '0000-00-00 00:00:00', 0, 2, NULL, 0, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:46:11'),
(17, 17, 'azlan', '', 1, 'regular', '2025-10-13 07:16:07', '0000-00-00 00:00:00', 2, 1, NULL, 2, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:47:48'),
(18, 18, 'q', '', 1, 'regular', '2025-10-13 07:17:44', '0000-00-00 00:00:00', 1, 2, NULL, 1, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', '', '2025-10-13 07:19:14', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:49:08'),
(19, 19, 'azlan', '', 1, 'regular', '2025-10-13 07:18:40', '0000-00-00 00:00:00', 4, 1, NULL, 4, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', '', '2025-10-13 07:22:32', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:52:25'),
(20, 20, 'azlan', '', 1, 'regular', '2025-10-13 07:22:12', '0000-00-00 00:00:00', 2, 2, NULL, 2, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', '', '2025-10-13 07:23:52', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:53:43'),
(21, 21, 'azlan', '', 1, 'regular', '2025-10-13 07:25:17', '0000-00-00 00:00:00', 1, 2, NULL, 1, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 30.00, 0.00, 'cash', '', '2025-10-13 07:26:45', NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:56:40'),
(22, 22, 'ajju', '', 1, 'regular', '2025-10-13 07:25:53', '0000-00-00 00:00:00', 1, 1, NULL, 1, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 15.00, 15.00, 'cash', '', '2025-10-13 07:27:22', NULL, '123', '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:57:08'),
(23, 23, 'azlan', '', 1, 'regular', '2025-10-13 07:27:05', '0000-00-00 00:00:00', 0, 2, NULL, 0, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 0.00, 0.00, '', 'pending', NULL, NULL, NULL, '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:57:29'),
(24, 24, 'azlan', '', 1, 'regular', '2025-10-13 07:28:51', '0000-00-00 00:00:00', 0, 2, NULL, 0, 30.00, 0.00, 30.00, 0.00, 0.00, 30.00, 15.00, 15.00, 'upi', '', '2025-10-13 07:29:32', NULL, '123', '0000-00-00 00:00:00', 1, 1, '2025-10-13 01:58:58');

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
(1, 'System Administrator', 'admin', 'admin@gamebot.com', '1234567890', '$2y$10$zvTuKoLj3YLzmTljiG16IuF6XQMVHWmrI.26KmXqOyfftqfG/g8VW', 'Staff', 1, 'Active', '2025-10-11 09:02:17', '2025-10-11 11:28:26');

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
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `is_available` (`is_available`);

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
  ADD KEY `session_id` (`session_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `consoles`
--
ALTER TABLE `consoles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `food_and_drinks`
--
ALTER TABLE `food_and_drinks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `game_console_assignments`
--
ALTER TABLE `game_console_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gaming_sessions`
--
ALTER TABLE `gaming_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `session_pauses`
--
ALTER TABLE `session_pauses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `session_segments`
--
ALTER TABLE `session_segments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `fk_pause_session` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `session_segments`
--
ALTER TABLE `session_segments`
  ADD CONSTRAINT `fk_segment_session` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE;

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
