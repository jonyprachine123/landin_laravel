<?php
// Start session
session_start();

// Load the logger
require_once __DIR__ . '/Logger.php';

// Load configuration
$config = require __DIR__ . '/config.php';

// Set up error handling based on environment
if ($config['environment'] === 'production') {
    // In production, log errors to file and don't display them
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../logs/error.log');
    
    // Initialize the logger
    Logger::init();
} else {
    // In development, display errors
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Database connection
function getDbConnection() {
    $dbPath = __DIR__ . '/../database/database.sqlite';
    
    // Create database file if it doesn't exist
    if (!file_exists($dbPath)) {
        touch($dbPath);
    }
    
    // Connect to SQLite database
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tables if they don't exist
    $db->exec('
        CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            address TEXT NOT NULL,
            phone TEXT NOT NULL,
            package TEXT NOT NULL,
            price REAL NOT NULL,
            shipping_method TEXT NOT NULL,
            shipping_cost REAL NOT NULL,
            country TEXT,
            honey_addon INTEGER DEFAULT 0,
            honey_price REAL DEFAULT 0,
            status TEXT DEFAULT "pending",
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ');
    
    // Check if status column exists, add it if it doesn't
    try {
        $result = $db->query("SELECT status FROM orders LIMIT 1");
    } catch (Exception $e) {
        // Column doesn't exist, add it
        $db->exec('ALTER TABLE orders ADD COLUMN status TEXT DEFAULT "pending"');
    }
    
    // Create settings table if it doesn't exist
    $db->exec('
        CREATE TABLE IF NOT EXISTS settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            setting_key TEXT NOT NULL UNIQUE,
            setting_value TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ');
    
    return $db;
}

// Helper functions
function redirect($url) {
    header("Location: $url");
    exit;
}

function view($name, $data = []) {
    extract($data);
    require __DIR__ . "/../resources/views/$name.php";
}
?>
