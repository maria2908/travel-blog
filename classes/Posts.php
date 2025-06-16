<?php

class Posts
{
    private $conn;
    private $table = 'posts';

    public function __construct()
    {
        require_once('classes/Database.php');
        require_once('classes/User.php');
        $database = new Database();
        $this->conn = $database->getConnection();
    }


    public function user_posts($user_id)
    {
        $query = "SELECT " . " posts.id, title, text, posts.img, country, topic " . " FROM " . $this->table . " INNER JOIN country ON posts.country_id = country.id WHERE user_id=:user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    public function all_posts($country = null)
    {
        if (empty($country)) {
            $query = "SELECT " . " posts.id, title, text, posts.img, country, topic " . " FROM " . $this->table . " INNER JOIN country ON posts.country_id = country.id INNER JOIN topic ON posts.topic_id = topic.id ORDER BY create_date DESC ";
            $stmt = $this->conn->prepare($query);
        } else {
            $query = "SELECT " . " posts.id, title, text, posts.img, country, topic " . " FROM " . $this->table . " INNER JOIN country ON posts.country_id = country.id INNER JOIN topic ON posts.topic_id = topic.id WHERE country=:country ORDER BY create_date DESC ";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':country', $country);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function one_post($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function my_posts($user_id)
    {
        $query = "SELECT posts.id, title, text, posts.img, user_id, country, topic FROM " . $this->table . " INNER JOIN country ON posts.country_id = country.id INNER JOIN topic ON posts.topic_id = topic.id WHERE user_id=:user_id ORDER BY create_date DESC ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function uploadImage($file)
    {
        $targetDir = 'uploads/';

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        if (isset($file) && $file['error'] === 0) {

            $targetFile = $targetDir . basename($file['name']);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageFileType, $allowedTypes)) {
                $uniqueFileName = uniqid() . '_' . time() . '.' . $imageFileType;
                $targetFile = $targetFile . "_" . $uniqueFileName;

                if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                    return $targetFile;
                } else {
                    return 'There was an error uploading the file';
                }
            } else {
                return 'Only JPG, JPEG, PNG, and GIF files are allowed';
            }
        }
        return '';
    }

    public function create($title, $text, $img, $user_id, $country_id, $topic_id, $create_date)
    {
        $query = "INSERT INTO " . $this->table . " (title, text, img, user_id, country_id, topic_id, create_date)
                      VALUES (:title, :text, :img, :user_id, :country_id, :topic_id, :create_date)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':img', $img);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':country_id', $country_id);
        $stmt->bindParam(':topic_id', $topic_id);
        $stmt->bindParam(':create_date', $create_date);

        return $stmt->execute();
    }
}
