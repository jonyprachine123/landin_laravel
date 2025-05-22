<?php
class AdminController {
    private $config;
    private $db;
    
    public function __construct() {
        global $config;
        $this->config = $config;
        $this->db = getDbConnection();
        
        // Create admin table if it doesn't exist
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS admins (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ');
        
        // Check if admin user exists, if not create default admin
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM admins");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] == 0) {
            // Create default admin user (username: admin, password: admin123)
            $stmt = $this->db->prepare("INSERT INTO admins (username, password) VALUES (:username, :password)");
            $stmt->execute([
                ':username' => 'admin',
                ':password' => password_hash('admin123', PASSWORD_DEFAULT)
            ]);
        }
    }
    
    public function loginPage() {
        // Check if already logged in
        if (isset($_SESSION['admin_id'])) {
            redirect($this->config['admin_dashboard_url']);
            return;
        }
        
        // Try to use the simple login page first, fall back to regular login if it doesn't exist
        $simplePath = __DIR__ . '/../../resources/views/admin/login-simple.php';
        if (file_exists($simplePath)) {
            // Use the simplified login page that works in all environments
            extract(['config' => $this->config, 'error' => isset($_GET['error']) ? $_GET['error'] : null]);
            require $simplePath;
        } else {
            // Fall back to the regular login page
            view('admin/login', [
                'config' => $this->config
            ]);
        }
    }
    
    public function login() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Validate input
        if (empty($username) || empty($password)) {
            view('admin/login', ['error' => 'Username and password are required']);
            return;
        }
        
        // Check credentials
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$admin || !password_verify($password, $admin['password'])) {
            view('admin/login', ['error' => 'Invalid username or password']);
            return;
        }
        
        // Set session
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        
        // Redirect to dashboard
        redirect($this->config['admin_dashboard_url']);
    }
    
    public function dashboard() {
        // Check if logged in
        if (!isset($_SESSION['admin_id'])) {
            redirect($this->config['admin_login_url']);
            return;
        }
        
        // Get all orders
        $stmt = $this->db->prepare("SELECT * FROM orders ORDER BY created_at DESC");
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
        
        // Calculate total revenue
        $totalRevenue = 0;
        foreach ($orders as $order) {
            $totalRevenue += $order['price'] + $order['shipping_cost'] + $order['honey_price'];
        }
        
        view('admin/dashboard', [
            'orders' => $orders,
            'todayOrders' => $todayOrders,
            'totalRevenue' => $totalRevenue,
            'config' => $this->config
        ]);
    }
    
    public function editOrder($id) {
        // Check if logged in
        if (!isset($_SESSION['admin_id'])) {
            redirect($this->config['admin_login_url']);
            return;
        }
        
        // Get order by ID
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            // Order not found
            redirect($this->config['admin_dashboard_url']);
            return;
        }
        
        view('admin/edit-order', [
            'order' => $order,
            'config' => $this->config
        ]);
    }
    
    public function updateOrder($id) {
        // Check if logged in
        if (!isset($_SESSION['admin_id'])) {
            redirect($this->config['admin_login_url']);
            return;
        }
        
        // Get order by ID to check if it exists
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            // Order not found
            redirect($this->config['admin_dashboard_url']);
            return;
        }
        
        // Get form data
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $country = $_POST['country'] ?? 'Bangladesh';
        $package = $_POST['package'] ?? '';
        $shipping_method = $_POST['shipping_method'] ?? '';
        $honey_addon = isset($_POST['honey_addon']) ? 1 : 0;
        $status = $_POST['status'] ?? $order['status'] ?? 'pending';
        
        // Validate form data
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'Name is required';
        }
        
        if (empty($address)) {
            $errors[] = 'Address is required';
        }
        
        if (empty($phone)) {
            $errors[] = 'Phone number is required';
        }
        
        if (empty($package)) {
            $errors[] = 'Package selection is required';
        }
        
        if (empty($shipping_method)) {
            $errors[] = 'Shipping method is required';
        }
        
        // If there are validation errors, return to form with errors
        if (!empty($errors)) {
            $errorMessage = implode(', ', $errors);
            view('admin/edit-order', ['order' => $order, 'error' => $errorMessage]);
            return;
        }
        
        // Calculate prices based on selections
        $price = 0;
        switch ($package) {
            case '1month':
                $price = 1800.00;
                break;
            case '3month':
                $price = 5000.00;
                break;
            case '15days':
                $price = 1000.00;
                break;
            default:
                $price = 0;
        }
        
        // Get shipping cost
        $shipping_cost = ($shipping_method === 'inside_dhaka') ? 50.00 : 100.00;
        
        // Honey price
        $honey_price = $honey_addon ? 500.00 : 0.00;
        
        // Update order in database
        $stmt = $this->db->prepare("
            UPDATE orders SET 
                name = :name, 
                address = :address, 
                phone = :phone, 
                package = :package, 
                price = :price, 
                shipping_method = :shipping_method, 
                shipping_cost = :shipping_cost, 
                country = :country, 
                honey_addon = :honey_addon, 
                honey_price = :honey_price,
                status = :status
            WHERE id = :id
        ");
        
        $stmt->execute([
            ':name' => $name,
            ':address' => $address,
            ':phone' => $phone,
            ':package' => $package,
            ':price' => $price,
            ':shipping_method' => $shipping_method,
            ':shipping_cost' => $shipping_cost,
            ':country' => $country,
            ':honey_addon' => $honey_addon,
            ':honey_price' => $honey_price,
            ':status' => $status,
            ':id' => $id
        ]);
        
        // Redirect back to edit page with success message
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $updatedOrder = $stmt->fetch(PDO::FETCH_ASSOC);
        
        view('admin/edit-order', [
            'order' => $updatedOrder, 
            'success' => 'Order has been updated successfully'
        ]);
    }
    
    public function updateOrderStatus($id, $status) {
        // Check if logged in
        if (!isset($_SESSION['admin_id'])) {
            redirect($this->config['admin_login_url']);
            return;
        }
        
        // Validate status
        $validStatuses = ['pending', 'processing', 'delivered', 'successful', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            redirect($this->config['admin_dashboard_url']);
            return;
        }
        
        // Update order status
        $stmt = $this->db->prepare("UPDATE orders SET status = :status WHERE id = :id");
        $stmt->execute([':status' => $status, ':id' => $id]);
        
        // Redirect back to dashboard
        redirect($this->config['admin_dashboard_url']);
    }
    
    public function deleteOrder($id) {
        // Check if logged in
        if (!isset($_SESSION['admin_id'])) {
            redirect($this->config['admin_login_url']);
            return;
        }
        
        // Get order by ID to check if it exists
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            // Order not found
            redirect($this->config['admin_dashboard_url']);
            return;
        }
        
        // Delete the order
        $stmt = $this->db->prepare("DELETE FROM orders WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        // Redirect back to dashboard with success message
        $_SESSION['success_message'] = 'Order #' . $id . ' has been deleted successfully.';
        redirect($this->config['admin_dashboard_url']);
    }
    
    public function settings() {
        // Check if logged in
        if (!isset($_SESSION['admin_id'])) {
            redirect($this->config['admin_login_url']);
            return;
        }
        
        // Get all settings
        $settings = $this->getSettings();
        
        // Get pricing info from settings
        $pricingInfo = [];
        if (isset($settings['pricing_info'])) {
            $pricingInfo = json_decode($settings['pricing_info'], true) ?? [];
        }
        
        // Get order form info from settings
        $orderFormInfo = [];
        if (isset($settings['order_form_info'])) {
            $orderFormInfo = json_decode($settings['order_form_info'], true) ?? [];
        }
        
        view('admin/settings', [
            'settings' => $settings,
            'pricingInfo' => $pricingInfo,
            'orderFormInfo' => $orderFormInfo,
            'config' => $this->config
        ]);
    }
    
    // Function to convert YouTube watch URL to embed URL
    private function convertYoutubeUrl($url) {
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
    
    public function saveSettings() {
        // Check if logged in
        if (!isset($_SESSION['admin_id'])) {
            redirect($this->config['admin_login_url']);
            return;
        }
        
        // Get form data
        $metaPixelCode = $_POST['meta_pixel_code'] ?? '';
        $mainHeading = $_POST['main_heading'] ?? '';
        $notificationText = $_POST['notification_text'] ?? '';
        $buttonText = $_POST['button_text'] ?? '';
        $orderButtonText = $_POST['order_button_text'] ?? '';
        $youtubeUrl = $_POST['youtube_url'] ?? '';
        
        // Handle customer reviews - if the form was submitted with the reviews section
        // but no reviews were present (all deleted), we should save an empty array
        if (isset($_POST['customer_reviews_section_submitted'])) {
            $customerReviews = isset($_POST['customer_reviews']) ? json_encode($_POST['customer_reviews']) : '[]';
        } else {
            // If the section wasn't in the form at all, don't update the setting
            $customerReviews = null;
        }
        
        // Get pricing information
        $product1Description = $_POST['product1_description'] ?? '';
        $product1Price = $_POST['product1_price'] ?? '';
        $product2Description = $_POST['product2_description'] ?? '';
        $product2Price = $_POST['product2_price'] ?? '';
        $deliveryInfo = $_POST['delivery_info'] ?? '';
        $phoneNumber = $_POST['phone_number'] ?? '';
        $whatsappNumber = $_POST['whatsapp_number'] ?? '';
        
        // Get order form information
        $orderFormHeading = $_POST['order_form_heading'] ?? '';
        $packageSelectText = $_POST['package_select_text'] ?? '';
        $bestSellerLabel = $_POST['best_seller_label'] ?? '';
        $economyLabel = $_POST['economy_label'] ?? '';
        $product1FullDescription = $_POST['product1_full_description'] ?? '';
        $product1RegularPrice = $_POST['product1_regular_price'] ?? '';
        $product1SalePrice = $_POST['product1_sale_price'] ?? '';
        $product2FullDescription = $_POST['product2_full_description'] ?? '';
        $product2RegularPrice = $_POST['product2_regular_price'] ?? '';
        $product2SalePrice = $_POST['product2_sale_price'] ?? '';
        $billingDetailsHeading = $_POST['billing_details_heading'] ?? '';
        $nameLabel = $_POST['name_label'] ?? '';
        $addressLabel = $_POST['address_label'] ?? '';
        $phoneLabel = $_POST['phone_label'] ?? '';
        $shippingHeading = $_POST['shipping_heading'] ?? '';
        $outsideDhakaLabel = $_POST['outside_dhaka_label'] ?? '';
        $outsideDhakaCost = $_POST['outside_dhaka_cost'] ?? '';
        $insideDhakaLabel = $_POST['inside_dhaka_label'] ?? '';
        $insideDhakaCost = $_POST['inside_dhaka_cost'] ?? '';
        $orderSummaryHeading = $_POST['order_summary_heading'] ?? '';
        $productColumnHeading = $_POST['product_column_heading'] ?? '';
        $subtotalColumnHeading = $_POST['subtotal_column_heading'] ?? '';
        $codLabel = $_POST['cod_label'] ?? '';
        $codDescription = $_POST['cod_description'] ?? '';
        $confirmOrderButtonText = $_POST['confirm_order_button_text'] ?? '';
        
        // Convert YouTube URL if needed
        $youtubeUrl = $this->convertYoutubeUrl($youtubeUrl);
        
        // Create an array of pricing information
        $pricingInfo = [
            'product1_description' => $product1Description,
            'product1_price' => $product1Price,
            'product2_description' => $product2Description,
            'product2_price' => $product2Price,
            'delivery_info' => $deliveryInfo,
            'phone_number' => $phoneNumber,
            'whatsapp_number' => $whatsappNumber
        ];
        
        // Create an array of order form information
        $orderFormInfo = [
            'order_form_heading' => $orderFormHeading,
            'package_select_text' => $packageSelectText,
            'best_seller_label' => $bestSellerLabel,
            'economy_label' => $economyLabel,
            'product1_full_description' => $product1FullDescription,
            'product1_regular_price' => $product1RegularPrice,
            'product1_sale_price' => $product1SalePrice,
            'product2_full_description' => $product2FullDescription,
            'product2_regular_price' => $product2RegularPrice,
            'product2_sale_price' => $product2SalePrice,
            'billing_details_heading' => $billingDetailsHeading,
            'name_label' => $nameLabel,
            'address_label' => $addressLabel,
            'phone_label' => $phoneLabel,
            'shipping_heading' => $shippingHeading,
            'outside_dhaka_label' => $outsideDhakaLabel,
            'outside_dhaka_cost' => $outsideDhakaCost,
            'inside_dhaka_label' => $insideDhakaLabel,
            'inside_dhaka_cost' => $insideDhakaCost,
            'order_summary_heading' => $orderSummaryHeading,
            'product_column_heading' => $productColumnHeading,
            'subtotal_column_heading' => $subtotalColumnHeading,
            'cod_label' => $codLabel,
            'cod_description' => $codDescription,
            'confirm_order_button_text' => $confirmOrderButtonText
        ];
        
        // Save settings
        $this->saveSetting('meta_pixel_code', $metaPixelCode);
        $this->saveSetting('main_heading', $mainHeading);
        $this->saveSetting('notification_text', $notificationText);
        $this->saveSetting('button_text', $buttonText);
        $this->saveSetting('order_button_text', $orderButtonText);
        $this->saveSetting('youtube_url', $youtubeUrl);
        $this->saveSetting('customer_reviews', $customerReviews);
        $this->saveSetting('pricing_info', json_encode($pricingInfo));
        $this->saveSetting('order_form_info', json_encode($orderFormInfo));
        
        // Redirect back to settings page with success message
        redirect($this->config['admin_settings_url'] . '?success=1');
        exit;
    }
    
    private function getSettings() {
        // Get all settings from database
        $stmt = $this->db->prepare("SELECT setting_key, setting_value FROM settings");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert to associative array
        $settings = [];
        foreach ($results as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        
        return $settings;
    }
    
    private function saveSetting($key, $value) {
        // If value is null, don't update this setting
        if ($value === null) {
            return;
        }
        
        // Check if setting exists
        $stmt = $this->db->prepare("SELECT id FROM settings WHERE setting_key = :key");
        $stmt->execute([':key' => $key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            // Update existing setting
            $stmt = $this->db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
            $stmt->execute([':value' => $value, ':key' => $key]);
        } else {
            // Insert new setting
            $stmt = $this->db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)");
            $stmt->execute([':key' => $key, ':value' => $value]);
        }
    }
    
    public function logout() {
        // Unset admin session variables
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_username']);
        
        // Redirect to login page
        redirect($this->config['admin_login_url']);
    }
    
    public function clearOrders() {
        // Check if logged in
        if (!isset($_SESSION['admin_id'])) {
            redirect($this->config['admin_login_url']);
            return;
        }
        
        // Simply load the clear orders view
        // The actual clearing is handled by the view itself via direct PDO connection
        // This is a simple approach for this specific functionality
        include __DIR__ . '/../../resources/views/admin/clear-orders.php';
    }
}
?>
