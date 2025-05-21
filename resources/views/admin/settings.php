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

                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        Meta Pixel Code
                    </div>
                    <div class="card-body">
                        <form action="<?php echo $config['admin_settings_url']; ?>?action=save" method="POST">
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
