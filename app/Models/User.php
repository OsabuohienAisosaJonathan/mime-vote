<?php
namespace app\Models;
use app\Core\Database;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($uuid, $first_name, $last_name, $email, $password) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO users (uuid, role_id, first_name, last_name, email, password_hash) 
                VALUES (:uuid, :role_id, :first_name, :last_name, :email, :password_hash)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':uuid' => $uuid,
            ':role_id' => 3, // Default voter role
            ':first_name' => $first_name, // Should be sanitized prior or via PDO
            ':last_name' => $last_name,
            ':email' => $email,
            ':password_hash' => $hash
        ]);
    }
    
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }
}
