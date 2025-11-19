<?php
require_once('classes/Database.php');

class User
{

    private $conn;
    private $table = 'user';

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }

    public function register($email, $password)
    {
        if (empty($email) || empty($password)) {
            return "Email and password cannot be empty.";
        }

        $existing = $this->conn->select('user', ['email' => 'eq.' . $email]);

        if (!empty($existing)) {
            return "User already exists, please use another email.";
        }

 
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $data = ['email' => $email, 'password' => $hashed_password];
        $result = $this->conn->insert($this->table, [$data]);
    
    
        if (!empty($result)) {
            session_start();
            $_SESSION['email'] = $result[0]['email'];
            $_SESSION['user_id'] = $result[0]['id'];
            return true;
        }

        return "Something went wrong. Please try again.";
    }

    public function login($email, $password)
    {
        $user = $this->conn->select($this->table, ['email' => 'eq.' . $email]);

        if (!empty($user) && password_verify($password, $user[0]['password'])) {
            $_SESSION['email'] = $user[0]['email'];
            $_SESSION['user_id'] = $user[0]['id'];
            return true;
        }
        return false;
    }

    public function setProfileImg($profile_image, $id)
    {
        return $this->conn->update($this->table, ['id' => 'eq.' . $id], ['profile_image' => $profile_image]);
    }

    public function getProfileImg($id)
    {
        $result = $this->conn->select($this->table, ['id' => 'eq.' . $id]);
        return $result[0]['profile_image'] ?? null;
    }

    public function getProfilData($id) 
    {
        $result = $this->conn->select($this->table, ['id' => 'eq.' . $id]);
        return $result[0] ?? null;
    }

    public function setProfilData($fieldname, $data, $id) 
    {
        return $this->conn->update($this->table, ['id' => 'eq.' . $id], [$fieldname => $data]);
    }

    public function change_password($user_id, $current_password, $new_password, $confirm_password)
    {
        if (empty($current_password)) return "Please add your current password.";
        if (empty($new_password)) return "Please enter a new password.";
        if (empty($confirm_password)) return "Please confirm your new password.";

        $user = $this->conn->select($this->table, ['id' => 'eq.' . $user_id]);
        if (empty($user)) return "User not found.";

        $stored = $user[0]['password'];
        if (!password_verify($current_password, $stored)) {
            return "The current password is incorrect.";
        }
        if (password_verify($new_password, $stored)) {
            return "New password cannot be the same as the current password.";
        }
        if ($new_password !== $confirm_password) {
            return "Your confirm password does not match.";
        }

        $hashed_new = password_hash($new_password, PASSWORD_DEFAULT);
        $success = $this->conn->update($this->table, ['id' => 'eq.' . $user_id], ['password' => $hashed_new]);
        return $success ? true : "Failed to update the password.";
    }

    public function like_post($user_id, $like) {
        return $this->conn->update($this->table, ['id' => 'eq.' . $user_id], ['like_post' => $like ? 1 : 0]);
    }
}
