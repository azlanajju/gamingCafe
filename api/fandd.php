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
            } elseif ($action === 'process_billing') {
                // Process F&D-only billing
                $input = json_decode(file_get_contents('php://input'), true);

                $customer_name = $input['customer_name'] ?? 'Walk-in Customer';
                $customer_number = $input['customer_number'] ?? '';
                $items = $input['items'] ?? [];
                $fandd_amount = floatval($input['fandd_amount'] ?? 0);
                $tax_amount = floatval($input['tax_amount'] ?? 0);
                $total_amount = floatval($input['total_amount'] ?? 0);
                $final_amount = floatval($input['final_amount'] ?? $total_amount);
                $payment_method = $input['payment_method'] ?? 'cash';
                $payment_details = $input['payment_details'] ?? null;
                $branch_id = intval($input['branch_id'] ?? 1);

                // Check if user is a manager and should be restricted to their branch
                $userBranchId = Auth::userBranchId();
                $isManagerRestricted = Auth::isManagerRestricted();

                if ($isManagerRestricted && $userBranchId) {
                    $branch_id = $userBranchId;
                }

                if (empty($items)) {
                    echo json_encode(['success' => false, 'message' => 'No items in cart']);
                    break;
                }

                if ($total_amount <= 0) {
                    echo json_encode(['success' => false, 'message' => 'Total amount must be greater than 0']);
                    break;
                }

                // Start transaction
                $db->begin_transaction();

                try {
                    // Validate stock and reduce stock for each item
                    foreach ($items as $item) {
                        $item_id = intval($item['fandd_item_id'] ?? 0);
                        $quantity = intval($item['quantity'] ?? 0);

                        if ($item_id <= 0 || $quantity <= 0) {
                            throw new Exception('Invalid item data');
                        }

                        // Check stock
                        $stockStmt = $db->prepare("SELECT stock FROM food_and_drinks WHERE id = ?");
                        $stockStmt->bind_param("i", $item_id);
                        $stockStmt->execute();
                        $stockResult = $stockStmt->get_result()->fetch_assoc();

                        if (!$stockResult) {
                            throw new Exception("Item with ID {$item_id} not found");
                        }

                        if ($stockResult['stock'] < $quantity) {
                            throw new Exception("Insufficient stock for item: {$item['item_name']}. Available: {$stockResult['stock']}, Requested: {$quantity}");
                        }

                        // Reduce stock
                        $updateStockStmt = $db->prepare("UPDATE food_and_drinks SET stock = stock - ? WHERE id = ?");
                        $updateStockStmt->bind_param("ii", $quantity, $item_id);
                        if (!$updateStockStmt->execute()) {
                            throw new Exception('Failed to update stock for item: ' . $item['item_name']);
                        }
                    }

                    // Create transaction record
                    $user_id = $_SESSION['user_id'] ?? 1;
                    $payment_details_json = $payment_details ? json_encode($payment_details) : null;

                    $transactionStmt = $db->prepare("
                        INSERT INTO transactions 
                        (customer_name, customer_number, gaming_amount, fandd_amount, subtotal, tax_amount, 
                         total_amount, final_amount, payment_method, payment_details, payment_status, 
                         created_by, branch_id, transaction_date, payment_date) 
                        VALUES (?, ?, 0.00, ?, ?, ?, ?, ?, ?, ?, 'completed', ?, ?, NOW(), NOW())
                    ");

                    $transactionStmt->bind_param(
                        "ssddddsssii",
                        $customer_name,
                        $customer_number,
                        $fandd_amount,
                        $fandd_amount, // subtotal (same as fandd_amount for F&D only)
                        $tax_amount,
                        $total_amount,
                        $final_amount,
                        $payment_method,
                        $payment_details_json,
                        $user_id,
                        $branch_id
                    );

                    if (!$transactionStmt->execute()) {
                        throw new Exception('Failed to create transaction: ' . $transactionStmt->error);
                    }

                    $transaction_id = $db->insert_id;

                    // Create transaction items
                    foreach ($items as $item) {
                        $itemStmt = $db->prepare("
                            INSERT INTO transaction_items 
                            (transaction_id, item_type, item_id, item_name, quantity, unit_price, total_price) 
                            VALUES (?, 'food', ?, ?, ?, ?, ?)
                        ");

                        $item_id = intval($item['fandd_item_id'] ?? 0);
                        $item_name = $item['item_name'] ?? '';
                        $quantity = intval($item['quantity'] ?? 0);
                        $unit_price = floatval($item['unit_price'] ?? 0);
                        $total_price = floatval($item['total_price'] ?? 0);

                        $itemStmt->bind_param("iisidd", $transaction_id, $item_id, $item_name, $quantity, $unit_price, $total_price);

                        if (!$itemStmt->execute()) {
                            throw new Exception('Failed to create transaction item: ' . $item['item_name']);
                        }
                    }

                    // Log activity
                    $logStmt = $db->prepare("
                        INSERT INTO activity_logs (user_id, action, description, created_at) 
                        VALUES (?, 'fandd_billing', ?, NOW())
                    ");
                    $logMessage = "F&D billing processed: ₹{$final_amount} ({$payment_method}), Customer: {$customer_name}, Transaction ID: {$transaction_id}";
                    $logStmt->bind_param("is", $user_id, $logMessage);
                    $logStmt->execute();

                    // Commit transaction
                    $db->commit();

                    echo json_encode([
                        'success' => true,
                        'message' => 'Payment processed successfully',
                        'data' => [
                            'transaction_id' => $transaction_id,
                            'customer_name' => $customer_name,
                            'items' => $items,
                            'fandd_amount' => $fandd_amount,
                            'tax_amount' => $tax_amount,
                            'total_amount' => $total_amount,
                            'final_amount' => $final_amount,
                            'payment_method' => $payment_method
                        ]
                    ]);
                } catch (Exception $e) {
                    $db->rollback();
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
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
