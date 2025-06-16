<?php

class Countries
{
    private $conn;
    private $table = 'country';

    public function __construct()
    {
        require_once('classes/Database.php');
        $database = new Database();
        $this->conn = $database->getConnection();
    }


    public function all_countries()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY country.id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
