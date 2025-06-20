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
$router->add('admin', function () {
    $_GET['page'] = 'dashboard';
    include __DIR__ . '/../admin/index.php';
    exit;
});

// Admin dynamic route - match /admin/{page} and proxy to admin/index.php
$router->add('admin/(.*)', function ($matches) {
    // Extract the page from the route and treat it as the page name
    $page = $matches[0];
    // Only allow certain admin pages
    $allowed_pages = ['dashboard', 'users', 'anime', 'episodes', 'genres', 'reports', 'settings'];
    $allowed_subpages = ['anime/add', 'anime/edit', 'anime/list'];
    $is_subpage = strpos($page, '/') !== false;
    $main_page = $is_subpage ? explode('/', $page)[0] : $page;
    if (!in_array($page, $allowed_pages) && !in_array($page, $allowed_subpages) && !in_array($main_page, $allowed_pages)) {
        $page = 'dashboard';
    }
    $_GET['page'] = $page;
    include __DIR__ . '/../admin/index.php';
    exit;
});

// Get the requested route
$route = isset($_GET['route']) ? $_GET['route'] : '';

// Route the request
$router->route($route);
