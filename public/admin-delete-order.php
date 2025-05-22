<?php
// Include the bootstrap file
require_once __DIR__ . '/../app/bootstrap.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page
    header("Location: admin-login.php");
    exit;
}

// Check if order ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = 'Order ID is required';
    header("Location: admin-dashboard.php");
    exit;
}

$orderId = $_GET['id'];

// Get database connection
$db = getDbConnection();

// Check if order exists
$stmt = $db->prepare("SELECT * FROM orders WHERE id = :id");
$stmt->execute([':id' => $orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    $_SESSION['error_message'] = 'Order not found';
    header("Location: admin-dashboard.php");
    exit;
}

// Delete the order
$stmt = $db->prepare("DELETE FROM orders WHERE id = :id");
$stmt->execute([':id' => $orderId]);

// Set success message
$_SESSION['success_message'] = 'Order #' . $orderId . ' has been deleted successfully';

// Redirect back to dashboard
header("Location: admin-dashboard.php");
exit;
