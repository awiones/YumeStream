<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
require_once '../config/database.php';

// Get PDO instance
$pdo = getDB();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id'])) {
    header('Location: /public/?route=login');
    exit;
}

// Get user role from database
try {
    $stmt = $pdo->prepare("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user || $user['role_name'] !== 'admin') {
        header('Location: /public/');
        exit;
    }
} catch (PDOException $e) {
    header('Location: /public/?route=login');
    exit;
}

// Get current page from URL
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$allowed_pages = ['dashboard', 'users', 'anime', 'episodes', 'genres', 'reports', 'settings'];

if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}

// Include header
include 'includes/header.php';
include 'includes/sidebar.php';

// Include the requested page
$page_file = "pages/{$page}.php";
if (file_exists($page_file)) {
    include $page_file;
} else {
    include 'pages/dashboard.php';
}

// Include footer
include 'includes/footer.php';
