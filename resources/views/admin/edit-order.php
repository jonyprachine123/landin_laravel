<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order - Admin Dashboard</title>
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
                        <a class="nav-link active" href="<?php echo $config['admin_dashboard_url']; ?>">
                            <i class="fas fa-shopping-cart"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $config['admin_settings_url']; ?>">
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
                    <h1>Edit Order #<?php echo $order['id']; ?></h1>
                    <a href="<?php echo $config['admin_dashboard_url']; ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        Order Information
                    </div>
                    <div class="card-body">
                        <form action="<?php echo $config['admin_update_order_url'] . $order['id']; ?>/update" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Customer Information</h5>
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($order['name']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($order['phone']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($order['address']); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <input type="text" class="form-control" id="country" name="country" value="<?php echo htmlspecialchars($order['country']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>Order Details</h5>
                                    <div class="form-group">
                                        <label for="package">Package</label>
                                        <select class="form-control" id="package" name="package" required>
                                            <option value="1month" <?php echo ($order['package'] === '1month') ? 'selected' : ''; ?>>১ বক্সে ৯০টি ট্যাবলেট | ১ মাস ১৫ দিনের কোর্স - 1,200.00৳</option>
                                            <!-- <option value="15days" <?php echo ($order['package'] === '15days') ? 'selected' : ''; ?>>৫০ টি ট্যাবলেট | ১৫ দিনের কোর্স - 1,000.00৳</option> -->
                                            <option value="3month" <?php echo ($order['package'] === '3month') ? 'selected' : ''; ?>>২ বক্সে ১৮০টি ট্যাবলেট ৩ মাসের সম্পূর্ণ কোর্স - 2,300.00৳</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="shipping_method">Shipping Method</label>
                                        <select class="form-control" id="shipping_method" name="shipping_method" required>
                                            <option value="inside_dhaka" <?php echo ($order['shipping_method'] === 'inside_dhaka') ? 'selected' : ''; ?>>ঢাকা সিটিতে - 0.00৳ (ফ্রি ডেলিভারি)</option>
                                            <option value="outside_dhaka" <?php echo ($order['shipping_method'] === 'outside_dhaka') ? 'selected' : ''; ?>>ঢাকা সিটির বাহিরে - 0.00৳ (ফ্রি ডেলিভারি)</option>
                                        </select>
                                    </div>
                                    <!-- Honey add-on option removed -->
                                    <div class="form-group">
                                        <label>Order Date</label>
                                        <p class="form-control-static"><?php echo date('d M Y h:i A', strtotime($order['created_at'])); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-end">
                                <button type="submit" class="btn btn-primary">Update Order</button>
                                <a href="<?php echo $config['admin_dashboard_url']; ?>" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
