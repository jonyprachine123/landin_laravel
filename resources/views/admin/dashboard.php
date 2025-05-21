<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Prachin Bangla</title>
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
        .table th {
            font-weight: 600;
            background-color: #f8f9fa;
        }
        .badge {
            font-weight: 500;
            padding: 5px 10px;
        }
        .logout-btn {
            margin-top: 30px;
        }
        .order-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .order-details p {
            margin-bottom: 5px;
        }
        .action-buttons .btn {
            margin-right: 5px;
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
                        <a class="nav-link active" href="<?php echo $config['admin_dashboard_url']; ?>">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $config['admin_dashboard_url']; ?>">
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
                    <h1>Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Orders</h5>
                                <h2 class="card-text"><?php echo count($orders); ?></h2>
                                <p class="card-text text-muted">All time orders</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Today's Orders</h5>
                                <h2 class="card-text"><?php echo $todayOrders; ?></h2>
                                <p class="card-text text-muted">Orders placed today</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Revenue</h5>
                                <h2 class="card-text"><?php echo number_format($totalRevenue, 2); ?>৳</h2>
                                <p class="card-text text-muted">All time revenue</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <div class="card mt-4">
                    <div class="card-header">
                        Recent Orders
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Package</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($orders)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No orders found</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td>#<?php echo $order['id']; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($order['name']); ?></strong><br>
                                                    <small><?php echo htmlspecialchars($order['phone']); ?></small>
                                                </td>
                                                <td>
                                                    <?php 
                                                    switch ($order['package']) {
                                                        case '1month':
                                                            echo '১ বক্সে ৯০টি ট্যাবলেট | ১ মাস ১৫ দিনের কোর্স';
                                                            break;
                                                        case '15days':
                                                            echo 'অ্যাজমা কিউর (১৫ দিনের প্যাকেজ)';
                                                            break;
                                                        case '3month':
                                                            echo '২ বক্সে ১৮০টি ট্যাবলেট ৩ মাসের সম্পূর্ণ কোর্স';
                                                            break;
                                                        default:
                                                            echo $order['package'];
                                                    }
                                                    ?>
                                                    <?php if ($order['honey_addon']): ?>
                                                        <span class="badge bg-warning text-dark">+ মধু</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $totalAmount = $order['price'] + $order['shipping_cost'] + $order['honey_price'];
                                                    echo number_format($totalAmount, 2) . '৳'; 
                                                    ?>
                                                </td>
                                                <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                                                <td>
                                                    <?php 
                                                    $statusClass = '';
                                                    $statusText = '';
                                                    switch ($order['status']) {
                                                        case 'pending':
                                                            $statusClass = 'bg-warning text-dark';
                                                            $statusText = 'Pending';
                                                            break;
                                                        case 'processing':
                                                            $statusClass = 'bg-info text-dark';
                                                            $statusText = 'Processing';
                                                            break;
                                                        case 'delivered':
                                                            $statusClass = 'bg-primary';
                                                            $statusText = 'Delivered';
                                                            break;
                                                        case 'successful':
                                                            $statusClass = 'bg-success';
                                                            $statusText = 'Successful';
                                                            break;
                                                        case 'cancelled':
                                                            $statusClass = 'bg-danger';
                                                            $statusText = 'Cancelled';
                                                            break;
                                                        default:
                                                            $statusClass = 'bg-secondary';
                                                            $statusText = 'Unknown';
                                                    }
                                                    ?>
                                                    <span class="badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                                    <div class="status-buttons mt-2">
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a href="/Landing/public/admin/orders/<?php echo $order['id']; ?>/status/pending" class="btn btn-sm <?php echo $order['status'] == 'pending' ? 'btn-warning' : 'btn-outline-warning'; ?>" title="Mark as Pending">
                                                                <i class="fas fa-clock"></i>
                                                            </a>
                                                            <a href="/Landing/public/admin/orders/<?php echo $order['id']; ?>/status/processing" class="btn btn-sm <?php echo $order['status'] == 'processing' ? 'btn-info' : 'btn-outline-info'; ?>" title="Mark as Processing">
                                                                <i class="fas fa-spinner"></i>
                                                            </a>
                                                            <a href="/Landing/public/admin/orders/<?php echo $order['id']; ?>/status/delivered" class="btn btn-sm <?php echo $order['status'] == 'delivered' ? 'btn-primary' : 'btn-outline-primary'; ?>" title="Mark as Delivered">
                                                                <i class="fas fa-truck"></i>
                                                            </a>
                                                            <a href="/Landing/public/admin/orders/<?php echo $order['id']; ?>/status/successful" class="btn btn-sm <?php echo $order['status'] == 'successful' ? 'btn-success' : 'btn-outline-success'; ?>" title="Mark as Successful">
                                                                <i class="fas fa-check"></i>
                                                            </a>
                                                            <a href="/Landing/public/admin/orders/<?php echo $order['id']; ?>/status/cancelled" class="btn btn-sm <?php echo $order['status'] == 'cancelled' ? 'btn-danger' : 'btn-outline-danger'; ?>" title="Cancel Order">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="action-buttons">
                                                    <button type="button" class="btn btn-sm btn-primary view-details" data-bs-toggle="modal" data-bs-target="#orderModal<?php echo $order['id']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="/Landing/public/admin/orders/<?php echo $order['id']; ?>/edit" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            
                                            <!-- Order Details Modal -->
                                            <div class="modal fade" id="orderModal<?php echo $order['id']; ?>" tabindex="-1" aria-labelledby="orderModalLabel<?php echo $order['id']; ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="orderModalLabel<?php echo $order['id']; ?>">Order #<?php echo $order['id']; ?> Details</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h6>Customer Information</h6>
                                                            <div class="order-details">
                                                                <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                                                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                                                                <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                                                                <p><strong>Country:</strong> <?php echo htmlspecialchars($order['country']); ?></p>
                                                            </div>
                                                            
                                                            <h6 class="mt-4">Order Information</h6>
                                                            <div class="order-details">
                                                                <p><strong>Package:</strong> 
                                                                    <?php 
                                                                    switch ($order['package']) {
                                                                        case '1month':
                                                                            echo '১ বক্সে ৯০টি ট্যাবলেট | ১ মাস ১৫ দিনের কোর্স';
                                                                            break;
                                                                        case '15days':
                                                                            echo '৫০ টি ট্যাবলেট | ১৫ দিনের কোর্স';
                                                                            break;
                                                                        case '3month':
                                                                            echo '২ বক্সে ১৮০টি ট্যাবলেট ৩ মাসের সম্পূর্ণ কোর্স';
                                                                            break;
                                                                        default:
                                                                            echo $order['package'];
                                                                    }
                                                                    ?>
                                                                </p>
                                                                <p><strong>Package Price:</strong> 
                                                                    <?php 
                                                                    // Display the correct package price based on the package type
                                                                    switch ($order['package']) {
                                                                        case '1month':
                                                                            echo '1,200.00৳';
                                                                            break;
                                                                        case '15days':
                                                                            echo '1,000.00৳';
                                                                            break;
                                                                        case '3month':
                                                                            echo '2,300.00৳';
                                                                            break;
                                                                        default:
                                                                            echo number_format($order['price'], 2) . '৳';
                                                                    }
                                                                    ?>
                                                                </p>
                                                                <p><strong>Shipping Method:</strong> 
                                                                    <?php echo ($order['shipping_method'] === 'inside_dhaka') ? 'ঢাকা সিটিতে (ফ্রি ডেলিভারি)' : 'ঢাকা সিটির বাহিরে (ফ্রি ডেলিভারি)'; ?>
                                                                </p>
                                                                <p><strong>Shipping Cost:</strong> 0.00৳</p>
                                                                
                                                                <!-- Honey add-on section removed -->
                                                                
                                                                <p><strong>Total Amount:</strong> 
                                                                    <?php 
                                                                    // Calculate the correct total amount based on the package type
                                                                    $packagePrice = 0;
                                                                    switch ($order['package']) {
                                                                        case '1month':
                                                                            $packagePrice = 1200;
                                                                            break;
                                                                        case '15days':
                                                                            $packagePrice = 1000;
                                                                            break;
                                                                        case '3month':
                                                                            $packagePrice = 2300;
                                                                            break;
                                                                        default:
                                                                            $packagePrice = $order['price'];
                                                                    }
                                                                    // Shipping is free
                                                                    $shippingCost = 0;
                                                                    
                                                                    // Calculate total
                                                                    $correctTotal = $packagePrice + $shippingCost;
                                                                    echo number_format($correctTotal, 2) . '৳';
                                                                    ?>
                                                                </p>
                                                                <p><strong>Order Date:</strong> <?php echo date('d M Y h:i A', strtotime($order['created_at'])); ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <a href="<?php echo $config['admin_edit_order_url'] . $order['id']; ?>/edit" class="btn btn-primary">Edit Order</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
