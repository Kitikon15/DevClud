<?php
require_once 'db.php';
if (session_status() === PHP_SESSION_NONE)
    session_start();

// สร้างตาราง users หากยังไม่มี
$conn->exec("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    display_name VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

function is_logged_in()
{
    return !empty($_SESSION['user_id']);
}

function require_login()
{
    if (!is_logged_in()) {
        $next = $_SERVER['REQUEST_URI'];
        header('Location: login.php?next=' . urlencode($next));
        exit;
    }
}

function login_user($username, $password)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        // ตั้งค่า session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['display_name'] = $user['display_name'];
        return true;
    }
    return false;
}

function logout_user()
{
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    session_unset();
    session_destroy();
}

function current_user()
{
    if (!is_logged_in())
        return null;
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'display_name' => $_SESSION['display_name'] ?? null
    ];
}

/**
 * Verify credentials without mutating session.
 */
function verify_credentials($username, $password)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    return $user && password_verify($password, $user['password_hash']);
}

/**
 * Delete a user by username. Returns true on success, false if user not found.
 * If the deleted user is the currently logged-in user, logs them out.
 */
function delete_user_by_username($username)
{
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if (!$user)
        return false;

    $id = $user['id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
        logout_user();
    }

    return true;
}
?>