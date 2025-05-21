<?php
// This file serves as the main entry point for all admin routes
// Include the main application bootstrap
require_once __DIR__ . '/app/bootstrap.php';

// Load the admin controller
require_once __DIR__ . '/app/controllers/AdminController.php';
$controller = new AdminController();

// Get the request URI and parse it
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Get base path from config
$base_path = $config['base_path'];

// Debug information for troubleshooting
if ($config['debug']) {
    error_log("Admin Request URI: " . $request_uri);
    error_log("Admin Base Path: " . $base_path);
}

// Simplified route handling to make it more reliable
// First, check if we're accessing directly via admin.php
if (strpos($path, '/admin.php') !== false) {
    // Direct access via admin.php
    $route = str_replace('/admin.php', '', $path);
    $route = ltrim($route, '/');
} else {
    // Access via /admin/ routes
    $adminPath = $base_path . 'admin/';
    if (strpos($path, $adminPath) === 0) {
        $route = substr($path, strlen($adminPath));
    } elseif (strpos($path, '/admin/') !== false) {
        $route = substr($path, strpos($path, '/admin/') + 7);
    } else {
        // Just /admin or /path/to/site/admin
        $route = '';
    }
}

// Additional debug info
if ($config['debug']) {
    error_log("Processed Admin Route: " . $route);
}

// Debug route information
if ($config['debug']) {
    error_log("Admin Route: " . $route);
}

// Remove any trailing slashes
$route = rtrim($route, '/');

// Handle nested routes like dashboard/settings
$route_parts = explode('/', $route);
$main_route = $route_parts[0] ?? '';
$sub_route = $route_parts[1] ?? '';
$action = $route_parts[2] ?? '';

// Add more detailed debugging
if ($config['debug']) {
    error_log("Admin route: " . $route);
    error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
}

// Route the request
if ($route === '' || $route === 'login') {
    // Force display of login page for debugging
    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->loginPage();
        }
    } catch (Exception $e) {
        if ($config['debug']) {
            error_log("Error in admin login: " . $e->getMessage());
            echo "<h1>Error in admin login</h1>";
            echo "<p>" . $e->getMessage() . "</p>";
        } else {
            // Just show login page in production
            $controller->loginPage();
        }
    }
} elseif ($route === 'dashboard' || $main_route === 'dashboard') {
    // Handle dashboard and its sub-routes
    if ($sub_route === 'settings') {
        if ($action === 'save' || isset($_GET['action']) && $_GET['action'] == 'save') {
            $controller->saveSettings();
        } else {
            $controller->settings();
        }
    } else {
        $controller->dashboard();
    }
} elseif ($route === 'logout') {
    $controller->logout();
} elseif ($route === 'settings') {
    if (isset($_GET['action']) && $_GET['action'] == 'save') {
        $controller->saveSettings();
    } else {
        $controller->settings();
    }
} elseif ($route === 'settings/save') {
    $controller->saveSettings();
} elseif (strpos($route, 'orders/') === 0) {
    // Handle order routes like orders/123/edit
    $parts = explode('/', substr($route, 7));
    $orderId = $parts[0] ?? '';
    $action = $parts[1] ?? '';
    
    if (is_numeric($orderId)) {
        if ($action == 'edit') {
            $controller->editOrder($orderId);
        } elseif ($action == 'update') {
            $controller->updateOrder($orderId);
        } elseif ($action == 'status' && isset($parts[2]) && in_array($parts[2], ['pending', 'processing', 'delivered', 'successful', 'cancelled'])) {
            $controller->updateOrderStatus($orderId, $parts[2]);
        } else {
            // Default to view order
            $controller->editOrder($orderId);
        }
    } else {
        // Invalid order ID
        header("HTTP/1.0 404 Not Found");
        echo '404 - Order Not Found';
    }
} elseif ($route === 'clear-orders') {
    $controller->clearOrders();
} else {
    // 404 Not Found
    header("HTTP/1.0 404 Not Found");
    echo '404 - Admin Page Not Found: ' . $route;
}
?>
