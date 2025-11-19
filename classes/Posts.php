<?php
require_once('classes/Database.php');
require_once('classes/User.php');

class Posts
{
    private $conn;
    private $table = 'posts';

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }

    public function user_posts($user_id) {
        return array_map(fn($p) => (object) $p, $this->conn->select('posts', ['user_id' => 'eq.' . $user_id]));
    }

    public function all_posts($country = null) {
        $filters = ['order' => 'create_date.desc'];
        if (!empty($country)) {
            $filters['country'] = 'ilike.%' . $country . '%';
        }
        return array_map(fn($p) => (object) $p, $this->conn->select('posts_view', $filters));
    }

    public function one_post($id) {
        $result = $this->conn->select('posts', ['id' => 'eq.' . $id]);
        return (object) ($result[0] ?? null);
    }

    public function my_posts($user_id) {
        return array_map(fn($p) => (object) $p, $this->conn->select('posts_view', ['user_id' => 'eq.' . $user_id]));
    }

    public function create($title, $text, $img, $user_id, $country_id, $topic_id, $create_date) {
        $data = [[
            'title' => $title,
            'text' => $text,
            'img' => $img,
            'user_id' => $user_id,
            'country_id' => $country_id,
            'topic_id' => $topic_id,
            'create_date' => $create_date
        ]];
        return $this->conn->insert($this->table, $data);
    }

    public function get_post_by_id($post_id) {
        $result = $this->conn->select('posts_view', ['id' => 'eq.' . $post_id]);
        return (object) ($result[0] ?? null);
    }

    public function delete_post_by_id($post_id) {
        return $this->conn->delete($this->table, ['id' => 'eq.' . $post_id]);
    }

    public function update_post($post_id, $title, $text, $img, $country_id, $topic_id) {
        $data = [
            'title' => $title,
            'text' => $text,
            'img' => $img,
            'country_id' => $country_id,
            'topic_id' => $topic_id
        ];
        return $this->conn->update($this->table, ['id' => 'eq.' . $post_id], $data);
    }

    public function userLiked($user_id, $post_id) {
        $result = $this->conn->select('post_likes', [
            'user_id' => 'eq.' . $user_id,
            'post_id' => 'eq.' . $post_id
        ]);
        return !empty($result);
    }

    public function likePost($user_id, $post_id) {
        return $this->conn->insert('post_likes', [[
            'user_id' => $user_id,
            'post_id' => $post_id
        ]]);
    }

    public function unlikePost($user_id, $post_id) {
        return $this->conn->delete('post_likes', [
            'user_id' => 'eq.' . $user_id,
            'post_id' => 'eq.' . $post_id
        ]);
    }

    public function getLikeCount($post_id) {
        $result = $this->conn->select('post_likes', ['post_id' => 'eq.' . $post_id]);
        return count($result);
    }
}
