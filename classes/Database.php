<?php
class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        // MANUAL CONFIGURATION (Optional: You can hardcode values here if env fails)
        // Just uncomment and set your values to bypass app_config()
        /*
        $host = 'localhost';
        $db = 'your_db_name';
        $user = 'your_db_user';
        $pass = 'your_db_pass';
        $port = 3306;
        */

        if (!isset($host)) {
            $config = app_config();
            // Support both Standard (DB_*) and Legacy (lowercase) keys
            $host = $config['DB_HOST'] ?? $config['host'] ?? 'localhost';
            $db = $config['DB_NAME'] ?? $config['database'] ?? '';
            $user = $config['DB_USER'] ?? $config['user'] ?? '';
            $pass = $config['DB_PASS'] ?? $config['password'] ?? '';
            $port = $config['DB_PORT'] ?? 3306;
        }

        $dsn = "mysql:host={$host};dbname={$db};port={$port};charset=utf8mb4";
        try {
            $this->connection = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (Throwable $e) {
            app_log('DB connection error: ' . $e->getMessage());

            // Show a friendly error instead of 500 White Screen
            if (isset($config['DISPLAY_ERRORS']) && $config['DISPLAY_ERRORS']) {
                die("<h1>Database Connection Failed</h1><p>" . $e->getMessage() . "</p>");
            } else {
                die("<h1>Service Unavailable</h1><p>The application is currently unable to connect to the database. Please check back later.</p>");
            }
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
