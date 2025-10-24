<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

header('Content-Type: application/json');
Auth::require();

$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($method) {
        case 'GET':
            if ($action === 'list') {
                $startDate = $_GET['start_date'] ?? null;
                $endDate = $_GET['end_date'] ?? null;
                $consoleId = $_GET['console_id'] ?? null;
                $paymentMethod = $_GET['payment_method'] ?? null;
                $branchId = $_GET['branch_id'] ?? null;

                // Get current user info for data isolation
                $currentUserId = Auth::userId();
                $userRole = Auth::userRole();

                $query = "SELECT t.*, u.full_name as user_name, c.name as console_name, b.name as branch_name, b.location as branch_location
                         FROM transactions t 
                         LEFT JOIN users u ON t.created_by = u.id 
                         LEFT JOIN consoles c ON t.console_id = c.id
                         LEFT JOIN branches b ON t.branch_id = b.id
                         WHERE 1=1";

                // Add data isolation for non-Admin users
                if ($userRole !== 'Admin') {
                    if ($userRole === 'Manager') {
                        // For Managers: show transactions from their branch that are NOT created by Admins
                        $query .= " AND t.branch_id = ? AND t.created_by NOT IN (SELECT id FROM users WHERE role = 'Admin')";
                        $userBranchId = Auth::userBranchId();
                        $params[] = $userBranchId;
                        $types .= "i";
                    } else {
                        // For Staff: show only transactions they created
                        $query .= " AND t.created_by = ?";
                        $params[] = $currentUserId;
                        $types .= "i";
                    }
                } else {
                    // For Admins: if branch_id is specified, filter by it
                    if ($branchId) {
                        $query .= " AND t.branch_id = ?";
                        $params[] = $branchId;
                        $types .= "i";
                    }
                }

                $params = $params ?? [];
                $types = $types ?? "";

                // Add debugging
                error_log("Transactions API Query: " . $query);
                error_log("Transactions API Params: " . json_encode($params));
                error_log("Transactions API Types: " . $types);
                error_log("Transactions API Branch ID: " . $branchId);

                if ($startDate) {
                    $query .= " AND DATE(t.created_at) >= ?";
                    $params[] = $startDate;
                    $types .= "s";
                }

                if ($endDate) {
                    $query .= " AND DATE(t.created_at) <= ?";
                    $params[] = $endDate;
                    $types .= "s";
                }

                if ($consoleId) {
                    $query .= " AND t.console_id = ?";
                    $params[] = $consoleId;
                    $types .= "i";
                }

                if ($paymentMethod) {
                    $query .= " AND t.payment_method = ?";
                    $params[] = $paymentMethod;
                    $types .= "s";
                }

                if ($branchId) {
                    $query .= " AND t.branch_id = ?";
                    $params[] = $branchId;
                    $types .= "i";
                }

                $query .= " ORDER BY t.created_at DESC";

                $stmt = $db->prepare($query);

                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }

                $stmt->execute();
                $result = $stmt->get_result();
                $transactions = [];

                while ($row = $result->fetch_assoc()) {
                    // Format the transaction data
                    $row['formatted_total'] = '₹' . number_format($row['total_amount'], 2);
                    $row['formatted_gaming'] = '₹' . number_format($row['gaming_amount'], 2);
                    $row['formatted_fandd'] = '₹' . number_format($row['fandd_amount'], 2);
                    $row['formatted_tax'] = '₹' . number_format($row['tax_amount'], 2);
                    $row['formatted_duration'] = $row['total_duration_minutes'] . ' min';

                    // Format dates
                    $row['formatted_created'] = date('M d, Y H:i', strtotime($row['created_at']));
                    $row['formatted_start'] = date('H:i', strtotime($row['start_time']));
                    $row['formatted_end'] = date('H:i', strtotime($row['end_time']));

                    // Parse payment details for split payments
                    if ($row['payment_details'] && $row['payment_method'] === 'cash_upi') {
                        $paymentDetails = json_decode($row['payment_details'], true);
                        if ($paymentDetails) {
                            $row['payment_breakdown'] = [
                                'cash_amount' => '₹' . number_format($paymentDetails['cash_amount'] ?? 0, 2),
                                'upi_amount' => '₹' . number_format($paymentDetails['upi_amount'] ?? 0, 2),
                                'total_amount' => '₹' . number_format($paymentDetails['total_amount'] ?? $row['total_amount'], 2)
                            ];
                        }
                    }

                    $transactions[] = $row;
                }

                echo json_encode(['success' => true, 'data' => $transactions]);
            } elseif ($action === 'stats') {
                // Get transaction statistics
                $period = $_GET['period'] ?? 'daily'; // daily, monthly, yearly

                $dateCondition = "DATE(created_at) = CURDATE()";
                if ($period === 'monthly') {
                    $dateCondition = "MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
                } elseif ($period === 'yearly') {
                    $dateCondition = "YEAR(created_at) = YEAR(CURDATE())";
                }

                $stmt = $db->prepare("SELECT 
                    COUNT(*) as total_transactions,
                    COUNT(DISTINCT customer_name) as total_customers,
                    SUM(gaming_amount) as total_gaming_revenue,
                    SUM(fandd_amount) as total_fandd_revenue,
                    SUM(total_amount) as total_revenue,
                    SUM(tax_amount) as total_tax_collected,
                    AVG(total_amount) as avg_transaction,
                    AVG(total_duration_minutes) as avg_session_duration
                FROM transactions 
                WHERE $dateCondition");

                $stmt->execute();
                $stats = $stmt->get_result()->fetch_assoc();

                echo json_encode(['success' => true, 'data' => $stats]);
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);

            if ($action === 'create') {
                // Create new transaction
                $stmt = $db->prepare("INSERT INTO transactions (session_id, customer_name, console_id, console_name, duration, gaming_amount, food_amount, total_amount, discount_amount, payment_method, payment_details, coupon_code, transaction_date, created_by, branch_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->bind_param(
                    "isisidddsssssii",
                    $data['session_id'],
                    $data['customer_name'],
                    $data['console_id'],
                    $data['console_name'],
                    $data['duration'],
                    $data['gaming_amount'],
                    $data['food_amount'],
                    $data['total_amount'],
                    $data['discount_amount'],
                    $data['payment_method'],
                    $data['payment_details'],
                    $data['coupon_code'],
                    $data['transaction_date'],
                    Auth::userId(),
                    $data['branch_id']
                );

                if ($stmt->execute()) {
                    $transactionId = $db->insert_id;

                    // Add transaction items if provided
                    if (isset($data['items']) && is_array($data['items'])) {
                        $itemStmt = $db->prepare("INSERT INTO transaction_items (transaction_id, item_type, item_id, item_name, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)");

                        foreach ($data['items'] as $item) {
                            $itemStmt->bind_param(
                                "isisdd",
                                $transactionId,
                                $item['item_type'],
                                $item['item_id'],
                                $item['item_name'],
                                $item['quantity'],
                                $item['unit_price'],
                                $item['total_price']
                            );
                            $itemStmt->execute();
                        }
                    }

                    // Update coupon usage if coupon was used
                    if (!empty($data['coupon_code'])) {
                        $db->query("UPDATE coupons SET times_used = times_used + 1 WHERE code = '{$data['coupon_code']}'");
                    }

                    Auth::logActivity(Auth::userId(), 'transaction_create', "Created transaction for: {$data['customer_name']}");
                    echo json_encode(['success' => true, 'id' => $transactionId, 'message' => 'Transaction created successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to create transaction']);
                }
            } elseif ($action === 'update_amount') {
                // Update transaction amount
                $transactionId = $data['transaction_id'] ?? null;
                $newAmount = $data['new_amount'] ?? null;

                if (!$transactionId || $newAmount === null) {
                    echo json_encode(['success' => false, 'message' => 'Transaction ID and new amount are required']);
                    break;
                }

                // Validate new amount
                if (!is_numeric($newAmount) || $newAmount < 0) {
                    echo json_encode(['success' => false, 'message' => 'Invalid amount']);
                    break;
                }

                // Update transaction amount
                $stmt = $db->prepare("UPDATE transactions SET total_amount = ? WHERE id = ?");
                $stmt->bind_param("di", $newAmount, $transactionId);

                if ($stmt->execute()) {
                    // Log the activity
                    $logStmt = $db->prepare("INSERT INTO activity_logs (user_id, action, description, created_at) VALUES (?, 'update', ?, CURRENT_TIMESTAMP)");
                    $description = "Updated transaction amount to ₹" . number_format($newAmount, 2);
                    $logStmt->bind_param("is", Auth::userId(), $description);
                    $logStmt->execute();

                    echo json_encode(['success' => true, 'message' => 'Transaction amount updated successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update transaction amount']);
                }
            }
            break;

        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            $action = $data['action'] ?? '';

            if ($action === 'delete') {
                $transactionId = $data['transaction_id'] ?? null;

                if (!$transactionId) {
                    echo json_encode(['success' => false, 'message' => 'Transaction ID is required']);
                    break;
                }

                // Check if transaction exists
                $stmt = $db->prepare("SELECT * FROM transactions WHERE id = ?");
                $stmt->bind_param("i", $transactionId);
                $stmt->execute();
                $transaction = $stmt->get_result()->fetch_assoc();

                if (!$transaction) {
                    echo json_encode(['success' => false, 'message' => 'Transaction not found']);
                    break;
                }

                // Start transaction for data integrity
                $db->begin_transaction();

                try {
                    // Delete transaction items first (if any)
                    $stmt = $db->prepare("DELETE FROM transaction_items WHERE transaction_id = ?");
                    $stmt->bind_param("i", $transactionId);
                    $stmt->execute();

                    // Delete the transaction
                    $stmt = $db->prepare("DELETE FROM transactions WHERE id = ?");
                    $stmt->bind_param("i", $transactionId);

                    if ($stmt->execute()) {
                        // Log the activity
                        $logStmt = $db->prepare("INSERT INTO activity_logs (user_id, action, description, created_at) VALUES (?, 'delete', ?, CURRENT_TIMESTAMP)");
                        $description = "Deleted transaction ID: {$transactionId} for customer: {$transaction['customer_name']} (₹" . number_format($transaction['total_amount'], 2) . ")";
                        $logStmt->bind_param("is", Auth::userId(), $description);
                        $logStmt->execute();

                        $db->commit();
                        echo json_encode(['success' => true, 'message' => 'Transaction deleted successfully']);
                    } else {
                        throw new Exception('Failed to delete transaction');
                    }
                } catch (Exception $e) {
                    $db->rollback();
                    echo json_encode(['success' => false, 'message' => 'Failed to delete transaction: ' . $e->getMessage()]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid delete action']);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
