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
            color: #ced4da;
            padding: 10px 15px;
            margin-bottom: 5px;
            border-radius: 5px;
        }
        .nav-link:hover, .nav-link.active {
            background-color: #495057;
            color: white;
        }
        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .main-content {
            padding: 20px;
        }
        .page-header {
            margin-bottom: 30px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 15px;
        }
        .page-header h1 {
            font-size: 1.8rem;
            color: #343a40;
        }
        .card {
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #007A11;
            border-color: #007A11;
        }
        .btn-primary:hover {
            background-color: #006010;
            border-color: #006010;
        }
        .logout-btn {
            margin-top: 30px;
        }
        .code-editor {
            font-family: monospace;
            min-height: 200px;
            white-space: pre;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="sidebar-header">
                    <h3>Prachin Bangla</h3>
                    <p class="text-muted">Admin Panel</p>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $config['admin_dashboard_url']; ?>">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $config['admin_dashboard_url']; ?>">
                            <i class="fas fa-shopping-cart"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo $config['admin_settings_url']; ?>">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                    <li class="nav-item logout-btn">
                        <a class="nav-link text-danger" href="<?php echo $config['admin_url']; ?>/logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="page-header d-flex justify-content-between align-items-center">
                    <h1>Settings</h1>
                </div>

                <?php if (!empty($success) || isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?php echo !empty($success) ? $success : 'Settings have been saved successfully'; ?></div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="<?php echo $config['admin_settings_url']; ?>?action=save" method="POST">
                    <div class="card">
                        <div class="card-header">
                            Main Heading Text
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="main_heading">Main Heading</label>
                                <p class="text-muted small">Edit the main heading text that appears at the top of your landing page.</p>
                                <textarea class="form-control" id="main_heading" name="main_heading" rows="4"><?php echo htmlspecialchars($settings['main_heading'] ?? 'হার্ট, কিডনি, লিভার, স্কিন, চক্ষু — সুস্থ জীবনের প্রাকৃতিক টনিক <b style="color:#EE5E11">নিডাস</b>'); ?></textarea>
                                <p class="text-muted small mt-2">HTML formatting is allowed. For example, use <code>&lt;b style="color:#EE5E11"&gt;নিডাস&lt;/b&gt;</code> to make text bold and orange.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            Notification Text
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="notification_text">Notification Message</label>
                                <p class="text-muted small">Edit the notification text that appears below the main heading.</p>
                                <textarea class="form-control" id="notification_text" name="notification_text" rows="3"><?php echo htmlspecialchars($settings['notification_text'] ?? 'নিয়মিত নিডাস খেলেই ইনশাআল্লাহ ইনফেকশন, কোলেস্টেরল, ডায়াবেটিস, ও গ্যাস্ট্রিক থেকে মুক্তি!'); ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            Button Text
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="button_text">Testimonials Button Text</label>
                                <p class="text-muted small">Edit the text for the button that links to customer testimonials.</p>
                                <textarea class="form-control" id="button_text" name="button_text" rows="2"><?php echo htmlspecialchars($settings['button_text'] ?? 'আমাদের সম্মানিত কাস্টমারদের মতামত'); ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            YouTube Video
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="youtube_url">YouTube Video URL</label>
                                <p class="text-muted small">You can enter either a regular YouTube URL (e.g., https://www.youtube.com/watch?v=VIDEO_ID) or an embed URL.</p>
                                <input type="text" class="form-control" id="youtube_url" name="youtube_url" value="<?php echo htmlspecialchars($settings['youtube_url'] ?? 'https://www.youtube.com/embed/Eod9gvxhHuU'); ?>">
                                <p class="text-muted small mt-2">The system will automatically convert regular YouTube URLs to the correct embed format.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            Customer Reviews
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Customer Review Images</label>
                                <p class="text-muted small">Manage the customer review images shown in the carousel on the landing page.</p>
                                
                                <div id="reviewsContainer">
                                    <?php 
                                    $reviews = [];
                                    if (isset($settings['customer_reviews'])) {
                                        $reviews = json_decode($settings['customer_reviews'], true);
                                    }
                                    
                                    // If no reviews, use default
                                    if (empty($reviews)) {
                                        $reviews = [
                                            ['image' => 'Customer Review 1.png', 'alt' => 'Customer Review 1'],
                                            ['image' => 'Customer Review 2.jpg', 'alt' => 'Customer Review 2'],
                                            ['image' => 'Customer Review3.jpg', 'alt' => 'Customer Review 3'],
                                            ['image' => 'Customer Review 4.jpg', 'alt' => 'Customer Review 4'],
                                            ['image' => 'Customer Review 1.png', 'alt' => 'Customer Review 5'],
                                            ['image' => 'Customer Review 2.jpg', 'alt' => 'Customer Review 6']
                                        ];
                                    }
                                    
                                    foreach ($reviews as $index => $review): 
                                    ?>
                                    <div class="review-item mb-3 p-3 border rounded">
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
                                                    <input type="text" class="form-control" name="customer_reviews[<?php echo $index; ?>][alt]" value="<?php echo htmlspecialchars($review['alt']); ?>" placeholder="e.g., Customer Review 1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <p class="text-muted small mt-3">Note: To add new review images, upload them to the <code>/public/images/</code> folder and then enter their filenames here.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            Meta Pixel Code
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="meta_pixel_code">Meta Pixel Code</label>
                                <textarea class="form-control code-editor" id="meta_pixel_code" name="meta_pixel_code" rows="5"><?php echo htmlspecialchars($settings['meta_pixel_code'] ?? ''); ?></textarea>
                                <small class="form-text text-muted">Enter your Meta Pixel code here. This will be added to the head section of your landing page.</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            Order Form Settings
                        </div>
                        <div class="card-body">
                            <h5 class="mb-3">Order Form Headings</h5>
                            <div class="form-group">
                                <label for="order_form_heading">Order Form Heading</label>
                                <input type="text" class="form-control" id="order_form_heading" name="order_form_heading" value="<?php echo htmlspecialchars($orderFormInfo['order_form_heading'] ?? 'অর্ডার করতে আপনার সঠিক তথ্য দিয়ে নিচের ফর্মটি পূরণ করে <span style="color:#EE5E11">Confirm Order</span> এ ক্লিক করুন:-'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="package_select_text">Package Selection Text</label>
                                <input type="text" class="form-control" id="package_select_text" name="package_select_text" value="<?php echo htmlspecialchars($orderFormInfo['package_select_text'] ?? 'কোন প্যাকেজটি নিতে চান সিলেক্ট করুন :'); ?>">
                            </div>
                            
                            <h5 class="mt-4 mb-3">Product Labels</h5>
                            <div class="form-group">
                                <label for="best_seller_label">Best Seller Label</label>
                                <input type="text" class="form-control" id="best_seller_label" name="best_seller_label" value="<?php echo htmlspecialchars($orderFormInfo['best_seller_label'] ?? 'সেরাবিক্রয়'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="economy_label">Economy Label</label>
                                <input type="text" class="form-control" id="economy_label" name="economy_label" value="<?php echo htmlspecialchars($orderFormInfo['economy_label'] ?? 'সাশ্রয়ী'); ?>">
                            </div>
                            
                            <h5 class="mt-4 mb-3">Product 1 Details</h5>
                            <div class="form-group">
                                <label for="product1_full_description">Product 1 Full Description</label>
                                <input type="text" class="form-control" id="product1_full_description" name="product1_full_description" value="<?php echo htmlspecialchars($orderFormInfo['product1_full_description'] ?? '১ বক্সে ৯০টি ট্যাবলেট | ১ মাস ১৫ দিনের কোর্স'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="product1_regular_price">Product 1 Regular Price</label>
                                <input type="text" class="form-control" id="product1_regular_price" name="product1_regular_price" value="<?php echo htmlspecialchars($orderFormInfo['product1_regular_price'] ?? '১৩৫০'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="product1_sale_price">Product 1 Sale Price</label>
                                <input type="text" class="form-control" id="product1_sale_price" name="product1_sale_price" value="<?php echo htmlspecialchars($orderFormInfo['product1_sale_price'] ?? '১২০০'); ?>">
                            </div>
                            
                            <h5 class="mt-4 mb-3">Product 2 Details</h5>
                            <div class="form-group">
                                <label for="product2_full_description">Product 2 Full Description</label>
                                <input type="text" class="form-control" id="product2_full_description" name="product2_full_description" value="<?php echo htmlspecialchars($orderFormInfo['product2_full_description'] ?? '২ বক্সে ১৮০টি ট্যাবলেট ৩ মাসের সম্পূর্ণ কোর্স'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="product2_regular_price">Product 2 Regular Price</label>
                                <input type="text" class="form-control" id="product2_regular_price" name="product2_regular_price" value="<?php echo htmlspecialchars($orderFormInfo['product2_regular_price'] ?? '২৭০০'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="product2_sale_price">Product 2 Sale Price</label>
                                <input type="text" class="form-control" id="product2_sale_price" name="product2_sale_price" value="<?php echo htmlspecialchars($orderFormInfo['product2_sale_price'] ?? '২৩০০'); ?>">
                            </div>
                            
                            <h5 class="mt-4 mb-3">Billing Details</h5>
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
                            
                            <h5 class="mt-4 mb-3">Shipping Options</h5>
                            <div class="form-group">
                                <label for="shipping_heading">Shipping Heading</label>
                                <input type="text" class="form-control" id="shipping_heading" name="shipping_heading" value="<?php echo htmlspecialchars($orderFormInfo['shipping_heading'] ?? 'Shipping'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="outside_dhaka_label">Outside Dhaka Label</label>
                                <input type="text" class="form-control" id="outside_dhaka_label" name="outside_dhaka_label" value="<?php echo htmlspecialchars($orderFormInfo['outside_dhaka_label'] ?? 'ঢাকা সিটির বাহিরে'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="outside_dhaka_cost">Outside Dhaka Cost</label>
                                <input type="text" class="form-control" id="outside_dhaka_cost" name="outside_dhaka_cost" value="<?php echo htmlspecialchars($orderFormInfo['outside_dhaka_cost'] ?? '0.00'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="inside_dhaka_label">Inside Dhaka Label</label>
                                <input type="text" class="form-control" id="inside_dhaka_label" name="inside_dhaka_label" value="<?php echo htmlspecialchars($orderFormInfo['inside_dhaka_label'] ?? 'ঢাকা সিটিতে'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="inside_dhaka_cost">Inside Dhaka Cost</label>
                                <input type="text" class="form-control" id="inside_dhaka_cost" name="inside_dhaka_cost" value="<?php echo htmlspecialchars($orderFormInfo['inside_dhaka_cost'] ?? '0.00'); ?>">
                            </div>
                            
                            <h5 class="mt-4 mb-3">Order Summary</h5>
                            <div class="form-group">
                                <label for="order_summary_heading">Order Summary Heading</label>
                                <input type="text" class="form-control" id="order_summary_heading" name="order_summary_heading" value="<?php echo htmlspecialchars($orderFormInfo['order_summary_heading'] ?? 'Your order'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="product_column_heading">Product Column Heading</label>
                                <input type="text" class="form-control" id="product_column_heading" name="product_column_heading" value="<?php echo htmlspecialchars($orderFormInfo['product_column_heading'] ?? 'Product'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="subtotal_column_heading">Subtotal Column Heading</label>
                                <input type="text" class="form-control" id="subtotal_column_heading" name="subtotal_column_heading" value="<?php echo htmlspecialchars($orderFormInfo['subtotal_column_heading'] ?? 'Subtotal'); ?>">
                            </div>
                            
                            <h5 class="mt-4 mb-3">Payment Method</h5>
                            <div class="form-group">
                                <label for="cod_label">Cash on Delivery Label</label>
                                <input type="text" class="form-control" id="cod_label" name="cod_label" value="<?php echo htmlspecialchars($orderFormInfo['cod_label'] ?? 'ক্যাশ অন ডেলিভারি'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="cod_description">Cash on Delivery Description</label>
                                <textarea class="form-control" id="cod_description" name="cod_description" rows="3"><?php echo htmlspecialchars($orderFormInfo['cod_description'] ?? 'আমি অবশ্যই পণ্যটি রিসিভ করবো, পণ্যটি হাতে পেয়ে টাকা পরিশোধ করবো, ইনশাআল্লাহ'); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="confirm_order_button_text">Confirm Order Button Text</label>
                                <input type="text" class="form-control" id="confirm_order_button_text" name="confirm_order_button_text" value="<?php echo htmlspecialchars($orderFormInfo['confirm_order_button_text'] ?? 'Confirm Order'); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Save All Settings</button>
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
</body>
</html>
