<?php

function sanitize($dirty)
{
    return htmlentities($dirty, ENT_QUOTES, "UTF-8");
}

function redirect($url)
{
    header("Location: " . $url);
    exit();
}

function flash($name, $string = '')
{
    if (isset($_SESSION[$name])) {
        $session = $_SESSION[$name];
        unset($_SESSION[$name]);
        return $session;
    } else {
        $_SESSION[$name] = $string;
    }
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function getCurrentUser()
{
    if (isLoggedIn()) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function app_config()
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }
    $path = __DIR__ . '/../config/env.php';
    if (file_exists($path)) {
        $data = require $path;
        if (is_array($data)) {
            $config = $data;
        }
    }
    if ($config === null) {
        $config = [
            'APP_ENV' => 'local',
            'DB_HOST' => 'localhost',
            'DB_NAME' => 'onlinemarket_ng',
            'DB_USER' => 'root',
            'DB_PASS' => '',
            'DB_PORT' => 3306,
            'DISPLAY_ERRORS' => true,
        ];
    }
    return $config;
}

function app_log($message)
{
    $dir = __DIR__ . '/../storage/logs';
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    @file_put_contents($dir . '/app.log', $line, FILE_APPEND);
}
