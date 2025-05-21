<?php
// Simple admin login that works directly in the public folder
// Include the bootstrap file
require_once __DIR__ . '/../app/bootstrap.php';

// Handle login form submission
$error = '';
$loggedIn = false;

if (isset($_SESSION['admin_id'])) {
    $loggedIn = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    // Get form data
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = 'Username and password are required';
    } else {
        // Connect to database
        $db = getDbConnection();
        
        // Check credentials
        $stmt = $db->prepare("SELECT * FROM admins WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$admin || !password_verify($password, $admin['password'])) {
            $error = 'Invalid username or password';
        } else {
            // Set session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $loggedIn = true;
        }
    }
}

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Unset admin session variables
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    $loggedIn = false;
    $error = '';
}

// Get all orders if logged in
$orders = [];
$totalRevenue = 0;
$todayOrders = 0;

if ($loggedIn) {
    $db = getDbConnection();
    
    // Get all orders
    $stmt = $db->prepare("SELECT * FROM orders ORDER BY created_at DESC");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate today's orders
    $today = date('Y-m-d');
    foreach ($orders as $order) {
        if (date('Y-m-d', strtotime($order['created_at'])) == $today) {
            $todayOrders++;
        }
        $totalRevenue += $order['price'] + $order['shipping_cost'] + $order['honey_price'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $loggedIn ? 'Admin Dashboard' : 'Admin Login'; ?> - Prachin Bangla</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 30px;
        }
        .login-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h1 {
            color: #007A11;
            font-size: 24px;
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-login {
            background-color: #007A11;
            color: white;
            width: 100%;
            padding: 10px;
            font-weight: bold;
        }
        .btn-login:hover {
            background-color: #006010;
            color: white;
        }
        .alert {
            margin-bottom: 20px;
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
        .badge {
            font-weight: 500;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!$loggedIn): ?>
        <!-- Login Form -->
        <div class="login-container">
            <div class="login-header">
                <h1>Admin Login</h1>
                <p>Prachin Bangla Limited Admin Dashboard</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="hidden" name="action" value="login">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-login">Login</button>
            </form>
        </div>
        <?php else: ?>
        <!-- Dashboard -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Admin Dashboard</h1>
                    <a href="?action=logout" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
                                <th>Price</th>
                                <th>Date</th>
                                <th>Status</th>
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
                                        <td><?php echo $order['id']; ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($order['name']); ?></strong><br>
                                            <small><?php echo htmlspecialchars($order['phone']); ?></small>
                                        </td>
                                        <td>
                                            <?php 
                                                switch ($order['package']) {
                                                    case '1month':
                                                        echo '১ মাসের প্যাকেজ';
                                                        break;
                                                    case '15days':
                                                        echo '১৫ দিনের প্যাকেজ';
                                                        break;
                                                    case '3month':
                                                        echo '৩ মাসের প্যাকেজ';
                                                        break;
                                                    default:
                                                        echo htmlspecialchars($order['package']);
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo number_format($order['price'], 2); ?>৳</td>
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
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
