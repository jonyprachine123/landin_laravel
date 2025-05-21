<?php
/**
 * Application Configuration
 * 
 * This file contains configuration settings for the application.
 * Modify these settings when deploying to different environments.
 */

// IMPORTANT: Change this to 'production' when deploying to your server
$environment = 'development';

// Environment-specific configurations
$environments = [
    'development' => [
        'base_url' => 'http://localhost/Landing',
        'base_path' => '/Landing/',
        'assets_url' => 'http://localhost/Landing/public',
        'admin_url' => 'http://localhost/Landing/admin',
        'admin_login_url' => '/Landing/admin/login',
        'admin_dashboard_url' => '/Landing/admin/dashboard',
        'admin_edit_order_url' => '/Landing/admin/orders/',
        'admin_update_order_url' => '/Landing/admin/orders/',
        'admin_settings_url' => '/Landing/admin/settings',
        'order_page_url' => '/Landing/order',
        'thank_you_url' => '/Landing/thank-you',
        'db_host' => 'localhost',
        'db_name' => 'landing_db',
        'db_user' => 'root',
        'db_pass' => '',
        'debug' => true
    ],
    'production' => [
        'base_url' => 'https://herbal.prachinbangla.online',
        'base_path' => '/',
        'assets_url' => 'https://herbal.prachinbangla.online/public',
        'admin_url' => 'https://herbal.prachinbangla.online/admin',
        'admin_login_url' => '/admin/login',
        'admin_dashboard_url' => '/admin/dashboard',
        'admin_edit_order_url' => '/admin/orders/',
        'admin_update_order_url' => '/admin/orders/',
        'admin_settings_url' => '/admin/settings',
        'order_page_url' => '/order',
        'thank_you_url' => '/thank-you',
        'db_host' => 'localhost', // Update with your production database settings
        'db_name' => 'landing_db',
        'db_user' => 'root',
        'db_pass' => '',
        'debug' => false
    ]
];

// Automatically detect environment based on hostname
if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'herbal.prachinbangla.online') {
    $environment = 'production';
}

// Load the appropriate environment configuration
$env_config = $environments[$environment];

// Build the complete configuration array
$config = [
    // Environment
    'environment' => $environment,
    
    // URLs and paths
    'base_url' => $env_config['base_url'],
    'base_path' => $env_config['base_path'],
    'assets_url' => $env_config['assets_url'],
    'admin_login_url' => $env_config['admin_login_url'],
    'admin_dashboard_url' => $env_config['admin_dashboard_url'],
    'admin_edit_order_url' => $env_config['admin_edit_order_url'],
    'admin_update_order_url' => $env_config['admin_update_order_url'],
    'order_page_url' => $env_config['order_page_url'],
    'thank_you_url' => $env_config['thank_you_url'],
    
    // Database
    'db_host' => $env_config['db_host'],
    'db_name' => $env_config['db_name'],
    'db_user' => $env_config['db_user'],
    'db_pass' => $env_config['db_pass'],
    
    // Site information
    'site_name' => 'Prachin Bangla Limited',
    'site_title' => 'Needus - Prachin Bangla Limited',
    'admin_email' => 'admin@prachinbangla.online',
    
    // File paths
    'upload_path' => __DIR__ . '/../public/uploads/',
    'images_path' => __DIR__ . '/../public/images/',
    
    // Session
    'session_lifetime' => 86400, // 24 hours
    
    // Debug
    'debug' => $env_config['debug']
];

// Set error reporting based on environment
if ($config['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

return $config;
?>
