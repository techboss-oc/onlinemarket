<?php
require_once 'config/db.php';

try {
    $db = Database::getInstance()->getConnection();

    // Add columns if they don't exist
    $columns = [
        "ALTER TABLE users ADD COLUMN IF NOT EXISTS phone VARCHAR(20)",
        "ALTER TABLE users ADD COLUMN IF NOT EXISTS bio TEXT",
        "ALTER TABLE users ADD COLUMN IF NOT EXISTS location VARCHAR(100)",
        "ALTER TABLE users ADD COLUMN IF NOT EXISTS profile_image VARCHAR(255)"
    ];

    foreach ($columns as $sql) {
        try {
            $db->exec($sql);
        } catch (PDOException $e) {
            // Ignore if column exists or other minor error
        }
    }

    echo "Schema updated successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
