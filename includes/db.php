<?php
/**
 * Database Connection Configuration
 * Uses PDO for PostgreSQL
 */

/**
 * Simple .env loader
 */
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}

class Database
{
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    private $conn = null;

    public function __construct()
    {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->port = getenv('DB_PORT') ?: '5432';
        $this->db_name = getenv('DB_NAME') ?: 'ecommerce_db2';
        $this->username = getenv('DB_USER') ?: 'postgres';
        $this->password = getenv('DB_PASS') ?: 'postgres';
    }

    public function connect()
    {
        if ($this->conn !== null) {
            return $this->conn;
        }

        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password);

            // Set error mode
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Set default fetch mode to associative array
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $this->conn;
        } catch (PDOException $e) {
            error_log("Connection Error: " . $e->getMessage());
            die("Database connection failed: " . $e->getMessage());
        }
    }
}

// Global helper to get connection
function getDb()
{
    static $db = null;
    if ($db === null) {
        $database = new Database();
        $db = $database->connect();
    }
    return $db;
}
