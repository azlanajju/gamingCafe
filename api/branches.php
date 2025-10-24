<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

header('Content-Type: application/json');

// Log the incoming request
error_log("Branches API - Request received: " . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI']);
error_log("Branches API - GET params: " . json_encode($_GET));
error_log("Branches API - POST data: " . file_get_contents('php://input'));

Auth::require();
Auth::requireRole('Admin'); // Only admins can manage branches

$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

error_log("Branches API - Method: " . $method . ", Action: " . $action);

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
            } elseif ($action === 'get') {
                $id = intval($_GET['id']);
                $stmt = $db->prepare("SELECT b.*, u.full_name as manager_full_name FROM branches b LEFT JOIN users u ON b.manager_id = u.id WHERE b.id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $branch = $result->fetch_assoc();

                if ($branch) {
                    echo json_encode(['success' => true, 'data' => $branch]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Branch not found']);
                }
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
                $stmt = $db->prepare("INSERT INTO branches (name, location, address, contact, manager_name, timing, console_count, established_year, status, manager_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                if (!$stmt) {
                    echo json_encode(['success' => false, 'message' => 'Database prepare failed: ' . $db->error]);
                    exit;
                }

                // Ensure manager_id is properly handled
                $manager_id = !empty($data['manager_id']) ? intval($data['manager_id']) : null;

                // Convert numeric fields to integers
                $console_count = intval($data['console_count']);
                $established_year = intval($data['established_year']);

                $bind_result = $stmt->bind_param(
                    "ssssssiisi",
                    $data['name'],
                    $data['location'],
                    $data['address'],
                    $data['contact'],
                    $data['manager_name'],
                    $data['timing'],
                    $console_count,
                    $established_year,
                    $data['status'],
                    $manager_id
                );

                if (!$bind_result) {
                    echo json_encode(['success' => false, 'message' => 'Parameter binding failed: ' . $stmt->error]);
                    exit;
                }

                if ($stmt->execute()) {
                    try {
                        Auth::logActivity(Auth::userId(), 'branch_create', "Created branch: {$data['name']}");
                    } catch (Exception $e) {
                        // Log activity failed, but don't stop the process
                        error_log("Activity logging failed: " . $e->getMessage());
                    }
                    echo json_encode(['success' => true, 'id' => $db->insert_id, 'message' => 'Branch created successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to create branch: ' . $stmt->error]);
                }
            } elseif ($action === 'update') {
                try {
                    // Log the received data for debugging
                    error_log("Branch update request - Data: " . json_encode($data));
                    error_log("Branch update request - Method: " . $_SERVER['REQUEST_METHOD']);
                    error_log("Branch update request - Content-Type: " . ($_SERVER['CONTENT_TYPE'] ?? 'not set'));

                    $id = intval($data['id']);
                    error_log("Branch update - ID: " . $id);

                    $stmt = $db->prepare("UPDATE branches SET name = ?, location = ?, address = ?, contact = ?, manager_name = ?, timing = ?, console_count = ?, established_year = ?, status = ?, manager_id = ? WHERE id = ?");

                    if (!$stmt) {
                        $error_msg = 'Database prepare failed: ' . $db->error;
                        error_log("Branch update error: " . $error_msg);
                        echo json_encode(['success' => false, 'message' => $error_msg]);
                        exit;
                    }
                    error_log("Branch update - SQL prepare successful");

                    // Ensure manager_id is properly handled - convert empty string to null
                    $manager_id = (!empty($data['manager_id']) && $data['manager_id'] !== '') ? intval($data['manager_id']) : null;
                    error_log("Branch update - Manager ID: " . ($manager_id ?? 'NULL'));

                    // Convert numeric fields to integers
                    $console_count = intval($data['console_count']);
                    $established_year = intval($data['established_year']);
                    error_log("Branch update - Console Count: " . $console_count . ", Established Year: " . $established_year);

                    $bind_result = $stmt->bind_param(
                        "ssssssiisii",
                        $data['name'],
                        $data['location'],
                        $data['address'],
                        $data['contact'],
                        $data['manager_name'],
                        $data['timing'],
                        $console_count,
                        $established_year,
                        $data['status'],
                        $manager_id,
                        $id
                    );

                    if (!$bind_result) {
                        $error_msg = 'Parameter binding failed: ' . $stmt->error;
                        error_log("Branch update error: " . $error_msg);
                        echo json_encode(['success' => false, 'message' => $error_msg]);
                        exit;
                    }
                    error_log("Branch update - Parameter binding successful");

                    if ($stmt->execute()) {
                        error_log("Branch update - Execute successful");
                        echo json_encode(['success' => true, 'message' => 'Branch updated successfully']);
                    } else {
                        $error_msg = 'Failed to update branch: ' . $stmt->error;
                        error_log("Branch update error: " . $error_msg);
                        echo json_encode(['success' => false, 'message' => $error_msg]);
                    }
                } catch (Exception $e) {
                    $error_msg = "Branch update exception: " . $e->getMessage();
                    error_log($error_msg);
                    error_log("Branch update stack trace: " . $e->getTraceAsString());
                    echo json_encode(['success' => false, 'message' => $error_msg]);
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
    $error_msg = "API Exception: " . $e->getMessage();
    error_log($error_msg);
    error_log("API stack trace: " . $e->getTraceAsString());
    echo json_encode(['success' => false, 'message' => $error_msg]);
}
