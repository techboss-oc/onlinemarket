<?php
// config/db.php

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $config = function_exists('app_config') ? app_config() : [
            'DB_HOST' => 'localhost',
            'DB_NAME' => 'onlinemarket_ng',
            'DB_USER' => 'root',
            'DB_PASS' => '',
            'DB_PORT' => 3306,
            'DISPLAY_ERRORS' => true,
        ];

        $host = $config['DB_HOST'] ?? 'localhost';
        $name = $config['DB_NAME'] ?? 'onlinemarket_ng';
        $user = $config['DB_USER'] ?? 'root';
        $pass = $config['DB_PASS'] ?? '';
        $port = (int)($config['DB_PORT'] ?? 3306);

        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

        try {
            $this->conn = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
            ]);
        } catch (PDOException $e) {
            if (function_exists('app_log')) {
                app_log('DB connection error: ' . $e->getMessage());
            }
            if (!empty($config['DISPLAY_ERRORS'])) {
                die('Database connection error: ' . $e->getMessage());
            }
            header('HTTP/1.1 500 Internal Server Error');
            die('We are experiencing technical difficulties. Please try again later.');
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}
