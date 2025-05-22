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

// Get pricing info
$pricingInfo = [];
if (isset($settings['pricing_info'])) {
    $pricingInfo = json_decode($settings['pricing_info'], true) ?: [];
}

// Handle settings form submission
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pricing Info
    $pricingHeading = $_POST['pricing_heading'] ?? '';
    $pricingSubheading = $_POST['pricing_subheading'] ?? '';
    $product1Description = $_POST['product1_description'] ?? '';
    $product1Price = $_POST['product1_price'] ?? '';
    $product2Description = $_POST['product2_description'] ?? '';
    $product2Price = $_POST['product2_price'] ?? '';
    $deliveryInfo = $_POST['delivery_info'] ?? '';
    $phoneNumber = $_POST['phone_number'] ?? '';
    $whatsappNumber = $_POST['whatsapp_number'] ?? '';
    $paymentInfo = $_POST['payment_info'] ?? '';
    
    // Create pricing info array
    $pricingInfo = [
        'heading' => $pricingHeading,
        'subheading' => $pricingSubheading,
        'product1_description' => $product1Description,
        'product1_price' => $product1Price,
        'product2_description' => $product2Description,
        'product2_price' => $product2Price,
        'delivery_info' => $deliveryInfo,
        'phone_number' => $phoneNumber,
        'whatsapp_number' => $whatsappNumber,
        'payment_info' => $paymentInfo
    ];
    
    // Save pricing info as JSON
    $pricingInfoJson = json_encode($pricingInfo, JSON_UNESCAPED_UNICODE);
    
    // Check if pricing_info setting exists
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
    
    // Debug: Log the pricing info being saved
    error_log('Saving pricing info: ' . $pricingInfoJson);
    
    // Set success message
    $success = 'Pricing settings have been saved successfully';
    
    // Redirect back to the settings page to prevent form resubmission
    header("Location: admin-settings-pricing.php?success=1");
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
    <title>Pricing Settings - Prachin Bangla</title>
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
                        <h1>Pricing Settings</h1>
                        <a href="admin-settings.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Settings</a>
                    </div>
                    
                    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div class="alert alert-success">
                        <?php echo $success ?: 'Settings have been saved successfully'; ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="admin-settings-pricing.php">
                        <div class="card">
                            <div class="card-header">
                                Pricing Section Heading
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="pricing_heading">Pricing Heading</label>
                                    <input type="text" class="form-control" id="pricing_heading" name="pricing_heading" value="<?php echo htmlspecialchars($pricingInfo['heading'] ?? 'মূল্য তালিকা'); ?>">
                                    <p class="text-muted small">This is the main heading for the pricing section.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                Pricing Subheading
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="pricing_subheading">Pricing Subheading</label>
                                    <input type="text" class="form-control" id="pricing_subheading" name="pricing_subheading" value="<?php echo htmlspecialchars($pricingInfo['subheading'] ?? 'আমাদের প্যাকেজ সমূহ'); ?>">
                                    <p class="text-muted small">This is the subheading displayed below the main pricing heading.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                Product 1 Information
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="product1_description">Product 1 Description</label>
                                    <input type="text" class="form-control" id="product1_description" name="product1_description" value="<?php echo htmlspecialchars($pricingInfo['product1_description'] ?? '১ বক্সে ৯০টি ট্যাবলেট ১ মাস ১৫ দিনের কোর্স='); ?>">
                                    <p class="text-muted small">Description of the first product package.</p>
                                </div>
                                <div class="form-group">
                                    <label for="product1_price">Product 1 Price</label>
                                    <input type="text" class="form-control" id="product1_price" name="product1_price" value="<?php echo htmlspecialchars($pricingInfo['product1_price'] ?? 'অফারে মাত্র 1200/='); ?>">
                                    <p class="text-muted small">Price of the first product package.</p>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                Product 2 Information
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="product2_description">Product 2 Description</label>
                                    <input type="text" class="form-control" id="product2_description" name="product2_description" value="<?php echo htmlspecialchars($pricingInfo['product2_description'] ?? '২ বক্সে ১৮০টি ট্যাবলেট ৩ মাসের সম্পূর্ণ কোর্স ='); ?>">
                                    <p class="text-muted small">Description of the second product package.</p>
                                </div>
                                <div class="form-group">
                                    <label for="product2_price">Product 2 Price</label>
                                    <input type="text" class="form-control" id="product2_price" name="product2_price" value="<?php echo htmlspecialchars($pricingInfo['product2_price'] ?? 'অফারে মাত্র 2300/='); ?>">
                                    <p class="text-muted small">Price of the second product package.</p>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                Delivery Information
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="delivery_info">Delivery Information</label>
                                    <textarea class="form-control" id="delivery_info" name="delivery_info" rows="4"><?php echo htmlspecialchars($pricingInfo['delivery_info'] ?? 'সারা বাংলাদেশে সম্পূর্ণ ফ্রি ডেলিভারি!'); ?></textarea>
                                    <p class="text-muted small">Information about delivery charges and policies.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                Contact Information
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="phone_number">Phone Number</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($pricingInfo['phone_number'] ?? '880 1990-888222'); ?>">
                                    <p class="text-muted small">Phone number for customers to contact.</p>
                                </div>
                                <div class="form-group">
                                    <label for="whatsapp_number">WhatsApp Number</label>
                                    <input type="text" class="form-control" id="whatsapp_number" name="whatsapp_number" value="<?php echo htmlspecialchars($pricingInfo['whatsapp_number'] ?? '880 1990-888222'); ?>">
                                    <p class="text-muted small">WhatsApp number for customers to contact.</p>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                Payment Information
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="payment_info">Payment Information</label>
                                    <textarea class="form-control" id="payment_info" name="payment_info" rows="4"><?php echo htmlspecialchars($pricingInfo['payment_info'] ?? 'ক্যাশ অন ডেলিভারি অথবা বিকাশ/নগদ/রকেট এর মাধ্যমে পেমেন্ট করতে পারবেন।'); ?></textarea>
                                    <p class="text-muted small">Information about payment methods and policies.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-danger btn-lg">Save Pricing Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
