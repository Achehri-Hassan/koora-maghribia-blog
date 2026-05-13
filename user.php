<?php

require_once "connection.php";

class User
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Register new user
    public function create($name, $email, $password, $role)
    {
        $sql = "INSERT INTO users
                (username, email, password , role) 
                VALUES 
                (:username, :email, :password , :role)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "username" => $name,
            "email" => $email,
            "password" => $password,
            "role" => $role
        ]);
    }

    // Login user (IMPORTANT)
    public function login($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            "email" => $email
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all users
    public function all()
    {
        $sql = "SELECT * FROM user ORDER BY id_user DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
