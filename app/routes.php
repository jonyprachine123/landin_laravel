<?php
// Load configuration
$config = require __DIR__ . '/config.php';

// Get the request URI
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Remove base path using the configuration
$base_path = $config['base_path'];

// Debug information
if ($config['debug']) {
    error_log("Request URI: " . $request_uri);
    error_log("Base Path: " . $base_path);
}

// Ensure the base path is properly formatted for replacement
$path = str_replace($base_path, '', $path);

// Additional check for localhost direct access
if (empty($path) && strpos($request_uri, '/Landing/public') !== false) {
    $path = '';
}

// Split path into segments
$segments = explode('/', trim($path, '/'));
$main_route = $segments[0] ?? '';
$sub_route = $segments[1] ?? '';
$action = $segments[2] ?? '';
$id = $segments[3] ?? '';

// Admin routes are now handled by admin.php via .htaccess
// Only handle non-admin routes here
if ($main_route !== 'admin') {
    // Debug information
    if ($config['debug']) {
        error_log("Path after processing: " . $path);
        error_log("Main route: " . $main_route);
    }
    
    // Regular routes
    switch ($path) {
        case '':
        case 'index.php':
        case 'home':
        case 'index':
            require __DIR__ . '/controllers/HomeController.php';
            $controller = new HomeController();
            $controller->index();
            break;
            
        case 'order':
            require __DIR__ . '/controllers/OrderController.php';
            $controller = new OrderController();
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->store($_POST);
            } else {
                $controller->index();
            }
            break;
            
        case 'thank-you':
            require __DIR__ . '/controllers/OrderController.php';
            $controller = new OrderController();
            $controller->thankYou();
            break;
            
        default:
            // Try to handle the request using the main_route
            if ($main_route == '') {
                // If no route specified, show the home page
                require __DIR__ . '/controllers/HomeController.php';
                $controller = new HomeController();
                $controller->index();
            } else {
                // 404 Not Found
                header("HTTP/1.0 404 Not Found");
                echo '404 - Page Not Found: ' . $path;
                
                if ($config['debug']) {
                    echo '<br>Debug info:<br>';
                    echo 'Request URI: ' . htmlspecialchars($request_uri) . '<br>';
                    echo 'Base Path: ' . htmlspecialchars($base_path) . '<br>';
                    echo 'Processed Path: ' . htmlspecialchars($path) . '<br>';
                }
            }
    }
}
?>
