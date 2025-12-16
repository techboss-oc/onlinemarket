<?php
// config/db.php

class Database {
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $db_name = 'onlinemarket_ng';
    private $username = 'root';
    private $password = ''; // Default Laragon password

    private function __construct() {
        try {
            // First connect without DB to ensure it exists
            $this->conn = new PDO("mysql:host=" . $this->host, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Create database if not exists
            $this->conn->exec("CREATE DATABASE IF NOT EXISTS " . $this->db_name);
            
            // Connect to the specific database
            $this->conn->exec("USE " . $this->db_name);
            $this->conn->exec("set names utf8mb4");
        } catch(PDOException $e) {
            die("Connection Error: " . $e->getMessage());
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
