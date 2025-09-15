<?php

namespace App\Models;

use App\Models\Database;

class User
{
    private static $db;

    public function __construct()
    {
        $this->db = new Database()->getConnection();
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

    public static function checkMail(string $email): bool

    {

        try {
            // Requête qui teste si un email existe déjà.
            // SELECT 1 → on ne demande qu’un "1" (pas besoin des infos de l’utilisateur).
            // LIMIT 1 → on arrête la recherche dès le premier résultat trouvé.
            $sql = 'SELECT 1 FROM `users` WHERE `u_email` = :email LIMIT 1';

            // On prépare la requête avant de l'exécuter
            $stmt = self::$db->prepare($sql);

            // On associe le paramètre nommé :email avec la valeur contenue dans $email,
            // en précisant qu’il s’agit d’une chaîne (db::PARAM_STR).
            // Cela permet à db de traiter correctement la valeur et d’éviter toute injection SQL.
            $stmt->bindValue(':email', $email, \PDO::PARAM_STR);

            // On exécute la requête
            $stmt->execute();

            // Récupère la première colonne du premier résultat de la requête.
            // Ici, comme la requête fait "SELECT 1", on obtiendra soit 1 si l’email existe,
            // soit false si aucun résultat n’est trouvé.
            $result = $stmt->fetchColumn();

            if ($result !== false) {
                // une ligne a été trouvée -> l'email existe déjà
                return true;
            } else {
                // aucune ligne trouvée -> l'email n'existe pas
                return false;
            }
        } catch (\PDOException $e) {
            // test unitaire pour connaitre la raison de l'echec
            // echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Permet de vérifier si un nom d’utilisateur (username) existe déjà dans la table users
     * @param string $username
     * @return bool true si le username existe, false s'il n'existe pas
     */
    public static function checkUsername(string $username): bool
    {
        try {
            // Création d'une instance de connexion à la base de données
          

            // Requête qui teste si un username existe déjà.
            // SELECT 1 → on ne demande qu’un "1" (pas besoin des infos de l’utilisateur).
            // LIMIT 1 → on arrête la recherche dès le premier résultat trouvé.
            $sql = 'SELECT 1 FROM `users` WHERE `u_username` = :username LIMIT 1';

            // On prépare la requête avant de l'exécuter
            $stmt = self::$db->prepare($sql);

            // On associe le paramètre nommé :username avec la valeur contenue dans $username,
            // en précisant qu’il s’agit d’une chaîne (db::PARAM_STR).
            // Cela permet à db de traiter correctement la valeur et d’éviter toute injection SQL.
            $stmt->bindValue(':username', $username, \PDO::PARAM_STR);

            // On exécute la requête
            $stmt->execute();

            // Récupère la première colonne du premier résultat de la requête.
            // Ici, comme la requête fait "SELECT 1", on obtiendra soit 1 si le username existe,
            // soit false si aucun résultat n’est trouvé.
            $result = $stmt->fetchColumn();

            if ($result !== false) {
                // une ligne a été trouvée -> le username existe déjà
                return true;
            } else {
                // aucune ligne trouvée -> le username n'existe pas
                return false;
            }
        } catch (\PDOException $e) {
            // Test unitaire pour connaître la raison de l’échec
            // echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }
}
