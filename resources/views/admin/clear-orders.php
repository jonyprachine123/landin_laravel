<?php
// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in as admin
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page
    header('Location: /Landing/public/admin/login');
    exit;
}

// Get database connection using the application's function
require_once __DIR__ . '/../../../app/bootstrap.php';

try {
    $pdo = getDbConnection();
    
    // Process form submission
    $message = '';
    $messageType = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['clear_all_orders'])) {
            // Clear all orders - SQLite doesn't have TRUNCATE, use DELETE instead
            $stmt = $pdo->prepare("DELETE FROM orders");
            $stmt->execute();
            $message = "All orders have been successfully cleared from the database.";
            $messageType = "success";
        } elseif (isset($_POST['clear_completed_orders'])) {
            // Clear only completed orders
            $stmt = $pdo->prepare("DELETE FROM orders WHERE status = 'completed'");
            $stmt->execute();
            $message = "All completed orders have been successfully cleared from the database.";
            $messageType = "success";
        } elseif (isset($_POST['clear_cancelled_orders'])) {
            // Clear only cancelled orders
            $stmt = $pdo->prepare("DELETE FROM orders WHERE status = 'cancelled'");
            $stmt->execute();
            $message = "All cancelled orders have been successfully cleared from the database.";
            $messageType = "success";
        }
    }
    
    // Get order count for display
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM orders");
    $orderCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Get order counts by status
    $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
    $ordersByStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $statusCounts = [
        'pending' => 0,
        'processing' => 0,
        'completed' => 0,
        'cancelled' => 0
    ];
    
    foreach ($ordersByStatus as $statusData) {
        $status = $statusData['status'];
        $count = $statusData['count'];
        $statusCounts[$status] = $count;
    }
    
} catch (PDOException $e) {
    $message = "Database error: " . $e->getMessage();
    $messageType = "danger";
    $orderCount = 0;
    $statusCounts = [
        'pending' => 0,
        'processing' => 0,
        'completed' => 0,
        'cancelled' => 0
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clear Orders - Admin Dashboard</title>
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
        .warning-card {
            border-left: 4px solid #dc3545;
        }
        .action-card {
            border-left: 4px solid #007bff;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
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
                        <a class="nav-link" href="/Landing/public/admin/dashboard">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Landing/public/admin/orders">
                            <i class="fas fa-shopping-cart"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/Landing/public/admin/clear-orders">
                            <i class="fas fa-trash-alt"></i> Clear Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Landing/public/admin/settings">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                    <li class="nav-item logout-btn">
                        <a class="nav-link text-danger" href="/Landing/public/admin/logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="page-header d-flex justify-content-between align-items-center">
                    <h1>Clear Orders</h1>
                </div>

                <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <!-- Order Summary -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                Order Summary
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Total Orders: <?php echo $orderCount; ?></h5>
                                <div class="mt-3">
                                    <p><strong>Pending Orders:</strong> <?php echo $statusCounts['pending']; ?></p>
                                    <p><strong>Processing Orders:</strong> <?php echo $statusCounts['processing']; ?></p>
                                    <p><strong>Completed Orders:</strong> <?php echo $statusCounts['completed']; ?></p>
                                    <p><strong>Cancelled Orders:</strong> <?php echo $statusCounts['cancelled']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Warning Card -->
                <div class="card warning-card mt-4">
                    <div class="card-header bg-danger text-white">
                        <i class="fas fa-exclamation-triangle"></i> Warning
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            <strong>Caution:</strong> Clearing orders is a permanent action and cannot be undone. 
                            All selected orders will be permanently deleted from the database.
                        </p>
                        <p class="card-text">
                            Please make sure you have a backup of your database before proceeding with any deletion operation.
                        </p>
                    </div>
                </div>

                <!-- Action Cards -->
                <div class="row mt-4">
                    <!-- Clear All Orders -->
                    <div class="col-md-4">
                        <div class="card action-card">
                            <div class="card-header">
                                Clear All Orders
                            </div>
                            <div class="card-body">
                                <p class="card-text">This will remove all orders from the database regardless of their status.</p>
                                <form method="POST" onsubmit="return confirmClearAll()">
                                    <button type="submit" name="clear_all_orders" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i> Clear All Orders
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Clear Completed Orders -->
                    <div class="col-md-4">
                        <div class="card action-card">
                            <div class="card-header">
                                Clear Completed Orders
                            </div>
                            <div class="card-body">
                                <p class="card-text">This will remove only completed orders from the database.</p>
                                <form method="POST" onsubmit="return confirmClearCompleted()">
                                    <button type="submit" name="clear_completed_orders" class="btn btn-warning">
                                        <i class="fas fa-check-circle"></i> Clear Completed Orders
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Clear Cancelled Orders -->
                    <div class="col-md-4">
                        <div class="card action-card">
                            <div class="card-header">
                                Clear Cancelled Orders
                            </div>
                            <div class="card-body">
                                <p class="card-text">This will remove only cancelled orders from the database.</p>
                                <form method="POST" onsubmit="return confirmClearCancelled()">
                                    <button type="submit" name="clear_cancelled_orders" class="btn btn-secondary">
                                        <i class="fas fa-ban"></i> Clear Cancelled Orders
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmClearAll() {
            return confirm("WARNING: You are about to delete ALL orders from the database. This action cannot be undone. Are you sure you want to proceed?");
        }
        
        function confirmClearCompleted() {
            return confirm("WARNING: You are about to delete all COMPLETED orders from the database. This action cannot be undone. Are you sure you want to proceed?");
        }
        
        function confirmClearCancelled() {
            return confirm("WARNING: You are about to delete all CANCELLED orders from the database. This action cannot be undone. Are you sure you want to proceed?");
        }
    </script>
</body>
</html>
