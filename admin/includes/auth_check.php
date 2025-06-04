<?php
/**
 * Admin authentication check
 * Verifies that the user is logged in and has admin privileges
 */

// Check if admin flag is set in session
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // Not authenticated as admin, redirect to admin area for proper checks
    header('Location: /admin/');
    exit;
}
?>