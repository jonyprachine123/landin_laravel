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

// Function to convert YouTube watch URL to embed URL
function convertYoutubeUrl($url) {
    // Check if it's already an embed URL
    if (strpos($url, 'youtube.com/embed/') !== false) {
        return $url;
    }
    
    // Extract video ID from watch URL
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';
    if (preg_match($pattern, $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    }
    
    // Return original URL if no match found
    return $url;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mainHeading = $_POST['main_heading'] ?? '';
    $notificationText = $_POST['notification_text'] ?? '';
    $buttonText = $_POST['button_text'] ?? '';
    $orderButtonText = $_POST['order_button_text'] ?? '';
    $youtubeUrl = $_POST['youtube_url'] ?? '';
    
    // Convert YouTube URL if needed
    $youtubeUrl = convertYoutubeUrl($youtubeUrl);
    
    // Save Main Heading setting
    $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
    $stmt->execute([':key' => 'main_heading']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
        $stmt->execute([':value' => $mainHeading, ':key' => 'main_heading']);
    } else {
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
        $stmt->execute([':key' => 'main_heading', ':value' => $mainHeading]);
    }
    
    // Save Notification Text setting
    $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
    $stmt->execute([':key' => 'notification_text']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
        $stmt->execute([':value' => $notificationText, ':key' => 'notification_text']);
    } else {
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
        $stmt->execute([':key' => 'notification_text', ':value' => $notificationText]);
    }
    
    // Save Button Text setting
    $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
    $stmt->execute([':key' => 'button_text']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
        $stmt->execute([':value' => $buttonText, ':key' => 'button_text']);
    } else {
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
        $stmt->execute([':key' => 'button_text', ':value' => $buttonText]);
    }
    
    // Save Order Button Text setting
    $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
    $stmt->execute([':key' => 'order_button_text']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
        $stmt->execute([':value' => $orderButtonText, ':key' => 'order_button_text']);
    } else {
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
        $stmt->execute([':key' => 'order_button_text', ':value' => $orderButtonText]);
    }
    
    // Save YouTube URL setting
    $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
    $stmt->execute([':key' => 'youtube_url']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
        $stmt->execute([':value' => $youtubeUrl, ':key' => 'youtube_url']);
    } else {
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
        $stmt->execute([':key' => 'youtube_url', ':value' => $youtubeUrl]);
    }
    
    // Debug: Log the YouTube URL value being saved
    error_log('Saving YouTube URL: ' . $youtubeUrl);
    
    // Set success message
    $success = 'General settings have been saved successfully';
    
    // Redirect back to the settings page to prevent form resubmission
    header("Location: admin-settings-general.php?success=1");
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
    <title>General Settings - Prachin Bangla</title>
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
                        <h1>General Content Settings</h1>
                        <a href="admin-settings.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Settings</a>
                    </div>
                    
                    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div class="alert alert-success">
                        <?php echo $success ?: 'Settings have been saved successfully'; ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="admin-settings-general.php">
                        <div class="card">
                            <div class="card-header">
                                Main Heading
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="main_heading">Main Heading</label>
                                    <textarea class="form-control" id="main_heading" name="main_heading" rows="4"><?php echo htmlspecialchars($settings['main_heading'] ?? 'হার্ট, কিডনি, লিভার, স্কিন, চক্ষু — সুস্থ জীবনের প্রাকৃতিক টনিক <b style="color:#EE5E11">নিডাস</b>'); ?></textarea>
                                    <p class="text-muted small mt-2">HTML formatting is allowed. For example, use <code>&lt;b style="color:#EE5E11"&gt;নিডাস&lt;/b&gt;</code> to make text bold and orange.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                Notification Text
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="notification_text">Notification Text</label>
                                    <input type="text" class="form-control" id="notification_text" name="notification_text" value="<?php echo htmlspecialchars($settings['notification_text'] ?? ''); ?>">
                                    <p class="text-muted small">This is the notification text displayed below the main heading.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                Button Text
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="button_text">Button Text</label>
                                    <input type="text" class="form-control" id="button_text" name="button_text" value="<?php echo htmlspecialchars($settings['button_text'] ?? 'আমাদের সম্মানিত কাস্টমারদের মতামত'); ?>">
                                    <p class="text-muted small">This is the text displayed on the button in the main section.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                Order Button Text
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="order_button_text">Order Button Text</label>
                                    <input type="text" class="form-control" id="order_button_text" name="order_button_text" value="<?php echo htmlspecialchars($settings['order_button_text'] ?? 'অর্ডার করতে চাই'); ?>">
                                    <p class="text-muted small">This is the text displayed on the order button in the product section.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
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
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Save General Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
