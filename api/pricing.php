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
                // Get all pricing data
                $rate_type = $_GET['rate_type'] ?? '';

                // Check if user is a manager/staff and should be restricted to their branch
                $userBranchId = Auth::userBranchId();
                $isManagerRestricted = Auth::isManagerRestricted();

                $where_conditions = [];
                $params = [];
                $param_types = '';

                if ($rate_type) {
                    $where_conditions[] = "rate_type = ?";
                    $params[] = $rate_type;
                    $param_types .= 's';
                }

                if ($isManagerRestricted && $userBranchId) {
                    $where_conditions[] = "branch_id = ?";
                    $params[] = $userBranchId;
                    $param_types .= 'i';
                }

                $where_clause = '';
                if (!empty($where_conditions)) {
                    $where_clause = "WHERE " . implode(' AND ', $where_conditions);
                }

                $stmt = $db->prepare("
                    SELECT * FROM pricing 
                    {$where_clause}
                    ORDER BY rate_type, player_count ASC
                ");

                if (!empty($params)) {
                    $stmt->bind_param($param_types, ...$params);
                }

                $stmt->execute();
                $result = $stmt->get_result();
                $pricing = [];

                while ($row = $result->fetch_assoc()) {
                    $pricing[] = $row;
                }

                echo json_encode(['success' => true, 'data' => $pricing]);
            } elseif ($action === 'get' && isset($_GET['id'])) {
                // Get single pricing entry
                $id = intval($_GET['id']);
                $stmt = $db->prepare("SELECT * FROM pricing WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $pricing = $result->fetch_assoc();

                if ($pricing) {
                    echo json_encode(['success' => true, 'data' => $pricing]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Pricing entry not found']);
                }
            } elseif ($action === 'settings') {
                // Get settings
                $stmt = $db->prepare("SELECT * FROM settings WHERE branch_id = 1");
                $stmt->execute();
                $result = $stmt->get_result();
                $settings = [];

                while ($row = $result->fetch_assoc()) {
                    $settings[$row['setting_key']] = $row['setting_value'];
                }

                echo json_encode(['success' => true, 'data' => $settings]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
            }
            break;

        case 'POST':
            if ($action === 'create') {
                // Create new pricing entry
                $input = json_decode(file_get_contents('php://input'), true);

                $rate_type = $input['rate_type'] ?? '';
                $player_count = intval($input['player_count'] ?? 0);
                $duration_15 = floatval($input['duration_15'] ?? 0);
                $duration_30 = floatval($input['duration_30'] ?? 0);
                $duration_45 = floatval($input['duration_45'] ?? 0);
                $duration_60 = floatval($input['duration_60'] ?? 0);
                $branch_id = intval($input['branch_id'] ?? 1);

                // Check if user is a manager/staff and should be restricted to their branch
                $userBranchId = Auth::userBranchId();
                $isManagerRestricted = Auth::isManagerRestricted();

                if ($isManagerRestricted && $userBranchId) {
                    // Manager/Staff can only create pricing for their branch
                    $branch_id = $userBranchId;
                }

                if (!$rate_type || !$player_count) {
                    echo json_encode(['success' => false, 'message' => 'Rate type and player count are required']);
                    break;
                }

                $stmt = $db->prepare("
                    INSERT INTO pricing (rate_type, player_count, duration_15, duration_30, duration_45, duration_60, branch_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->bind_param("siddddi", $rate_type, $player_count, $duration_15, $duration_30, $duration_45, $duration_60, $branch_id);

                if ($stmt->execute()) {
                    $pricing_id = $db->insert_id;
                    echo json_encode(['success' => true, 'message' => 'Pricing entry created successfully', 'data' => ['id' => $pricing_id]]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to create pricing entry']);
                }
            } elseif ($action === 'update' && isset($_GET['id'])) {
                // Update existing pricing entry
                $id = intval($_GET['id']);
                $input = json_decode(file_get_contents('php://input'), true);

                $rate_type = $input['rate_type'] ?? '';
                $player_count = intval($input['player_count'] ?? 0);
                $duration_15 = floatval($input['duration_15'] ?? 0);
                $duration_30 = floatval($input['duration_30'] ?? 0);
                $duration_45 = floatval($input['duration_45'] ?? 0);
                $duration_60 = floatval($input['duration_60'] ?? 0);

                if (!$rate_type || !$player_count) {
                    echo json_encode(['success' => false, 'message' => 'Rate type and player count are required']);
                    break;
                }

                $stmt = $db->prepare("
                    UPDATE pricing 
                    SET rate_type = ?, player_count = ?, duration_15 = ?, duration_30 = ?, duration_45 = ?, duration_60 = ? 
                    WHERE id = ?
                ");
                $stmt->bind_param("siddddi", $rate_type, $player_count, $duration_15, $duration_30, $duration_45, $duration_60, $id);

                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Pricing entry updated successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update pricing entry']);
                }
            } elseif ($action === 'update_settings') {
                // Update settings
                $input = json_decode(file_get_contents('php://input'), true);

                $stmt = $db->prepare("
                    INSERT INTO settings (branch_id, setting_key, setting_value) 
                    VALUES (1, ?, ?)
                    ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
                ");

                $updated = 0;
                foreach ($input as $key => $value) {
                    $stmt->bind_param("ss", $key, $value);
                    if ($stmt->execute()) {
                        $updated++;
                    }
                }

                echo json_encode(['success' => true, 'message' => "Updated $updated settings"]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
            }
            break;

        case 'DELETE':
            if ($action === 'delete' && isset($_GET['id'])) {
                // Delete pricing entry
                $id = intval($_GET['id']);
                $stmt = $db->prepare("DELETE FROM pricing WHERE id = ?");
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Pricing entry deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete pricing entry']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
