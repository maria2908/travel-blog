<?php

require_once 'config.php';

class Database {
    private $connection;

    public function __construct() {
        try {
            $dsn = "mysql:host=" . hostname . ";dbname=" . db_name . ";charset=utf8mb4";

            $this->connection = new PDO($dsn, username, password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("❌ Connection error: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}
