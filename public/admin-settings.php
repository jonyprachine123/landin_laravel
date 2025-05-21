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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metaPixelCode = $_POST['meta_pixel_code'] ?? '';
    
    // Save setting
    $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
    $stmt->execute([':key' => 'meta_pixel_code']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        // Update existing setting
        $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
        $stmt->execute([':value' => $metaPixelCode, ':key' => 'meta_pixel_code']);
    } else {
        // Insert new setting
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
        $stmt->execute([':key' => 'meta_pixel_code', ':value' => $metaPixelCode]);
    }
    
    // Update the settings array
    $settings['meta_pixel_code'] = $metaPixelCode;
    
    // Set success message
    $success = 'Settings have been saved successfully';
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
                        <a class="nav-link" href="admin-dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-dashboard.php">
                            <i class="fas fa-shopping-cart"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin-settings.php">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                    <li class="nav-item logout-btn">
                        <a class="nav-link text-danger" href="admin-settings.php?action=logout">
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

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        Meta Pixel Code
                    </div>
                    <div class="card-body">
                        <form action="admin-settings.php" method="POST">
                            <div class="form-group">
                                <label for="meta_pixel_code">Meta Pixel Tracking Code</label>
                                <p class="text-muted small">Paste your Facebook Meta Pixel code here. It will be added to all pages of your website.</p>
                                <textarea class="form-control code-editor" id="meta_pixel_code" name="meta_pixel_code" rows="12"><?php echo htmlspecialchars($settings['meta_pixel_code'] ?? ''); ?></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>

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
