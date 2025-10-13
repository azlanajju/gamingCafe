<?php

/**
 * Database Connection Test Script
 * This file helps verify that your database connection is working properly
 */

echo "<h1>GameBot Gaming Cafe - Database Connection Test</h1>";
echo "<hr>";

// Test 1: PHP Version
echo "<h2>1. PHP Version Check</h2>";
$phpVersion = phpversion();
echo "PHP Version: <strong>$phpVersion</strong>";
if (version_compare($phpVersion, '7.4.0', '>=')) {
    echo " ✅ <span style='color: green;'>OK</span>";
} else {
    echo " ❌ <span style='color: red;'>PHP 7.4 or higher required</span>";
}
echo "<br><br>";

// Test 2: Required Extensions
echo "<h2>2. Required PHP Extensions</h2>";
$extensions = ['mysqli', 'json', 'session'];
foreach ($extensions as $ext) {
    echo "Extension '$ext': ";
    if (extension_loaded($ext)) {
        echo "✅ <span style='color: green;'>Loaded</span><br>";
    } else {
        echo "❌ <span style='color: red;'>Not loaded</span><br>";
    }
}
echo "<br>";

// Test 3: Config File
echo "<h2>3. Configuration File</h2>";
if (file_exists('config/config.php')) {
    echo "config.php: ✅ <span style='color: green;'>Found</span><br>";
    require_once 'config/config.php';
    echo "DB_HOST: <strong>" . DB_HOST . "</strong><br>";
    echo "DB_NAME: <strong>" . DB_NAME . "</strong><br>";
    echo "DB_USER: <strong>" . DB_USER . "</strong><br>";
} else {
    echo "config.php: ❌ <span style='color: red;'>Not found</span><br>";
    die("Please ensure config/config.php exists!");
}
echo "<br>";

// Test 4: Database Connection
echo "<h2>4. Database Connection</h2>";
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        echo "❌ <span style='color: red;'>Connection failed: " . $conn->connect_error . "</span><br>";
    } else {
        echo "✅ <span style='color: green;'>Connected successfully to database!</span><br>";
        echo "Server info: " . $conn->server_info . "<br>";
        echo "Character set: " . $conn->character_set_name() . "<br>";
    }
    echo "<br>";

    // Test 5: Database Tables
    echo "<h2>5. Database Tables Check</h2>";
    $tables = [
        'users',
        'branches',
        'consoles',
        'games',
        'inventory',
        'transactions',
        'coupons',
        'pricing',
        'settings',
        'sessions',
        'activity_logs'
    ];

    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        echo "Table '$table': ";
        if ($result && $result->num_rows > 0) {
            echo "✅ <span style='color: green;'>Exists</span>";

            // Count rows
            $countResult = $conn->query("SELECT COUNT(*) as count FROM $table");
            if ($countResult) {
                $count = $countResult->fetch_assoc()['count'];
                echo " ($count rows)";
            }
        } else {
            echo "❌ <span style='color: red;'>Missing</span>";
        }
        echo "<br>";
    }
    echo "<br>";

    // Test 6: Admin User
    echo "<h2>6. Admin User Check</h2>";
    $result = $conn->query("SELECT id, username, email, role FROM users WHERE username = 'admin'");
    if ($result && $result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        echo "✅ <span style='color: green;'>Admin user found!</span><br>";
        echo "ID: " . $admin['id'] . "<br>";
        echo "Username: " . $admin['username'] . "<br>";
        echo "Email: " . $admin['email'] . "<br>";
        echo "Role: " . $admin['role'] . "<br>";
        echo "<br>";
        echo "<strong>Default Login Credentials:</strong><br>";
        echo "Username: <code>admin</code><br>";
        echo "Password: <code>admin123</code><br>";
    } else {
        echo "❌ <span style='color: red;'>Admin user not found!</span><br>";
        echo "Please run the database.sql file to create the default admin user.<br>";
    }
    echo "<br>";

    // Test 7: File Structure
    echo "<h2>7. File Structure Check</h2>";
    $directories = ['config', 'includes', 'pages', 'api', 'assets'];
    foreach ($directories as $dir) {
        echo "Directory '$dir': ";
        if (is_dir($dir)) {
            echo "✅ <span style='color: green;'>Exists</span><br>";
        } else {
            echo "❌ <span style='color: red;'>Missing</span><br>";
        }
    }
    echo "<br>";

    $files = ['login.php', 'logout.php', 'style.css', 'app.js', 'database.sql'];
    foreach ($files as $file) {
        echo "File '$file': ";
        if (file_exists($file)) {
            echo "✅ <span style='color: green;'>Exists</span><br>";
        } else {
            echo "❌ <span style='color: red;'>Missing</span><br>";
        }
    }

    echo "<br><hr>";
    echo "<h2>✅ System Status</h2>";

    // Overall status
    $errors = 0;
    if (!$conn) $errors++;

    if ($errors === 0) {
        echo "<p style='color: green; font-size: 18px; font-weight: bold;'>";
        echo "✅ All tests passed! Your system is ready to use.";
        echo "</p>";
        echo "<p><a href='login.php' style='display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px;'>Go to Login Page</a></p>";
    } else {
        echo "<p style='color: red; font-size: 18px; font-weight: bold;'>";
        echo "❌ Some tests failed. Please fix the issues above.";
        echo "</p>";
    }

    $conn->close();
} catch (Exception $e) {
    echo "❌ <span style='color: red;'>Error: " . $e->getMessage() . "</span><br>";
}

echo "<hr>";
echo "<p><small>Test completed at: " . date('Y-m-d H:i:s') . "</small></p>";
echo "<p><small><a href='README.md'>View README</a> | <a href='INSTALLATION.md'>View Installation Guide</a></small></p>";
?>

<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 900px;
        margin: 50px auto;
        padding: 20px;
        background: #f5f5f5;
    }

    h1 {
        color: #333;
        border-bottom: 3px solid #4CAF50;
        padding-bottom: 10px;
    }

    h2 {
        color: #555;
        margin-top: 20px;
    }

    code {
        background: #f0f0f0;
        padding: 2px 6px;
        border-radius: 3px;
        font-family: monospace;
    }
</style>

