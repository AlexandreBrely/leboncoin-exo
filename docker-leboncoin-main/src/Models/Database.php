<?php

namespace App\Models;

class Database {
    private $pdo;

    public function __construct() {
        $this->pdo = new \PDO('mysql:host=db;dbname=leboncoin;charset=utf8', 'root', 'root');
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function getConnection() {
        return $this->pdo;
    }
}
