<?php
// This is a direct admin dashboard file that doesn't rely on routing
// Include the main application bootstrap
require_once __DIR__ . '/app/bootstrap.php';

// Load the admin controller
require_once __DIR__ . '/app/controllers/AdminController.php';
$controller = new AdminController();

// Check if logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to the admin login page
    header("Location: admin-login.php");
    exit;
}

// Display the dashboard
$controller->dashboard();
?>
