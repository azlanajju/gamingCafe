-- Migration: Add Super Admin role and convert existing Admin to Super Admin
-- Run this SQL script to update your database

USE `gaming_cafe_db`;

-- Step 1: Update existing Admin users to Super Admin
UPDATE `users` SET `role` = 'Super Admin' WHERE `role` = 'Admin';

-- Step 2: Modify the enum to include Super Admin and Admin
ALTER TABLE `users` MODIFY COLUMN `role` ENUM('Super Admin','Admin','Manager','Staff') NOT NULL DEFAULT 'Staff';

-- Verification query (run to check):
-- SELECT id, username, role FROM users;

