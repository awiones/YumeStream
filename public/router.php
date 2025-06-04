<?php
// Simple router for PHP development server
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove leading slash
$uri = ltrim($uri, '/');

// Handle static files
if (preg_match('/\.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$/', $uri)) {
    return false; // Let the server handle static files
}

// Handle assets directory
if (strpos($uri, 'assets/') === 0) {
    return false; // Let the server handle assets
}

// Handle admin assets directory
if (strpos($uri, 'admin/assets/') === 0) {
    return false; // Let the server handle admin assets
}

// Set the route parameter
$_GET['route'] = $uri;

// Include the main index.php
require_once 'index.php';