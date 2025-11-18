-- Gaming Cafe Management System Database Schema
-- Created: 2025

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Database: `gaming_cafe_db`
CREATE DATABASE IF NOT EXISTS `gaming_cafe_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `gaming_cafe_db`;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Super Admin','Admin','Manager','Staff') NOT NULL DEFAULT 'Staff',
  `branch_id` int(11) DEFAULT 1,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default super admin user (password: admin123)
INSERT INTO `users` (`full_name`, `username`, `email`, `phone`, `password`, `role`, `status`) VALUES
('System Administrator', 'admin', 'admin@gamebot.com', '1234567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Admin', 'Active');

-- --------------------------------------------------------
-- Table structure for table `branches`
-- --------------------------------------------------------

CREATE TABLE `branches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `contact` varchar(20) NOT NULL,
  `manager_name` varchar(255) NOT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `timing` varchar(100) NOT NULL,
  `console_count` int(11) DEFAULT 0,
  `established_year` int(4) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_manager` FOREIGN KEY (`manager_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default branch
INSERT INTO `branches` (`name`, `location`, `address`, `contact`, `manager_name`, `timing`, `console_count`, `established_year`, `status`) VALUES
('Main Branch', 'Downtown', '123 Gaming Street, City Center', '+1234567890', 'John Manager', '10:00 AM - 12:00 AM', 10, 2023, 'Active');

-- --------------------------------------------------------
-- Table structure for table `consoles`
-- --------------------------------------------------------

CREATE TABLE `consoles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `specifications` text,
  `purchase_year` int(4) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `primary_user` varchar(255) DEFAULT NULL,
  `location` varchar(100) NOT NULL,
  `has_plus_account` tinyint(1) DEFAULT 0,
  `under_maintenance` tinyint(1) DEFAULT 0,
  `status` enum('Available','In Use','Maintenance') NOT NULL DEFAULT 'Available',
  `branch_id` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `games`
-- --------------------------------------------------------

CREATE TABLE `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `developer` varchar(255) NOT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `branch_id` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `game_console_assignments`
-- --------------------------------------------------------

CREATE TABLE `game_console_assignments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `console_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`),
  KEY `console_id` (`console_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `inventory`
-- --------------------------------------------------------
CREATE TABLE `food_and_drinks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `category` enum('beverages','snacks','meals','desserts','other') NOT NULL DEFAULT 'other',
  `description` text DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `image_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `branch_id` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `expiry_date` date DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `branch_id` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `sessions`
-- --------------------------------------------------------

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `console_id` (`console_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `gaming_sessions`
-- --------------------------------------------------------

CREATE TABLE `gaming_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `console_id` (`console_id`),
  KEY `created_by` (`created_by`),
  KEY `start_time` (`start_time`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `session_items`
-- --------------------------------------------------------

CREATE TABLE `session_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `item_type` enum('food_drinks','gaming','other') NOT NULL DEFAULT 'food_drinks',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `session_segments`
-- --------------------------------------------------------

CREATE TABLE `session_segments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) NOT NULL,
  `player_count` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `duration` int(11) DEFAULT 0 COMMENT 'in minutes',
  `amount` decimal(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`),
  CONSTRAINT `fk_segment_session` FOREIGN KEY (`session_id`) REFERENCES `gaming_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `session_pauses`
-- --------------------------------------------------------

CREATE TABLE `session_pauses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) NOT NULL,
  `pause_start` datetime NOT NULL,
  `pause_end` datetime DEFAULT NULL,
  `reason` text,
  `duration` int(11) DEFAULT 0 COMMENT 'in minutes',
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `transactions`
-- --------------------------------------------------------

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_number` varchar(20) DEFAULT NULL,
  `console_id` int(11) DEFAULT NULL,
  `console_name` varchar(255) DEFAULT NULL,
  `player_count` int(11) DEFAULT 1,
  `rate_type` enum('regular','vip') DEFAULT 'regular',
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `duration` int(11) DEFAULT 0 COMMENT 'in minutes',
  `total_duration_minutes` int(11) DEFAULT 0,
  `gaming_amount` decimal(10,2) DEFAULT 0.00,
  `food_amount` decimal(10,2) DEFAULT 0.00,
  `fandd_amount` decimal(10,2) DEFAULT 0.00,
  `subtotal` decimal(10,2) DEFAULT 0.00,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `final_amount` decimal(10,2) DEFAULT 0.00,
  `payment_method` varchar(50) NOT NULL,
  `payment_details` text,
  `payment_status` enum('pending','completed','refunded') DEFAULT 'pending',
  `payment_date` datetime DEFAULT NULL,
  `coupon_code` varchar(50) DEFAULT NULL,
  `transaction_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`),
  KEY `console_id` (`console_id`),
  KEY `created_by` (`created_by`),
  KEY `transaction_date` (`transaction_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `transaction_items`
-- --------------------------------------------------------

CREATE TABLE `transaction_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `item_type` enum('food','gaming') NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `coupons`
-- --------------------------------------------------------

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text,
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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `pricing`
-- --------------------------------------------------------

CREATE TABLE `pricing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rate_type` enum('regular','vip') NOT NULL,
  `player_count` int(11) NOT NULL,
  `duration_15` decimal(10,2) DEFAULT NULL,
  `duration_30` decimal(10,2) DEFAULT NULL,
  `duration_45` decimal(10,2) DEFAULT NULL,
  `duration_60` decimal(10,2) DEFAULT NULL,
  `branch_id` int(11) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_pricing` (`rate_type`,`player_count`,`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default pricing
INSERT INTO `pricing` (`rate_type`, `player_count`, `duration_15`, `duration_30`, `duration_45`, `duration_60`, `branch_id`) VALUES
('regular', 1, 30.00, 50.00, 70.00, 90.00, 1),
('regular', 2, 50.00, 80.00, 110.00, 140.00, 1),
('regular', 3, 70.00, 110.00, 150.00, 190.00, 1),
('regular', 4, 90.00, 140.00, 190.00, 240.00, 1),
('vip', 1, 40.00, 70.00, 100.00, 130.00, 1),
('vip', 2, 70.00, 110.00, 150.00, 190.00, 1),
('vip', 3, 100.00, 150.00, 200.00, 250.00, 1),
('vip', 4, 130.00, 190.00, 250.00, 310.00, 1);

-- --------------------------------------------------------
-- Table structure for table `settings`
-- --------------------------------------------------------

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  `branch_id` int(11) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_setting` (`setting_key`,`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default settings
INSERT INTO `settings` (`setting_key`, `setting_value`, `branch_id`) VALUES
('peak_hours', '18,19,20,21,22', 1),
('peak_multiplier', '1.2', 1),
('weekend_multiplier', '1.1', 1);

-- --------------------------------------------------------
-- Table structure for table `activity_logs`
-- --------------------------------------------------------

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `branch_id` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add foreign key constraints
ALTER TABLE `game_console_assignments`
  ADD CONSTRAINT `fk_game_console_game` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_game_console_console` FOREIGN KEY (`console_id`) REFERENCES `consoles` (`id`) ON DELETE CASCADE;

-- Note: sessions table was renamed to gaming_sessions, constraints are defined below

ALTER TABLE `gaming_sessions`
  ADD CONSTRAINT `fk_gaming_session_console` FOREIGN KEY (`console_id`) REFERENCES `consoles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_gaming_session_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `session_items`
  ADD CONSTRAINT `fk_session_item_session` FOREIGN KEY (`session_id`) REFERENCES `gaming_sessions` (`id`) ON DELETE CASCADE;

ALTER TABLE `session_segments`
  ADD CONSTRAINT `fk_segment_session` FOREIGN KEY (`session_id`) REFERENCES `gaming_sessions` (`id`) ON DELETE CASCADE;

ALTER TABLE `session_pauses`
  ADD CONSTRAINT `fk_pause_session` FOREIGN KEY (`session_id`) REFERENCES `gaming_sessions` (`id`) ON DELETE CASCADE;

ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transaction_session` FOREIGN KEY (`session_id`) REFERENCES `gaming_sessions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_transaction_console` FOREIGN KEY (`console_id`) REFERENCES `consoles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_transaction_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `transaction_items`
  ADD CONSTRAINT `fk_item_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

COMMIT;