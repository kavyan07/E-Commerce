<?php

class Core_Connection
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $host = getenv('DB_HOST') ?: 'localhost';
        $port = getenv('DB_PORT') ?: '5432';
        $db_name = getenv('DB_NAME') ?: 'ecommerce_db2';
        $username = getenv('DB_USER') ?: 'postgres';
        $password = getenv('DB_PASS') ?: 'postgres';

        try {
            $dsn = "pgsql:host={$host};port={$port};dbname={$db_name}";
            $this->conn = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->conn;
    }
}
