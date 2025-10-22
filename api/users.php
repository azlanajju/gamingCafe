<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

header('Content-Type: application/json');
Auth::require();
Auth::requireRole('Admin'); // Only admins can manage users

$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($method) {
        case 'GET':
            if ($action === 'list') {
                $stmt = $db->prepare("SELECT u.id, u.full_name, u.username, u.email, u.phone, u.role, u.branch_id, u.status, b.name as branch_name FROM users u LEFT JOIN branches b ON u.branch_id = b.id ORDER BY u.id DESC");
                $stmt->execute();
                $result = $stmt->get_result();
                $users = [];

                while ($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }

                echo json_encode(['success' => true, 'data' => $users]);
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
                // Check if username or email already exists
                $checkStmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                $checkStmt->bind_param("ss", $data['username'], $data['email']);
                $checkStmt->execute();
                if ($checkStmt->get_result()->num_rows > 0) {
                    echo json_encode(['success' => false, 'message' => 'Username or email already exists']);
                    exit;
                }

                $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

                $stmt = $db->prepare("INSERT INTO users (full_name, username, email, phone, password, role, branch_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->bind_param(
                    "ssssssis",
                    $data['full_name'],
                    $data['username'],
                    $data['email'],
                    $data['phone'],
                    $hashedPassword,
                    $data['role'],
                    $data['branch_id'],
                    $data['status']
                );

                if ($stmt->execute()) {
                    Auth::logActivity(Auth::userId(), 'user_create', "Created user: {$data['username']}");
                    echo json_encode(['success' => true, 'id' => $db->insert_id, 'message' => 'User created successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to create user']);
                }
            } elseif ($action === 'update') {
                $id = intval($data['id']);

                if (isset($data['password']) && !empty($data['password'])) {
                    // Update with password
                    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
                    $stmt = $db->prepare("UPDATE users SET full_name = ?, username = ?, email = ?, phone = ?, password = ?, role = ?, branch_id = ?, status = ? WHERE id = ?");
                    $stmt->bind_param(
                        "ssssssisi",
                        $data['full_name'],
                        $data['username'],
                        $data['email'],
                        $data['phone'],
                        $hashedPassword,
                        $data['role'],
                        $data['branch_id'],
                        $data['status'],
                        $id
                    );
                } else {
                    // Update without password
                    $stmt = $db->prepare("UPDATE users SET full_name = ?, username = ?, email = ?, phone = ?, role = ?, branch_id = ?, status = ? WHERE id = ?");
                    $stmt->bind_param(
                        "sssssisi",
                        $data['full_name'],
                        $data['username'],
                        $data['email'],
                        $data['phone'],
                        $data['role'],
                        $data['branch_id'],
                        $data['status'],
                        $id
                    );
                }

                if ($stmt->execute()) {
                    Auth::logActivity(Auth::userId(), 'user_update', "Updated user: {$data['username']}");
                    echo json_encode(['success' => true, 'message' => 'User updated successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update user']);
                }
            }
            break;

        case 'DELETE':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);

                // Prevent self-deletion
                if ($id == Auth::userId()) {
                    echo json_encode(['success' => false, 'message' => 'Cannot delete your own account']);
                    exit;
                }

                $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    Auth::logActivity(Auth::userId(), 'user_delete', "Deleted user ID: $id");
                    echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
                }
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
