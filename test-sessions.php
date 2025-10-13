<?php
require_once 'config/database.php';
require_once 'config/auth.php';

echo "<h2>Session Management Test</h2>\n";

try {
    $db = getDB();

    // Test database connection
    echo "<p>✅ Database connection successful</p>\n";

    // Check if gaming_sessions table exists
    $result = $db->query("SHOW TABLES LIKE 'gaming_sessions'");
    if ($result->num_rows > 0) {
        echo "<p>✅ gaming_sessions table exists</p>\n";

        // Show table structure
        $result = $db->query("DESCRIBE gaming_sessions");
        echo "<h3>gaming_sessions table structure:</h3>\n";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>\n";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>\n";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['Field']}</td>";
            echo "<td>{$row['Type']}</td>";
            echo "<td>{$row['Null']}</td>";
            echo "<td>{$row['Key']}</td>";
            echo "<td>{$row['Default']}</td>";
            echo "<td>{$row['Extra']}</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
    } else {
        echo "<p>❌ gaming_sessions table does not exist</p>\n";
    }

    // Check if session_items table exists
    $result = $db->query("SHOW TABLES LIKE 'session_items'");
    if ($result->num_rows > 0) {
        echo "<p>✅ session_items table exists</p>\n";
    } else {
        echo "<p>❌ session_items table does not exist</p>\n";
    }

    // Check consoles table
    $result = $db->query("SELECT COUNT(*) as count FROM consoles");
    $row = $result->fetch_assoc();
    echo "<p>📊 Total consoles in database: {$row['count']}</p>\n";

    // Test API endpoint
    echo "<h3>API Endpoint Test:</h3>\n";
    echo "<p>🔗 <a href='api/sessions.php?action=list' target='_blank'>Test Sessions API</a></p>\n";
    echo "<p>🔗 <a href='api/consoles.php?action=list' target='_blank'>Test Consoles API</a></p>\n";

    // Test console mapping page
    echo "<h3>Console Management Page:</h3>\n";
    echo "<p>🔗 <a href='pages/console-mapping.php' target='_blank'>Open Console Management</a></p>\n";
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>\n";
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }

    table {
        border-collapse: collapse;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

