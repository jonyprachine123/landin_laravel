<?php
// Include the bootstrap file
require_once __DIR__ . '/../app/bootstrap.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    // Return error response
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['review_image']) || $_FILES['review_image']['error'] !== UPLOAD_ERR_OK) {
    // Return error response
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    exit;
}

// Define allowed file types
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];

// Check file type
if (!in_array($_FILES['review_image']['type'], $allowedTypes)) {
    // Return error response
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and GIF are allowed.']);
    exit;
}

// Generate a safe filename
$originalName = $_FILES['review_image']['name'];
$filename = preg_replace('/[^a-zA-Z0-9_.-]/', '', $originalName);

// Set upload directory
$uploadDir = __DIR__ . '/images/';

// Make sure the directory exists
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Move the uploaded file
if (move_uploaded_file($_FILES['review_image']['tmp_name'], $uploadDir . $filename)) {
    // Return success response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true, 
        'message' => 'File uploaded successfully',
        'filename' => $filename
    ]);
} else {
    // Return error response
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Failed to save the file']);
}
