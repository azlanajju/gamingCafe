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
            $selectedBranchId = $_GET['branch_id'] ?? null; // From topbar dropdown

            // Check if user is a manager and should be restricted to their branch
            $userBranchId = Auth::userBranchId();
            $isManagerRestricted = Auth::isManagerRestricted();
            $currentUserId = Auth::userId();
            $userRole = Auth::userRole();

            $branchCondition = '';
            $branchParams = [];
            $branchParamTypes = '';

            // Priority: Admin can select any branch via dropdown, Manager restricted to their branch
            if ($userRole === 'Admin' && $selectedBranchId) {
                // Admin selected a specific branch from dropdown
                $branchCondition = " AND branch_id = ?";
                $branchParams[] = $selectedBranchId;
                $branchParamTypes = 'i';
            } elseif ($isManagerRestricted && $userBranchId) {
                // Manager restricted to their branch
                $branchCondition = " AND branch_id = ?";
                $branchParams[] = $userBranchId;
                $branchParamTypes = 'i';
            }

            // Add data isolation for non-Admin users
            $dataIsolationCondition = '';
            if ($userRole !== 'Admin') {
                $dataIsolationCondition = " AND created_by = ?";
                $branchParams[] = $currentUserId;
                $branchParamTypes .= 'i';
            }

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
            WHERE $dateCondition $branchCondition $dataIsolationCondition");

            if (!empty($branchParams)) {
                $revenueStmt->bind_param($branchParamTypes, ...$branchParams);
            }
            $revenueStmt->execute();
            $revenueStats = $revenueStmt->get_result()->fetch_assoc();

            // Get active consoles count (using gaming_sessions table)
            $consoleQuery = "SELECT COUNT(*) as active_consoles FROM gaming_sessions gs 
                            LEFT JOIN consoles c ON gs.console_id = c.id 
                            WHERE gs.status IN ('active', 'paused')";
            if (($userRole === 'Admin' && $selectedBranchId) || ($isManagerRestricted && $userBranchId)) {
                $branchIdToUse = ($userRole === 'Admin' && $selectedBranchId) ? $selectedBranchId : $userBranchId;
                $consoleQuery .= " AND c.branch_id = ?";
            }
            $consoleStmt = $db->prepare($consoleQuery);
            if (($userRole === 'Admin' && $selectedBranchId) || ($isManagerRestricted && $userBranchId)) {
                $branchIdToUse = ($userRole === 'Admin' && $selectedBranchId) ? $selectedBranchId : $userBranchId;
                $consoleStmt->bind_param("i", $branchIdToUse);
            }
            $consoleStmt->execute();
            $consoleStats = $consoleStmt->get_result()->fetch_assoc();

            // Get average session duration (using gaming_sessions table)
            $sessionStmt = $db->prepare("SELECT AVG(total_duration_seconds/60) as avg_duration FROM gaming_sessions WHERE status = 'completed' AND DATE(start_time) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
            $sessionStmt->execute();
            $sessionStats = $sessionStmt->get_result()->fetch_assoc();

            // Get peak hours (hour with most transactions)
            $peakQuery = "SELECT HOUR(created_at) as hour, COUNT(*) as count FROM transactions WHERE $dateCondition $branchCondition $dataIsolationCondition GROUP BY HOUR(created_at) ORDER BY count DESC LIMIT 1";
            $peakStmt = $db->prepare($peakQuery);
            if (!empty($branchParams)) {
                $peakStmt->bind_param($branchParamTypes, ...$branchParams);
            }
            $peakStmt->execute();
            $peakStats = $peakStmt->get_result()->fetch_assoc();

            // Calculate utilization percentage
            $totalConsolesQuery = "SELECT COUNT(*) as total FROM consoles WHERE status != 'Maintenance'";
            if (($userRole === 'Admin' && $selectedBranchId) || ($isManagerRestricted && $userBranchId)) {
                $branchIdToUse = ($userRole === 'Admin' && $selectedBranchId) ? $selectedBranchId : $userBranchId;
                $totalConsolesQuery .= " AND branch_id = ?";
            }
            $totalConsolesStmt = $db->prepare($totalConsolesQuery);
            if (($userRole === 'Admin' && $selectedBranchId) || ($isManagerRestricted && $userBranchId)) {
                $branchIdToUse = ($userRole === 'Admin' && $selectedBranchId) ? $selectedBranchId : $userBranchId;
                $totalConsolesStmt->bind_param("i", $branchIdToUse);
            }
            $totalConsolesStmt->execute();
            $totalConsoles = $totalConsolesStmt->get_result()->fetch_assoc()['total'];
            $utilization = $totalConsoles > 0 ? ($consoleStats['active_consoles'] / $totalConsoles) * 100 : 0;

            // Compute total running time across all currently active/paused sessions
            // Sum of (now - start_time) minus pauses, including ongoing pause time
            $uptimeQuery = "
                SELECT 
                    COUNT(*) AS active_sessions,
                    SUM(
                        GREATEST(
                            TIMESTAMPDIFF(SECOND, gs.start_time, NOW())
                            - COALESCE(gs.total_pause_duration, 0)
                            - CASE 
                                WHEN gs.status = 'paused' AND gs.pause_start_time IS NOT NULL 
                                THEN TIMESTAMPDIFF(SECOND, gs.pause_start_time, NOW())
                                ELSE 0
                              END,
                            0
                        )
                    ) AS total_running_seconds
                FROM gaming_sessions gs
                LEFT JOIN consoles c ON gs.console_id = c.id
                WHERE gs.status IN ('active', 'paused')";

            if (($userRole === 'Admin' && $selectedBranchId) || ($isManagerRestricted && $userBranchId)) {
                $branchIdToUse = ($userRole === 'Admin' && $selectedBranchId) ? $selectedBranchId : $userBranchId;
                $uptimeQuery .= " AND c.branch_id = ?";
            }

            $uptimeStmt = $db->prepare($uptimeQuery);
            if (($userRole === 'Admin' && $selectedBranchId) || ($isManagerRestricted && $userBranchId)) {
                $branchIdToUse = ($userRole === 'Admin' && $selectedBranchId) ? $selectedBranchId : $userBranchId;
                $uptimeStmt->bind_param("i", $branchIdToUse);
            }
            $uptimeStmt->execute();
            $uptimeRow = $uptimeStmt->get_result()->fetch_assoc() ?: ['active_sessions' => 0, 'total_running_seconds' => 0];

            $totalRunningSeconds = intval($uptimeRow['total_running_seconds'] ?? 0);
            $hours = floor($totalRunningSeconds / 3600);
            $minutes = floor(($totalRunningSeconds % 3600) / 60);
            $seconds = $totalRunningSeconds % 60;
            $uptimeFormatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

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
                'utilization' => round($utilization, 1),
                'system_uptime_seconds' => $totalRunningSeconds,
                'system_uptime_formatted' => $uptimeFormatted,
                'active_sessions' => intval($uptimeRow['active_sessions'] ?? 0)
            ];

            echo json_encode(['success' => true, 'data' => $data]);
        } elseif ($action === 'charts') {
            $period = $_GET['period'] ?? 'daily';
            $selectedBranchId = $_GET['branch_id'] ?? null; // From topbar dropdown

            // Check if user is a manager and should be restricted to their branch
            $userBranchId = Auth::userBranchId();
            $isManagerRestricted = Auth::isManagerRestricted();
            $userRole = Auth::userRole();

            $branchCondition = '';
            $branchParams = [];
            $branchParamTypes = '';

            // Priority: Admin can select any branch via dropdown, Manager restricted to their branch
            if ($userRole === 'Admin' && $selectedBranchId) {
                // Admin selected a specific branch from dropdown
                $branchCondition = " AND branch_id = ?";
                $branchParams[] = $selectedBranchId;
                $branchParamTypes = 'i';
            } elseif ($isManagerRestricted && $userBranchId) {
                // Manager restricted to their branch
                $branchCondition = " AND branch_id = ?";
                $branchParams[] = $userBranchId;
                $branchParamTypes = 'i';
            }

            // Add data isolation for non-Admin users
            $dataIsolationCondition = '';
            if ($userRole !== 'Admin') {
                $dataIsolationCondition = " AND created_by = ?";
                $branchParams[] = $currentUserId;
                $branchParamTypes .= 'i';
            }

            // Revenue chart data
            if ($period === 'daily') {
                $revenueChartQuery = "SELECT HOUR(created_at) as label, SUM(total_amount) as value FROM transactions WHERE DATE(created_at) = CURDATE() $branchCondition $dataIsolationCondition GROUP BY HOUR(created_at) ORDER BY label";
            } elseif ($period === 'monthly') {
                $revenueChartQuery = "SELECT DAY(created_at) as label, SUM(total_amount) as value FROM transactions WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) $branchCondition $dataIsolationCondition GROUP BY DAY(created_at) ORDER BY label";
            } else {
                $revenueChartQuery = "SELECT MONTH(created_at) as label, SUM(total_amount) as value FROM transactions WHERE YEAR(created_at) = YEAR(CURDATE()) $branchCondition $dataIsolationCondition GROUP BY MONTH(created_at) ORDER BY label";
            }

            $revenueChartStmt = $db->prepare($revenueChartQuery);
            if (!empty($branchParams)) {
                $revenueChartStmt->bind_param($branchParamTypes, ...$branchParams);
            }
            $revenueChartStmt->execute();
            $revenueChart = $revenueChartStmt->get_result()->fetch_all(MYSQLI_ASSOC);

            // Customer trend
            if ($period === 'daily') {
                $customerChartQuery = "SELECT HOUR(created_at) as label, COUNT(DISTINCT customer_name) as value FROM transactions WHERE DATE(created_at) = CURDATE() $branchCondition $dataIsolationCondition GROUP BY HOUR(created_at) ORDER BY label";
            } elseif ($period === 'monthly') {
                $customerChartQuery = "SELECT DAY(created_at) as label, COUNT(DISTINCT customer_name) as value FROM transactions WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) $branchCondition $dataIsolationCondition GROUP BY DAY(created_at) ORDER BY label";
            } else {
                $customerChartQuery = "SELECT MONTH(created_at) as label, COUNT(DISTINCT customer_name) as value FROM transactions WHERE YEAR(created_at) = YEAR(CURDATE()) $branchCondition $dataIsolationCondition GROUP BY MONTH(created_at) ORDER BY label";
            }

            $customerChartStmt = $db->prepare($customerChartQuery);
            if (!empty($branchParams)) {
                $customerChartStmt->bind_param($branchParamTypes, ...$branchParams);
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
            // Restrict activity logs to Admin only
            if (!Auth::hasRole('Admin')) {
                echo json_encode(['success' => false, 'message' => 'Access denied. Admin privileges required.']);
                exit;
            }

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
