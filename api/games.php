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
                $stmt = $db->prepare("SELECT * FROM games ORDER BY id DESC");
                $stmt->execute();
                $result = $stmt->get_result();
                $games = [];

                while ($row = $result->fetch_assoc()) {
                    // Get assigned consoles
                    $gameId = $row['id'];
                    $consoleStmt = $db->prepare("SELECT console_id FROM game_console_assignments WHERE game_id = ?");
                    $consoleStmt->bind_param("i", $gameId);
                    $consoleStmt->execute();
                    $consoleResult = $consoleStmt->get_result();

                    $assignedConsoles = [];
                    while ($consoleRow = $consoleResult->fetch_assoc()) {
                        $assignedConsoles[] = $consoleRow['console_id'];
                    }

                    $row['assigned_consoles'] = $assignedConsoles;
                    $games[] = $row;
                }

                echo json_encode(['success' => true, 'data' => $games]);
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);

            if ($action === 'create') {
                $stmt = $db->prepare("INSERT INTO games (name, category, developer, rating, release_date, branch_id) VALUES (?, ?, ?, ?, ?, ?)");

                $stmt->bind_param(
                    "sssdsi",
                    $data['name'],
                    $data['category'],
                    $data['developer'],
                    $data['rating'],
                    $data['release_date'],
                    $data['branch_id']
                );

                if ($stmt->execute()) {
                    $gameId = $db->insert_id;

                    // Assign to consoles
                    if (isset($data['consoles']) && is_array($data['consoles'])) {
                        $assignStmt = $db->prepare("INSERT INTO game_console_assignments (game_id, console_id) VALUES (?, ?)");
                        foreach ($data['consoles'] as $consoleId) {
                            $assignStmt->bind_param("ii", $gameId, $consoleId);
                            $assignStmt->execute();
                        }
                    }

                    Auth::logActivity(Auth::userId(), 'game_create', "Created game: {$data['name']}");
                    echo json_encode(['success' => true, 'id' => $gameId, 'message' => 'Game created successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to create game']);
                }
            } elseif ($action === 'update') {
                $id = intval($data['id']);
                $stmt = $db->prepare("UPDATE games SET name = ?, category = ?, developer = ?, rating = ?, release_date = ? WHERE id = ?");

                $stmt->bind_param(
                    "sssdsi",
                    $data['name'],
                    $data['category'],
                    $data['developer'],
                    $data['rating'],
                    $data['release_date'],
                    $id
                );

                if ($stmt->execute()) {
                    // Update console assignments
                    $db->query("DELETE FROM game_console_assignments WHERE game_id = $id");

                    if (isset($data['consoles']) && is_array($data['consoles'])) {
                        $assignStmt = $db->prepare("INSERT INTO game_console_assignments (game_id, console_id) VALUES (?, ?)");
                        foreach ($data['consoles'] as $consoleId) {
                            $assignStmt->bind_param("ii", $id, $consoleId);
                            $assignStmt->execute();
                        }
                    }

                    Auth::logActivity(Auth::userId(), 'game_update', "Updated game: {$data['name']}");
                    echo json_encode(['success' => true, 'message' => 'Game updated successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update game']);
                }
            }
            break;

        case 'DELETE':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $stmt = $db->prepare("DELETE FROM games WHERE id = ?");
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    Auth::logActivity(Auth::userId(), 'game_delete', "Deleted game ID: $id");
                    echo json_encode(['success' => true, 'message' => 'Game deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete game']);
                }
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

