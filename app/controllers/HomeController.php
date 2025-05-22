<?php
class HomeController {
    private $db;
    private $config;
    
    public function __construct() {
        global $config;
        $this->config = $config;
        $this->db = getDbConnection();
    }
    
    public function index() {
        // Get main heading from settings
        $mainHeading = $this->getMainHeading();
        
        // Get notification text from settings
        $notificationText = $this->getNotificationText();
        $buttonText = $this->getButtonText();
        $youtubeUrl = $this->getYoutubeUrl();
        $customerReviews = $this->getCustomerReviews();
        $orderButtonText = $this->getOrderButtonText();
        $pricingInfo = $this->getPricingInfo();
        $orderFormInfo = $this->getOrderFormInfo();
        
        // Get Meta Pixel code
        $stmt = $this->db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'meta_pixel_code'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $metaPixelCode = $result ? $result['setting_value'] : '';
        
        // Get footer settings
        $settings = [];
        $stmt = $this->db->prepare("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('footer_copyright', 'footer_link')");
        $stmt->execute();
        $footerSettings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($footerSettings as $setting) {
            $settings[$setting['setting_key']] = $setting['setting_value'];
        }
        
        // Render the home page with Meta Pixel code, main heading, notification text, and config
        view('home', [
            'config' => $this->config,
            'meta_pixel_code' => $metaPixelCode,
            'main_heading' => $mainHeading,
            'notification_text' => $notificationText,
            'button_text' => $buttonText,
            'youtube_url' => $youtubeUrl,
            'customer_reviews' => $customerReviews,
            'order_button_text' => $orderButtonText,
            'pricing_info' => $pricingInfo,
            'order_form_info' => $orderFormInfo,
            'settings' => $settings
        ]);
    }
    
    private function getMainHeading() {
        // Get main heading from settings
        $stmt = $this->db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'main_heading'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return the heading from settings or use default if not set
        return $result ? $result['setting_value'] : 'হার্ট, কিডনি, লিভার, স্কিন, চক্ষু — সুস্থ জীবনের প্রাকৃতিক টনিক <b style="color:#EE5E11">নিডাস</b>';
    }
    
    private function getNotificationText() {
        // Get notification text from settings
        $stmt = $this->db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'notification_text'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return the notification text from settings or use default if not set
        return $result ? $result['setting_value'] : 'নিয়মিত নিডাস খেলেই ইনশাআল্লাহ ইনফেকশন, কোলেস্টেরল, ডায়াবেটিস, ও গ্যাস্ট্রিক থেকে মুক্তি!';
    }
    
    private function getButtonText() {
        // Direct query to get button text
        $stmt = $this->db->query("SELECT setting_value FROM settings WHERE setting_key = 'button_text'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Default text if not found
        $defaultText = 'আমাদের সম্মানিত কাস্টমারদের মতামত';
        
        // Return the value or default
        return ($result && !empty($result['setting_value'])) ? $result['setting_value'] : $defaultText;
    }
    
    private function getOrderButtonText() {
        // Direct query to get order button text
        $stmt = $this->db->query("SELECT setting_value FROM settings WHERE setting_key = 'order_button_text'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Default text if not found
        $defaultText = 'অর্ডার করতে চাই';
        
        // Return the value or default
        return ($result && !empty($result['setting_value'])) ? $result['setting_value'] : $defaultText;
    }
    
    private function getPricingInfo() {
        // Get pricing information from settings as JSON
        $stmt = $this->db->query("SELECT setting_value FROM settings WHERE setting_key = 'pricing_info'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Default values if no settings found
        $defaultPricingInfo = [
            'product1_description' => '১ বক্সে ৯০টি ট্যাবলেট ১ মাস ১৫ দিনের কোর্স=',
            'product1_price' => 'অফারে মাত্র 1200/=',
            'product2_description' => '২ বক্সে ১৮০টি ট্যাবলেট ৩ মাসের সম্পূর্ণ কোর্স =',
            'product2_price' => 'অফারে মাত্র 2300/=',
            'delivery_info' => 'সারা বাংলাদেশে সম্পূর্ণ ফ্রি ডেলিভারি!',
            'phone_number' => '880 1990-888222',
            'whatsapp_number' => '880 1990-888222'
        ];
        
        // If we have settings in the database, use those, otherwise use defaults
        if ($result && !empty($result['setting_value'])) {
            $pricingInfo = json_decode($result['setting_value'], true);
            // In case the JSON is invalid or empty
            if (!$pricingInfo) {
                $pricingInfo = $defaultPricingInfo;
            }
        } else {
            $pricingInfo = $defaultPricingInfo;
        }
        
        return $pricingInfo;
    }
    
    private function getOrderFormInfo() {
        // Get order form information from settings as JSON
        $stmt = $this->db->query("SELECT setting_value FROM settings WHERE setting_key = 'order_form_info'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Default values if no settings found
        $defaultOrderFormInfo = [
            'order_form_heading' => 'অর্ডার করতে আপনার সঠিক তথ্য দিয়ে নিচের ফর্মটি পূরণ করে <span style="color:#EE5E11">Confirm Order</span> এ ক্লিক করুন:-',
            'package_select_text' => 'কোন প্যাকেজটি নিতে চান সিলেক্ট করুন :',
            'best_seller_label' => 'সেরাবিক্রয়',
            'economy_label' => 'সাশ্রয়ী',
            'product1_full_description' => '১ বক্সে ৯০টি ট্যাবলেট | ১ মাস ১৫ দিনের কোর্স',
            'product1_regular_price' => '১৩৫০',
            'product1_sale_price' => '১২০০',
            'product2_full_description' => '২ বক্সে ১৮০টি ট্যাবলেট ৩ মাসের সম্পূর্ণ কোর্স',
            'product2_regular_price' => '২৭০০',
            'product2_sale_price' => '২৩০০',
            'billing_details_heading' => 'Billing details',
            'name_label' => 'আপনার নাম',
            'address_label' => 'আপনার সম্পূর্ণ ঠিকানা',
            'phone_label' => 'আপনার মোবাইল নাম্বার',
            'shipping_heading' => 'Shipping',
            'outside_dhaka_label' => 'ঢাকা সিটির বাহিরে',
            'outside_dhaka_cost' => '170.00',
            'inside_dhaka_label' => 'ঢাকা সিটিতে',
            'inside_dhaka_cost' => '60.00',
            'order_summary_heading' => 'Your order',
            'product_column_heading' => 'Product',
            'subtotal_column_heading' => 'Subtotal',
            'cod_label' => 'ক্যাশ অন ডেলিভারি',
            'cod_description' => 'আমি অবশ্যই পণ্যটি রিসিভ করবো, পণ্যটি হাতে পেয়ে টাকা পরিশোধ করবো, ইনশাআল্লাহ',
            'confirm_order_button_text' => 'Confirm Order'
        ];
        
        // If we have settings in the database, use those, otherwise use defaults
        if ($result && !empty($result['setting_value'])) {
            $orderFormInfo = json_decode($result['setting_value'], true);
            // In case the JSON is invalid or empty
            if (!$orderFormInfo) {
                $orderFormInfo = $defaultOrderFormInfo;
            }
        } else {
            $orderFormInfo = $defaultOrderFormInfo;
        }
        
        return $orderFormInfo;
    }
    
    private function getSetting($key, $default) {
        // Get a setting value with a default fallback
        $stmt = $this->db->query("SELECT setting_value FROM settings WHERE setting_key = '$key'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return ($result && !empty($result['setting_value'])) ? $result['setting_value'] : $default;
    }
    
    private function getYoutubeUrl() {
        // Direct query to get YouTube URL
        $stmt = $this->db->query("SELECT setting_value FROM settings WHERE setting_key = 'youtube_url'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Debug: Log the result
        if ($result) {
            error_log('YouTube URL from DB: ' . $result['setting_value']);
        } else {
            error_log('YouTube URL not found in DB, using default');
        }
        
        // Default YouTube URL if not found
        $defaultUrl = 'https://www.youtube.com/embed/Eod9gvxhHuU';
        
        // Get the URL to return
        $url = ($result && !empty($result['setting_value'])) ? $result['setting_value'] : $defaultUrl;
        
        // Debug: Log the final URL
        error_log('Returning YouTube URL: ' . $url);
        
        return $url;
    }
    
    private function getCustomerReviews() {
        $stmt = $this->db->query("SELECT setting_value FROM settings WHERE setting_key = 'customer_reviews'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && !empty($result['setting_value'])) {
            $reviews = json_decode($result['setting_value'], true);
            if (is_array($reviews)) {
                // Return the reviews even if the array is empty
                return $reviews;
            }
        }
        
        // Only use default reviews if the setting doesn't exist at all (first time setup)
        return [
            ['image' => 'CustomerReview1.png', 'alt' => 'Customer Review 1'],
            ['image' => 'CustomerReview2.jpg', 'alt' => 'Customer Review 2'],
            ['image' => 'CustomerReview3.jpg', 'alt' => 'Customer Review 3'],
            ['image' => 'CustomerReview4.jpg', 'alt' => 'Customer Review 4'],
            ['image' => 'CustomerReview1.png', 'alt' => 'Customer Review 5'],
            ['image' => 'CustomerReview2.jpg', 'alt' => 'Customer Review 6']
        ];
    }
}
?>
