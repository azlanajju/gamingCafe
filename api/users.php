<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

header('Content-Type: application/json');
Auth::require();
// Allow Super Admin, Admin and Manager roles to manage users
if (!Auth::hasRole('Admin') && !Auth::hasRole('Manager')) {
    echo json_encode(['success' => false, 'message' => 'Access denied. Admin or Manager privileges required.']);
    exit;
}

$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($method) {
        case 'GET':
            if ($action === 'list') {
                // For managers, only show users from their branch
                if (Auth::hasRole('Manager') && !Auth::hasRole('Admin')) {
                    $userBranchId = Auth::userBranchId();
                    $stmt = $db->prepare("SELECT u.id, u.full_name, u.username, u.email, u.phone, u.role, u.branch_id, u.status, b.name as branch_name FROM users u LEFT JOIN branches b ON u.branch_id = b.id WHERE u.branch_id = ? ORDER BY u.id DESC");
                    $stmt->bind_param("i", $userBranchId);
                } else {
                    // Super Admin and Admin can see all users
                    $stmt = $db->prepare("SELECT u.id, u.full_name, u.username, u.email, u.phone, u.role, u.branch_id, u.status, b.name as branch_name FROM users u LEFT JOIN branches b ON u.branch_id = b.id ORDER BY u.id DESC");
                }
                $stmt->execute();
                $result = $stmt->get_result();
                $users = [];

                while ($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }

                echo json_encode(['success' => true, 'data' => $users]);
            } elseif ($action === 'branches') {
                // Get list of branches for selection
                if (Auth::hasRole('Manager') && !Auth::hasRole('Admin')) {
                    // Managers can only see their own branch
                    $userBranchId = Auth::userBranchId();
                    $stmt = $db->prepare("SELECT id, name, location FROM branches WHERE status = 'Active' AND id = ? ORDER BY name");
                    $stmt->bind_param("i", $userBranchId);
                } else {
                    // Super Admin and Admin can see all branches
                    $stmt = $db->prepare("SELECT id, name, location FROM branches WHERE status = 'Active' ORDER BY name");
                }
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
                // Manager restrictions
                if (Auth::hasRole('Manager') && !Auth::hasRole('Admin')) {
                    // Managers can only create Staff and Manager roles
                    if (!in_array($data['role'], ['Staff', 'Manager'])) {
                        echo json_encode(['success' => false, 'message' => 'Managers can only create Staff and Manager roles']);
                        exit;
                    }

                    // Managers can only create users for their own branch
                    $userBranchId = Auth::userBranchId();
                    if ($data['branch_id'] != $userBranchId) {
                        echo json_encode(['success' => false, 'message' => 'Managers can only create users for their own branch']);
                        exit;
                    }
                }

                // Only Super Admin can create Super Admin users
                $userRole = Auth::userRole();
                if ($data['role'] === 'Super Admin' && $userRole !== 'Super Admin') {
                    echo json_encode(['success' => false, 'message' => 'Only Super Admin can create Super Admin users']);
                    exit;
                }

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

                // Manager restrictions for updates
                if (Auth::hasRole('Manager') && !Auth::hasRole('Admin')) {
                    // Check if the user being updated belongs to manager's branch
                    $checkStmt = $db->prepare("SELECT branch_id FROM users WHERE id = ?");
                    $checkStmt->bind_param("i", $id);
                    $checkStmt->execute();
                    $userBranch = $checkStmt->get_result()->fetch_assoc();

                    $userBranchId = Auth::userBranchId();
                    if (!$userBranch || $userBranch['branch_id'] != $userBranchId) {
                        echo json_encode(['success' => false, 'message' => 'Managers can only update users from their own branch']);
                        exit;
                    }

                    // Managers can only update to Staff and Manager roles
                    if (!in_array($data['role'], ['Staff', 'Manager'])) {
                        echo json_encode(['success' => false, 'message' => 'Managers can only assign Staff and Manager roles']);
                        exit;
                    }

                    // Ensure branch_id remains the same (managers can't change branch)
                    $data['branch_id'] = $userBranchId;
                }

                // Only Super Admin can assign Super Admin role
                $userRole = Auth::userRole();
                if ($data['role'] === 'Super Admin' && $userRole !== 'Super Admin') {
                    echo json_encode(['success' => false, 'message' => 'Only Super Admin can assign Super Admin role']);
                    exit;
                }

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

                // Manager restrictions for deletion
                if (Auth::hasRole('Manager') && !Auth::hasRole('Admin')) {
                    // Check if the user being deleted belongs to manager's branch
                    $checkStmt = $db->prepare("SELECT branch_id FROM users WHERE id = ?");
                    $checkStmt->bind_param("i", $id);
                    $checkStmt->execute();
                    $userBranch = $checkStmt->get_result()->fetch_assoc();

                    $userBranchId = Auth::userBranchId();
                    if (!$userBranch || $userBranch['branch_id'] != $userBranchId) {
                        echo json_encode(['success' => false, 'message' => 'Managers can only delete users from their own branch']);
                        exit;
                    }
                }

                // Prevent deletion of Super Admin users (only Super Admin can delete Super Admin)
                $checkStmt = $db->prepare("SELECT role FROM users WHERE id = ?");
                $checkStmt->bind_param("i", $id);
                $checkStmt->execute();
                $targetUser = $checkStmt->get_result()->fetch_assoc();
                $userRole = Auth::userRole();
                if ($targetUser && $targetUser['role'] === 'Super Admin' && $userRole !== 'Super Admin') {
                    echo json_encode(['success' => false, 'message' => 'Only Super Admin can delete Super Admin users']);
                    exit;
                }

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
