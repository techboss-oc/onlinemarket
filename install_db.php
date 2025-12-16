<?php
require_once 'config/db.php';

echo "Starting Database Installation...\n";

try {
    $db = Database::getInstance();
    if ($db === null) {
        throw new RuntimeException("Failed to obtain database instance.");
    }
    $connection = $db->getConnection();

    // Read Schema
    $sql = file_get_contents(__DIR__ . '/sql/schema.sql');

    // Execute Schema (splitting by semi-colon might be needed if PDO doesn't handle multiple queries at once depending on driver settings, but let's try direct execution first or split it)
    // PDO::exec handles multiple queries in some drivers, but safer to split.

    $statements = array_filter(array_map('trim', explode(';', $sql)));

    foreach ($statements as $stmt) {
        if (!empty($stmt)) {
            $db->exec($stmt);
        }
    }
    echo "Tables created successfully.\n";

    // Create Test Accounts
    $password_seller = password_hash('password123', PASSWORD_DEFAULT);
    $password_buyer = password_hash('password123', PASSWORD_DEFAULT);
    $password_admin = password_hash('admin123', PASSWORD_DEFAULT);

    // Insert Users (using ON DUPLICATE KEY UPDATE to avoid errors on re-run)
    $stmt = $db->prepare("INSERT INTO users (username, email, password_hash, role, is_verified) VALUES 
        (:seller_user, :seller_email, :seller_pass, 'seller', 1),
        (:buyer_user, :buyer_email, :buyer_pass, 'buyer', 1),
        (:admin_user, :admin_email, :admin_pass, 'admin', 1)
        ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash)
    ");

    $stmt->execute([
        ':seller_user' => 'Seller Test',
        ':seller_email' => 'seller@test.com',
        ':seller_pass' => $password_seller,
        ':buyer_user' => 'Buyer Test',
        ':buyer_email' => 'buyer@test.com',
        ':buyer_pass' => $password_buyer,
        ':admin_user' => 'Admin Test',
        ':admin_email' => 'admin@test.com',
        ':admin_pass' => $password_admin
    ]);

    echo "Test accounts created successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
