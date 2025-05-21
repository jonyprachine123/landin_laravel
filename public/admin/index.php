<?php
// This file serves as a front controller for admin routes
// Include the main application bootstrap
require_once __DIR__ . '/../../app/bootstrap.php';

// Get the request URI and parse it
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Remove base admin path
$base_admin_path = '/admin/';
if (strpos($path, $base_admin_path) === 0) {
    $path = substr($path, strlen($base_admin_path));
} else {
    $path = '';
}

// Load the admin controller
require_once __DIR__ . '/../../app/controllers/AdminController.php';
$controller = new AdminController();

// Route the request
switch ($path) {
    case '':
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->loginPage();
        }
        break;
        
    case 'dashboard':
        $controller->dashboard();
        break;
        
    case 'logout':
        $controller->logout();
        break;
        
    case 'settings':
        if (isset($_GET['action']) && $_GET['action'] == 'save') {
            $controller->saveSettings();
        } else {
            $controller->settings();
        }
        break;
        
    case 'clear-orders':
        $controller->clearOrders();
        break;
        
    default:
        // Check if it's an order-related request
        if (strpos($path, 'orders/') === 0) {
            $parts = explode('/', substr($path, 7));
            $id = $parts[0] ?? '';
            $action = $parts[1] ?? '';
            
            if (is_numeric($id)) {
                if ($action == 'edit') {
                    $controller->editOrder($id);
                } elseif ($action == 'update') {
                    $controller->updateOrder($id);
                } elseif ($action == 'status' && isset($parts[2]) && in_array($parts[2], ['pending', 'processing', 'delivered', 'successful', 'cancelled'])) {
                    $controller->updateOrderStatus($id, $parts[2]);
                } else {
                    header("HTTP/1.0 404 Not Found");
                    echo '404 - Admin Action Not Found';
                }
            } else {
                header("HTTP/1.0 404 Not Found");
                echo '404 - Invalid Order ID';
            }
        } else {
            // 404 Not Found
            header("HTTP/1.0 404 Not Found");
            echo '404 - Admin Page Not Found';
        }
        break;
}
?>
