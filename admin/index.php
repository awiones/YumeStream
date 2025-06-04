<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Not logged in, redirect to login page with return URL
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: /login');
    exit;
}

// Check if user has admin role
$stmt = getDB()->prepare("
    SELECT r.name 
    FROM users u
    JOIN roles r ON u.role_id = r.id
    WHERE u.id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$role = $stmt->fetchColumn();

if ($role !== 'admin') {
    // Not an admin, redirect to home page with error message
    $_SESSION['error_message'] = 'You do not have permission to access the admin area.';
    header('Location: /home');
    exit;
}

// Set admin session flag
$_SESSION['is_admin'] = true;

// Get the requested page from URL
$requestUri = $_SERVER['REQUEST_URI'];
$adminBasePath = '/admin/';

// Check if there's a specific admin page requested
if (strlen($requestUri) > strlen($adminBasePath)) {
    $page = substr($requestUri, strlen($adminBasePath));
    
    // Handle direct access to admin pages with clean URLs
    if (preg_match('/^([a-zA-Z0-9_-]+)$/', $page, $matches)) {
        $pageName = $matches[1];
        $pagePath = __DIR__ . '/pages/' . $pageName . '.php';
        
        if (file_exists($pagePath)) {
            // Include the requested page
            include $pagePath;
            exit;
        }
    }
    
    // For backward compatibility, also handle /pages/name.php format
    if (preg_match('/^pages\/([a-zA-Z0-9_-]+)\.php/', $page, $matches)) {
        $pageName = $matches[1];
        $pagePath = __DIR__ . '/pages/' . $pageName . '.php';
        
        if (file_exists($pagePath)) {
            // Redirect to clean URL
            header('Location: /admin/' . $pageName);
            exit;
        }
    }
}

// Default: if no specific page is requested, show dashboard
include __DIR__ . '/pages/dashboard.php';
exit;
?>