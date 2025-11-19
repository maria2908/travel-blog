<?php
require_once('classes/Database.php');

class Countries
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }


    public function all_countries()
    {
        return array_map(fn($c) => (object) $c, $this->conn->select('country', ['order' => 'country.asc']));
    }
}
