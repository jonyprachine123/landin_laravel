<?php
// Direct admin access file that preserves the original admin interface
// Include the bootstrap file
require_once __DIR__ . '/app/bootstrap.php';
require_once __DIR__ . '/app/controllers/AdminController.php';

// Create the admin controller
$controller = new AdminController();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['admin_id']);

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$isLoggedIn) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Connect to database
    $db = getDbConnection();
    
    // Check credentials
    $stmt = $db->prepare("SELECT * FROM admins WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin && password_verify($password, $admin['password'])) {
        // Set session
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $isLoggedIn = true;
        
        // Redirect to the dashboard section of this page
        header("Location: admin-direct.php?section=dashboard");
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Unset admin session variables
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    $isLoggedIn = false;
    
    // Redirect to the login section
    header("Location: admin-direct.php");
    exit;
}

// Determine which section to show
$section = isset($_GET['section']) ? $_GET['section'] : '';

// If not logged in, always show login page
if (!$isLoggedIn) {
    // Show login page
    include __DIR__ . '/resources/views/admin/login.php';
    exit;
}

// If logged in, show the appropriate section
switch ($section) {
    case 'dashboard':
    default:
        // Show dashboard
        $db = getDbConnection();
        
        // Get all orders
        $stmt = $db->prepare("SELECT * FROM orders ORDER BY created_at DESC");
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate today's orders
        $todayOrders = 0;
        $today = date('Y-m-d');
        foreach ($orders as $order) {
            if (date('Y-m-d', strtotime($order['created_at'])) == $today) {
                $todayOrders++;
            }
        }
        
        // Calculate total revenue
        $totalRevenue = 0;
        foreach ($orders as $order) {
            $totalRevenue += $order['price'] + $order['shipping_cost'] + $order['honey_price'];
        }
        
        include __DIR__ . '/resources/views/admin/dashboard.php';
        break;
        
    case 'settings':
        // Get all settings
        $settings = [];
        $db = getDbConnection();
        $stmt = $db->prepare("SELECT setting_key, setting_value FROM settings");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert to associative array
        foreach ($results as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        
        // Handle settings form submission
        if (isset($_POST['meta_pixel_code'])) {
            $metaPixelCode = $_POST['meta_pixel_code'] ?? '';
            
            // Save setting
            $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
            $stmt->execute([':key' => 'meta_pixel_code']);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                // Update existing setting
                $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
                $stmt->execute([':value' => $metaPixelCode, ':key' => 'meta_pixel_code']);
            } else {
                // Insert new setting
                $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
                $stmt->execute([':key' => 'meta_pixel_code', ':value' => $metaPixelCode]);
            }
            
            // Update the settings array
            $settings['meta_pixel_code'] = $metaPixelCode;
            
            // Set success message
            $success = 'Settings have been saved successfully';
        }
        
        include __DIR__ . '/resources/views/admin/settings.php';
        break;
        
    case 'edit-order':
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        
        if (!is_numeric($id) || $id <= 0) {
            echo '404 - Order Not Found';
            exit;
        }
        
        // Get order by ID
        $db = getDbConnection();
        $stmt = $db->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            echo '404 - Order Not Found';
            exit;
        }
        
        // Handle order update
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $name = $_POST['name'] ?? '';
            $address = $_POST['address'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $package = $_POST['package'] ?? '';
            $shipping_method = $_POST['shipping_method'] ?? '';
            $country = $_POST['country'] ?? 'Bangladesh';
            $status = $_POST['status'] ?? 'pending';
            
            // Validate input
            if (empty($name) || empty($address) || empty($phone) || empty($package) || empty($shipping_method)) {
                $error = 'All fields are required';
            } else {
                // Calculate price based on package
                $price = 0;
                switch ($package) {
                    case '1month':
                        $price = 1200;
                        break;
                    case '15days':
                        $price = 1000;
                        break;
                    case '3month':
                        $price = 2300;
                        break;
                }
                
                // Calculate shipping cost
                $shipping_cost = 0; // Free shipping
                
                // Update order
                $stmt = $db->prepare("
                    UPDATE orders SET 
                    name = :name,
                    address = :address,
                    phone = :phone,
                    package = :package,
                    price = :price,
                    shipping_method = :shipping_method,
                    shipping_cost = :shipping_cost,
                    country = :country,
                    status = :status
                    WHERE id = :id
                ");
                
                $stmt->execute([
                    ':name' => $name,
                    ':address' => $address,
                    ':phone' => $phone,
                    ':package' => $package,
                    ':price' => $price,
                    ':shipping_method' => $shipping_method,
                    ':shipping_cost' => $shipping_cost,
                    ':country' => $country,
                    ':status' => $status,
                    ':id' => $id
                ]);
                
                // Get updated order
                $stmt = $db->prepare("SELECT * FROM orders WHERE id = :id");
                $stmt->execute([':id' => $id]);
                $order = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Set success message
                $success = 'Order has been updated successfully';
            }
        }
        
        include __DIR__ . '/resources/views/admin/edit-order.php';
        break;
}
?>
