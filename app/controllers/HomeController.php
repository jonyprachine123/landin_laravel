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
        // Get Meta Pixel code from settings
        $metaPixelCode = $this->getMetaPixelCode();
        
        // Render the home page with Meta Pixel code and config
        view('home', [
            'meta_pixel_code' => $metaPixelCode,
            'config' => $this->config
        ]);
    }
    
    private function getMetaPixelCode() {
        // Get Meta Pixel code from settings
        $stmt = $this->db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'meta_pixel_code'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['setting_value'] : '';
    }
}
?>
