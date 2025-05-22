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

// Get order form info
$orderFormInfo = [];
if (isset($settings['order_form_info'])) {
    $orderFormInfo = json_decode($settings['order_form_info'], true) ?: [];
}

// Handle settings form submission
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Order Form Info
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
    
    // Create order form info array
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
    
    // Save order form info as JSON
    $orderFormInfoJson = json_encode($orderFormInfo, JSON_UNESCAPED_UNICODE);
    
    // Check if order_form_info setting exists
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
    
    // Debug: Log the order form info being saved
    error_log('Saving order form info: ' . $orderFormInfoJson);
    
    // Set success message
    $success = 'Order form settings have been saved successfully';
    
    // Redirect back to the settings page to prevent form resubmission
    header("Location: admin-settings-order.php?success=1");
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
    <title>Order Form Settings - Prachin Bangla</title>
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
                        <h1>Order Form Settings</h1>
                        <a href="admin-settings.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Settings</a>
                    </div>
                    
                    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div class="alert alert-success">
                        <?php echo $success ?: 'Settings have been saved successfully'; ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="admin-settings-order.php">
                        <div class="card">
                            <div class="card-header">
                                Order Form Heading
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="order_form_heading">Order Form Heading</label>
                                    <textarea class="form-control" id="order_form_heading" name="order_form_heading" rows="3"><?php echo htmlspecialchars($orderFormInfo['order_form_heading'] ?? 'অর্ডার করতে আপনার সঠিক তথ্য দিয়ে নিচের ফর্মটি পূরণ করে <span style="color:#EE5E11">Confirm Order</span> এ ক্লিক করুন:-'); ?></textarea>
                                    <p class="text-muted small">This is the heading displayed at the top of the order form. HTML is allowed.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                Package Selection
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="package_select_text">Package Selection Text</label>
                                    <input type="text" class="form-control" id="package_select_text" name="package_select_text" value="<?php echo htmlspecialchars($orderFormInfo['package_select_text'] ?? 'কোন প্যাকেজটি নিতে চান সিলেক্ট করুন :'); ?>">
                                    <p class="text-muted small">This is the text displayed above the package selection options.</p>
                                </div>
                                
                                <div class="form-group">
                                    <label for="best_seller_label">Best Seller Label</label>
                                    <input type="text" class="form-control" id="best_seller_label" name="best_seller_label" value="<?php echo htmlspecialchars($orderFormInfo['best_seller_label'] ?? 'সেরাবিক্রয়'); ?>">
                                    <p class="text-muted small">This is the label for the best seller ribbon.</p>
                                </div>
                                
                                <div class="form-group">
                                    <label for="economy_label">Economy Label</label>
                                    <input type="text" class="form-control" id="economy_label" name="economy_label" value="<?php echo htmlspecialchars($orderFormInfo['economy_label'] ?? 'সাশ্রয়ী'); ?>">
                                    <p class="text-muted small">This is the label for the economy ribbon.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                Product 1 Information
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="product1_full_description">Product 1 Full Description</label>
                                    <input type="text" class="form-control" id="product1_full_description" name="product1_full_description" value="<?php echo htmlspecialchars($orderFormInfo['product1_full_description'] ?? '১ বক্সে ৯০টি ট্যাবলেট | ১ মাস ১৫ দিনের কোর্স'); ?>">
                                    <p class="text-muted small">Full description of the first product package.</p>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product1_regular_price">Product 1 Regular Price</label>
                                            <input type="text" class="form-control" id="product1_regular_price" name="product1_regular_price" value="<?php echo htmlspecialchars($orderFormInfo['product1_regular_price'] ?? '১৩৫০'); ?>">
                                            <p class="text-muted small">Regular price (will be shown with strikethrough).</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product1_sale_price">Product 1 Sale Price</label>
                                            <input type="text" class="form-control" id="product1_sale_price" name="product1_sale_price" value="<?php echo htmlspecialchars($orderFormInfo['product1_sale_price'] ?? '১২০০'); ?>">
                                            <p class="text-muted small">Sale price (will be shown in bold).</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                Product 2 Information
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="product2_full_description">Product 2 Full Description</label>
                                    <input type="text" class="form-control" id="product2_full_description" name="product2_full_description" value="<?php echo htmlspecialchars($orderFormInfo['product2_full_description'] ?? '২ বক্সে ১৮০টি ট্যাবলেট ৩ মাসের সম্পূর্ণ কোর্স'); ?>">
                                    <p class="text-muted small">Full description of the second product package.</p>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product2_regular_price">Product 2 Regular Price</label>
                                            <input type="text" class="form-control" id="product2_regular_price" name="product2_regular_price" value="<?php echo htmlspecialchars($orderFormInfo['product2_regular_price'] ?? '২৭০০'); ?>">
                                            <p class="text-muted small">Regular price (will be shown with strikethrough).</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product2_sale_price">Product 2 Sale Price</label>
                                            <input type="text" class="form-control" id="product2_sale_price" name="product2_sale_price" value="<?php echo htmlspecialchars($orderFormInfo['product2_sale_price'] ?? '২৩০০'); ?>">
                                            <p class="text-muted small">Sale price (will be shown in bold).</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                Billing Details
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="billing_details_heading">Billing Details Heading</label>
                                    <input type="text" class="form-control" id="billing_details_heading" name="billing_details_heading" value="<?php echo htmlspecialchars($orderFormInfo['billing_details_heading'] ?? 'Billing details'); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="name_label">Name Field Label</label>
                                    <input type="text" class="form-control" id="name_label" name="name_label" value="<?php echo htmlspecialchars($orderFormInfo['name_label'] ?? 'আপনার নাম'); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="address_label">Address Field Label</label>
                                    <input type="text" class="form-control" id="address_label" name="address_label" value="<?php echo htmlspecialchars($orderFormInfo['address_label'] ?? 'আপনার সম্পূর্ণ ঠিকানা'); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="phone_label">Phone Field Label</label>
                                    <input type="text" class="form-control" id="phone_label" name="phone_label" value="<?php echo htmlspecialchars($orderFormInfo['phone_label'] ?? 'আপনার মোবাইল নাম্বার'); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                Shipping Options
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="shipping_heading">Shipping Heading</label>
                                    <input type="text" class="form-control" id="shipping_heading" name="shipping_heading" value="<?php echo htmlspecialchars($orderFormInfo['shipping_heading'] ?? 'Shipping'); ?>">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="outside_dhaka_label">Outside Dhaka Label</label>
                                            <input type="text" class="form-control" id="outside_dhaka_label" name="outside_dhaka_label" value="<?php echo htmlspecialchars($orderFormInfo['outside_dhaka_label'] ?? 'ঢাকা সিটির বাহিরে'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="outside_dhaka_cost">Outside Dhaka Cost</label>
                                            <input type="text" class="form-control" id="outside_dhaka_cost" name="outside_dhaka_cost" value="<?php echo htmlspecialchars($orderFormInfo['outside_dhaka_cost'] ?? '0.00'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inside_dhaka_label">Inside Dhaka Label</label>
                                            <input type="text" class="form-control" id="inside_dhaka_label" name="inside_dhaka_label" value="<?php echo htmlspecialchars($orderFormInfo['inside_dhaka_label'] ?? 'ঢাকা সিটিতে'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inside_dhaka_cost">Inside Dhaka Cost</label>
                                            <input type="text" class="form-control" id="inside_dhaka_cost" name="inside_dhaka_cost" value="<?php echo htmlspecialchars($orderFormInfo['inside_dhaka_cost'] ?? '0.00'); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                Order Summary
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="order_summary_heading">Order Summary Heading</label>
                                    <input type="text" class="form-control" id="order_summary_heading" name="order_summary_heading" value="<?php echo htmlspecialchars($orderFormInfo['order_summary_heading'] ?? 'Your order'); ?>">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_column_heading">Product Column Heading</label>
                                            <input type="text" class="form-control" id="product_column_heading" name="product_column_heading" value="<?php echo htmlspecialchars($orderFormInfo['product_column_heading'] ?? 'Product'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="subtotal_column_heading">Subtotal Column Heading</label>
                                            <input type="text" class="form-control" id="subtotal_column_heading" name="subtotal_column_heading" value="<?php echo htmlspecialchars($orderFormInfo['subtotal_column_heading'] ?? 'Subtotal'); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                Payment and Confirmation
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="cod_label">Cash on Delivery Label</label>
                                    <input type="text" class="form-control" id="cod_label" name="cod_label" value="<?php echo htmlspecialchars($orderFormInfo['cod_label'] ?? 'ক্যাশ অন ডেলিভারি'); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="cod_description">Cash on Delivery Description</label>
                                    <textarea class="form-control" id="cod_description" name="cod_description" rows="2"><?php echo htmlspecialchars($orderFormInfo['cod_description'] ?? 'আমি অবশ্যই পণ্যটি রিসিভ করবো, পণ্যটি হাতে পেয়ে টাকা পরিশোধ করবো, ইনশাআল্লাহ'); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_order_button_text">Confirm Order Button Text</label>
                                    <input type="text" class="form-control" id="confirm_order_button_text" name="confirm_order_button_text" value="<?php echo htmlspecialchars($orderFormInfo['confirm_order_button_text'] ?? 'Confirm Order'); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="order_confirmation_text">Order Confirmation Text</label>
                                    <textarea class="form-control" id="order_confirmation_text" name="order_confirmation_text" rows="2"><?php echo htmlspecialchars($orderFormInfo['order_confirmation_text'] ?? 'অর্ডার কন্ফার্ম করতে Confirm Order এ ক্লিক করুন'); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-success btn-lg">Save Order Form Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
