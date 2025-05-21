<?php
// This is a direct admin login file that doesn't rely on routing
// Include the main application bootstrap
require_once __DIR__ . '/app/bootstrap.php';

// Check if already logged in
if (isset($_SESSION['admin_id'])) {
    // Redirect to dashboard
    header("Location: admin-dashboard.php");
    exit;
}

// Custom login handler for direct file access
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = 'Username and password are required';
        include __DIR__ . '/resources/views/admin/login.php';
        exit;
    }
    
    // Connect to database
    $db = getDbConnection();
    
    // Check credentials
    $stmt = $db->prepare("SELECT * FROM admins WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$admin || !password_verify($password, $admin['password'])) {
        $error = 'Invalid username or password';
        include __DIR__ . '/resources/views/admin/login.php';
        exit;
    }
    
    // Set session
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    
    // Redirect to dashboard
    header("Location: admin-dashboard.php");
    exit;
} else {
    // Display login form
    include __DIR__ . '/resources/views/admin/login.php';
}
?>
