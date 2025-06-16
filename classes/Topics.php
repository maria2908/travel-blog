<?php

class Topics
{
    private $conn;
    private $table = 'topic';

    public function __construct()
    {
        require_once('classes/Database.php');
        $database = new Database();
        $this->conn = $database->getConnection();
    }


    public function all_topics()
    {
        $query = "SELECT * FROM " . $this->table ;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
