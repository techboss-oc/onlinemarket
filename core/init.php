<?php
session_start();

// Define Constants
define('SITE_URL', 'http://market.test'); // Update this based on actual virtual host
define('SITE_NAME', 'Onlinemarket.ng');

// Autoload Classes
spl_autoload_register(function ($class) {
    require_once __DIR__ . '/../classes/' . $class . '.php';
});

// Include Configuration
require_once __DIR__ . '/../config/db.php';

// Include Helper Functions
require_once __DIR__ . '/../includes/functions.php';
