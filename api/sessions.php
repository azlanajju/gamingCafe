<?php
// Completely suppress all error output
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 0);

// Start output buffering immediately
ob_start();

// Suppress any output from included files
ob_start();

// Include files with output buffering
ob_start();
require_once __DIR__ . '/../config/auth.php';
$auth_output = ob_get_clean();

ob_start();
require_once __DIR__ . '/../config/database.php';
$db_output = ob_get_clean();

// Clear any accumulated output
ob_clean();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Check authentication
if (!Auth::check()) {
    sendJsonResponse([
        'success' => false,
        'message' => 'Authentication required',
        'redirect' => SITE_URL . '/login.php'
    ]);
}

// Clear any output buffer
ob_clean();

class SessionManager
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function handleRequest()
    {
        $action = $_GET['action'] ?? '';
        $method = $_SERVER['REQUEST_METHOD'];

        try {
            switch ($action) {
                case 'start':
                    if ($method === 'POST') {
                        return $this->startSession();
                    }
                    break;
                case 'pause':
                    if ($method === 'POST') {
                        return $this->pauseSession();
                    }
                    break;
                case 'resume':
                    if ($method === 'POST') {
                        return $this->resumeSession();
                    }
                    break;
                case 'end':
                    if ($method === 'POST') {
                        return $this->endSession();
                    }
                    break;
                case 'debug':
                    if ($method === 'GET') {
                        return $this->debugInfo();
                    }
                    break;
                case 'test':
                    if ($method === 'GET') {
                        return $this->testResponse();
                    }
                    break;
                case 'transfer':
                    if ($method === 'POST') {
                        return $this->transferSession();
                    }
                    break;
                case 'change_players':
                    if ($method === 'POST') {
                        return $this->changePlayers();
                    }
                    break;
                case 'add_fandd':
                    if ($method === 'POST') {
                        return $this->addFoodAndDrinks();
                    }
                    break;
                case 'remove_fandd':
                    if ($method === 'POST') {
                        return $this->removeFoodAndDrinks();
                    }
                    break;
                case 'get_time':
                    if ($method === 'GET') {
                        return $this->getSessionTime();
                    }
                    break;
                case 'list':
                    if ($method === 'GET') {
                        return $this->getActiveSessions();
                    }
                    break;
                case 'process_payment':
                    if ($method === 'POST') {
                        return $this->processPayment();
                    }
                    break;
                default:
                    return ['success' => false, 'message' => 'Invalid action'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function startSession()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $console_id = $input['console_id'] ?? null;
        $customer_name = $input['customer_name'] ?? '';
        $customer_number = $input['customer_number'] ?? '';
        $player_count = $input['player_count'] ?? 1;
        $rate_type = $input['rate_type'] ?? 'regular';
        $timezone_offset = $input['timezone_offset'] ?? '+05:30';

        if (!$console_id || !$customer_name) {
            return ['success' => false, 'message' => 'Console ID and customer name are required'];
        }

        // Check if console is available (case-insensitive)
        $stmt = $this->db->prepare("SELECT * FROM consoles WHERE id = ? AND LOWER(status) = 'available'");
        $stmt->bind_param("i", $console_id);
        $stmt->execute();
        $console = $stmt->get_result()->fetch_assoc();

        if (!$console) {
            return ['success' => false, 'message' => 'Console not available'];
        }

        // Start session
        $start_time = date('Y-m-d H:i:s');
        $user_id = $_SESSION['user_id'] ?? 1;
        $stmt = $this->db->prepare("
            INSERT INTO gaming_sessions 
            (console_id, customer_name, customer_number, player_count, rate_type, start_time, timezone_offset, status, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'active', ?)
        ");
        $stmt->bind_param("ississsi", $console_id, $customer_name, $customer_number, $player_count, $rate_type, $start_time, $timezone_offset, $user_id);

        if ($stmt->execute()) {
            $session_id = $this->db->insert_id;

            // Update console status
            $stmt = $this->db->prepare("UPDATE consoles SET status = 'Occupied' WHERE id = ?");
            $stmt->bind_param("i", $console_id);
            $stmt->execute();

            // Log activity
            $this->logActivity("Started gaming session for {$customer_name} on console {$console['name']}", $console_id);

            return [
                'success' => true,
                'message' => 'Session started successfully',
                'data' => ['session_id' => $session_id]
            ];
        } else {
            return ['success' => false, 'message' => 'Failed to start session'];
        }
    }

    private function pauseSession()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $console_id = $input['console_id'] ?? null;

        if (!$console_id) {
            return ['success' => false, 'message' => 'Console ID is required'];
        }

        // Get active session
        $stmt = $this->db->prepare("SELECT * FROM gaming_sessions WHERE console_id = ? AND status = 'active'");
        $stmt->bind_param("i", $console_id);
        $stmt->execute();
        $session = $stmt->get_result()->fetch_assoc();

        if (!$session) {
            return ['success' => false, 'message' => 'No active session found'];
        }

        // Update session
        $pause_time = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("
            UPDATE gaming_sessions 
            SET status = 'paused', pause_start_time = ?, total_pause_duration = COALESCE(total_pause_duration, 0) 
            WHERE id = ?
        ");
        $stmt->bind_param("si", $pause_time, $session['id']);

        if ($stmt->execute()) {
            $this->logActivity("Paused gaming session on console {$console_id}", $console_id);
            return ['success' => true, 'message' => 'Session paused successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to pause session'];
        }
    }

    private function resumeSession()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $console_id = $input['console_id'] ?? null;

        if (!$console_id) {
            return ['success' => false, 'message' => 'Console ID is required'];
        }

        // Get paused session
        $stmt = $this->db->prepare("SELECT * FROM gaming_sessions WHERE console_id = ? AND status = 'paused'");
        $stmt->bind_param("i", $console_id);
        $stmt->execute();
        $session = $stmt->get_result()->fetch_assoc();

        if (!$session) {
            return ['success' => false, 'message' => 'No paused session found'];
        }

        // Calculate pause duration and update session
        $pause_duration = 0;
        if ($session['pause_start_time']) {
            $pause_start = new DateTime($session['pause_start_time']);
            $pause_duration = time() - $pause_start->getTimestamp();
        }

        $resume_time = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("
            UPDATE gaming_sessions 
            SET status = 'active', 
                pause_start_time = NULL,
                total_pause_duration = COALESCE(total_pause_duration, 0) + ?,
                last_resume_time = ?
            WHERE id = ?
        ");
        $stmt->bind_param("isi", $pause_duration, $resume_time, $session['id']);

        if ($stmt->execute()) {
            $this->logActivity("Resumed gaming session on console {$console_id}", $console_id);
            return ['success' => true, 'message' => 'Session resumed successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to resume session'];
        }
    }

    private function endSession()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $console_id = $input['console_id'] ?? null;

            if (!$console_id) {
                return ['success' => false, 'message' => 'Console ID is required'];
            }

            // Get active session
            $stmt = $this->db->prepare("SELECT * FROM gaming_sessions WHERE console_id = ? AND status IN ('active', 'paused')");
            $stmt->bind_param("i", $console_id);
            $stmt->execute();
            $session = $stmt->get_result()->fetch_assoc();

            if (!$session) {
                return ['success' => false, 'message' => 'No active session found'];
            }

            // Calculate final amounts
            $end_time = date('Y-m-d H:i:s');
            $start_time = new DateTime($session['start_time']);
            $end_datetime = new DateTime($end_time);
            $total_seconds = $end_datetime->getTimestamp() - $start_time->getTimestamp();
            $pause_duration = $session['total_pause_duration'] ?? 0;
            $actual_play_time = $total_seconds - $pause_duration;

            // Calculate billing using pricing table
            $billing_data = $this->calculateSessionBilling($actual_play_time, $session['player_count'], $session['rate_type']);

            // Get F&D total
            $fandd_total = $this->getSessionFandDTotal($session['id']);

            // Calculate totals
            $gaming_amount = $billing_data['total_amount'];
            $subtotal = $gaming_amount + $fandd_total;
            $tax_rate = $this->getTaxRate();
            $tax_amount = $subtotal * $tax_rate;
            $total_amount = $subtotal + $tax_amount;

            // Start transaction
            $this->db->begin_transaction();

            try {
                // Update session
                $stmt = $this->db->prepare("
                UPDATE gaming_sessions 
                SET status = 'completed', 
                    end_time = ?, 
                    total_amount = ?,
                    total_fandd_amount = ?,
                    total_duration_seconds = ?
                WHERE id = ?
            ");
                $stmt->bind_param("sdddi", $end_time, $total_amount, $fandd_total, $actual_play_time, $session['id']);

                if (!$stmt->execute()) {
                    throw new Exception('Failed to update session');
                }

                // Create transaction record
                $user_id = $_SESSION['user_id'] ?? 1;
                $stmt = $this->db->prepare("
                INSERT INTO transactions 
                (session_id, console_id, customer_name, customer_number, player_count, rate_type, 
                 start_time, end_time, duration, total_duration_minutes, gaming_amount, fandd_amount, 
                 subtotal, tax_amount, total_amount, payment_method, payment_details, coupon_code, 
                 discount_amount, final_amount, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

                $duration_minutes = round($actual_play_time / 60);
                $payment_method = $session['payment_method'] ?? 'pending';
                $payment_details = $session['payment_details'] ?? null;
                $coupon_code = $session['coupon_code'] ?? null;
                $discount_amount = $session['discount_amount'] ?? 0;
                $final_amount = $session['final_amount'] ?? $total_amount;

                $stmt->bind_param(
                    "iississsiidddddsssddi",
                    $session['id'],
                    $console_id,
                    $session['customer_name'],
                    $session['customer_number'],
                    $session['player_count'],
                    $session['rate_type'],
                    $session['start_time'],
                    $end_time,
                    $duration_minutes,
                    $duration_minutes,
                    $gaming_amount,
                    $fandd_total,
                    $subtotal,
                    $tax_amount,
                    $total_amount,
                    $payment_method,
                    $payment_details,
                    $coupon_code,
                    $discount_amount,
                    $final_amount,
                    $user_id
                );

                if (!$stmt->execute()) {
                    throw new Exception('Failed to create transaction record');
                }

                // Update console status
                $stmt = $this->db->prepare("UPDATE consoles SET status = 'Available' WHERE id = ?");
                $stmt->bind_param("i", $console_id);
                if (!$stmt->execute()) {
                    throw new Exception('Failed to update console status');
                }

                // Get F&D items for the session
                $stmt = $this->db->prepare("
                SELECT item_name as name, quantity, unit_price, total_price 
                FROM session_items 
                WHERE session_id = ?
            ");
                $stmt->bind_param("i", $session['id']);
                $stmt->execute();
                $fandd_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

                // Commit transaction
                $this->db->commit();

                $this->logActivity("Ended gaming session on console {$console_id}. Total: ₹" . number_format($total_amount, 2), $console_id);

                return [
                    'success' => true,
                    'message' => 'Session ended successfully',
                    'data' => [
                        'session_id' => $session['id'],
                        'customer_name' => $session['customer_name'],
                        'customer_number' => $session['customer_number'],
                        'player_count' => $session['player_count'],
                        'rate_type' => $session['rate_type'],
                        'total_amount' => $total_amount,
                        'gaming_amount' => $gaming_amount,
                        'fandd_amount' => $fandd_total,
                        'tax_amount' => $tax_amount,
                        'duration_minutes' => $duration_minutes,
                        'billing_details' => $billing_data,
                        'fandd_items' => $fandd_items
                    ]
                ];
            } catch (Exception $e) {
                $this->db->rollback();
                return ['success' => false, 'message' => 'Failed to end session: ' . $e->getMessage()];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'End session error: ' . $e->getMessage()];
        }
    }

    private function transferSession()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $source_console_id = $input['source_console_id'] ?? null;
        $target_console_id = $input['target_console_id'] ?? null;

        if (!$source_console_id || !$target_console_id) {
            return ['success' => false, 'message' => 'Source and target console IDs are required'];
        }

        if ($source_console_id == $target_console_id) {
            return ['success' => false, 'message' => 'Cannot transfer to the same console'];
        }

        // Get active session
        $stmt = $this->db->prepare("SELECT * FROM gaming_sessions WHERE console_id = ? AND status IN ('active', 'paused')");
        $stmt->bind_param("i", $source_console_id);
        $stmt->execute();
        $session = $stmt->get_result()->fetch_assoc();

        if (!$session) {
            return ['success' => false, 'message' => 'No active session found on source console'];
        }

        // Check if target console is available
        $stmt = $this->db->prepare("SELECT * FROM consoles WHERE id = ? AND LOWER(status) = 'available'");
        $stmt->bind_param("i", $target_console_id);
        $stmt->execute();
        $target_console = $stmt->get_result()->fetch_assoc();

        if (!$target_console) {
            return ['success' => false, 'message' => 'Target console is not available'];
        }

        // Update session
        $stmt = $this->db->prepare("UPDATE gaming_sessions SET console_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $target_console_id, $session['id']);

        if ($stmt->execute()) {
            // Update console statuses
            $stmt = $this->db->prepare("UPDATE consoles SET status = 'Available' WHERE id = ?");
            $stmt->bind_param("i", $source_console_id);
            $stmt->execute();

            $stmt = $this->db->prepare("UPDATE consoles SET status = 'Occupied' WHERE id = ?");
            $stmt->bind_param("i", $target_console_id);
            $stmt->execute();

            $this->logActivity("Transferred session from console {$source_console_id} to console {$target_console_id}", $target_console_id);

            return [
                'success' => true,
                'message' => 'Session transferred successfully',
                'data' => ['session_id' => $session['id']]
            ];
        } else {
            return ['success' => false, 'message' => 'Failed to transfer session'];
        }
    }

    private function changePlayers()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $console_id = $input['console_id'] ?? null;
        $new_player_count = $input['new_player_count'] ?? null;

        if (!$console_id || !$new_player_count) {
            return ['success' => false, 'message' => 'Console ID and new player count are required'];
        }

        // Get active session
        $stmt = $this->db->prepare("SELECT * FROM gaming_sessions WHERE console_id = ? AND status = 'active'");
        $stmt->bind_param("i", $console_id);
        $stmt->execute();
        $session = $stmt->get_result()->fetch_assoc();

        if (!$session) {
            return ['success' => false, 'message' => 'No active session found'];
        }

        // Update player count
        $stmt = $this->db->prepare("UPDATE gaming_sessions SET player_count = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_player_count, $session['id']);

        if ($stmt->execute()) {
            $this->logActivity("Changed player count to {$new_player_count} on console {$console_id}", $console_id);
            return ['success' => true, 'message' => 'Player count updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update player count'];
        }
    }

    private function addFoodAndDrinks()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $console_id = $input['console_id'] ?? null;
        $item_name = $input['item_name'] ?? '';
        $quantity = $input['quantity'] ?? 1;
        $price = $input['price'] ?? 0;

        if (!$console_id || !$item_name || !$quantity || !$price) {
            return ['success' => false, 'message' => 'All fields are required'];
        }

        // Get active session
        $stmt = $this->db->prepare("SELECT * FROM gaming_sessions WHERE console_id = ? AND status = 'active'");
        $stmt->bind_param("i", $console_id);
        $stmt->execute();
        $session = $stmt->get_result()->fetch_assoc();

        if (!$session) {
            return ['success' => false, 'message' => 'No active session found'];
        }

        // Get F&D item details and check stock
        $stmt = $this->db->prepare("SELECT id, stock FROM food_and_drinks WHERE name = ? AND is_available = 1");
        $stmt->bind_param("s", $item_name);
        $stmt->execute();
        $item = $stmt->get_result()->fetch_assoc();

        if (!$item) {
            return ['success' => false, 'message' => 'Item not found or not available'];
        }

        if ($item['stock'] < $quantity) {
            return ['success' => false, 'message' => "Insufficient stock. Available: {$item['stock']}, Requested: {$quantity}"];
        }

        $total_price = $quantity * $price;

        // Start transaction
        $this->db->begin_transaction();

        try {
            // Add F&D item to session
            $stmt = $this->db->prepare("
                INSERT INTO session_items 
                (session_id, item_name, quantity, unit_price, total_price, item_type, fandd_item_id) 
                VALUES (?, ?, ?, ?, ?, 'food_drinks', ?)
            ");
            $stmt->bind_param("isiddi", $session['id'], $item_name, $quantity, $price, $total_price, $item['id']);

            if (!$stmt->execute()) {
                throw new Exception('Failed to add item to session');
            }

            // Reduce stock in food_and_drinks table
            $stmt = $this->db->prepare("
                UPDATE food_and_drinks 
                SET stock = stock - ? 
                WHERE id = ?
            ");
            $stmt->bind_param("ii", $quantity, $item['id']);

            if (!$stmt->execute()) {
                throw new Exception('Failed to update stock');
            }

            // Update session total
            $stmt = $this->db->prepare("
                UPDATE gaming_sessions 
                SET total_fandd_amount = COALESCE(total_fandd_amount, 0) + ? 
                WHERE id = ?
            ");
            $stmt->bind_param("di", $total_price, $session['id']);

            if (!$stmt->execute()) {
                throw new Exception('Failed to update session total');
            }

            // Commit transaction
            $this->db->commit();

            $this->logActivity("Added {$quantity}x {$item_name} to session on console {$console_id}. Stock reduced by {$quantity}", $console_id);
            return ['success' => true, 'message' => 'Food & Drinks added successfully'];
        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Failed to add Food & Drinks: ' . $e->getMessage()];
        }
    }

    private function removeFoodAndDrinks()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $console_id = $input['console_id'] ?? null;
        $item_id = $input['item_id'] ?? null;

        if (!$console_id || !$item_id) {
            return ['success' => false, 'message' => 'Console ID and Item ID are required'];
        }

        // Get active session
        $stmt = $this->db->prepare("SELECT * FROM gaming_sessions WHERE console_id = ? AND status = 'active'");
        $stmt->bind_param("i", $console_id);
        $stmt->execute();
        $session = $stmt->get_result()->fetch_assoc();

        if (!$session) {
            return ['success' => false, 'message' => 'No active session found'];
        }

        // Get session item details
        $stmt = $this->db->prepare("
            SELECT si.*, fad.name as fandd_name 
            FROM session_items si 
            LEFT JOIN food_and_drinks fad ON si.fandd_item_id = fad.id 
            WHERE si.id = ? AND si.session_id = ?
        ");
        $stmt->bind_param("ii", $item_id, $session['id']);
        $stmt->execute();
        $session_item = $stmt->get_result()->fetch_assoc();

        if (!$session_item) {
            return ['success' => false, 'message' => 'Session item not found'];
        }

        // Start transaction
        $this->db->begin_transaction();

        try {
            // Remove item from session
            $stmt = $this->db->prepare("DELETE FROM session_items WHERE id = ?");
            $stmt->bind_param("i", $item_id);

            if (!$stmt->execute()) {
                throw new Exception('Failed to remove item from session');
            }

            // Restore stock if it's a F&D item
            if ($session_item['fandd_item_id']) {
                $stmt = $this->db->prepare("
                    UPDATE food_and_drinks 
                    SET stock = stock + ? 
                    WHERE id = ?
                ");
                $stmt->bind_param("ii", $session_item['quantity'], $session_item['fandd_item_id']);

                if (!$stmt->execute()) {
                    throw new Exception('Failed to restore stock');
                }
            }

            // Update session total
            $stmt = $this->db->prepare("
                UPDATE gaming_sessions 
                SET total_fandd_amount = GREATEST(COALESCE(total_fandd_amount, 0) - ?, 0)
                WHERE id = ?
            ");
            $stmt->bind_param("di", $session_item['total_price'], $session['id']);

            if (!$stmt->execute()) {
                throw new Exception('Failed to update session total');
            }

            // Commit transaction
            $this->db->commit();

            $item_name = $session_item['fandd_name'] ?: $session_item['item_name'];
            $this->logActivity("Removed {$session_item['quantity']}x {$item_name} from session on console {$console_id}. Stock restored by {$session_item['quantity']}", $console_id);
            return ['success' => true, 'message' => 'Item removed successfully'];
        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Failed to remove item: ' . $e->getMessage()];
        }
    }

    private function getSessionTime()
    {
        $console_id = $_GET['console_id'] ?? null;

        if (!$console_id) {
            return ['success' => false, 'message' => 'Console ID is required'];
        }

        // Get active or paused session
        $stmt = $this->db->prepare("
            SELECT *, 
            TIMESTAMPDIFF(SECOND, start_time, NOW()) as elapsed_seconds,
            COALESCE(total_pause_duration, 0) as pause_seconds,
            pause_start_time,
            status
            FROM gaming_sessions 
            WHERE console_id = ? AND status IN ('active', 'paused')
        ");
        $stmt->bind_param("i", $console_id);
        $stmt->execute();
        $session = $stmt->get_result()->fetch_assoc();

        if (!$session) {
            return ['success' => false, 'message' => 'No active session found'];
        }

        // Calculate total elapsed time
        $total_elapsed = $session['elapsed_seconds'] - $session['pause_seconds'];

        // If session is paused, add the current pause duration
        if ($session['status'] === 'paused' && $session['pause_start_time']) {
            $pause_start = new DateTime($session['pause_start_time']);
            $current_pause_duration = time() - $pause_start->getTimestamp();
            $total_elapsed -= $current_pause_duration;
        }

        $hours = floor($total_elapsed / 3600);
        $minutes = floor(($total_elapsed % 3600) / 60);
        $seconds = $total_elapsed % 60;

        $formatted_time = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        return [
            'success' => true,
            'data' => [
                'formatted_time' => $formatted_time,
                'elapsed_seconds' => $total_elapsed,
                'session_id' => $session['id']
            ]
        ];
    }

    private function getActiveSessions()
    {
        $stmt = $this->db->prepare("
            SELECT gs.*, c.name as console_name, c.type as console_type, c.location
            FROM gaming_sessions gs
            JOIN consoles c ON gs.console_id = c.id
            WHERE gs.status IN ('active', 'paused')
        ");
        $stmt->execute();
        $sessions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        return ['success' => true, 'data' => $sessions];
    }

    private function processPayment()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $session_id = $input['session_id'] ?? null;
        $payment_method = $input['payment_method'] ?? '';
        $coupon_code = $input['coupon_code'] ?? null;
        // Ignore client-sent discount/final amounts; compute server-side to ensure coupon applies only to gaming
        $discount_amount = 0;
        $final_amount = 0;
        $payment_details = $input['payment_details'] ?? null;

        if (!$session_id || !$payment_method) {
            return ['success' => false, 'message' => 'Session ID and payment method are required'];
        }

        // Start transaction
        $this->db->begin_transaction();

        try {
            // Read authoritative amounts from the transaction created at session end
            $txStmt = $this->db->prepare("SELECT gaming_amount, fandd_amount, tax_amount FROM transactions WHERE session_id = ? LIMIT 1");
            $txStmt->bind_param("i", $session_id);
            $txStmt->execute();
            $tx = $txStmt->get_result()->fetch_assoc();

            if (!$tx) {
                throw new Exception('Transaction not found for session');
            }

            $gaming_amount = floatval($tx['gaming_amount'] ?? 0);
            $fandd_amount = floatval($tx['fandd_amount'] ?? 0);
            $tax_amount = floatval($tx['tax_amount'] ?? 0);

            // Compute discount only on gaming amount
            $computed_discount = 0.0;
            if ($coupon_code) {
                $couponStmt = $this->db->prepare("SELECT * FROM coupons WHERE code = ? AND status = 'Active' LIMIT 1");
                $couponStmt->bind_param("s", $coupon_code);
                $couponStmt->execute();
                $coupon = $couponStmt->get_result()->fetch_assoc();

                if ($coupon) {
                    $today = date('Y-m-d');
                    if (($coupon['valid_from'] && $coupon['valid_from'] > $today) || ($coupon['valid_to'] && $coupon['valid_to'] < $today)) {
                        throw new Exception('Coupon is not valid today');
                    }
                    if ($coupon['usage_limit'] > 0 && $coupon['times_used'] >= $coupon['usage_limit']) {
                        throw new Exception('Coupon usage limit reached');
                    }
                    if ($gaming_amount < floatval($coupon['min_order_amount'])) {
                        throw new Exception('Minimum gaming amount not met for coupon');
                    }

                    if ($coupon['discount_type'] === 'percentage') {
                        $computed_discount = ($gaming_amount * floatval($coupon['discount_value'])) / 100.0;
                    } elseif ($coupon['discount_type'] === 'flat') {
                        $computed_discount = min(floatval($coupon['discount_value']), $gaming_amount);
                    } elseif ($coupon['discount_type'] === 'time_bonus') {
                        $computed_discount = 0.0; // time bonus handled as extra minutes, not monetary here
                    }
                }
            }

            $discount_amount = round($computed_discount, 2);
            $final_amount = round(($gaming_amount - $discount_amount) + $fandd_amount + $tax_amount, 2);

            // Update session with payment details (authoritative server-side amounts)
            $stmt = $this->db->prepare("
				UPDATE gaming_sessions 
				SET payment_method = ?, 
					coupon_code = ?, 
					discount_amount = ?, 
					final_amount = ?,
					payment_status = 'completed',
					payment_date = NOW()
				WHERE id = ?
			");
            $stmt->bind_param("ssddi", $payment_method, $coupon_code, $discount_amount, $final_amount, $session_id);

            if (!$stmt->execute()) {
                throw new Exception('Failed to update session payment details');
            }

            // Update transaction record with payment details
            $payment_details_json = $payment_details ? json_encode($payment_details) : null;
            $stmt = $this->db->prepare("
				UPDATE transactions 
				SET payment_method = ?, 
					coupon_code = ?, 
					discount_amount = ?, 
					final_amount = ?,
					total_amount = ?,
					payment_details = ?,
					payment_status = 'completed',
					payment_date = NOW()
				WHERE session_id = ?
			");
            $stmt->bind_param("ssddssi", $payment_method, $coupon_code, $discount_amount, $final_amount, $final_amount, $payment_details_json, $session_id);

            if (!$stmt->execute()) {
                throw new Exception('Failed to update transaction payment details');
            }

            // If coupon was used, mark it as used
            if ($coupon_code && $discount_amount > 0) {
                $stmt = $this->db->prepare("
					UPDATE coupons 
					SET usage_count = usage_count + 1,
						last_used_at = NOW()
					WHERE code = ? AND status = 'active'
				");
                $stmt->bind_param("s", $coupon_code);
                $stmt->execute();
            }

            // Commit transaction
            $this->db->commit();

            $this->logActivity("Payment processed for session {$session_id}. Method: {$payment_method}, Amount: ₹" . number_format($final_amount, 2), null);

            return [
                'success' => true,
                'message' => 'Payment processed successfully',
                'data' => [
                    'session_id' => $session_id,
                    'payment_method' => $payment_method,
                    'final_amount' => $final_amount,
                    'discount_amount' => $discount_amount
                ]
            ];
        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Failed to process payment: ' . $e->getMessage()];
        }
    }


    private function calculateSessionBilling($seconds, $player_count, $rate_type)
    {
        $minutes = round($seconds / 60);

        // Get pricing from database
        $stmt = $this->db->prepare("
            SELECT * FROM pricing 
            WHERE rate_type = ? AND player_count = ?
        ");
        $stmt->bind_param("si", $rate_type, $player_count);
        $stmt->execute();
        $pricing = $stmt->get_result()->fetch_assoc();

        if (!$pricing) {
            // Fallback to basic calculation if no pricing found
            return [
                'total_amount' => $this->calculateSessionAmount($seconds, $player_count, $rate_type),
                'breakdown' => ['fallback_rate' => true]
            ];
        }

        // Calculate billing based on duration brackets
        $total_amount = 0;
        $breakdown = [];

        // Handle different duration brackets
        if ($minutes <= 15) {
            $total_amount = $pricing['duration_15'] ?? 0;
            $breakdown['15_min'] = $total_amount;
        } elseif ($minutes <= 30) {
            $total_amount = $pricing['duration_30'] ?? 0;
            $breakdown['30_min'] = $total_amount;
        } elseif ($minutes <= 45) {
            $total_amount = $pricing['duration_45'] ?? 0;
            $breakdown['45_min'] = $total_amount;
        } else {
            $total_amount = $pricing['duration_60'] ?? 0;
            $breakdown['60_min'] = $total_amount;

            // For sessions longer than 60 minutes, add additional hourly charges
            if ($minutes > 60) {
                $additional_hours = ceil(($minutes - 60) / 60);
                $hourly_rate = $pricing['duration_60'] ?? 0;
                $additional_amount = $additional_hours * $hourly_rate;
                $total_amount += $additional_amount;
                $breakdown['additional_hours'] = $additional_amount;
            }
        }

        return [
            'total_amount' => $total_amount,
            'breakdown' => $breakdown,
            'duration_minutes' => $minutes,
            'pricing_used' => $pricing
        ];
    }

    private function getSessionFandDTotal($session_id)
    {
        $stmt = $this->db->prepare("
            SELECT SUM(total_price) as total 
            FROM session_items 
            WHERE session_id = ?
        ");
        $stmt->bind_param("i", $session_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result['total'] ?? 0;
    }

    private function getTaxRate()
    {
        // Return 0% GST (no tax)
        return 0.00;
    }

    private function calculateSessionAmount($seconds, $player_count, $rate_type)
    {
        // Fallback calculation if pricing table is not available
        $minutes = $seconds / 60;
        $rate_per_minute = $rate_type === 'vip' ? 0.15 : 0.10;
        return $minutes * $player_count * $rate_per_minute;
    }

    private function logActivity($message, $console_id)
    {
        $stmt = $this->db->prepare("
            INSERT INTO activity_logs (user_id, action, description, created_at) 
            VALUES (?, 'session_management', ?, NOW())
        ");
        $user_id = $_SESSION['user_id'] ?? 1;
        $stmt->bind_param("is", $user_id, $message);
        $stmt->execute();
    }

    private function debugInfo()
    {
        return [
            'success' => true,
            'message' => 'Debug info',
            'data' => [
                'session_id' => session_id(),
                'user_id' => $_SESSION['user_id'] ?? 'Not set',
                'user_email' => $_SESSION['user_email'] ?? 'Not set',
                'database_connected' => $this->db ? 'Yes' : 'No',
                'php_version' => PHP_VERSION,
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ];
    }

    private function testResponse()
    {
        return [
            'success' => true,
            'message' => 'Test response working',
            'data' => [
                'test' => 'API is working correctly',
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ];
    }
}

// Function to send clean JSON response
function sendJsonResponse($data)
{
    // Clear all output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }

    // Set headers
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');

    // Send JSON response
    echo json_encode($data);
    exit;
}

try {
    $sessionManager = new SessionManager();
    $result = $sessionManager->handleRequest();

    // Get any output that might have been generated
    $output = ob_get_contents();
    ob_clean();

    // If there's any output, log it and return error
    if (!empty($output)) {
        sendJsonResponse([
            'success' => false,
            'message' => 'Unexpected output detected',
            'debug' => [
                'output' => $output,
                'session_id' => session_id(),
                'user_id' => $_SESSION['user_id'] ?? 'Not set'
            ]
        ]);
    } else {
        sendJsonResponse($result);
    }
} catch (Exception $e) {
    // Get any output that might have been generated
    $output = ob_get_contents();
    ob_clean();

    sendJsonResponse([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'debug' => [
            'output' => $output,
            'session_id' => session_id(),
            'user_id' => $_SESSION['user_id'] ?? 'Not set',
            'user_email' => $_SESSION['user_email'] ?? 'Not set'
        ]
    ]);
}
