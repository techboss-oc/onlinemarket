<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function login($email, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];
            return true;
        }
        return false;
    }

    public function register($username, $email, $password, $role = 'buyer')
    {
        // Check if email exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->rowCount() > 0) {
            return "Email already exists.";
        }

        // Check if username exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        if ($stmt->rowCount() > 0) {
            return "Username already exists.";
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :pass, :role)");
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':pass' => $hash,
                ':role' => $role
            ]);
            return true;
        } catch (PDOException $e) {
            return "Registration error: " . $e->getMessage();
        }
    }

    public function logout()
    {
        session_destroy();
        unset($_SESSION['user_id']);
        return true;
    }

    public function updateProfile($user_id, $data)
    {
        $sql = "UPDATE users SET username = :username, phone = :phone, location = :location, bio = :bio";
        $params = [
            ':username' => $data['username'],
            ':phone' => $data['phone'],
            ':location' => $data['location'],
            ':bio' => $data['bio'],
            ':uid' => $user_id
        ];

        if (isset($data['profile_image'])) {
            $sql .= ", profile_image = :img";
            $params[':img'] = $data['profile_image'];
        }

        $sql .= " WHERE id = :uid";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updatePassword($user_id, $current_password, $new_password)
    {
        // Verify current
        $stmt = $this->db->prepare("SELECT password_hash FROM users WHERE id = :id");
        $stmt->execute([':id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($current_password, $user['password_hash'])) {
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE users SET password_hash = :hash WHERE id = :id");
            return $stmt->execute([':hash' => $new_hash, ':id' => $user_id]);
        }
        return false;
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateRole($id, $role)
    {
        $stmt = $this->db->prepare("UPDATE users SET role = :role WHERE id = :id");
        return $stmt->execute([':role' => $role, ':id' => $id]);
    }

    public function toggleVerification($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE users SET is_verified = :status WHERE id = :id");
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    public function deleteUser($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
