<?php

namespace App\Models;

// use PDO;
// use PDOException;

class Database
{
    //ancienne version

    //private $pdo;

    //public function __construct() {

    // $this->pdo = new \PDO('mysql:host=db;dbname=leboncoin;charset=utf8', 'root', 'root');
    //$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);}
    // public function getConnection() {return $this->pdo;}

    private $pdo;

    public function __construct()
    {

        $this->pdo = new \PDO(
            "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8",
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD']
        );

        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }


    public function getConnection()
    {
        return $this->pdo;
    }
}


    //nouvelle version variable env

//     public static function createInstancePDO(): ?PDO
//     {
//         try {
//             $pdo = new PDO(
//                 "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8",
//                 $_ENV['DB_USER'],
//                 $_ENV['DB_PASSWORD'],
//                 [
//                     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//                     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
//                 ]
//             );
//             return $pdo;
//         } catch (PDOException $e) {
//             echo "erreur de connexion :" . $e->getMessage();
//             return null;
//         }
//     }
// }
