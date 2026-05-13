<?php
require_once 'config.php';

class Database {
    private $conn;
    
    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    public function query($sql) {
        return $this->conn->query($sql);
    }
    
    public function escape($value) {
        return $this->conn->real_escape_string($value);
    }
    
    public function getLastInsertId() {
        return $this->conn->insert_id;
    }
    
    public function __destruct() {
        $this->conn->close();
    }
}
?>