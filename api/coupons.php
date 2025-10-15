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
                // Filter by status if provided, otherwise get all
                $status = $_GET['status'] ?? '';
                if ($status) {
                    $stmt = $db->prepare("SELECT * FROM coupons WHERE status = ? ORDER BY id DESC");
                    $stmt->bind_param("s", $status);
                } else {
                    $stmt = $db->prepare("SELECT * FROM coupons ORDER BY id DESC");
                }
                $stmt->execute();
                $result = $stmt->get_result();
                $coupons = [];

                while ($row = $result->fetch_assoc()) {
                    // Only include active coupons for billing
                    if (!$status || $row['status'] === 'Active') {
                        $coupons[] = $row;
                    }
                }

                echo json_encode(['success' => true, 'data' => $coupons]);
            } elseif ($action === 'calculate_time_bonus') {
                // Calculate time bonus discount
                $code = $_GET['code'] ?? '';
                $duration_minutes = intval($_GET['duration'] ?? 0);

                $stmt = $db->prepare("SELECT * FROM coupons WHERE code = ? AND status = 'Active' AND discount_type = 'time_bonus' LIMIT 1");
                $stmt->bind_param("s", $code);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    $base_minutes = intval($row['base_minutes']);
                    $bonus_minutes = intval($row['bonus_minutes']);
                    $loop_bonus = $row['loop_bonus'] == 1;

                    $total_bonus = 0;

                    if ($duration_minutes >= $base_minutes) {
                        if ($loop_bonus) {
                            // Loop bonus: calculate how many complete cycles and give bonus for each
                            $cycles = floor($duration_minutes / $base_minutes);
                            $total_bonus = $cycles * $bonus_minutes;
                        } else {
                            // No loop: only give bonus once
                            $total_bonus = $bonus_minutes;
                        }
                    }

                    echo json_encode([
                        'success' => true,
                        'data' => [
                            'bonus_minutes' => $total_bonus,
                            'base_minutes' => $base_minutes,
                            'bonus_per_cycle' => $bonus_minutes,
                            'loop_bonus' => $loop_bonus,
                            'cycles' => $loop_bonus ? floor($duration_minutes / $base_minutes) : ($duration_minutes >= $base_minutes ? 1 : 0)
                        ]
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Invalid time bonus coupon']);
                }
            } elseif ($action === 'validate') {
                // Validate coupon code
                $code = $_GET['code'] ?? '';
                $amount = floatval($_GET['amount'] ?? 0);

                $stmt = $db->prepare("SELECT * FROM coupons WHERE code = ? AND status = 'Active' LIMIT 1");
                $stmt->bind_param("s", $code);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    // Check validity dates
                    $today = date('Y-m-d');
                    if ($row['valid_from'] && $row['valid_from'] > $today) {
                        echo json_encode(['success' => false, 'message' => 'Coupon is not yet valid']);
                        exit;
                    }
                    if ($row['valid_to'] && $row['valid_to'] < $today) {
                        echo json_encode(['success' => false, 'message' => 'Coupon has expired']);
                        exit;
                    }

                    // Check usage limit
                    if ($row['usage_limit'] > 0 && $row['times_used'] >= $row['usage_limit']) {
                        echo json_encode(['success' => false, 'message' => 'Coupon usage limit reached']);
                        exit;
                    }

                    // Check minimum order amount
                    if ($amount < $row['min_order_amount']) {
                        echo json_encode(['success' => false, 'message' => "Minimum order amount is ₹{$row['min_order_amount']}"]);
                        exit;
                    }

                    // Calculate discount
                    $discount = 0;
                    if ($row['discount_type'] === 'percentage') {
                        $discount = ($amount * $row['discount_value']) / 100;
                    } elseif ($row['discount_type'] === 'flat') {
                        $discount = $row['discount_value'];
                    } elseif ($row['discount_type'] === 'time_bonus') {
                        // For time bonus, we need to calculate based on duration
                        // This will be handled in the frontend based on actual session duration
                        $discount = 0; // Will be calculated based on actual time played
                    }

                    $row['calculated_discount'] = $discount;
                    echo json_encode(['success' => true, 'data' => $row]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Invalid coupon code']);
                }
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);

            if ($action === 'create') {
                $stmt = $db->prepare("INSERT INTO coupons (name, code, description, discount_type, discount_value, base_minutes, bonus_minutes, loop_bonus, usage_limit, min_order_amount, valid_from, valid_to, branch_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->bind_param(
                    "ssssdiiiidssss",
                    $data['name'],
                    $data['code'],
                    $data['description'],
                    $data['discount_type'],
                    $data['discount_value'],
                    $data['base_minutes'],
                    $data['bonus_minutes'],
                    $data['loop_bonus'],
                    $data['usage_limit'],
                    $data['min_order_amount'],
                    $data['valid_from'],
                    $data['valid_to'],
                    $data['branch_id'],
                    $data['status']
                );

                if ($stmt->execute()) {
                    Auth::logActivity(Auth::userId(), 'coupon_create', "Created coupon: {$data['name']}");
                    echo json_encode(['success' => true, 'id' => $db->insert_id, 'message' => 'Coupon created successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to create coupon']);
                }
            } elseif ($action === 'update') {
                $id = intval($data['id']);
                $stmt = $db->prepare("UPDATE coupons SET name = ?, code = ?, description = ?, discount_type = ?, discount_value = ?, base_minutes = ?, bonus_minutes = ?, loop_bonus = ?, usage_limit = ?, min_order_amount = ?, valid_from = ?, valid_to = ?, status = ? WHERE id = ?");

                $stmt->bind_param(
                    "ssssdiiiidsssi",
                    $data['name'],
                    $data['code'],
                    $data['description'],
                    $data['discount_type'],
                    $data['discount_value'],
                    $data['base_minutes'],
                    $data['bonus_minutes'],
                    $data['loop_bonus'],
                    $data['usage_limit'],
                    $data['min_order_amount'],
                    $data['valid_from'],
                    $data['valid_to'],
                    $data['status'],
                    $id
                );

                if ($stmt->execute()) {
                    Auth::logActivity(Auth::userId(), 'coupon_update', "Updated coupon: {$data['name']}");
                    echo json_encode(['success' => true, 'message' => 'Coupon updated successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update coupon']);
                }
            }
            break;

        case 'DELETE':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $stmt = $db->prepare("DELETE FROM coupons WHERE id = ?");
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    Auth::logActivity(Auth::userId(), 'coupon_delete', "Deleted coupon ID: $id");
                    echo json_encode(['success' => true, 'message' => 'Coupon deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete coupon']);
                }
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
