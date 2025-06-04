<?php
session_start();

// Include necessary files
require_once '../config/database.php';
require_once '../includes/Router.php';

// Create router instance
$router = new Router();

// Define routes with absolute paths
$router->add('', __DIR__ . '/../includes/pages/home.php');
$router->add('home', __DIR__ . '/../includes/pages/home.php');
$router->add('login', __DIR__ . '/../includes/pages/auth/login.php');
$router->add('register', __DIR__ . '/../includes/pages/auth/register.php');
$router->add('logout', __DIR__ . '/../includes/pages/auth/logout.php');

// Admin routes - redirect to admin area
$router->add('admin', function() {
    header('Location: /admin/');
    exit;
});

// Admin routes - proxy to admin directory
$router->add('admin/dashboard', function() {
    // Proxy the request to the public admin directory
    include __DIR__ . '/admin/index.php';
    exit;
});

$router->add('admin/users', function() {
    // Proxy the request to the public admin directory
    include __DIR__ . '/admin/index.php';
    exit;
});

$router->add('admin/anime', function() {
    // Proxy the request to the public admin directory
    include __DIR__ . '/admin/index.php';
    exit;
});

$router->add('admin/episodes', function() {
    // Proxy the request to the public admin directory
    include __DIR__ . '/admin/index.php';
    exit;
});

$router->add('admin/genres', function() {
    // Proxy the request to the public admin directory
    include __DIR__ . '/admin/index.php';
    exit;
});

$router->add('admin/reports', function() {
    // Proxy the request to the public admin directory
    include __DIR__ . '/admin/index.php';
    exit;
});

$router->add('admin/settings', function() {
    // Proxy the request to the public admin directory
    include __DIR__ . '/admin/index.php';
    exit;
});

// Get the requested route
$route = isset($_GET['route']) ? $_GET['route'] : '';

// Route the request
$router->route($route);
