<?php
class OrderController {
    private $db;
    private $config;
    
    public function __construct() {
        global $config;
        $this->config = $config;
        $this->db = getDbConnection();
    }
    
    public function index() {
        // Render the order form view
        view('order');
    }
    
    public function store($data) {
        // Validate form data
        $errors = [];
        
        if (empty($data['name'])) {
            $errors[] = 'Name is required';
        }
        
        if (empty($data['address'])) {
            $errors[] = 'Address is required';
        }
        
        if (empty($data['phone'])) {
            $errors[] = 'Phone number is required';
        }
        
        if (empty($data['package'])) {
            $errors[] = 'Package selection is required';
        }
        
        if (empty($data['shipping_method'])) {
            $errors[] = 'Shipping method is required';
        }
        
        // If there are validation errors, return to form with errors
        if (!empty($errors)) {
            view('order', ['errors' => $errors, 'data' => $data]);
            return;
        }
        
        // Get price based on package
        $price = 0;
        switch ($data['package']) {
            case '1month':
                $price = 1200.00;
                break;
            case '3month':
                $price = 2300.00;
                break;
            case '15days':
                $price = 1000.00;
                break;
            default:
                $price = 0;
        }
        
        // Calculate shipping cost based on shipping method
        $shipping_cost = 0.00;
        switch ($data['shipping_method']) {
            case 'inside_dhaka':
                $shipping_cost = 60.00; // Dhaka City shipping cost
                break;
            case 'outside_dhaka':
                $shipping_cost = 170.00; // Outside Dhaka shipping cost
                break;
        }
        
        // Check if honey addon was selected
        $honey_addon = isset($data['honey_addon']) ? 1 : 0;
        $honey_price = $honey_addon ? 500.00 : 0.00;
        
        // Store order in database
        $stmt = $this->db->prepare("
            INSERT INTO orders (name, address, phone, package, price, shipping_method, shipping_cost, country, honey_addon, honey_price, status)
            VALUES (:name, :address, :phone, :package, :price, :shipping_method, :shipping_cost, :country, :honey_addon, :honey_price, :status)
        ");
        
        $stmt->execute([
            ':name' => $data['name'],
            ':address' => $data['address'],
            ':phone' => $data['phone'],
            ':package' => $data['package'],
            ':price' => $price,
            ':shipping_method' => $data['shipping_method'],
            ':shipping_cost' => $shipping_cost,
            ':country' => $data['country'] ?? 'Bangladesh',
            ':honey_addon' => $honey_addon,
            ':honey_price' => $honey_price,
            ':status' => 'pending'
        ]);
        
        // Redirect to thank you page
        redirect($this->config['thank_you_url']);
    }
    
    public function thankYou() {
        // Get Meta Pixel code
        $stmt = $this->db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'meta_pixel_code'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $metaPixelCode = $result ? $result['setting_value'] : '';
        
        // Get thank you page info
        $thankYouInfo = [];
        $stmt = $this->db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'thank_you_info'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && !empty($result['setting_value'])) {
            $thankYouInfo = json_decode($result['setting_value'], true);
        }
        
        // Render the thank you page with config and settings
        view('thank-you', [
            'config' => $this->config,
            'meta_pixel_code' => $metaPixelCode,
            'thank_you_info' => $thankYouInfo
        ]);
    }
}
?>
