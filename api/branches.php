<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

header('Content-Type: application/json');
Auth::require();
Auth::requireRole('Admin'); // Only admins can manage branches

$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($method) {
        case 'GET':
            if ($action === 'list') {
                $stmt = $db->prepare("SELECT b.*, u.full_name as manager_full_name FROM branches b LEFT JOIN users u ON b.manager_id = u.id ORDER BY b.id DESC");
                $stmt->execute();
                $result = $stmt->get_result();
                $branches = [];

                while ($row = $result->fetch_assoc()) {
                    $branches[] = $row;
                }

                echo json_encode(['success' => true, 'data' => $branches]);
            } elseif ($action === 'managers') {
                // Get list of users who can be managers (Admin or Manager role)
                $stmt = $db->prepare("SELECT id, full_name, username FROM users WHERE role IN ('Admin', 'Manager') AND status = 'Active' ORDER BY full_name");
                $stmt->execute();
                $result = $stmt->get_result();
                $managers = [];

                while ($row = $result->fetch_assoc()) {
                    $managers[] = $row;
                }

                echo json_encode(['success' => true, 'data' => $managers]);
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);

            if ($action === 'create') {
                $stmt = $db->prepare("INSERT INTO branches (name, location, address, contact, manager_name, manager_id, timing, console_count, established_year, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->bind_param(
                    "ssssssiisi",
                    $data['name'],
                    $data['location'],
                    $data['address'],
                    $data['contact'],
                    $data['manager_name'],
                    $data['manager_id'],
                    $data['timing'],
                    $data['console_count'],
                    $data['established_year'],
                    $data['status']
                );

                if ($stmt->execute()) {
                    Auth::logActivity(Auth::userId(), 'branch_create', "Created branch: {$data['name']}");
                    echo json_encode(['success' => true, 'id' => $db->insert_id, 'message' => 'Branch created successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to create branch']);
                }
            } elseif ($action === 'update') {
                $id = intval($data['id']);
                $stmt = $db->prepare("UPDATE branches SET name = ?, location = ?, address = ?, contact = ?, manager_name = ?, manager_id = ?, timing = ?, console_count = ?, established_year = ?, status = ? WHERE id = ?");

                $stmt->bind_param(
                    "ssssssiisi",
                    $data['name'],
                    $data['location'],
                    $data['address'],
                    $data['contact'],
                    $data['manager_name'],
                    $data['manager_id'],
                    $data['timing'],
                    $data['console_count'],
                    $data['established_year'],
                    $data['status'],
                    $id
                );

                if ($stmt->execute()) {
                    Auth::logActivity(Auth::userId(), 'branch_update', "Updated branch: {$data['name']}");
                    echo json_encode(['success' => true, 'message' => 'Branch updated successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update branch']);
                }
            }
            break;

        case 'DELETE':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);

                // Prevent deletion of default branch
                if ($id == 1) {
                    echo json_encode(['success' => false, 'message' => 'Cannot delete the default branch']);
                    exit;
                }

                $stmt = $db->prepare("DELETE FROM branches WHERE id = ?");
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    Auth::logActivity(Auth::userId(), 'branch_delete', "Deleted branch ID: $id");
                    echo json_encode(['success' => true, 'message' => 'Branch deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete branch']);
                }
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
