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
                $stmt = $db->prepare("SELECT * FROM inventory ORDER BY id DESC");
                $stmt->execute();
                $result = $stmt->get_result();
                $items = [];

                while ($row = $result->fetch_assoc()) {
                    $items[] = $row;
                }

                echo json_encode(['success' => true, 'data' => $items]);
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);

            if ($action === 'create') {
                $stmt = $db->prepare("INSERT INTO inventory (name, category, cost_price, selling_price, stock_quantity, expiry_date, supplier, branch_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->bind_param(
                    "ssddissi",
                    $data['name'],
                    $data['category'],
                    $data['cost_price'],
                    $data['selling_price'],
                    $data['stock_quantity'],
                    $data['expiry_date'],
                    $data['supplier'],
                    $data['branch_id']
                );

                if ($stmt->execute()) {
                    Auth::logActivity(Auth::userId(), 'inventory_create', "Created inventory item: {$data['name']}");
                    echo json_encode(['success' => true, 'id' => $db->insert_id, 'message' => 'Inventory item created successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to create inventory item']);
                }
            } elseif ($action === 'update') {
                $id = intval($data['id']);
                $stmt = $db->prepare("UPDATE inventory SET name = ?, category = ?, cost_price = ?, selling_price = ?, stock_quantity = ?, expiry_date = ?, supplier = ? WHERE id = ?");

                $stmt->bind_param(
                    "ssddissi",
                    $data['name'],
                    $data['category'],
                    $data['cost_price'],
                    $data['selling_price'],
                    $data['stock_quantity'],
                    $data['expiry_date'],
                    $data['supplier'],
                    $id
                );

                if ($stmt->execute()) {
                    Auth::logActivity(Auth::userId(), 'inventory_update', "Updated inventory item: {$data['name']}");
                    echo json_encode(['success' => true, 'message' => 'Inventory item updated successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update inventory item']);
                }
            }
            break;

        case 'DELETE':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $stmt = $db->prepare("DELETE FROM inventory WHERE id = ?");
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    Auth::logActivity(Auth::userId(), 'inventory_delete', "Deleted inventory item ID: $id");
                    echo json_encode(['success' => true, 'message' => 'Inventory item deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete inventory item']);
                }
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

