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
                $selectedBranchId = $_GET['branch_id'] ?? null; // From topbar dropdown

                // Check if user is a manager and should be restricted to their branch
                $userBranchId = Auth::userBranchId();
                $isManagerRestricted = Auth::isManagerRestricted();
                $userRole = Auth::userRole();

                // Determine which branch to filter by
                $branchCondition = '';
                $branchParams = [];
                $branchParamTypes = '';

                // Priority: Admin can select any branch via dropdown, Manager restricted to their branch
                if ($userRole === 'Admin' && $selectedBranchId) {
                    // Admin selected a specific branch from dropdown
                    $branchCondition = " AND c.branch_id = ?";
                    $branchParams[] = $selectedBranchId;
                    $branchParamTypes = 'i';
                } elseif ($isManagerRestricted && $userBranchId) {
                    // Manager restricted to their branch
                    $branchCondition = " AND c.branch_id = ?";
                    $branchParams[] = $userBranchId;
                    $branchParamTypes = 'i';
                }

                if ($status) {
                    $query = "SELECT c.*, b.name as branch_name, b.location as branch_location FROM coupons c LEFT JOIN branches b ON c.branch_id = b.id WHERE c.status = ?";
                    $params = [$status];
                    $param_types = 's';

                    if ($branchCondition) {
                        $query .= $branchCondition;
                        $params = array_merge($params, $branchParams);
                        $param_types .= $branchParamTypes;
                    }

                    $query .= " ORDER BY c.id DESC";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param($param_types, ...$params);
                } else {
                    $query = "SELECT c.*, b.name as branch_name, b.location as branch_location FROM coupons c LEFT JOIN branches b ON c.branch_id = b.id";

                    if ($branchCondition) {
                        $query .= " WHERE 1=1" . $branchCondition;
                        $query .= " ORDER BY c.id DESC";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param($branchParamTypes, ...$branchParams);
                    } else {
                        $query .= " ORDER BY c.id DESC";
                        $stmt = $db->prepare($query);
                    }
                }

                // Add debugging
                error_log("Coupons API Query: " . $query);
                error_log("Coupons API Params: " . json_encode($branchParams));
                error_log("Coupons API Types: " . $branchParamTypes);
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
                // Calculate time bonus discount with proper pricing
                $code = $_GET['code'] ?? '';
                $duration_minutes = intval($_GET['duration'] ?? 0);
                $player_count = intval($_GET['player_count'] ?? 1);
                $rate_type = $_GET['rate_type'] ?? 'regular';

                $stmt = $db->prepare("SELECT * FROM coupons WHERE code = ? AND status = 'Active' AND discount_type = 'time_bonus' LIMIT 1");
                $stmt->bind_param("s", $code);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    $base_minutes = intval($row['base_minutes']);
                    $bonus_minutes = intval($row['bonus_minutes']);
                    $loop_bonus = $row['loop_bonus'] == 1;

                    $total_bonus_minutes = 0;
                    $discount_amount = 0;

                    if ($duration_minutes >= $base_minutes) {
                        if ($loop_bonus) {
                            // Loop bonus: calculate how many complete cycles and give bonus for each
                            $cycles = floor($duration_minutes / $base_minutes);
                            $total_bonus_minutes = $cycles * $bonus_minutes;
                        } else {
                            // No loop: only give bonus once
                            $total_bonus_minutes = $bonus_minutes;
                        }

                        // Calculate the discount amount based on pricing table
                        $discount_amount = calculateTimeBonusDiscount($db, $total_bonus_minutes, $player_count, $rate_type);
                    }

                    echo json_encode([
                        'success' => true,
                        'data' => [
                            'bonus_minutes' => $total_bonus_minutes,
                            'discount_amount' => $discount_amount,
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
            } elseif ($action === 'branches') {
                // Get list of branches for selection
                $stmt = $db->prepare("SELECT id, name, location FROM branches WHERE status = 'Active' ORDER BY name");
                $stmt->execute();
                $result = $stmt->get_result();
                $branches = [];
                while ($row = $result->fetch_assoc()) {
                    $branches[] = $row;
                }
                echo json_encode(['success' => true, 'data' => $branches]);
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);

            if ($action === 'create') {
                // Check if user is a manager and should be restricted to their branch
                $userBranchId = Auth::userBranchId();
                $isManagerRestricted = Auth::isManagerRestricted();

                if ($isManagerRestricted && $userBranchId) {
                    // Manager can only create coupons for their branch
                    $data['branch_id'] = $userBranchId;
                }

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

// Function to calculate time bonus discount based on pricing table
function calculateTimeBonusDiscount($db, $bonus_minutes, $player_count, $rate_type)
{
    if ($bonus_minutes <= 0) {
        return 0;
    }

    // Get pricing from database
    $stmt = $db->prepare("
        SELECT * FROM pricing 
        WHERE rate_type = ? AND player_count = ?
    ");
    $stmt->bind_param("si", $rate_type, $player_count);
    $stmt->execute();
    $pricing = $stmt->get_result()->fetch_assoc();

    if (!$pricing) {
        // Fallback: return 0 if no pricing found
        return 0;
    }

    // Calculate discount amount based on duration brackets
    $discount_amount = 0;

    // Handle different duration brackets for bonus minutes
    if ($bonus_minutes <= 15) {
        $discount_amount = $pricing['duration_15'] ?? 0;
    } elseif ($bonus_minutes <= 30) {
        $discount_amount = $pricing['duration_30'] ?? 0;
    } elseif ($bonus_minutes <= 45) {
        $discount_amount = $pricing['duration_45'] ?? 0;
    } else {
        $discount_amount = $pricing['duration_60'] ?? 0;

        // For bonus minutes longer than 60 minutes, add additional hourly charges
        if ($bonus_minutes > 60) {
            $additional_hours = ceil(($bonus_minutes - 60) / 60);
            $hourly_rate = $pricing['duration_60'] ?? 0;
            $additional_amount = $additional_hours * $hourly_rate;
            $discount_amount += $additional_amount;
        }
    }

    return $discount_amount;
}
