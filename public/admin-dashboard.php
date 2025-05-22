<?php
// Simple admin dashboard file that works directly in the public folder
// Include the bootstrap file
require_once __DIR__ . '/../app/bootstrap.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page
    header("Location: admin-login.php");
    exit;
}

// Get all orders
$db = getDbConnection();
$stmt = $db->prepare("SELECT * FROM orders ORDER BY created_at DESC");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate today's orders
$todayOrders = 0;
$today = date('Y-m-d');
foreach ($orders as $order) {
    if (date('Y-m-d', strtotime($order['created_at'])) == $today) {
        $todayOrders++;
    }
}

// Calculate total revenue (only from successful orders)
$totalRevenue = 0;
$successfulOrders = 0;
foreach ($orders as $order) {
    if ($order['status'] === 'successful') {
        $totalRevenue += $order['price'] + $order['shipping_cost'] + $order['honey_price'];
        $successfulOrders++;
    }
}

// Check for success message
$successMessage = '';
if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

// Check for error message
$errorMessage = '';
if (isset($_SESSION['error_message'])) {
    $errorMessage = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
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
                        <a class="nav-link active" href="admin-dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-dashboard.php">
                            <i class="fas fa-shopping-cart"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-settings.php">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                    <li class="nav-item logout-btn">
                        <a class="nav-link text-danger" href="admin-dashboard.php?action=logout">
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
                            <a href="admin-settings.php" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </div>
                    </div>
                </div>
                
                <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $successMessage; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $errorMessage; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <!-- Summary Cards -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Orders</h5>
                                <h2 class="card-text"><?php echo count($orders); ?></h2>
                                <p class="card-text text-muted">All orders</p>
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
                                <p class="card-text text-muted">From <?php echo $successfulOrders; ?> successful orders</p>
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
                                        <th>Price</th>
                                        <th>Shipping</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($orders)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No orders found</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td><?php echo $order['id']; ?></td>
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
                                                            case '3month':
                                                                echo '২ বক্সে ১৮০টি ট্যাবলেট ৩ মাসের সম্পূর্ণ কোর্স';
                                                                break;
                                                            case '15days':
                                                                echo 'অ্যাজমা কিউর (১৫ দিনের প্যাকেজ) - Legacy';
                                                                break;
                                                            default:
                                                                echo htmlspecialchars($order['package']);
                                                        }
                                                    ?>
                                                </td>
                                                <td><?php echo number_format($order['price'], 2); ?>৳</td>
                                                <td>
                                                    <?php 
                                                        switch ($order['shipping_method']) {
                                                            case 'inside_dhaka':
                                                                echo '60.00৳ (ঢাকা সিটিতে)';
                                                                break;
                                                            case 'outside_dhaka':
                                                                echo '170.00৳ (ঢাকা সিটির বাহিরে)';
                                                                break;
                                                            default:
                                                                echo number_format($order['shipping_cost'], 2) . '৳';
                                                        }
                                                    ?>
                                                </td>
                                                <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                                                <td>
                                                    <?php 
                                                        $statusClass = '';
                                                        switch ($order['status']) {
                                                            case 'pending':
                                                                $statusClass = 'bg-warning';
                                                                break;
                                                            case 'processing':
                                                                $statusClass = 'bg-info';
                                                                break;
                                                            case 'delivered':
                                                                $statusClass = 'bg-primary';
                                                                break;
                                                            case 'successful':
                                                                $statusClass = 'bg-success';
                                                                break;
                                                            case 'cancelled':
                                                                $statusClass = 'bg-danger';
                                                                break;
                                                            default:
                                                                $statusClass = 'bg-secondary';
                                                        }
                                                    ?>
                                                    <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($order['status']); ?></span>
                                                </td>
                                                <td class="action-buttons">
                                                    <a href="admin-edit-order.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#orderModal<?php echo $order['id']; ?>">
                                                        <i class="fas fa-eye"></i> View
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteOrderModal<?php echo $order['id']; ?>">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>
                                            
                                            <!-- Order Modal -->
                                            <div class="modal fade" id="orderModal<?php echo $order['id']; ?>" tabindex="-1" aria-labelledby="orderModalLabel<?php echo $order['id']; ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="orderModalLabel<?php echo $order['id']; ?>">Order #<?php echo $order['id']; ?> Details</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <h5>Customer Information</h5>
                                                                    <div class="order-details">
                                                                        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                                                                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                                                                        <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                                                                        <p><strong>Country:</strong> <?php echo htmlspecialchars($order['country']); ?></p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h5>Order Information</h5>
                                                                    <div class="order-details">
                                                                        <p><strong>Package:</strong> 
                                                                            <?php 
                                                                                switch ($order['package']) {
                                                                                    case '1month':
                                                                                        echo '১ বক্সে ৯০টি ট্যাবলেট | ১ মাস ১৫ দিনের কোর্স';
                                                                                        break;
                                                                                    case '3month':
                                                                                        echo '২ বক্সে ১৮০টি ট্যাবলেট ৩ মাসের সম্পূর্ণ কোর্স';
                                                                                        break;
                                                                                    case '15days':
                                                                                        echo 'অ্যাজমা কিউর (১৫ দিনের প্যাকেজ) - Legacy';
                                                                                        break;
                                                                                    default:
                                                                                        echo htmlspecialchars($order['package']);
                                                                                }
                                                                            ?>
                                                                        </p>
                                                                        <p><strong>Price:</strong> <?php echo number_format($order['price'], 2); ?>৳</p>
                                                                        <p><strong>Shipping Method:</strong> 
                                                                            <?php 
                                                                                switch ($order['shipping_method']) {
                                                                                    case 'inside_dhaka':
                                                                                        echo 'ঢাকা সিটিতে';
                                                                                        break;
                                                                                    case 'outside_dhaka':
                                                                                        echo 'ঢাকা সিটির বাহিরে';
                                                                                        break;
                                                                                    default:
                                                                                        echo htmlspecialchars($order['shipping_method']);
                                                                                }
                                                                            ?>
                                                                        </p>
                                                                        <p><strong>Shipping Cost:</strong> <?php echo number_format($order['shipping_cost'], 2); ?>৳</p>
                                                                        <p><strong>Total Amount:</strong> <?php echo number_format($order['price'] + $order['shipping_cost'] + $order['honey_price'], 2); ?>৳</p>
                                                                        <p><strong>Order Date:</strong> <?php echo date('d M Y h:i A', strtotime($order['created_at'])); ?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <a href="admin-edit-order.php?id=<?php echo $order['id']; ?>" class="btn btn-primary">Edit Order</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Delete Order Confirmation Modal -->
                                            <div class="modal fade" id="deleteOrderModal<?php echo $order['id']; ?>" tabindex="-1" aria-labelledby="deleteOrderModalLabel<?php echo $order['id']; ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title" id="deleteOrderModalLabel<?php echo $order['id']; ?>">Confirm Delete</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete Order #<?php echo $order['id']; ?>?</p>
                                                            <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                                                            <p><strong>This action cannot be undone.</strong></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <a href="admin-delete-order.php?id=<?php echo $order['id']; ?>" class="btn btn-danger">Delete Order</a>
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
