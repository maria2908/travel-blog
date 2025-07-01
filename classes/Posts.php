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
        $query = "SELECT " . " posts.id, title, text, posts.img, country, topic, likes " . " FROM " . $this->table . " INNER JOIN country ON posts.country_id = country.id WHERE user_id=:user_id";
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
        $query = "SELECT posts.id, title, text, posts.img, user_id, country.id AS country_id, country, topic.id AS topic_id, topic FROM " . $this->table . " INNER JOIN country ON posts.country_id = country.id INNER JOIN topic ON posts.topic_id = topic.id WHERE user_id=:user_id ORDER BY create_date DESC ";
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
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($imageFileType, $allowedTypes)) {
                $uniqueFileName = pathinfo($file['name'], PATHINFO_FILENAME) . '_' . uniqid() . '_' . time() . '.' . $imageFileType;
                $targetFile = $targetDir . $uniqueFileName;

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

    public function get_post_by_id($post_id)
    {
        $query = "SELECT posts.id, title, text, posts.img, user_id, country.id AS country_id, topic.id AS topic_id FROM " . $this->table . 
                " INNER JOIN country ON posts.country_id = country.id INNER JOIN topic ON posts.topic_id = topic.id WHERE posts.id=:post_id ORDER BY create_date DESC ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':post_id', $post_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function delete_post_by_id($post_id) {
        $query = "DELETE FROM posts WHERE id=:post_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':post_id', $post_id);

        $stmt->execute();
    }

    public function update_post($post_id, $title, $text, $img, $country_id, $topic_id)
    {
        $query = "UPDATE posts
                  SET title = :title, text =:text, img = :img, country_id= :country_id, topic_id = :topic_id
                  WHERE id=:post_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':post_id', $post_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':img', $img);
        $stmt->bindParam(':country_id', $country_id);
        $stmt->bindParam(':topic_id', $topic_id);

        $stmt->execute();

    }

    public function userLiked($user_id, $post_id) {
        $query = "SELECT 1 FROM post_likes WHERE user_id = ? AND post_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id, $post_id]);
        return $stmt->fetchColumn() !== false;
    }

    public function likePost($user_id, $post_id) {
        $query = "INSERT IGNORE INTO post_likes (user_id, post_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$user_id, $post_id]);
    }

    public function unlikePost($user_id, $post_id) {
        $query = "DELETE FROM post_likes WHERE user_id = ? AND post_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$user_id, $post_id]);
    }

    public function getLikeCount($post_id) {
        $query = "SELECT COUNT(*) FROM post_likes WHERE post_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->execute([$post_id]);
        return $stmt->fetchColumn();
    }
}
