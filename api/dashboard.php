<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

header('Content-Type: application/json');
Auth::require();

$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    if ($method === 'GET') {
        if ($action === 'stats') {
            $period = $_GET['period'] ?? 'daily'; // daily, monthly, yearly

            $dateCondition = "DATE(created_at) = CURDATE()";
            if ($period === 'monthly') {
                $dateCondition = "MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
            } elseif ($period === 'yearly') {
                $dateCondition = "YEAR(created_at) = YEAR(CURDATE())";
            }

            // Get revenue stats
            $revenueStmt = $db->prepare("SELECT 
                SUM(total_amount) as total_revenue,
                SUM(gaming_amount) as gaming_revenue,
                SUM(fandd_amount) as fandd_revenue,
                SUM(tax_amount) as tax_collected,
                COUNT(DISTINCT customer_name) as total_customers,
                COUNT(*) as total_transactions
            FROM transactions 
            WHERE $dateCondition");
            $revenueStmt->execute();
            $revenueStats = $revenueStmt->get_result()->fetch_assoc();

            // Get active consoles count (using gaming_sessions table)
            $consoleStmt = $db->prepare("SELECT COUNT(*) as active_consoles FROM gaming_sessions WHERE status IN ('active', 'paused')");
            $consoleStmt->execute();
            $consoleStats = $consoleStmt->get_result()->fetch_assoc();

            // Get average session duration (using gaming_sessions table)
            $sessionStmt = $db->prepare("SELECT AVG(total_duration_seconds/60) as avg_duration FROM gaming_sessions WHERE status = 'completed' AND DATE(start_time) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
            $sessionStmt->execute();
            $sessionStats = $sessionStmt->get_result()->fetch_assoc();

            // Get peak hours (hour with most transactions)
            $peakStmt = $db->prepare("SELECT HOUR(created_at) as hour, COUNT(*) as count FROM transactions WHERE $dateCondition GROUP BY HOUR(created_at) ORDER BY count DESC LIMIT 1");
            $peakStmt->execute();
            $peakStats = $peakStmt->get_result()->fetch_assoc();

            // Calculate utilization percentage
            $totalConsolesStmt = $db->prepare("SELECT COUNT(*) as total FROM consoles WHERE status != 'Maintenance'");
            $totalConsolesStmt->execute();
            $totalConsoles = $totalConsolesStmt->get_result()->fetch_assoc()['total'];
            $utilization = $totalConsoles > 0 ? ($consoleStats['active_consoles'] / $totalConsoles) * 100 : 0;

            $data = [
                'revenue' => $revenueStats['total_revenue'] ?? 0,
                'gaming_revenue' => $revenueStats['gaming_revenue'] ?? 0,
                'fandd_revenue' => $revenueStats['fandd_revenue'] ?? 0,
                'tax_collected' => $revenueStats['tax_collected'] ?? 0,
                'customers' => $revenueStats['total_customers'] ?? 0,
                'transactions' => $revenueStats['total_transactions'] ?? 0,
                'active_consoles' => $consoleStats['active_consoles'] ?? 0,
                'avg_session' => round($sessionStats['avg_duration'] ?? 0),
                'peak_hour' => isset($peakStats['hour']) ? $peakStats['hour'] . ':00' : '--',
                'utilization' => round($utilization, 1)
            ];

            echo json_encode(['success' => true, 'data' => $data]);
        } elseif ($action === 'charts') {
            $period = $_GET['period'] ?? 'daily';

            // Revenue chart data
            if ($period === 'daily') {
                $revenueChartStmt = $db->prepare("SELECT HOUR(created_at) as label, SUM(total_amount) as value FROM transactions WHERE DATE(created_at) = CURDATE() GROUP BY HOUR(created_at) ORDER BY label");
            } elseif ($period === 'monthly') {
                $revenueChartStmt = $db->prepare("SELECT DAY(created_at) as label, SUM(total_amount) as value FROM transactions WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) GROUP BY DAY(created_at) ORDER BY label");
            } else {
                $revenueChartStmt = $db->prepare("SELECT MONTH(created_at) as label, SUM(total_amount) as value FROM transactions WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP BY MONTH(created_at) ORDER BY label");
            }

            $revenueChartStmt->execute();
            $revenueChart = $revenueChartStmt->get_result()->fetch_all(MYSQLI_ASSOC);

            // Customer trend
            if ($period === 'daily') {
                $customerChartStmt = $db->prepare("SELECT HOUR(created_at) as label, COUNT(DISTINCT customer_name) as value FROM transactions WHERE DATE(created_at) = CURDATE() GROUP BY HOUR(created_at) ORDER BY label");
            } elseif ($period === 'monthly') {
                $customerChartStmt = $db->prepare("SELECT DAY(created_at) as label, COUNT(DISTINCT customer_name) as value FROM transactions WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) GROUP BY DAY(created_at) ORDER BY label");
            } else {
                $customerChartStmt = $db->prepare("SELECT MONTH(created_at) as label, COUNT(DISTINCT customer_name) as value FROM transactions WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP BY MONTH(created_at) ORDER BY label");
            }

            $customerChartStmt->execute();
            $customerChart = $customerChartStmt->get_result()->fetch_all(MYSQLI_ASSOC);

            echo json_encode([
                'success' => true,
                'data' => [
                    'revenue_chart' => $revenueChart,
                    'customer_chart' => $customerChart
                ]
            ]);
        } elseif ($action === 'activity-logs') {
            $limit = intval($_GET['limit'] ?? 10);
            $stmt = $db->prepare("SELECT al.*, u.full_name as user_name FROM activity_logs al LEFT JOIN users u ON al.user_id = u.id ORDER BY al.created_at DESC LIMIT ?");
            $stmt->bind_param("i", $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            $logs = [];

            while ($row = $result->fetch_assoc()) {
                $logs[] = $row;
            }

            echo json_encode(['success' => true, 'data' => $logs]);
        }
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
