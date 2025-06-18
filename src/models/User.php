<?php

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($email, $password, $name, $role = 'user') {
        $stmt = $this->db->prepare("
            INSERT INTO users (email, password, name, role) 
            VALUES (:email, :password, :name, :role)
        ");
        
        return $stmt->execute([
            'email' => $email,
            'password' => $password,
            'name' => $name,
            'role' => $role
        ]);
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
} 