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
                // Get all consoles with session information
                $status_filter = $_GET['status'] ?? '';
                $where_clause = '';
                $params = [];
                $param_types = '';

                if ($status_filter) {
                    $where_clause = "WHERE c.status = ?";
                    $params[] = $status_filter;
                    $param_types .= 's';
                }

                $stmt = $db->prepare("
                    SELECT c.*, 
                           gs.id as session_id,
                           gs.customer_name,
                           gs.customer_number,
                           gs.player_count,
                           gs.rate_type,
                           gs.start_time,
                           gs.status as session_status,
                           gs.total_amount,
                           gs.total_fandd_amount,
                           gs.pause_start_time,
                           gs.total_pause_duration,
                           CASE 
                               WHEN gs.status = 'paused' AND gs.pause_start_time IS NOT NULL THEN
                                   TIME_FORMAT(
                                       SEC_TO_TIME(
                                           TIMESTAMPDIFF(SECOND, gs.start_time, gs.pause_start_time) - COALESCE(gs.total_pause_duration, 0)
                                       ), 
                                       '%H:%i:%s'
                                   )
                               WHEN gs.status = 'active' THEN
                                   TIME_FORMAT(
                                       SEC_TO_TIME(
                                           TIMESTAMPDIFF(SECOND, gs.start_time, NOW()) - COALESCE(gs.total_pause_duration, 0)
                                       ), 
                                       '%H:%i:%s'
                                   )
                               ELSE '00:00:00'
                           END as formatted_time
                    FROM consoles c
                    LEFT JOIN gaming_sessions gs ON c.id = gs.console_id AND gs.status IN ('active', 'paused')
                    {$where_clause}
                    ORDER BY c.id DESC
                ");

                if (!empty($params)) {
                    $stmt->bind_param($param_types, ...$params);
                }

                $stmt->execute();
                $result = $stmt->get_result();
                $consoles = [];

                while ($row = $result->fetch_assoc()) {
                    // Group session data
                    if ($row['session_id']) {
                        $row['current_session'] = [
                            'id' => $row['session_id'],
                            'customer_name' => $row['customer_name'],
                            'customer_number' => $row['customer_number'],
                            'player_count' => $row['player_count'],
                            'rate_type' => $row['rate_type'],
                            'start_time' => $row['start_time'],
                            'total_amount' => $row['total_amount'],
                            'total_fandd_amount' => $row['total_fandd_amount'],
                            'formatted_time' => $row['formatted_time'],
                            'is_paused' => ($row['session_status'] === 'paused')
                        ];

                        // Fetch session items (Food & Drinks) for display on cards
                        $itemsStmt = $db->prepare("SELECT id, item_name, quantity, total_price FROM session_items WHERE session_id = ? ORDER BY id DESC");
                        $itemsStmt->bind_param("i", $row['session_id']);
                        $itemsStmt->execute();
                        $itemsRes = $itemsStmt->get_result();
                        $row['current_session']['items'] = $itemsRes->fetch_all(MYSQLI_ASSOC);
                    } else {
                        $row['current_session'] = null;
                    }

                    // Remove session fields from main object
                    unset(
                        $row['session_id'],
                        $row['customer_name'],
                        $row['customer_number'],
                        $row['player_count'],
                        $row['rate_type'],
                        $row['start_time'],
                        $row['session_status'],
                        $row['total_amount'],
                        $row['total_fandd_amount'],
                        $row['pause_start_time'],
                        $row['total_pause_duration'],
                        $row['formatted_time']
                    );

                    $consoles[] = $row;
                }

                echo json_encode(['success' => true, 'data' => $consoles]);
            } elseif ($action === 'get' && isset($_GET['id'])) {
                // Get single console
                $id = intval($_GET['id']);
                $stmt = $db->prepare("SELECT * FROM consoles WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    echo json_encode(['success' => true, 'data' => $row]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Console not found']);
                }
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);

            if ($action === 'create') {
                // Create new console
                $stmt = $db->prepare("INSERT INTO consoles (name, type, specifications, purchase_year, email, primary_user, location, has_plus_account, under_maintenance, branch_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->bind_param(
                    "sssississi",
                    $data['name'],
                    $data['type'],
                    $data['specifications'],
                    $data['purchase_year'],
                    $data['email'],
                    $data['primary_user'],
                    $data['location'],
                    $data['has_plus_account'],
                    $data['under_maintenance'],
                    $data['branch_id']
                );

                if ($stmt->execute()) {
                    Auth::logActivity(Auth::userId(), 'console_create', "Created console: {$data['name']}");
                    echo json_encode(['success' => true, 'id' => $db->insert_id, 'message' => 'Console created successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to create console']);
                }
            } elseif ($action === 'update') {
                // Update console
                $id = intval($data['id']);
                $stmt = $db->prepare("UPDATE consoles SET name = ?, type = ?, specifications = ?, purchase_year = ?, email = ?, primary_user = ?, location = ?, has_plus_account = ?, under_maintenance = ? WHERE id = ?");

                $stmt->bind_param(
                    "sssississi",
                    $data['name'],
                    $data['type'],
                    $data['specifications'],
                    $data['purchase_year'],
                    $data['email'],
                    $data['primary_user'],
                    $data['location'],
                    $data['has_plus_account'],
                    $data['under_maintenance'],
                    $id
                );

                if ($stmt->execute()) {
                    Auth::logActivity(Auth::userId(), 'console_update', "Updated console: {$data['name']}");
                    echo json_encode(['success' => true, 'message' => 'Console updated successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update console']);
                }
            }
            break;

        case 'DELETE':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $stmt = $db->prepare("DELETE FROM consoles WHERE id = ?");
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    Auth::logActivity(Auth::userId(), 'console_delete', "Deleted console ID: $id");
                    echo json_encode(['success' => true, 'message' => 'Console deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete console']);
                }
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
