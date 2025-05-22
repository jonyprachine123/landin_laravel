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

// Handle settings form submission
$success = '';
$error = '';

// Function to convert YouTube watch URL to embed URL
function convertYoutubeUrl($url) {
    // Check if it's already an embed URL
    if (strpos($url, 'youtube.com/embed/') !== false) {
        return $url;
    }
    
    // Extract video ID from watch URL
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';
    if (preg_match($pattern, $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    }
    
    // Return original URL if no match found
    return $url;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metaPixelCode = $_POST['meta_pixel_code'] ?? '';
    $mainHeading = $_POST['main_heading'] ?? '';
    $notificationText = $_POST['notification_text'] ?? '';
    $buttonText = $_POST['button_text'] ?? '';
    $orderButtonText = $_POST['order_button_text'] ?? '';
    $youtubeUrl = $_POST['youtube_url'] ?? '';
    
    // Get pricing information
    $product1Description = $_POST['product1_description'] ?? '';
    $product1Price = $_POST['product1_price'] ?? '';
    $product2Description = $_POST['product2_description'] ?? '';
    $product2Price = $_POST['product2_price'] ?? '';
    $deliveryInfo = $_POST['delivery_info'] ?? '';
    $phoneNumber = $_POST['phone_number'] ?? '';
    $whatsappNumber = $_POST['whatsapp_number'] ?? '';
    
    // Get order form information
    $orderFormHeading = $_POST['order_form_heading'] ?? '';
    $packageSelectText = $_POST['package_select_text'] ?? '';
    $bestSellerLabel = $_POST['best_seller_label'] ?? '';
    $economyLabel = $_POST['economy_label'] ?? '';
    $product1FullDescription = $_POST['product1_full_description'] ?? '';
    $product1RegularPrice = $_POST['product1_regular_price'] ?? '';
    $product1SalePrice = $_POST['product1_sale_price'] ?? '';
    $product2FullDescription = $_POST['product2_full_description'] ?? '';
    $product2RegularPrice = $_POST['product2_regular_price'] ?? '';
    $product2SalePrice = $_POST['product2_sale_price'] ?? '';
    $billingDetailsHeading = $_POST['billing_details_heading'] ?? '';
    $nameLabel = $_POST['name_label'] ?? '';
    $addressLabel = $_POST['address_label'] ?? '';
    $phoneLabel = $_POST['phone_label'] ?? '';
    $shippingHeading = $_POST['shipping_heading'] ?? '';
    $outsideDhakaLabel = $_POST['outside_dhaka_label'] ?? '';
    $outsideDhakaCost = $_POST['outside_dhaka_cost'] ?? '';
    $insideDhakaLabel = $_POST['inside_dhaka_label'] ?? '';
    $insideDhakaCost = $_POST['inside_dhaka_cost'] ?? '';
    $orderSummaryHeading = $_POST['order_summary_heading'] ?? '';
    $productColumnHeading = $_POST['product_column_heading'] ?? '';
    $subtotalColumnHeading = $_POST['subtotal_column_heading'] ?? '';
    $codLabel = $_POST['cod_label'] ?? '';
    $codDescription = $_POST['cod_description'] ?? '';
    $confirmOrderButtonText = $_POST['confirm_order_button_text'] ?? '';
    $orderConfirmationText = $_POST['order_confirmation_text'] ?? '';
    
    // Convert YouTube URL if needed
    $youtubeUrl = convertYoutubeUrl($youtubeUrl);
    
    // Create an array of order form information
    $orderFormInfo = [
        'order_form_heading' => $orderFormHeading,
        'package_select_text' => $packageSelectText,
        'best_seller_label' => $bestSellerLabel,
        'economy_label' => $economyLabel,
        'product1_full_description' => $product1FullDescription,
        'product1_regular_price' => $product1RegularPrice,
        'product1_sale_price' => $product1SalePrice,
        'product2_full_description' => $product2FullDescription,
        'product2_regular_price' => $product2RegularPrice,
        'product2_sale_price' => $product2SalePrice,
        'billing_details_heading' => $billingDetailsHeading,
        'name_label' => $nameLabel,
        'address_label' => $addressLabel,
        'phone_label' => $phoneLabel,
        'shipping_heading' => $shippingHeading,
        'outside_dhaka_label' => $outsideDhakaLabel,
        'outside_dhaka_cost' => $outsideDhakaCost,
        'inside_dhaka_label' => $insideDhakaLabel,
        'inside_dhaka_cost' => $insideDhakaCost,
        'order_summary_heading' => $orderSummaryHeading,
        'product_column_heading' => $productColumnHeading,
        'subtotal_column_heading' => $subtotalColumnHeading,
        'cod_label' => $codLabel,
        'cod_description' => $codDescription,
        'confirm_order_button_text' => $confirmOrderButtonText,
        'order_confirmation_text' => $orderConfirmationText
    ];
    
    // Create an array of pricing information
    $pricingInfo = [
        'product1_description' => $product1Description,
        'product1_price' => $product1Price,
        'product2_description' => $product2Description,
        'product2_price' => $product2Price,
        'delivery_info' => $deliveryInfo,
        'phone_number' => $phoneNumber,
        'whatsapp_number' => $whatsappNumber
    ];
    

    
    // Save Main Heading setting
    $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
    $stmt->execute([':key' => 'main_heading']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        // Update existing setting
        $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
        $stmt->execute([':value' => $mainHeading, ':key' => 'main_heading']);
    } else {
        // Insert new setting
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
        $stmt->execute([':key' => 'main_heading', ':value' => $mainHeading]);
    }
    
    // Save Notification Text setting
    $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
    $stmt->execute([':key' => 'notification_text']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        // Update existing setting
        $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
        $stmt->execute([':value' => $notificationText, ':key' => 'notification_text']);
    } else {
        // Insert new setting
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
        $stmt->execute([':key' => 'notification_text', ':value' => $notificationText]);
    }
    
    // Save Button Text setting
    $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
    $stmt->execute([':key' => 'button_text']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Debug: Log the button text value being saved
    error_log('Saving button text: ' . $buttonText);
    
    if ($result) {
        // Update existing setting
        $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
        $stmt->execute([':value' => $buttonText, ':key' => 'button_text']);
    } else {
        // Insert new setting
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
        $stmt->execute([':key' => 'button_text', ':value' => $buttonText]);
    }
    
    // Save Order Button Text setting
    $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
    $stmt->execute([':key' => 'order_button_text']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        // Update existing setting
        $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
        $stmt->execute([':value' => $orderButtonText, ':key' => 'order_button_text']);
    } else {
        // Insert new setting
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
        $stmt->execute([':key' => 'order_button_text', ':value' => $orderButtonText]);
    }
    
    // We're now saving all pricing and order form settings as JSON objects instead of individual settings
    // This prevents database errors from unique constraint violations
    
    // Save Pricing Info setting as JSON
    $pricingInfoJson = json_encode($pricingInfo);
    $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
    $stmt->execute([':key' => 'pricing_info']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
        $stmt->execute([':value' => $pricingInfoJson, ':key' => 'pricing_info']);
    } else {
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
        $stmt->execute([':key' => 'pricing_info', ':value' => $pricingInfoJson]);
    }
    
    // Save Order Form Info setting as JSON
    $orderFormInfoJson = json_encode($orderFormInfo);
    $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
    $stmt->execute([':key' => 'order_form_info']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
        $stmt->execute([':value' => $orderFormInfoJson, ':key' => 'order_form_info']);
    } else {
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
        $stmt->execute([':key' => 'order_form_info', ':value' => $orderFormInfoJson]);
    }
    
    // Save YouTube URL setting
    $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
    $stmt->execute([':key' => 'youtube_url']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
        $stmt->execute([':value' => $youtubeUrl, ':key' => 'youtube_url']);
    } else {
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
        $stmt->execute([':key' => 'youtube_url', ':value' => $youtubeUrl]);
    }
    

    
    // Debug: Log the YouTube URL value being saved
    error_log('Saving YouTube URL: ' . $youtubeUrl);
    
    // Handle customer reviews
    if (isset($_POST['customer_reviews_section_submitted'])) {
        $customerReviews = isset($_POST['customer_reviews']) ? json_encode($_POST['customer_reviews']) : '[]';
        
        // Save Customer Reviews setting
        $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
        $stmt->execute([':key' => 'customer_reviews']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            // Update existing setting
            $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
            $stmt->execute([':value' => $customerReviews, ':key' => 'customer_reviews']);
        } else {
            // Insert new setting
            $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
            $stmt->execute([':key' => 'customer_reviews', ':value' => $customerReviews]);
        }
        
        // Update the settings array
        $settings['customer_reviews'] = $customerReviews;
    }
    
    // Update the settings array
    $settings['meta_pixel_code'] = $metaPixelCode;
    $settings['main_heading'] = $mainHeading;
    $settings['notification_text'] = $notificationText;
    $settings['button_text'] = $buttonText;
    $settings['order_button_text'] = $orderButtonText;
    $settings['youtube_url'] = $youtubeUrl;
    $settings['product1_description'] = $product1Description;
    $settings['product1_price'] = $product1Price;
    $settings['product2_description'] = $product2Description;
    $settings['product2_price'] = $product2Price;
    $settings['delivery_info'] = $deliveryInfo;
    $settings['phone_number'] = $phoneNumber;
    $settings['whatsapp_number'] = $whatsappNumber;
    
    // Set success message
    $success = 'Settings have been saved successfully';
    
    // Redirect back to the settings page to prevent form resubmission
    header("Location: admin-settings.php?success=1");
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
    <title>Admin Settings - Prachin Bangla</title>
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
        .code-editor {
            font-family: monospace;
        }
        .review-item {
            background-color: #f8f9fa;
        }
        .accordion-button:not(.collapsed) {
            background-color: #e7f1ff;
            color: #0c63e4;
        }
        .accordion-item {
            margin-bottom: 15px;
            border-radius: 5px;
            overflow: hidden;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .settings-nav {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .settings-nav .nav-link {
            color: #495057;
            border-radius: 0;
            padding: 15px 20px;
        }
        .settings-nav .nav-link.active {
            color: #007bff;
            background-color: #f8f9fa;
            border-bottom: 2px solid #007bff;
        }
        .settings-section {
            display: none;
        }
        .settings-section.active {
            display: block;
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

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="col-md-10">
                <div class="content-wrapper">
                    <h1 class="mb-4">Admin Settings</h1>
                    
                    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div class="alert alert-success">
                        Settings have been saved successfully.
                    </div>
                    <?php endif; ?>
                    
                    <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-home fa-3x mb-3 text-primary"></i>
                                    <h5 class="card-title">General Content</h5>
                                    <p class="card-text">Manage main heading, notification text, and button text.</p>
                                    <a href="admin-settings-general.php" class="btn btn-primary">Edit Settings</a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-shopping-cart fa-3x mb-3 text-success"></i>
                                    <h5 class="card-title">Order Form</h5>
                                    <p class="card-text">Customize order form fields, labels, and button text.</p>
                                    <a href="admin-settings-order.php" class="btn btn-success">Edit Settings</a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-tag fa-3x mb-3 text-danger"></i>
                                    <h5 class="card-title">Pricing</h5>
                                    <p class="card-text">Update product descriptions, prices, and delivery info.</p>
                                    <a href="admin-settings-pricing.php" class="btn btn-danger">Edit Settings</a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-copyright fa-3x mb-3 text-info"></i>
                                    <h5 class="card-title">Footer</h5>
                                    <p class="card-text">Edit footer copyright text and links.</p>
                                    <a href="admin-settings-footer.php" class="btn btn-info">Edit Settings</a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-star fa-3x mb-3 text-warning"></i>
                                    <h5 class="card-title">Customer Reviews</h5>
                                    <p class="card-text">Manage customer review images and content.</p>
                                    <a href="admin-settings-reviews.php" class="btn btn-warning">Edit Settings</a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-code fa-3x mb-3 text-secondary"></i>
                                    <h5 class="card-title">Meta Pixel</h5>
                                    <p class="card-text">Configure Meta Pixel tracking code.</p>
                                    <a href="admin-settings-meta.php" class="btn btn-secondary">Edit Settings</a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                                    <h5 class="card-title">Thank You Page</h5>
                                    <p class="card-text">Customize the thank you page content and messages.</p>
                                    <a href="admin-settings-thankyou.php" class="btn btn-success">Edit Settings</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    

                        </div>
                    </div>
                    



                </form>

                <div class="card">
                    <div class="card-header">
                        Preview
                    </div>
                    <div class="card-body">
                        <h5>Current Meta Pixel Code</h5>
                        <?php if (empty($settings['meta_pixel_code'])): ?>
                            <div class="alert alert-info">No Meta Pixel code has been added yet.</div>
                        <?php else: ?>
                            <pre class="bg-light p-3 border rounded"><code><?php echo htmlspecialchars($settings['meta_pixel_code']); ?></code></pre>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image upload functionality
        const uploadButton = document.getElementById('uploadButton');
        const fileInput = document.getElementById('reviewImageUpload');
        const progressBar = document.getElementById('uploadProgress');
        const progressBarInner = progressBar.querySelector('.progress-bar');
        const statusDiv = document.getElementById('uploadStatus');
        const reviewsContainer = document.getElementById('reviewsContainer');
        
        // Handle delete buttons for review items
        reviewsContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-review')) {
                // Ask for confirmation before deleting
                if (confirm('Are you sure you want to delete this review?')) {
                    // Get the review item to delete
                    const reviewItem = e.target.closest('.review-item');
                    
                    // Remove the review item
                    reviewItem.remove();
                    
                    // Renumber the remaining review items
                    renumberReviewItems();
                    
                    // Show success message
                    showStatus('Review deleted successfully', 'success');
                }
            }
        });
        
        // Function to renumber review items after deletion
        function renumberReviewItems() {
            const reviewItems = reviewsContainer.querySelectorAll('.review-item');
            
            reviewItems.forEach((item, index) => {
                // Update the input names with the new index
                const inputs = item.querySelectorAll('input[name^="customer_reviews"]');
                
                inputs.forEach(input => {
                    const name = input.name;
                    const newName = name.replace(/customer_reviews\[\d+\]/, `customer_reviews[${index}]`);
                    input.name = newName;
                });
                
                // Update the data-index attribute on the delete button
                const deleteButton = item.querySelector('.delete-review');
                if (deleteButton) {
                    deleteButton.setAttribute('data-index', index);
                }
            });
        }
        
        uploadButton.addEventListener('click', function() {
            // Check if a file is selected
            if (!fileInput.files.length) {
                showStatus('Please select a file to upload', 'danger');
                return;
            }
            
            const file = fileInput.files[0];
            
            // Check file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                showStatus('Invalid file type. Only JPG, PNG, and GIF are allowed.', 'danger');
                return;
            }
            
            // Create FormData object
            const formData = new FormData();
            formData.append('review_image', file);
            
            // Show progress bar
            progressBar.classList.remove('d-none');
            progressBarInner.style.width = '0%';
            progressBarInner.setAttribute('aria-valuenow', 0);
            
            // Create and configure XMLHttpRequest
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'upload-handler.php', true);
            
            // Track upload progress
            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    progressBarInner.style.width = percentComplete + '%';
                    progressBarInner.setAttribute('aria-valuenow', percentComplete);
                }
            };
            
            // Handle response
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            showStatus('File uploaded successfully!', 'success');
                            
                            // Add new review item to the form
                            addNewReviewItem(response.filename);
                            
                            // Reset file input
                            fileInput.value = '';
                        } else {
                            showStatus('Error: ' + response.message, 'danger');
                        }
                    } catch (e) {
                        showStatus('Error parsing server response', 'danger');
                    }
                } else {
                    showStatus('Error uploading file: ' + xhr.statusText, 'danger');
                }
                
                // Hide progress bar after a delay
                setTimeout(function() {
                    progressBar.classList.add('d-none');
                }, 2000);
            };
            
            // Handle errors
            xhr.onerror = function() {
                showStatus('Network error occurred', 'danger');
                progressBar.classList.add('d-none');
            };
            
            // Send the request
            xhr.send(formData);
        });
        
        // Function to show status messages
        function showStatus(message, type) {
            statusDiv.textContent = message;
            statusDiv.className = 'alert alert-' + type;
            statusDiv.classList.remove('d-none');
            
            // Hide the message after 5 seconds
            setTimeout(function() {
                statusDiv.classList.add('d-none');
            }, 5000);
        }
        
        // Function to add a new review item to the form
        function addNewReviewItem(filename) {
            // Get the current number of review items
            const reviewItems = reviewsContainer.querySelectorAll('.review-item');
            const newIndex = reviewItems.length;
            
            // Create a new review item
            const newItem = document.createElement('div');
            newItem.className = 'review-item mb-3 p-3 border rounded';
            newItem.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Image Filename</label>
                            <input type="text" class="form-control" name="customer_reviews[${newIndex}][image]" value="${filename}" placeholder="e.g., Customer Review 1.png">
                            <small class="text-muted">Enter the filename of the image in the images folder</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Alt Text</label>
                            <input type="text" class="form-control" name="customer_reviews[${newIndex}][alt]" value="Customer Review" placeholder="e.g., Customer Review 1">
                        </div>
                    </div>
                </div>
            `;
            
            // Add the new item to the container
            reviewsContainer.appendChild(newItem);
        }
        
        // Add collapsible sections functionality
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            // Skip the first card which contains the form submit button
            if (index === cards.length - 1) return;
            
            const cardHeader = card.querySelector('.card-header');
            const cardBody = card.querySelector('.card-body');
            
            // Add toggle icon and make header clickable
            cardHeader.innerHTML = `<div class="d-flex justify-content-between align-items-center">
                <span>${cardHeader.textContent}</span>
                <i class="fas fa-chevron-down"></i>
            </div>`;
            cardHeader.style.cursor = 'pointer';
            
            // Initially hide all card bodies except the first one
            if (index > 0) {
                cardBody.style.display = 'none';
            }
            
            // Add click event to toggle visibility
            cardHeader.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (cardBody.style.display === 'none') {
                    cardBody.style.display = 'block';
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    cardBody.style.display = 'none';
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            });
        });
    });
    </script>
</body>
</html>
