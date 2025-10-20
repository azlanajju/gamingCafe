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
                // Get all food and drinks
                $category_filter = $_GET['category'] ?? '';
                $where_clause = '';
                $params = [];
                $param_types = '';

                if ($category_filter) {
                    $where_clause = "WHERE category = ?";
                    $params[] = $category_filter;
                    $param_types .= 's';
                }

                $stmt = $db->prepare("
                    SELECT * FROM food_and_drinks 
                    {$where_clause}
                    ORDER BY category, name ASC
                ");

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
                $stmt = $db->prepare("SELECT * FROM food_and_drinks WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $item = $result->fetch_assoc();

                if ($item) {
                    echo json_encode(['success' => true, 'data' => $item]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Item not found']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
            }
            break;

        case 'POST':
            if ($action === 'create') {
                // Create new item
                $input = json_decode(file_get_contents('php://input'), true);

                $name = $input['name'] ?? '';
                $price = floatval($input['price'] ?? 0);
                $stock = intval($input['stock'] ?? 0);
                $category = $input['category'] ?? 'other';
                $description = $input['description'] ?? '';
                $is_available = intval($input['is_available'] ?? 1);

                if (!$name || $price <= 0) {
                    echo json_encode(['success' => false, 'message' => 'Name and price are required']);
                    break;
                }

                $stmt = $db->prepare("
                    INSERT INTO food_and_drinks (name, price, stock, category, description, is_available) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->bind_param("sdisi", $name, $price, $stock, $category, $description, $is_available);

                if ($stmt->execute()) {
                    $item_id = $db->insert_id;
                    echo json_encode(['success' => true, 'message' => 'Item created successfully', 'data' => ['id' => $item_id]]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to create item']);
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

                if (!$name || $price <= 0) {
                    echo json_encode(['success' => false, 'message' => 'Name and price are required']);
                    break;
                }

                $stmt = $db->prepare("
                    UPDATE food_and_drinks 
                    SET name = ?, price = ?, stock = ?, category = ?, description = ?, is_available = ? 
                    WHERE id = ?
                ");
                $stmt->bind_param("sdisii", $name, $price, $stock, $category, $description, $is_available, $id);

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









