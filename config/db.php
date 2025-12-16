<?php
class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $config = app_config();
        $host = $config['DB_HOST'] ?? 'localhost';
        $db = $config['DB_NAME'] ?? '';
        $user = $config['DB_USER'] ?? '';
        $pass = $config['DB_PASS'] ?? '';
        $port = $config['DB_PORT'] ?? 3306;
        $dsn = "mysql:host={$host};dbname={$db};port={$port};charset=utf8mb4";
        try {
            $this->connection = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (Throwable $e) {
            app_log('DB connection error: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
