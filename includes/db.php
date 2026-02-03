<?php
/**
 * Database Connection Configuration
 * Uses PDO for PostgreSQL
 */

class Database
{
    private $host = 'localhost';
    private $port = '5432';
    private $db_name = 'ecommerce_db2';
    private $username = 'postgres';
    private $password = 'postgres'; // Update with your actual password
    private $conn = null;

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
