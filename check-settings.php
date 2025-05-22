<?php
// Simple script to check settings in the database
require_once __DIR__ . '/app/bootstrap.php';

$db = getDbConnection();
$stmt = $db->prepare("SELECT * FROM settings");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h1>Database Settings</h1>";
echo "<pre>";
foreach ($results as $row) {
    echo "Setting: {$row['setting_key']} = {$row['setting_value']}\n";
}
echo "</pre>";
?>
