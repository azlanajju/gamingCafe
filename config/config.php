<?php
// Gaming Cafe Management System Configuration
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'gaming_cafe_db');

// Application Configuration
define('SITE_NAME', 'GameBot Gaming Cafe');
define('SITE_URL', 'http://localhost/Gaming-cafe');
define('TIMEZONE', 'Asia/Kolkata');

// Session Configuration
define('SESSION_LIFETIME', 3600); // 1 hour in seconds

// Set timezone
date_default_timezone_set(TIMEZONE);

// Error reporting (set to 0 in production)
error_reporting(0);
ini_set('display_errors', 0);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
