<?php
require_once __DIR__ . '/database.php';

class Auth
{

    // Check if user is logged in
    public static function check()
    {
        return isset($_SESSION['user_id']) && isset($_SESSION['user_email']);
    }

    // Get current user ID
    public static function userId()
    {
        return $_SESSION['user_id'] ?? null;
    }

    // Get current user's branch ID
    public static function userBranchId()
    {
        if (!self::check()) {
            return null;
        }
        return $_SESSION['user_branch_id'] ?? null;
    }

    // Get current user data
    public static function user()
    {
        if (!self::check()) {
            return null;
        }

        $db = getDB();
        $userId = self::userId();
        $stmt = $db->prepare("SELECT id, full_name, username, email, phone, role, branch_id, status FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }

    // Login user
    public static function login($username, $password)
    {
        $db = getDB();

        $stmt = $db->prepare("SELECT id, full_name, username, email, password, role, branch_id, status FROM users WHERE (username = ? OR email = ?) AND status = 'Active' LIMIT 1");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        $user = $result->fetch_assoc();

        // Verify password
        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        // Set session data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_branch_id'] = $user['branch_id'];

        // Log activity
        self::logActivity($user['id'], 'login', 'User logged in');

        return ['success' => true, 'user' => $user];
    }

    // Logout user
    public static function logout()
    {
        if (self::check()) {
            self::logActivity(self::userId(), 'logout', 'User logged out');
        }

        session_unset();
        session_destroy();
    }

    // Check if user should be restricted to their branch (Manager or Staff role)
    public static function isManagerRestricted()
    {
        if (!self::check()) {
            return false;
        }

        $userRole = $_SESSION['user_role'] ?? '';
        return in_array($userRole, ['Manager', 'Staff']);
    }

    // Check if user has specific role
    public static function hasRole($role)
    {
        if (!self::check()) {
            return false;
        }

        $userRole = $_SESSION['user_role'] ?? '';

        if ($role === 'Admin') {
            return $userRole === 'Admin';
        } elseif ($role === 'Manager') {
            return in_array($userRole, ['Admin', 'Manager']);
        } elseif ($role === 'Staff') {
            return in_array($userRole, ['Admin', 'Manager', 'Staff']);
        }

        return false;
    }

    // Require authentication (redirect if not logged in)
    public static function require()
    {
        if (!self::check()) {
            header('Location: ' . SITE_URL . '/login.php');
            exit;
        }
    }

    // Require specific role
    public static function requireRole($role)
    {
        self::require();

        if (!self::hasRole($role)) {
            http_response_code(403);
            die('Access denied. You do not have permission to access this resource.');
        }
    }

    // Log activity
    public static function logActivity($userId, $action, $description = null)
    {
        $db = getDB();
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';

        $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userId, $action, $description, $ipAddress);
        $stmt->execute();
    }
}
