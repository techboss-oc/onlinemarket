<?php
require_once 'config/db.php';

try {
    $db = Database::getInstance()->getConnection();
    $db->exec("ALTER TABLE ad_images MODIFY COLUMN image_url TEXT");
    echo "Table altered successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
