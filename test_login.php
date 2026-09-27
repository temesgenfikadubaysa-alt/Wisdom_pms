<?php
session_start();
$_POST['username'] = 'admin';
$_POST['password'] = 'password';
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/login';

// Set session directly
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';

$pdo = App\Core\Database::getInstance();
$stmt = $pdo->prepare("SELECT id, username, full_name, role, branch_id, status FROM users WHERE username = ? AND status = 'active'");
$stmt->execute(['admin']);
$user = $stmt->fetch();
if ($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user'] = $user;
    echo "Logged in as: " . $user['username'] . " (role: " . $user['role'] . ")\n";
    echo "Session user_id: " . $_SESSION['user_id'] . "\n";
}
