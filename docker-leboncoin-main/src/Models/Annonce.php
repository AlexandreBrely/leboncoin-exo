<?php

namespace App\Models;

use App\Models\Database;

class Annonce
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    public function getAll()
    {
        $query = $this->db->query("SELECT * FROM annonces ORDER BY a_publication DESC");
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
    

    public function create($titre, $description, $prix, $image, $userId)
    {
        $sql = "INSERT INTO annonces (a_title, a_description, a_price, a_picture, u_id)
            VALUES (:titre, :description, :prix, :image, :userId)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':titre' => $titre,
            ':description' => $description,
            ':prix' => $prix,
            ':image' => $image,
            ':userId' => $userId

        ]);
    }

    public function getById($id)
    {
        $sql = "SELECT a.*, u.u_username 
            FROM annonces a
            JOIN users u ON a.u_id = u.u_id
            WHERE a.a_id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByUser($userId)
    {
        $sql = "SELECT * FROM annonces WHERE u_id = :userId ORDER BY a_publication DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM annonces WHERE a_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function update($id, $title, $description, $price, $image = null)
    {
        if ($image) {
            $stmt = $this->db->prepare("UPDATE annonces SET a_title = ?, a_description = ?, a_price = ?, a_image = ? WHERE a_id = ?");
            $stmt->execute([$title, $description, $price, $image, $id]);
        } else {
            $stmt = $this->db->prepare("UPDATE annonces SET a_title = ?, a_description = ?, a_price = ? WHERE a_id = ?");
            $stmt->execute([$title, $description, $price, $id]);
        }
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM annonces WHERE a_id = ?");
        $stmt->execute([$id]);
    }
}
