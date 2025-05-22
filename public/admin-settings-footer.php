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
    $footerCopyright = $_POST['footer_copyright'] ?? '';
    $footerLinkUrl = $_POST['footer_link_url'] ?? '';
    
    // Save Footer Copyright setting
    $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
    $stmt->execute([':key' => 'footer_copyright']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
        $stmt->execute([':value' => $footerCopyright, ':key' => 'footer_copyright']);
    } else {
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
        $stmt->execute([':key' => 'footer_copyright', ':value' => $footerCopyright]);
    }
    
    // Save Footer Link URL setting
    $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
    $stmt->execute([':key' => 'footer_link_url']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
        $stmt->execute([':value' => $footerLinkUrl, ':key' => 'footer_link_url']);
    } else {
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
        $stmt->execute([':key' => 'footer_link_url', ':value' => $footerLinkUrl]);
    }
    
    // Debug: Log the footer settings being saved
    error_log('Saving footer settings: ' . $footerCopyright . ' | ' . $footerLinkUrl);
    
    // Set success message
    $success = 'Footer settings have been saved successfully';
    
    // Redirect back to the settings page to prevent form resubmission
    header("Location: admin-settings-footer.php?success=1");
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
    <title>Footer Settings - Prachin Bangla</title>
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
                        <h1>Footer Settings</h1>
                        <a href="admin-settings.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Settings</a>
                    </div>
                    
                    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div class="alert alert-success">
                        <?php echo $success ?: 'Settings have been saved successfully'; ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="admin-settings-footer.php">
                        <div class="card">
                            <div class="card-header">
                                Footer Copyright Text
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="footer_copyright">Footer Copyright Text</label>
                                    <input type="text" class="form-control" id="footer_copyright" name="footer_copyright" value="<?php echo htmlspecialchars($settings['footer_copyright'] ?? '© 2023 Prachin Bangla. All rights reserved.'); ?>">
                                    <p class="text-muted small">This is the copyright text that appears in the footer of your website.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                Footer Link URL
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="footer_link_url">Footer Link URL</label>
                                    <input type="text" class="form-control" id="footer_link_url" name="footer_link_url" value="<?php echo htmlspecialchars($settings['footer_link_url'] ?? 'https://prachinbangla.com'); ?>">
                                    <p class="text-muted small">This is the URL that the footer copyright text links to.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-info btn-lg">Save Footer Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
