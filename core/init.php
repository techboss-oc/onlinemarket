<?php
session_start();

// Define Constants
define('SITE_URL', ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
define('SITE_NAME', 'Onlinemarket.ng');

// Autoload Classes
spl_autoload_register(function ($class) {
    require_once __DIR__ . '/../classes/' . $class . '.php';
});

// Include Helper Functions first (provides app_config/app_log)
require_once __DIR__ . '/../includes/functions.php';

// Configure error reporting based on environment
$config = app_config();
if (!empty($config['DISPLAY_ERRORS'])) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
}
