-- Sample data for Gaming Cafe Management System
-- Run this after creating the database schema

USE gaming_cafe_db;

-- Insert sample branches
INSERT INTO branches (id, name, location, address, contact, manager_name, timing, console_count, established_year, status) VALUES
(1, 'Gamebot Central', 'Downtown', '123 Gaming Street, City Center', '+1234567890', 'John Smith', '10:00 AM - 12:00 AM', 6, 2023, 'Active'),
(2, 'Gamebot Mall', 'Shopping Mall', '456 Mall Avenue, Shopping District', '+1234567891', 'Jane Doe', '11:00 AM - 11:00 PM', 8, 2023, 'Active');

-- Insert sample consoles
INSERT INTO consoles (id, name, type, specifications, purchase_year, email, primary_user, location, has_plus_account, under_maintenance, status, branch_id) VALUES
(1, 'Gaming PC Alpha', 'PC', 'RTX 4080, i7-13700K, 32GB RAM', 2024, 'pc1@gamebot.com', 'System Admin', 'Zone A', 1, 0, 'available', 1),
(2, 'Gaming PC Beta', 'PC', 'RTX 4070, i5-13600K, 16GB RAM', 2024, 'pc2@gamebot.com', 'Manager', 'Zone A', 0, 0, 'available', 1),
(3, 'PlayStation 5 Pro', 'PS5', 'PlayStation 5, 1TB SSD', 2024, 'ps5@gamebot.com', 'Gaming Admin', 'Zone B', 1, 0, 'available', 1),
(4, 'Xbox Series X', 'Xbox', 'Xbox Series X, 1TB Storage', 2023, 'xbox@gamebot.com', 'Console Manager', 'Zone B', 0, 0, 'available', 1),
(5, 'Nintendo Switch', 'Nintendo Switch', 'Nintendo Switch OLED', 2024, 'switch@gamebot.com', 'Switch Admin', 'Zone C', 0, 0, 'available', 1),
(6, 'Gaming PC Gamma', 'PC', 'RTX 4060, i5-13400F, 16GB RAM', 2024, 'pc3@gamebot.com', 'Staff Member', 'Zone C', 0, 0, 'available', 1);

-- Insert sample games
INSERT INTO games (id, name, category, developer, rating, release_date, branch_id) VALUES
(1, 'Valorant', 'FPS', 'Riot Games', 4.8, '2020-06-02', 1),
(2, 'FIFA 24', 'Sports', 'EA Sports', 4.5, '2023-09-29', 1),
(3, 'Call of Duty', 'FPS', 'Activision', 4.7, '2023-11-10', 1),
(4, 'Fortnite', 'Action', 'Epic Games', 4.3, '2017-07-25', 1),
(5, 'Grand Theft Auto V', 'Action', 'Rockstar Games', 4.9, '2013-09-17', 1),
(6, 'Counter-Strike 2', 'FPS', 'Valve', 4.6, '2023-09-27', 1);

-- Insert sample inventory items
INSERT INTO inventory (id, name, category, cost_price, selling_price, stock_quantity, expiry_date, supplier, branch_id) VALUES
(1, 'Energy Drink', 'Beverages', 2.0, 3.99, 45, '2025-12-31', 'Red Bull Distribution', 1),
(2, 'Gaming Chips', 'Snacks', 1.5, 2.99, 30, '2025-10-15', 'Frito-Lay', 1),
(3, 'Coca Cola', 'Beverages', 1.2, 2.5, 60, '2025-11-30', 'Coca Cola Company', 1),
(4, 'Pizza Slice', 'Food', 4.5, 8.99, 15, '2025-08-25', 'Fresh Foods Co', 1),
(5, 'Coffee', 'Beverages', 1.8, 3.5, 25, '2025-12-15', 'Coffee Masters', 1),
(6, 'Sandwich', 'Food', 3.2, 6.99, 20, '2025-08-26', 'Fresh Foods Co', 1),
(7, 'Chocolate Bar', 'Snacks', 1.0, 2.25, 35, '2025-10-30', 'Nestle', 1),
(8, 'Water Bottle', 'Beverages', 0.5, 1.5, 100, '2026-01-01', 'Aqua Pure', 1);

-- Insert sample coupons
INSERT INTO coupons (id, name, code, description, discount_type, discount_value, base_minutes, bonus_minutes, loop_bonus, usage_limit, min_order_amount, valid_from, valid_to, status, branch_id) VALUES
(1, 'Happy Hour', 'HAPPY50', '50% off during 2-5 PM', 'percentage', 50, NULL, NULL, 0, 100, 10.0, '2025-01-01', '2025-12-31', 'Active', 1),
(2, 'Weekend Special', 'WEEKEND20', '20% off on weekends', 'percentage', 20, NULL, NULL, 0, 50, 15.0, '2025-01-01', '2025-12-31', 'Active', 1),
(3, 'Free Time Bonus', 'FREETIME', 'Play 2h get 30m free', 'time_bonus', NULL, 120, 30, 0, 200, 25.0, '2025-01-01', '2025-12-31', 'Active', 1),
(4, 'Loop Time Bonus', 'LOOPBONUS', 'Play 2h get 30m free (loops)', 'time_bonus', NULL, 120, 30, 1, 100, 50.0, '2025-01-01', '2025-12-31', 'Active', 1),
(5, 'Student Discount', 'STUDENT15', '15% off for students', 'percentage', 15, NULL, NULL, 0, 150, 20.0, '2025-01-01', '2025-12-31', 'Active', 1);

-- Insert sample pricing rules
INSERT INTO pricing (id, rate_type, player_count, duration_15, duration_30, duration_45, duration_60, branch_id) VALUES
(1, 'regular', 1, 5.00, 9.00, 13.00, 17.00, 1),
(2, 'regular', 2, 8.00, 15.00, 22.00, 29.00, 1),
(3, 'regular', 3, 11.00, 21.00, 31.00, 41.00, 1),
(4, 'regular', 4, 14.00, 27.00, 40.00, 53.00, 1),
(5, 'vip', 1, 7.50, 14.00, 20.00, 26.00, 1),
(6, 'vip', 2, 12.00, 23.00, 34.00, 45.00, 1),
(7, 'vip', 3, 16.50, 32.00, 47.00, 62.00, 1),
(8, 'vip', 4, 21.00, 41.00, 61.00, 81.00, 1);

-- Insert sample settings
INSERT INTO settings (id, branch_id, setting_key, setting_value, description) VALUES
(1, 1, 'peak_multiplier', '1.2', 'Multiplier applied during peak hours'),
(2, 1, 'weekend_multiplier', '1.1', 'Multiplier applied on weekends'),
(3, 1, 'peak_hours', '18:00-22:00', 'Peak hours definition'),
(4, 1, 'currency_symbol', '₹', 'Currency symbol for display'),
(5, 1, 'timezone', 'Asia/Kolkata', 'System timezone');

-- Insert sample transactions
INSERT INTO transactions (id, customer_name, console_name, console_id, duration, gaming_amount, food_amount, total_amount, payment_method, transaction_date, created_by, branch_id) VALUES
(1, 'Mike Wilson', 'Gaming PC Alpha', 1, 150, 37.50, 8.00, 45.50, 'Cash', '2025-01-15 14:30:00', 1, 1),
(2, 'Sarah Davis', 'PlayStation 5 Pro', 3, 105, 28.75, 4.00, 32.75, 'UPI', '2025-01-15 16:15:00', 1, 1),
(3, 'Tom Brown', 'Gaming PC Beta', 2, 195, 58.99, 12.50, 71.49, 'Card', '2025-01-15 18:45:00', 1, 1),
(4, 'Emma Johnson', 'Xbox Series X', 4, 120, 29.00, 6.99, 35.99, 'Cash', '2025-01-15 20:00:00', 1, 1),
(5, 'Alex Chen', 'Gaming PC Alpha', 1, 90, 17.00, 3.99, 20.99, 'UPI', '2025-01-15 21:30:00', 1, 1);

-- Insert sample activity logs
INSERT INTO activity_logs (id, user_id, action, description, ip_address, created_at) VALUES
(1, 1, 'login', 'User logged in', '127.0.0.1', '2025-01-15 09:00:00'),
(2, 1, 'session_management', 'Started gaming session for Mike Wilson', '127.0.0.1', '2025-01-15 14:30:00'),
(3, 1, 'session_management', 'Ended gaming session for Sarah Davis', '127.0.0.1', '2025-01-15 18:00:00'),
(4, 1, 'inventory', 'Added new inventory item: Energy Drink', '127.0.0.1', '2025-01-15 10:15:00'),
(5, 1, 'coupon', 'Created new coupon: Happy Hour', '127.0.0.1', '2025-01-15 11:00:00');

-- Insert game-console assignments
INSERT INTO game_console_assignments (id, game_id, console_id) VALUES
(1, 1, 1), -- Valorant on Gaming PC Alpha
(1, 1, 2), -- Valorant on Gaming PC Beta
(2, 2, 1), -- FIFA 24 on Gaming PC Alpha
(2, 2, 2), -- FIFA 24 on Gaming PC Beta
(2, 2, 3), -- FIFA 24 on PlayStation 5
(3, 3, 1), -- Call of Duty on Gaming PC Alpha
(3, 3, 2), -- Call of Duty on Gaming PC Beta
(3, 3, 4), -- Call of Duty on Xbox Series X
(4, 4, 1), -- Fortnite on Gaming PC Alpha
(4, 4, 2), -- Fortnite on Gaming PC Beta
(4, 4, 3), -- Fortnite on PlayStation 5
(4, 4, 4), -- Fortnite on Xbox Series X
(5, 5, 1), -- GTA V on Gaming PC Alpha
(5, 5, 2), -- GTA V on Gaming PC Beta
(6, 6, 1), -- CS2 on Gaming PC Alpha
(6, 6, 2); -- CS2 on Gaming PC Beta


