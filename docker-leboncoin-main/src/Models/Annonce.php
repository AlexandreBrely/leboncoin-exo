<?php

namespace App\Models;
use App\Models\Database;

class Annonce {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getAll() {
        $query = $this->db->query("SELECT * FROM annonces ORDER BY a_publication DESC");
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
}
