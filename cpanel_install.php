<?php
require_once __DIR__ . '/core/init.php';

try {
    $pdo = Database::getInstance()->getConnection();
    $sql = file_get_contents(__DIR__ . '/sql/schema.sql');
    $statements = array_filter(array_map('trim', preg_split('/;\s*\n|;$/m', $sql)));
    $count = 0;
    foreach ($statements as $stmt) {
        if ($stmt === '' || preg_match('/^--|^\/\*/', $stmt)) {
            continue;
        }
        $pdo->exec($stmt);
        $count++;
    }
    echo "OK: {$count} statements executed";
} catch (Throwable $e) {
    app_log('Install error: ' . $e->getMessage());
    http_response_code(500);
    echo 'ERROR';
}

