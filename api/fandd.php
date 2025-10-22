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
                // Get all food and drinks with optional category and branch filters
                $category_filter = $_GET['category'] ?? '';
                $branch_filter = $_GET['branch'] ?? '';

                // Check if user is a manager and should be restricted to their branch
                $userBranchId = Auth::userBranchId();
                $isManagerRestricted = Auth::isManagerRestricted();

                $query = "
                    SELECT f.*, b.name as branch_name, b.location as branch_location
                    FROM food_and_drinks f
                    LEFT JOIN branches b ON f.branch_id = b.id
                    WHERE 1=1
                ";
                $params = [];
                $param_types = '';

                if ($category_filter) {
                    $query .= " AND f.category = ?";
                    $params[] = $category_filter;
                    $param_types .= 's';
                }

                if ($branch_filter) {
                    $query .= " AND f.branch_id = ?";
                    $params[] = intval($branch_filter);
                    $param_types .= 'i';
                } elseif ($isManagerRestricted && $userBranchId) {
                    // Manager can only see items from their branch
                    $query .= " AND f.branch_id = ?";
                    $params[] = $userBranchId;
                    $param_types .= 'i';
                }

                $query .= " ORDER BY f.category, f.name ASC";

                $stmt = $db->prepare($query);

                if (!empty($params)) {
                    $stmt->bind_param($param_types, ...$params);
                }

                $stmt->execute();
                $result = $stmt->get_result();
                $items = [];

                while ($row = $result->fetch_assoc()) {
                    $items[] = $row;
                }

                echo json_encode(['success' => true, 'data' => $items]);
            } elseif ($action === 'get' && isset($_GET['id'])) {
                // Get single item
                $id = intval($_GET['id']);
                $stmt = $db->prepare("SELECT f.*, b.name as branch_name, b.location as branch_location FROM food_and_drinks f LEFT JOIN branches b ON f.branch_id = b.id WHERE f.id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $item = $result->fetch_assoc();

                if ($item) {
                    echo json_encode(['success' => true, 'data' => $item]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Item not found']);
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
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
            }
            break;

        case 'POST':
            if ($action === 'create') {
                // Create new item
                $input = json_decode(file_get_contents('php://input'), true);

                // Debug: Log the input data
                error_log('F&D API Create - Input data: ' . json_encode($input));

                $name = $input['name'] ?? '';
                $price = floatval($input['price'] ?? 0);
                $stock = intval($input['stock'] ?? 0);
                $category = $input['category'] ?? 'other';
                $description = $input['description'] ?? '';
                $is_available = intval($input['is_available'] ?? 1);
                $branch_id = intval($input['branch_id'] ?? 1);

                // Check if user is a manager/staff and should be restricted to their branch
                $userBranchId = Auth::userBranchId();
                $isManagerRestricted = Auth::isManagerRestricted();

                if ($isManagerRestricted && $userBranchId) {
                    // Manager/Staff can only create items for their branch
                    $branch_id = $userBranchId;
                }

                if (!$name || $price <= 0) {
                    echo json_encode(['success' => false, 'message' => 'Name and price are required']);
                    break;
                }

                $stmt = $db->prepare("
                    INSERT INTO food_and_drinks (name, price, stock, category, description, is_available, branch_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");

                if (!$stmt) {
                    echo json_encode(['success' => false, 'message' => 'Database prepare failed: ' . $db->error]);
                    break;
                }

                $bind_result = $stmt->bind_param("sdisssi", $name, $price, $stock, $category, $description, $is_available, $branch_id);

                if (!$bind_result) {
                    echo json_encode(['success' => false, 'message' => 'Bind param failed: ' . $stmt->error]);
                    break;
                }

                if ($stmt->execute()) {
                    $item_id = $db->insert_id;
                    echo json_encode(['success' => true, 'message' => 'Item created successfully', 'data' => ['id' => $item_id]]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to create item: ' . $stmt->error]);
                }
            } elseif ($action === 'update' && isset($_GET['id'])) {
                // Update existing item
                $id = intval($_GET['id']);
                $input = json_decode(file_get_contents('php://input'), true);

                $name = $input['name'] ?? '';
                $price = floatval($input['price'] ?? 0);
                $stock = intval($input['stock'] ?? 0);
                $category = $input['category'] ?? 'other';
                $description = $input['description'] ?? '';
                $is_available = intval($input['is_available'] ?? 1);
                $branch_id = intval($input['branch_id'] ?? 1);

                // Check if user is a manager/staff and should be restricted to their branch
                $userBranchId = Auth::userBranchId();
                $isManagerRestricted = Auth::isManagerRestricted();

                if ($isManagerRestricted && $userBranchId) {
                    // Manager/Staff can only update items for their branch
                    $branch_id = $userBranchId;
                }

                if (!$name || $price <= 0) {
                    echo json_encode(['success' => false, 'message' => 'Name and price are required']);
                    break;
                }

                $stmt = $db->prepare("
                    UPDATE food_and_drinks 
                    SET name = ?, price = ?, stock = ?, category = ?, description = ?, is_available = ?, branch_id = ?
                    WHERE id = ?
                ");
                $stmt->bind_param("sdisssii", $name, $price, $stock, $category, $description, $is_available, $branch_id, $id);

                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Item updated successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update item']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
            }
            break;

        case 'DELETE':
            if ($action === 'delete' && isset($_GET['id'])) {
                // Delete item
                $id = intval($_GET['id']);
                $stmt = $db->prepare("DELETE FROM food_and_drinks WHERE id = ?");
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Item deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete item']);
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
