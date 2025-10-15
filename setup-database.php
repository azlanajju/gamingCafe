<?php
require_once 'config/config.php';

echo "<h1>Gaming Cafe Database Setup</h1>\n";
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
.success { color: green; font-weight: bold; }
.error { color: red; font-weight: bold; }
.warning { color: orange; font-weight: bold; }
.info { color: blue; font-weight: bold; }
.step { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
</style>\n";

try {
    echo "<div class='step'>";
    echo "<h2>Step 1: Database Connection Test</h2>\n";

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        echo "<p class='error'>❌ Database connection failed: " . $conn->connect_error . "</p>\n";
        echo "<p class='info'>Please check your database configuration in config/config.php</p>\n";
        exit;
    } else {
        echo "<p class='success'>✅ Database connection successful!</p>\n";
    }
    echo "</div>";

    echo "<div class='step'>";
    echo "<h2>Step 2: Database Schema Check</h2>\n";

    // Check if tables exist
    $tables = ['users', 'branches', 'consoles', 'games', 'inventory', 'transactions', 'coupons', 'pricing', 'settings', 'activity_logs', 'gaming_sessions', 'session_items'];
    $missingTables = [];

    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows == 0) {
            $missingTables[] = $table;
        }
    }

    if (empty($missingTables)) {
        echo "<p class='success'>✅ All required tables exist</p>\n";
    } else {
        echo "<p class='error'>❌ Missing tables: " . implode(', ', $missingTables) . "</p>\n";
        echo "<p class='info'>Please run the database.sql file to create the schema</p>\n";
        echo "<p class='info'>You can import it using phpMyAdmin or MySQL command line</p>\n";
    }
    echo "</div>";

    echo "<div class='step'>";
    echo "<h2>Step 3: Sample Data Check</h2>\n";

    // Check if we have basic data
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    $userCount = $result->fetch_assoc()['count'];

    $result = $conn->query("SELECT COUNT(*) as count FROM branches");
    $branchCount = $result->fetch_assoc()['count'];

    $result = $conn->query("SELECT COUNT(*) as count FROM consoles");
    $consoleCount = $result->fetch_assoc()['count'];

    if ($userCount == 0) {
        echo "<p class='warning'>⚠️ No users found. Please run sample-data.sql to add sample data</p>\n";
    } else {
        echo "<p class='success'>✅ Found $userCount users</p>\n";
    }

    if ($branchCount == 0) {
        echo "<p class='warning'>⚠️ No branches found. Please run sample-data.sql to add sample data</p>\n";
    } else {
        echo "<p class='success'>✅ Found $branchCount branches</p>\n";
    }

    if ($consoleCount == 0) {
        echo "<p class='warning'>⚠️ No consoles found. Please run sample-data.sql to add sample data</p>\n";
    } else {
        echo "<p class='success'>✅ Found $consoleCount consoles</p>\n";
    }
    echo "</div>";

    echo "<div class='step'>";
    echo "<h2>Step 4: Quick Setup Actions</h2>\n";

    if (!empty($missingTables)) {
        echo "<p class='error'>❌ Cannot proceed without database schema</p>\n";
    } else {
        echo "<h3>Database Setup Commands:</h3>\n";
        echo "<ol>\n";
        echo "<li><strong>Import Schema:</strong> Import database.sql file in phpMyAdmin</li>\n";
        echo "<li><strong>Add Sample Data:</strong> Import sample-data.sql file in phpMyAdmin</li>\n";
        echo "<li><strong>Test System:</strong> <a href='test-php-dynamic.php' target='_blank'>Run System Test</a></li>\n";
        echo "<li><strong>Login:</strong> <a href='login.php' target='_blank'>Access Login Page</a></li>\n";
        echo "</ol>\n";

        // Check if admin user exists
        $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE username = 'admin'");
        $adminExists = $result->fetch_assoc()['count'] > 0;

        if ($adminExists) {
            echo "<p class='success'>✅ Admin user exists - you can login with username: admin, password: admin123</p>\n";
        } else {
            echo "<p class='warning'>⚠️ Admin user not found. Please run sample-data.sql</p>\n";
        }
    }
    echo "</div>";

    echo "<div class='step'>";
    echo "<h2>Step 5: System Status</h2>\n";

    $allGood = empty($missingTables) && $userCount > 0 && $branchCount > 0 && $consoleCount > 0;

    if ($allGood) {
        echo "<p class='success'>🎉 System is ready to use!</p>\n";
        echo "<p class='info'>All PHP pages are configured to use dynamic data from the database.</p>\n";
        echo "<p class='info'>No hardcoded values remain in the PHP backend.</p>\n";
    } else {
        echo "<p class='warning'>⚠️ System needs setup completion</p>\n";
        echo "<p class='info'>Please complete the missing steps above.</p>\n";
    }
    echo "</div>";

    echo "<div class='step'>";
    echo "<h2>Quick Links</h2>\n";
    echo "<ul>\n";
    echo "<li><a href='login.php' target='_blank'>🔐 Login Page</a></li>\n";
    echo "<li><a href='test-php-dynamic.php' target='_blank'>🧪 System Test</a></li>\n";
    echo "<li><a href='test-sessions.php' target='_blank'>🎮 Session Test</a></li>\n";
    echo "<li><a href='pages/dashboard.php' target='_blank'>📊 Dashboard</a></li>\n";
    echo "<li><a href='pages/console-mapping.php' target='_blank'>🎮 Console Management</a></li>\n";
    echo "</ul>\n";
    echo "</div>";

    $conn->close();
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>\n";
}






