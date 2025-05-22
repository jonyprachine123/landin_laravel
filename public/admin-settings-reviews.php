<?php
// Simple admin settings file that works directly in the public folder
// Include the bootstrap file
require_once __DIR__ . '/../app/bootstrap.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page
    header("Location: admin-login.php");
    exit;
}

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

// Get customer reviews
$customerReviews = [];
if (isset($settings['customer_reviews'])) {
    $customerReviews = json_decode($settings['customer_reviews'], true) ?: [];
}

// Handle settings form submission
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Customer Reviews
    $customerReviews = [];
    
    if (isset($_POST['customer_reviews']) && is_array($_POST['customer_reviews'])) {
        foreach ($_POST['customer_reviews'] as $review) {
            if (!empty($review['image'])) {
                $customerReviews[] = [
                    'image' => $review['image'],
                    'alt' => $review['alt'] ?? ''
                ];
            }
        }
    }
    
    // Save customer reviews as JSON
    $customerReviewsJson = json_encode($customerReviews, JSON_UNESCAPED_UNICODE);
    
    // Debug information
    error_log('Saving customer reviews: ' . $customerReviewsJson);
    
    // Check if customer_reviews setting exists
    $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
    $stmt->execute([':key' => 'customer_reviews']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
        $stmt->execute([':value' => $customerReviewsJson, ':key' => 'customer_reviews']);
    } else {
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
        $stmt->execute([':key' => 'customer_reviews', ':value' => $customerReviewsJson]);
    }
    
    // Debug: Log the customer reviews being saved
    error_log('Saving customer reviews: ' . $customerReviewsJson);
    
    // Set success message
    $success = 'Customer reviews have been saved successfully';
    
    // Redirect back to the settings page to prevent form resubmission
    header("Location: admin-settings-reviews.php?success=1");
    exit;
}

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Unset admin session variables
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    
    // Redirect to login page
    header("Location: admin-login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews Settings - Prachin Bangla</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }
        .sidebar-header {
            padding: 0 15px 20px 15px;
            border-bottom: 1px solid #495057;
            margin-bottom: 20px;
        }
        .sidebar-header h3 {
            color: #fff;
            font-size: 1.2rem;
            margin-bottom: 0;
        }
        .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 10px 15px;
            margin-bottom: 5px;
        }
        .nav-link:hover {
            color: #fff;
            background-color: #495057;
        }
        .nav-link.active {
            color: #fff;
            background-color: #007bff;
        }
        .nav-link i {
            margin-right: 10px;
        }
        .content-wrapper {
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .review-item {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar">
                <div class="sidebar-header">
                    <h3>Admin Panel</h3>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="admin-dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin-settings.php">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-settings.php?action=logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-10">
                <div class="content-wrapper">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1>Customer Reviews Settings</h1>
                        <a href="admin-settings.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Settings</a>
                    </div>
                    
                    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div class="alert alert-success">
                        <?php echo $success ?: 'Settings have been saved successfully'; ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="card">
                        <div class="card-header">
                            Upload Customer Review Image
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="reviewImageUpload">Select Image</label>
                                <input type="file" class="form-control" id="reviewImageUpload" accept="image/*">
                                <p class="text-muted small">Upload customer review images here. Recommended size: 600px x 400px.</p>
                            </div>
                            <button type="button" class="btn btn-primary" id="uploadButton">Upload Image</button>
                            
                            <div id="uploadProgress" class="progress mt-3 d-none">
                                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div id="uploadStatus" class="alert mt-3 d-none"></div>
                        </div>
                    </div>
                    
                    <form method="POST" action="admin-settings-reviews.php">
                        <div class="card">
                            <div class="card-header">
                                Manage Customer Reviews
                            </div>
                            <div class="card-body">
                                <div id="reviewsContainer">
                                    <?php if (!empty($customerReviews)): ?>
                                        <?php foreach ($customerReviews as $index => $review): ?>
                                            <div class="review-item mb-3 p-3 border rounded">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h5 class="mb-0">Review #<?php echo $index + 1; ?></h5>
                                                    <button type="button" class="btn btn-sm btn-danger delete-review" data-index="<?php echo $index; ?>">Delete</button>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Image Filename</label>
                                                            <input type="text" class="form-control" name="customer_reviews[<?php echo $index; ?>][image]" value="<?php echo htmlspecialchars($review['image']); ?>" placeholder="e.g., Customer Review 1.png">
                                                            <small class="text-muted">Enter the filename of the image in the images folder</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Alt Text</label>
                                                            <input type="text" class="form-control" name="customer_reviews[<?php echo $index; ?>][alt]" value="<?php echo htmlspecialchars($review['alt'] ?? ''); ?>" placeholder="e.g., Customer Review 1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p>No customer reviews have been added yet.</p>
                                    <?php endif; ?>
                                </div>
                                
                                <button type="button" class="btn btn-success mt-3" id="addReviewButton">
                                    <i class="fas fa-plus"></i> Add New Review
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-warning btn-lg">Save Customer Reviews</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Customer Review Image Upload
        document.addEventListener('DOMContentLoaded', function() {
            const uploadButton = document.getElementById('uploadButton');
            const reviewImageUpload = document.getElementById('reviewImageUpload');
            const uploadProgress = document.getElementById('uploadProgress');
            const progressBar = uploadProgress.querySelector('.progress-bar');
            const uploadStatus = document.getElementById('uploadStatus');
            const reviewsContainer = document.getElementById('reviewsContainer');
            
            if (uploadButton) {
                uploadButton.addEventListener('click', function() {
                    if (!reviewImageUpload.files.length) {
                        alert('Please select an image to upload');
                        return;
                    }
                    
                    const file = reviewImageUpload.files[0];
                    const formData = new FormData();
                    formData.append('review_image', file);
                    
                    // Show progress bar
                    uploadProgress.classList.remove('d-none');
                    progressBar.style.width = '0%';
                    progressBar.setAttribute('aria-valuenow', 0);
                    uploadStatus.classList.add('d-none');
                    
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'upload-handler.php', true);
                    
                    xhr.upload.onprogress = function(e) {
                        if (e.lengthComputable) {
                            const percentComplete = (e.loaded / e.total) * 100;
                            progressBar.style.width = percentComplete + '%';
                            progressBar.setAttribute('aria-valuenow', percentComplete);
                        }
                    };
                    
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    uploadStatus.textContent = 'Upload successful! Image added to review list.';
                                    uploadStatus.classList.remove('alert-danger', 'd-none');
                                    uploadStatus.classList.add('alert-success');
                                    
                                    // Clear the file input
                                    reviewImageUpload.value = '';
                                    
                                    // Automatically add the uploaded image to the review list
                                    const reviewCount = document.querySelectorAll('.review-item').length;
                                    const newIndex = reviewCount;
                                    
                                    const newReviewHtml = `
                                    <div class="review-item mb-3 p-3 border rounded">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h5 class="mb-0">Review #${newIndex + 1}</h5>
                                            <button type="button" class="btn btn-sm btn-danger delete-review">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Image</label>
                                            <input type="text" name="customer_reviews[${newIndex}][image]" class="form-control" value="${response.filename}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Alt Text</label>
                                            <input type="text" name="customer_reviews[${newIndex}][alt]" class="form-control" value="Customer Review">
                                        </div>
                                        <div class="mt-2">
                                            <img src="images/${response.filename}" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                    </div>
                                    `;
                                    
                                    // Add the new review to the container
                                    const tempDiv = document.createElement('div');
                                    tempDiv.innerHTML = newReviewHtml;
                                    const newReviewElement = tempDiv.firstElementChild;
                                    reviewsContainer.appendChild(newReviewElement);
                                    
                                    // Add event listener to the new delete button
                                    const deleteButton = newReviewElement.querySelector('.delete-review');
                                    deleteButton.addEventListener('click', function() {
                                        if (confirm('Are you sure you want to delete this review?')) {
                                            const reviewItem = this.closest('.review-item');
                                            reviewItem.remove();
                                        }
                                    });
                                    
                                    // Auto-submit the form to save the new review to the database
                                    document.querySelector('form').submit();
                                } else {
                                    uploadStatus.textContent = 'Error: ' + response.message;
                                    uploadStatus.classList.remove('alert-success', 'd-none');
                                    uploadStatus.classList.add('alert-danger');
                                }
                            } catch (e) {
                                uploadStatus.textContent = 'Error parsing server response';
                                uploadStatus.classList.remove('alert-success', 'd-none');
                                uploadStatus.classList.add('alert-danger');
                            }
                        } else {
                            uploadStatus.textContent = 'Upload failed with status: ' + xhr.status;
                            uploadStatus.classList.remove('alert-success', 'd-none');
                            uploadStatus.classList.add('alert-danger');
                        }
                    };
                    
                    xhr.onerror = function() {
                        uploadStatus.textContent = 'Upload failed due to network error';
                        uploadStatus.classList.remove('alert-success', 'd-none');
                        uploadStatus.classList.add('alert-danger');
                    };
                    
                    xhr.send(formData);
                });
            }
            
            // Handle delete review buttons
            document.querySelectorAll('.delete-review').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Are you sure you want to delete this review?')) {
                        const reviewItem = this.closest('.review-item');
                        reviewItem.remove();
                    }
                });
            });
            
            // Handle add review button
            const addReviewButton = document.getElementById('addReviewButton');
            if (addReviewButton) {
                addReviewButton.addEventListener('click', function() {
                    const reviewCount = document.querySelectorAll('.review-item').length;
                    const newIndex = reviewCount;
                    
                    const newReviewHtml = `
                    <div class="review-item mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Review #${newIndex + 1}</h5>
                            <button type="button" class="btn btn-sm btn-danger delete-review" data-index="${newIndex}">Delete</button>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Image Filename</label>
                                    <input type="text" class="form-control" name="customer_reviews[${newIndex}][image]" value="" placeholder="e.g., Customer Review 1.png">
                                    <small class="text-muted">Enter the filename of the image in the images folder</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Alt Text</label>
                                    <input type="text" class="form-control" name="customer_reviews[${newIndex}][alt]" value="" placeholder="e.g., Customer Review 1">
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                    
                    if (reviewsContainer.querySelector('p')) {
                        reviewsContainer.innerHTML = '';
                    }
                    
                    reviewsContainer.insertAdjacentHTML('beforeend', newReviewHtml);
                    
                    // Add event listener to the new delete button
                    const newDeleteButton = reviewsContainer.querySelector(`.review-item:last-child .delete-review`);
                    newDeleteButton.addEventListener('click', function() {
                        if (confirm('Are you sure you want to delete this review?')) {
                            const reviewItem = this.closest('.review-item');
                            reviewItem.remove();
                            
                            if (document.querySelectorAll('.review-item').length === 0) {
                                reviewsContainer.innerHTML = '<p>No customer reviews have been added yet.</p>';
                            }
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>
