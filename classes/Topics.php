<?php
require_once('classes/Database.php');

class Topics
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }


    public function all_topics()
    {
        return array_map(fn($t) => (object) $t, $this->conn->select('topic'));
    }
}
