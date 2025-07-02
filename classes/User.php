<?php

class User
{

    private $conn;
    private $table = 'user';

    public function __construct()
    {
        require_once('classes/Database.php');
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function register($email, $password)
    {
        if (empty($email) || empty($password)) {
            return "Email and password cannot be empty.";
        }

        $query_compare = "SELECT * FROM " . $this->table . " WHERE email=:email";
        $stmt = $this->conn->prepare($query_compare);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if ($user && $email === $user->email) {
            return "User already exists, please use another email.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO " . $this->table . " (email, password) VALUES (:email, :password)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
        }
        
        if ($stmt->execute()) {
            session_start();
            $query = "SELECT * FROM " . $this->table . " WHERE email=:email ";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            $_SESSION['email'] = $user->email;
            $_SESSION['user_id'] = $user->id;

            return true;
        }

        return "Something went wrong. Please try again.";
    }

    public function login($email, $password)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE email=:email ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if ($user && password_verify($password, $user->password)) {
            $_SESSION['email'] = $user->email;
            $_SESSION['user_id'] = $user->id;

            return true;
        }
    }

    public function setProfileImg($profile_image, $id)
    {
        $query = "UPDATE user SET profile_image = :profile_image WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':profile_image', $profile_image);

        $stmt->execute();
    }

    public function getProfileImg($id)
    {
        $query = "SELECT profile_image FROM user WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProfilData($id) 
    {
        $query = "SELECT * FROM user WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function setProfilData($fieldname, $data, $id) 
    {
        $query = "UPDATE user SET " . $fieldname . " = :data WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':data', $data);

        $stmt->execute();
    }

    public function change_password($user_id, $current_password, $new_password, $confirm_password)
    {
        if (empty($current_password)  ) {
            return "Please add you current password";
        } 
        if (empty($new_password)) {
            return "Please enter a new password.";
        }
        if (empty($confirm_password)) {
            return "Please confirm your new password.";
        }

        $query_compare = "SELECT password FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query_compare);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        $stored_hashed_password = $result->password;
        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

        var_dump(password_verify($current_password, $stored_hashed_password));
        
        if (!password_verify($current_password, $stored_hashed_password)) {
            return "The current password is incorrect.";
        } 
        elseif (password_verify($new_password, $stored_hashed_password)) {
            return "New password cannot be the same as the current password.";
        } 
        elseif ($new_password !== $confirm_password) {
            return "Your confirm password does not match. Please try again.";
        } else {

            $query = "UPDATE " . $this->table . " SET password = :password WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':password', $hashed_new_password);

            if ($stmt->execute()) {
                return true;
            } else {
                return "Failed to update the password. Please try again.";
            } 
        }
    }

    public function like_post($user_id, $like)
    {
        if($like === 1) {
            $query = "UPDATE user SET like_post = 1 WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $user_id);

            $stmt->execute();
        } else {
            $query = "UPDATE user SET like_post = 0 WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $user_id);

            $stmt->execute();
        }
    }
}
