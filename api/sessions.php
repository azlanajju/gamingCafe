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
                case 'get_pre_billing_details':
                    if ($method === 'GET') {
                        return $this->getPreBillingDetails();
                    }
                    break;
                case 'list':
                    if ($method === 'GET') {
                        return $this->getActiveSessions();
                    }
                    break;
                case 'get_session_segments':
                    if ($method === 'GET') {
                        return $this->getSessionSegmentsApi();
                    }
                    break;
                case 'get_session_fandd':
                    if ($method === 'GET') {
                        return $this->getSessionFandDApi();
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

            // Create initial session segment
            error_log("startSession: Attempting to create initial segment for session_id={$session_id}");
            $segment_created = $this->createSessionSegment($session_id, $player_count, $start_time);
            error_log("startSession: createSessionSegment returned: " . ($segment_created ? 'true' : 'false'));
            if (!$segment_created) {
                error_log("startSession: Failed to create initial segment for session_id={$session_id}");
                // Optionally, roll back the gaming_sessions insert if segment creation fails
                // $this->db->rollback();
                // return ['success' => false, 'message' => 'Failed to start session: initial segment creation failed'];
            } else {
                error_log("startSession: Successfully created initial segment for session_id={$session_id}");
            }

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
        $reason = $input['reason'] ?? 'No reason provided';

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

        // Start transaction
        $this->db->begin_transaction();

        try {
            // Update session
            $pause_time = date('Y-m-d H:i:s');
            $stmt = $this->db->prepare("
                UPDATE gaming_sessions 
                SET status = 'paused', pause_start_time = ?
                WHERE id = ?
            ");
            $stmt->bind_param("si", $pause_time, $session['id']);

            if (!$stmt->execute()) {
                throw new Exception('Failed to update session status');
            }

            // Insert pause record into session_pauses table
            $pauseStmt = $this->db->prepare("
                INSERT INTO session_pauses (session_id, pause_start, reason) 
                VALUES (?, ?, ?)
            ");
            $pauseStmt->bind_param("iss", $session['id'], $pause_time, $reason);

            if (!$pauseStmt->execute()) {
                throw new Exception('Failed to record pause details');
            }

            $this->db->commit();
            $this->logActivity("Paused gaming session on console {$console_id} - Reason: {$reason}", $console_id);
            return ['success' => true, 'message' => 'Session paused successfully'];
        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Failed to pause session: ' . $e->getMessage()];
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

        // Start transaction
        $this->db->begin_transaction();

        try {
            // Calculate pause duration
            $pause_duration = 0;
            if ($session['pause_start_time']) {
                $pause_start = new DateTime($session['pause_start_time']);
                $pause_duration = time() - $pause_start->getTimestamp();
            }

            $resume_time = date('Y-m-d H:i:s');

            // Update session status and add pause duration to total
            $stmt = $this->db->prepare("
                UPDATE gaming_sessions 
                SET status = 'active', 
                    pause_start_time = NULL,
                    total_pause_duration = COALESCE(total_pause_duration, 0) + ?,
                    last_resume_time = ?
                WHERE id = ?
            ");
            $stmt->bind_param("isi", $pause_duration, $resume_time, $session['id']);

            if (!$stmt->execute()) {
                throw new Exception('Failed to update session status');
            }

            // Update the latest pause record with end time and duration
            $pauseStmt = $this->db->prepare("
                UPDATE session_pauses 
                SET pause_end = ?, duration = ?
                WHERE session_id = ? AND pause_end IS NULL
                ORDER BY id DESC
                LIMIT 1
            ");
            $duration_minutes = max(0, round($pause_duration / 60));
            $pauseStmt->bind_param("sii", $resume_time, $duration_minutes, $session['id']);

            if (!$pauseStmt->execute()) {
                throw new Exception('Failed to update pause record');
            }

            $this->db->commit();
            $this->logActivity("Resumed gaming session on console {$console_id}", $console_id);
            return ['success' => true, 'message' => 'Session resumed successfully'];
        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Failed to resume session: ' . $e->getMessage()];
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

            // Get accurate billing details using segment-based calculation
            $_GET['session_id'] = $session['id']; // Set session_id for getPreBillingDetails
            $billing_details = $this->getPreBillingDetails();

            if (!$billing_details['success']) {
                return ['success' => false, 'message' => 'Failed to calculate billing: ' . $billing_details['message']];
            }

            $billing_data = $billing_details['data'];
            $gaming_amount = $billing_data['gaming_amount'];
            $fandd_total = $billing_data['fandd_amount'];
            $tax_amount = $billing_data['tax_amount'];
            $total_amount = $billing_data['total_amount'];

            // End the current active segment
            $end_time = date('Y-m-d H:i:s');
            $this->endCurrentActiveSegment($session['id'], $end_time, $session['player_count'], $session['rate_type']);

            // Start transaction
            $this->db->begin_transaction();

            try {
                // Update session to completed and set final amounts
                $stmt = $this->db->prepare("
                UPDATE gaming_sessions 
                SET status = 'completed', 
                    end_time = ?, 
                    total_amount = ?,
                    total_fandd_amount = ?,
                    total_duration_seconds = ?,
                    payment_status = 'pending' 
                WHERE id = ?
            ");
                $total_duration_seconds = $billing_data['duration_minutes'] * 60;
                $stmt->bind_param("sdddi", $end_time, $total_amount, $fandd_total, $total_duration_seconds, $session['id']);

                if (!$stmt->execute()) {
                    throw new Exception('Failed to update session status to completed');
                }

                // DO NOT CREATE TRANSACTION RECORD HERE
                // DO NOT UPDATE CONSOLE STATUS HERE

                // Get F&D items for the session to return
                $fandd_items = $this->getSessionFandDItems($session['id']);

                // Commit the session status update
                $this->db->commit();

                $this->logActivity("Session {$session['id']} ended and billing details generated for console {$console_id}.", $console_id);

                return [
                    'success' => true,
                    'message' => 'Session ended and billing details generated',
                    'data' => [
                        'session_id' => $session['id'],
                        'console_id' => $console_id,
                        'customer_name' => $session['customer_name'],
                        'customer_number' => $session['customer_number'],
                        'player_count' => $session['player_count'],
                        'rate_type' => $session['rate_type'],
                        'total_amount' => $total_amount,
                        'gaming_amount' => $gaming_amount,
                        'fandd_amount' => $fandd_total,
                        'tax_amount' => $tax_amount,
                        'duration_minutes' => $billing_data['duration_minutes'],
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

        // Check if player count has actually changed
        if ($session['player_count'] == $new_player_count) {
            return ['success' => false, 'message' => 'Player count is already the same.'];
        }

        // End the current segment and start a new one
        $result = $this->_endCurrentSegmentAndStartNew($session['id'], $new_player_count, $session['player_count'], $session['rate_type']);
        if (!$result['success']) {
            return $result;
        }

        // Update player count in the main session table
        $stmt = $this->db->prepare("UPDATE gaming_sessions SET player_count = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_player_count, $session['id']);

        if ($stmt->execute()) {
            $this->logActivity("Changed player count from {$session['player_count']} to {$new_player_count} on console {$console_id}", $console_id);
            return ['success' => true, 'message' => 'Player count updated and new segment started successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update player count'];
        }
    }

    private function createSessionSegment($session_id, $player_count, $start_time)
    {
        error_log("createSessionSegment: session_id={$session_id}, player_count={$player_count}, start_time={$start_time}");
        $stmt = $this->db->prepare("INSERT INTO session_segments (session_id, player_count, start_time) VALUES (?, ?, ?)");
        if (!$stmt) {
            error_log("createSessionSegment: prepare failed - " . $this->db->error);
            return false;
        }
        $stmt->bind_param("iis", $session_id, $player_count, $start_time);
        $success = $stmt->execute();
        if (!$success) {
            error_log("createSessionSegment failed: " . $this->db->error);
        } else {
            error_log("createSessionSegment: Successfully created segment with ID " . $this->db->insert_id);
        }
        return $success;
    }

    private function endCurrentActiveSegment($session_id, $end_time, $player_count, $rate_type)
    {
        error_log("endCurrentActiveSegment: session_id={$session_id}, end_time={$end_time}, player_count={$player_count}, rate_type={$rate_type}");
        $stmt = $this->db->prepare("
            SELECT id, start_time FROM session_segments 
            WHERE session_id = ? AND end_time IS NULL 
            ORDER BY start_time DESC LIMIT 1
        ");
        $stmt->bind_param("i", $session_id);
        $stmt->execute();
        $active_segment = $stmt->get_result()->fetch_assoc();

        if ($active_segment) {
            error_log("endCurrentActiveSegment: Found active segment id={$active_segment['id']} starting at {$active_segment['start_time']}");
            $start_datetime = new DateTime($active_segment['start_time']);
            $end_datetime = new DateTime($end_time);
            $interval = $end_datetime->diff($start_datetime);
            $duration_minutes = $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;
            $actual_seconds = $end_datetime->getTimestamp() - $start_datetime->getTimestamp();

            // Calculate amount for the segment using billing logic
            $segment_billing = $this->calculateSessionBilling($actual_seconds, $player_count, $rate_type);
            $segment_amount = $segment_billing['total_amount'];

            error_log("endCurrentActiveSegment: Calculated duration_minutes={$duration_minutes}, actual_seconds={$actual_seconds}, segment_amount={$segment_amount}");

            $stmt = $this->db->prepare("
                UPDATE session_segments 
                SET end_time = ?, duration = ?, amount = ? 
                WHERE id = ?
            ");
            $stmt->bind_param("sddi", $end_time, $duration_minutes, $segment_amount, $active_segment['id']);
            $success = $stmt->execute();
            if (!$success) {
                error_log("endCurrentActiveSegment UPDATE failed: " . $this->db->error);
            }
            return $success;
        }
        error_log("endCurrentActiveSegment: No active segment found for session_id={$session_id}");
        return false;
    }

    private function _endCurrentSegmentAndStartNew($session_id, $new_player_count, $old_player_count, $rate_type)
    {
        error_log("_endCurrentSegmentAndStartNew: session_id={$session_id}, new_player_count={$new_player_count}, old_player_count={$old_player_count}, rate_type={$rate_type}");
        $current_time = date('Y-m-d H:i:s');

        // End the current active segment
        $end_segment_success = $this->endCurrentActiveSegment($session_id, $current_time, $old_player_count, $rate_type);
        if (!$end_segment_success) {
            error_log("_endCurrentSegmentAndStartNew: endCurrentActiveSegment failed.");
            return ['success' => false, 'message' => 'Failed to end current session segment.'];
        }

        // Start a new segment
        $start_new_segment_success = $this->createSessionSegment($session_id, $new_player_count, $current_time);
        if (!$start_new_segment_success) {
            error_log("_endCurrentSegmentAndStartNew: createSessionSegment failed.");
            return ['success' => false, 'message' => 'Failed to start new session segment.'];
        }

        return ['success' => true, 'message' => 'Segment updated successfully'];
    }

    private function addFoodAndDrinks()
    {
        error_log("Calling addFoodAndDrinks");
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

        // Get the current active segment (the one without end_time)
        $segmentStmt = $this->db->prepare("
            SELECT start_time, end_time, duration, player_count
            FROM session_segments 
            WHERE session_id = ? AND end_time IS NULL
            ORDER BY start_time DESC 
            LIMIT 1
        ");
        $segmentStmt->bind_param("i", $session['id']);
        $segmentStmt->execute();
        $currentSegment = $segmentStmt->get_result()->fetch_assoc();

        if (!$currentSegment) {
            // Fallback to total session time if no active segment found
            $total_elapsed = $session['elapsed_seconds'] - $session['pause_seconds'];
            if ($session['status'] === 'paused' && $session['pause_start_time']) {
                $pause_start = new DateTime($session['pause_start_time']);
                $current_pause_duration = time() - $pause_start->getTimestamp();
                $total_elapsed -= $current_pause_duration;
            }
        } else {
            // Calculate current segment duration
            $segment_start_time = new DateTime($currentSegment['start_time']);

            // Determine the end time for calculation
            if ($session['status'] === 'paused' && $session['pause_start_time']) {
                // If currently paused, calculate up to the pause start time
                $pause_start = new DateTime($session['pause_start_time']);
                $end_time = $segment_start_time < $pause_start ? $pause_start : new DateTime();
            } else {
                // If active, calculate up to now
                $end_time = new DateTime();
            }

            $segment_elapsed = $end_time->getTimestamp() - $segment_start_time->getTimestamp();

            // Get pause time that occurred during this segment from session_pauses table
            $pauseStmt = $this->db->prepare("
                SELECT 
                    SUM(CASE 
                        WHEN pause_end IS NOT NULL THEN duration * 60 
                        ELSE 0 
                    END) as completed_pause_seconds,
                    SUM(CASE 
                        WHEN pause_end IS NULL AND pause_start >= ? AND pause_start < ? THEN 
                            TIMESTAMPDIFF(SECOND, pause_start, NOW())
                        ELSE 0 
                    END) as ongoing_pause_seconds
                FROM session_pauses 
                WHERE session_id = ? 
                AND pause_start >= ? 
                AND pause_start < ?
            ");
            $end_time_str = $end_time->format('Y-m-d H:i:s');
            $segment_start_str = $segment_start_time->format('Y-m-d H:i:s');
            $pauseStmt->bind_param("ssiss", $segment_start_str, $end_time_str, $session['id'], $segment_start_str, $end_time_str);
            $pauseStmt->execute();
            $pauseResult = $pauseStmt->get_result()->fetch_assoc();
            $pause_during_segment = ($pauseResult['completed_pause_seconds'] ?? 0) + ($pauseResult['ongoing_pause_seconds'] ?? 0);


            $total_elapsed = max(0, $segment_elapsed - $pause_during_segment);
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

    private function getBillingDetails()
    {
        $session_id = $_GET['session_id'] ?? null;

        if (!$session_id) {
            return ['success' => false, 'message' => 'Session ID is required'];
        }

        // Get session details
        $stmt = $this->db->prepare("
            SELECT gs.*, c.name as console_name, c.type as console_type, c.location
            FROM gaming_sessions gs
            JOIN consoles c ON gs.console_id = c.id
            WHERE gs.id = ?
        ");
        $stmt->bind_param("i", $session_id);
        $stmt->execute();
        $session = $stmt->get_result()->fetch_assoc();

        if (!$session) {
            return ['success' => false, 'message' => 'Session not found'];
        }

        // Calculate final amounts (without ending the session)
        $end_time = date('Y-m-d H:i:s');
        $start_time = new DateTime($session['start_time']);
        $end_datetime = new DateTime($end_time);
        $total_seconds = $end_datetime->getTimestamp() - $start_time->getTimestamp();
        $pause_duration = $session['total_pause_duration'] ?? 0;
        $actual_play_time = $total_seconds - $pause_duration;

        $billing_data = $this->calculateSessionBilling($actual_play_time, $session['player_count'], $session['rate_type']);
        $fandd_total = $this->getSessionFandDTotal($session['id']);

        $gaming_amount = $billing_data['total_amount'];
        $subtotal = $gaming_amount + $fandd_total;
        $tax_rate = $this->getTaxRate();
        $tax_amount = $subtotal * $tax_rate;
        $total_amount = $subtotal + $tax_amount;

        return [
            'success' => true,
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
                'duration_minutes' => round($actual_play_time / 60),
                'billing_details' => $billing_data,
                'fandd_items' => $this->getSessionFandDItems($session['id'])
            ]
        ];
    }

    private function processPayment()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $session_id = $input['session_id'] ?? null;
        $payment_method = $input['payment_method'] ?? '';
        $coupon_code = $input['coupon_code'] ?? null;
        $discount_amount = $input['discount_amount'] ?? 0; // Now received from frontend
        $final_amount = $input['final_amount'] ?? 0; // Now received from frontend
        $gaming_amount = $input['gaming_amount'] ?? 0;
        $fandd_amount = $input['fandd_amount'] ?? 0;
        $tax_amount = $input['tax_amount'] ?? 0;

        $payment_details = $input['payment_details'] ?? null;

        if (!$session_id || !$payment_method) {
            return ['success' => false, 'message' => 'Session ID and payment method are required'];
        }

        // Start transaction
        $this->db->begin_transaction();

        try {
            // Get session details to retrieve console_id and other info needed for transaction record
            $stmt = $this->db->prepare("SELECT * FROM gaming_sessions WHERE id = ?");
            $stmt->bind_param("i", $session_id);
            $stmt->execute();
            $session = $stmt->get_result()->fetch_assoc();

            if (!$session) {
                throw new Exception('Session not found for payment processing');
            }

            $console_id = $session['console_id'];

            // Get console's branch_id
            $stmt = $this->db->prepare("SELECT branch_id FROM consoles WHERE id = ?");
            $stmt->bind_param("i", $console_id);
            $stmt->execute();
            $console = $stmt->get_result()->fetch_assoc();
            $branch_id = $console ? $console['branch_id'] : 1; // Default to 1 if console not found
            $customer_name = $session['customer_name'];
            $customer_number = $session['customer_number'];
            // $player_count = $session['player_count']; // Get this from segment data if needed for transaction
            $rate_type = $session['rate_type'];
            $start_time = $session['start_time'];
            $end_time = $session['end_time'];
            $duration_minutes = round(($session['total_duration_seconds'] ?? 0) / 60);
            $subtotal = $gaming_amount + $fandd_amount;
            $total_amount_with_tax = $subtotal + $tax_amount;

            // Get player count from the last segment, or sum of segments if needed for transaction record
            $stmt_segments = $this->db->prepare("SELECT SUM(amount) as total_gaming_from_segments FROM session_segments WHERE session_id = ?");
            $stmt_segments->bind_param("i", $session_id);
            $stmt_segments->execute();
            $segments_result = $stmt_segments->get_result()->fetch_assoc();
            $gaming_amount_from_segments = $segments_result['total_gaming_from_segments'] ?? 0;

            // For transaction, use the gaming_amount passed from frontend (which came from getPreBillingDetails)
            // Or, if more authoritative, use $gaming_amount_from_segments if preferred.
            // For now, sticking with frontend passed $gaming_amount for consistency with coupon logic

            // Create transaction record
            $user_id = $_SESSION['user_id'] ?? 1;
            $stmt = $this->db->prepare("
                INSERT INTO transactions 
                (session_id, console_id, customer_name, customer_number, player_count, rate_type, 
                 start_time, end_time, duration, total_duration_minutes, gaming_amount, fandd_amount, 
                 subtotal, tax_amount, total_amount, payment_method, payment_details, coupon_code, 
                 discount_amount, final_amount, payment_status, created_by, payment_date, branch_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'completed', ?, NOW(), ?)
            ");

            $payment_details_json = $payment_details ? json_encode($payment_details) : null;

            $stmt->bind_param(
                "iississsiiddddsssddiii",
                $session_id,
                $console_id,
                $customer_name,
                $customer_number,
                $session['player_count'], // Use the player_count from the main session record
                $rate_type,
                $start_time,
                $end_time,
                $duration_minutes,
                $duration_minutes,
                $gaming_amount, // Use gaming_amount from frontend (which is from getPreBillingDetails)
                $fandd_amount,
                $subtotal,
                $tax_amount,
                $total_amount_with_tax, // Use the total_amount calculated here with tax
                $payment_method,
                $payment_details_json,
                $coupon_code,
                $discount_amount,
                $final_amount,
                $user_id,
                $branch_id
            );

            if (!$stmt->execute()) {
                throw new Exception('Failed to create transaction record');
            }

            // Update console status to Available
            $stmt = $this->db->prepare("UPDATE consoles SET status = 'Available' WHERE id = ?");
            $stmt->bind_param("i", $console_id);
            if (!$stmt->execute()) {
                throw new Exception('Failed to update console status');
            }

            // Update gaming_sessions with final payment status
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

            $this->logActivity("Payment processed and session finalized for session {$session_id}. Method: {$payment_method}, Amount: ₹" . number_format($final_amount, 2), $console_id);

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

    private function getPreBillingDetails()
    {
        $session_id = $_GET['session_id'] ?? null;

        if (!$session_id) {
            return ['success' => false, 'message' => 'Session ID is required'];
        }

        // Get session details
        $stmt = $this->db->prepare("
            SELECT gs.*, c.name as console_name, c.type as console_type, c.location
            FROM gaming_sessions gs
            JOIN consoles c ON gs.console_id = c.id
            WHERE gs.id = ? AND gs.status IN ('active', 'paused')
        ");
        $stmt->bind_param("i", $session_id);
        $stmt->execute();
        $session = $stmt->get_result()->fetch_assoc();

        if (!$session) {
            return ['success' => false, 'message' => 'No active or paused session found for this ID.'];
        }

        // Calculate final amounts without modifying the session status or creating a transaction
        $end_time = date('Y-m-d H:i:s');
        $start_time = new DateTime($session['start_time']);
        $end_datetime = new DateTime($end_time);
        $total_seconds_since_start = $end_datetime->getTimestamp() - $start_time->getTimestamp();
        $pause_duration = $session['total_pause_duration'] ?? 0;

        // Get all segments for the session
        $segments = $this->getSessionSegments($session_id);
        $total_gaming_amount = 0;
        $total_actual_play_time_segments = 0; // In seconds

        foreach ($segments as &$segment) {
            $segment_start_time = new DateTime($segment['start_time']);
            $segment_end_time = $segment['end_time'] ? new DateTime($segment['end_time']) : $end_datetime;
            $segment_duration_seconds = $segment_end_time->getTimestamp() - $segment_start_time->getTimestamp();

            // Calculate pause time that occurred during this specific segment
            $pauseStmt = $this->db->prepare("
                SELECT 
                    SUM(CASE 
                        WHEN pause_end IS NOT NULL THEN duration * 60 
                        ELSE 0 
                    END) as completed_pause_seconds,
                    SUM(CASE 
                        WHEN pause_end IS NULL AND pause_start >= ? AND pause_start < ? THEN 
                            TIMESTAMPDIFF(SECOND, pause_start, ?)
                        ELSE 0 
                    END) as ongoing_pause_seconds
                FROM session_pauses 
                WHERE session_id = ? 
                AND pause_start >= ? 
                AND pause_start < ?
            ");
            $segment_start_str = $segment_start_time->format('Y-m-d H:i:s');
            $segment_end_str = $segment_end_time->format('Y-m-d H:i:s');
            $pauseStmt->bind_param("ssssss", $segment_start_str, $segment_end_str, $segment_end_str, $session['id'], $segment_start_str, $segment_end_str);
            $pauseStmt->execute();
            $pauseResult = $pauseStmt->get_result()->fetch_assoc();
            $pause_during_segment = ($pauseResult['completed_pause_seconds'] ?? 0) + ($pauseResult['ongoing_pause_seconds'] ?? 0);

            $segment_actual_play_time = max(0, $segment_duration_seconds - $pause_during_segment);
            $segment_billing = $this->calculateSessionBilling($segment_actual_play_time, $segment['player_count'], $session['rate_type']);
            $segment['calculated_amount'] = $segment_billing['total_amount'];
            $segment['calculated_duration_minutes'] = round($segment_actual_play_time / 60);
            $total_gaming_amount += $segment['calculated_amount'];
            $total_actual_play_time_segments += $segment_actual_play_time;
        }
        unset($segment); // Break the reference with the last element

        // Get F&D total
        $fandd_total = $this->getSessionFandDTotal($session['id']);

        // Get pause history
        $pauses = [];
        $pauseStmt = $this->db->prepare("
            SELECT pause_start, pause_end, reason, duration 
            FROM session_pauses 
            WHERE session_id = ? 
            ORDER BY pause_start ASC
        ");
        $pauseStmt->bind_param("i", $session['id']);
        if ($pauseStmt->execute()) {
            $pauses = $pauseStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        // Calculate totals based on summed gaming amount and fandd
        $subtotal = $total_gaming_amount + $fandd_total;
        $tax_rate = $this->getTaxRate();
        $tax_amount = $subtotal * $tax_rate;
        $total_amount = $subtotal + $tax_amount;

        return [
            'success' => true,
            'message' => 'Pre-billing details generated',
            'data' => [
                'session_id' => $session['id'],
                'console_id' => $session['console_id'],
                'customer_name' => $session['customer_name'],
                'customer_number' => $session['customer_number'],
                'player_count' => $session['player_count'], // Current player count of the session
                'rate_type' => $session['rate_type'],
                'start_time' => $session['start_time'], // Session start time for total elapsed calculation
                'total_amount' => $total_amount,
                'gaming_amount' => $total_gaming_amount,
                'fandd_amount' => $fandd_total,
                'tax_amount' => $tax_amount,
                'duration_minutes' => round($total_actual_play_time_segments / 60),
                'billing_details' => ['segments' => $segments], // Pass segments as part of billing details
                'fandd_items' => $this->getSessionFandDItems($session['id']),
                'segments' => $segments, // Also pass segments directly for easier access on frontend
                'pauses' => $pauses // Pass pause history
            ]
        ];
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

    private function getSessionFandDItems($session_id)
    {
        $stmt = $this->db->prepare("
            SELECT id, item_name as name, quantity, unit_price, total_price 
            FROM session_items 
            WHERE session_id = ?
        ");
        $stmt->bind_param("i", $session_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    private function getSessionSegmentsApi()
    {
        $session_id = $_GET['session_id'] ?? null;

        if (!$session_id) {
            return ['success' => false, 'message' => 'Session ID is required'];
        }

        return ['success' => true, 'data' => $this->getSessionSegments($session_id)];
    }

    private function getSessionSegments($session_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM session_segments WHERE session_id = ? ORDER BY start_time ASC");
        $stmt->bind_param("i", $session_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    private function getSessionFandDApi()
    {
        $session_id = $_GET['session_id'] ?? null;

        if (!$session_id) {
            return ['success' => false, 'message' => 'Session ID is required'];
        }

        return ['success' => true, 'data' => $this->getSessionFandDItems($session_id)];
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
