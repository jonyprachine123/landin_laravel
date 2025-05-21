<?php
// Define the application path with absolute path
define('APP_PATH', realpath(__DIR__ . '/app'));

// Load the bootstrap file
require_once APP_PATH . '/bootstrap.php';

// Load the routes file
require_once APP_PATH . '/routes.php';