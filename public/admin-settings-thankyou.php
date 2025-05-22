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

// Get thank you page info
$thankYouInfo = [];
if (isset($settings['thank_you_info'])) {
    $thankYouInfo = json_decode($settings['thank_you_info'], true) ?: [];
}

// Handle settings form submission
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Thank You Page Info
    $pageTitle = $_POST['page_title'] ?? '';
    $mainHeading = $_POST['main_heading'] ?? '';
    $successMessage = $_POST['success_message'] ?? '';
    $thankYouMessage = $_POST['thank_you_message'] ?? '';
    $homeButtonText = $_POST['home_button_text'] ?? '';
    $contactSectionTitle = $_POST['contact_section_title'] ?? '';
    $whatsappNumber = $_POST['whatsapp_number'] ?? '';
    $phoneNumber = $_POST['phone_number'] ?? '';
    $footerText = $_POST['footer_text'] ?? '';
    $footerLink = $_POST['footer_link'] ?? '';
    
    // Create thank you page info array
    $thankYouInfo = [
        'page_title' => $pageTitle,
        'main_heading' => $mainHeading,
        'success_message' => $successMessage,
        'thank_you_message' => $thankYouMessage,
        'home_button_text' => $homeButtonText,
        'contact_section_title' => $contactSectionTitle,
        'whatsapp_number' => $whatsappNumber,
        'phone_number' => $phoneNumber,
        'footer_text' => $footerText,
        'footer_link' => $footerLink
    ];
    
    // Save thank you page info as JSON
    $thankYouInfoJson = json_encode($thankYouInfo, JSON_UNESCAPED_UNICODE);
    
    // Check if thank_you_info setting exists
    $stmt = $db->prepare("SELECT id FROM settings WHERE setting_key = :key");
    $stmt->execute([':key' => 'thank_you_info']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $stmt = $db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
        $stmt->execute([':value' => $thankYouInfoJson, ':key' => 'thank_you_info']);
    } else {
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
        $stmt->execute([':key' => 'thank_you_info', ':value' => $thankYouInfoJson]);
    }
    
    // Debug: Log the thank you page info being saved
    error_log('Saving thank you page info: ' . $thankYouInfoJson);
    
    // Set success message
    $success = 'Thank you page settings have been saved successfully';
    
    // Redirect back to the settings page to prevent form resubmission
    header("Location: admin-settings-thankyou.php?success=1");
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
    <title>Thank You Page Settings - Prachin Bangla</title>
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
            background-color: rgba(255, 255, 255, 0.1);
        }
        .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.2);
        }
        .nav-link i {
            margin-right: 10px;
        }
        .content-wrapper {
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .text-muted.small {
            font-size: 0.875rem;
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
                        <h1>Thank You Page Settings</h1>
                        <a href="admin-settings.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Settings</a>
                    </div>
                    
                    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div class="alert alert-success">
                        <?php echo $success ?: 'Settings have been saved successfully'; ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="admin-settings-thankyou.php">
                        <div class="card">
                            <div class="card-header">
                                Page Settings
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="page_title">Page Title</label>
                                    <input type="text" class="form-control" id="page_title" name="page_title" value="<?php echo htmlspecialchars($thankYouInfo['page_title'] ?? 'নিডাস- ধন্যবাদ'); ?>">
                                    <p class="text-muted small">This is the title that appears in the browser tab.</p>
                                </div>
                                <div class="form-group">
                                    <label for="main_heading">Main Heading</label>
                                    <input type="text" class="form-control" id="main_heading" name="main_heading" value="<?php echo htmlspecialchars($thankYouInfo['main_heading'] ?? '<span>নিডাস</span> - অর্ডার সম্পন্ন হয়েছে'); ?>">
                                    <p class="text-muted small">This is the main heading at the top of the page. HTML is allowed for styling.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                Success Message Section
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="success_message">Success Message Heading</label>
                                    <input type="text" class="form-control" id="success_message" name="success_message" value="<?php echo htmlspecialchars($thankYouInfo['success_message'] ?? 'আপনার অর্ডার সফলভাবে গ্রহণ করা হয়েছে!'); ?>">
                                    <p class="text-muted small">This is the heading displayed in the success message section.</p>
                                </div>
                                <div class="form-group">
                                    <label for="thank_you_message">Thank You Message</label>
                                    <textarea class="form-control" id="thank_you_message" name="thank_you_message" rows="3"><?php echo htmlspecialchars($thankYouInfo['thank_you_message'] ?? 'আমাদের একজন প্রতিনিধি শীঘ্রই আপনার সাথে যোগাযোগ করবেন। আপনার সহযোগিতার জন্য ধন্যবাদ।'); ?></textarea>
                                    <p class="text-muted small">This is the message displayed below the success message heading.</p>
                                </div>
                                <div class="form-group">
                                    <label for="home_button_text">Home Button Text</label>
                                    <input type="text" class="form-control" id="home_button_text" name="home_button_text" value="<?php echo htmlspecialchars($thankYouInfo['home_button_text'] ?? 'হোম পেজে ফিরে যান'); ?>">
                                    <p class="text-muted small">This is the text displayed on the button that links back to the home page.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                Contact Section
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="contact_section_title">Contact Section Title</label>
                                    <input type="text" class="form-control" id="contact_section_title" name="contact_section_title" value="<?php echo htmlspecialchars($thankYouInfo['contact_section_title'] ?? 'যেকোনো প্রয়োজনে যোগাযোগ করুন'); ?>">
                                    <p class="text-muted small">This is the title displayed at the top of the contact section.</p>
                                </div>
                                <div class="form-group">
                                    <label for="whatsapp_number">WhatsApp Number</label>
                                    <input type="text" class="form-control" id="whatsapp_number" name="whatsapp_number" value="<?php echo htmlspecialchars($thankYouInfo['whatsapp_number'] ?? '01990888222'); ?>">
                                    <p class="text-muted small">This is the WhatsApp number displayed in the contact section.</p>
                                </div>
                                <div class="form-group">
                                    <label for="phone_number">Phone Number</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($thankYouInfo['phone_number'] ?? '0 1990-888222'); ?>">
                                    <p class="text-muted small">This is the phone number displayed in the contact section.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                Footer Settings
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="footer_text">Footer Text</label>
                                    <input type="text" class="form-control" id="footer_text" name="footer_text" value="<?php echo htmlspecialchars($thankYouInfo['footer_text'] ?? 'Prachin Bangla Limited'); ?>">
                                    <p class="text-muted small">This is the text displayed in the footer.</p>
                                </div>
                                <div class="form-group">
                                    <label for="footer_link">Footer Link</label>
                                    <input type="text" class="form-control" id="footer_link" name="footer_link" value="<?php echo htmlspecialchars($thankYouInfo['footer_link'] ?? 'https://www.prachinebangla.com'); ?>">
                                    <p class="text-muted small">This is the URL that the footer text links to.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Save Thank You Page Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
