<?php

namespace App\Models;

use App\Models\Database;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    public function create($username, $email, $password)
    {
        $sql = "INSERT INTO users (u_username, u_email, u_password)
                VALUES (:username, :email, :password)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $password
        ]);
    }
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE u_email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
