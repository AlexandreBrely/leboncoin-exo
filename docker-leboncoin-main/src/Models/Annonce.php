<?php

namespace App\Models;

// Import des dépendances : Database (ton wrapper) et les classes globales PDO/PDOException
use App\Models\Database;
use PDO;
use PDOException;

/**
 * Class Annonce
 * Modèle représentant les opérations CRUD sur la table `annonces`.
 *
 * Notes générales :
 * - Cette classe attend que Database::getConnection() retourne un objet PDO configuré
 *   (idéalement avec PDO::ERRMODE_EXCEPTION).
 * - Les méthodes retournent des types simples (array, string|false, bool) pour faciliter
 *   la gestion côté contrôleur.
 */
class Annonce
{
    /** @var PDO Connexion PDO utilisée par la classe */
    private $db;

    /**
     * Constructeur
     * Initialise la connexion en appelant ta classe Database.
     */
    public function __construct()
    {
        // Récupère la connexion PDO depuis la classe Database
        // Database::getConnection() doit renvoyer un objet PDO déjà configuré
        $this->db = (new Database())->getConnection();
    }

    /**
     * Récupère toutes les annonces triées par date de publication descendante.
     *
     * Retour :
     * - array : tableau associatif d'annonces (vide si aucune ligne)
     */
    public function getAll(): array
    {
        // Query simple (sans préparation) car pas de paramètres utilisateurs
        $stmt = $this->db->query("SELECT * FROM annonces ORDER BY a_publication DESC");

        // Récupère toutes les lignes sous forme associative
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une annonce par son id avec le nom d'utilisateur (jointure users).
     *
     * Param :
     * - $id : identifiant de l'annonce (int ou valeur convertible)
     *
     * Retour :
     * - array | false : tableau de l'annonce (associatif) ou false si aucune ligne trouvée
     */
    public function getById($id)
    {
        // Requête préparée pour éviter injections et accepter différents types d'id
        $sql = "
            SELECT a.*, u.u_username
            FROM annonces a
            JOIN users u ON a.u_id = u.u_id
            WHERE a.a_id = :id
        ";

        $stmt = $this->db->prepare($sql);

        // execute attend un tableau associatif de placeholders
        $stmt->execute([':id' => $id]);

        // fetch renvoie une seule ligne ou false
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère toutes les annonces d'un utilisateur donné, triées par date.
     *
     * Param :
     * - $userId : identifiant de l'utilisateur
     *
     * Retour :
     * - array : tableau d'annonces (peut être vide)
     */
    public function getByUser($userId): array
    {
        $sql = "SELECT * FROM annonces WHERE u_id = :userId ORDER BY a_publication DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':userId' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recherche simple d'une annonce par son id (sans jointure).
     *
     * Différence avec getById :
     * - find retourne les colonnes de la table annonces uniquement,
     * - getById inclut le nom d'utilisateur via jointure.
     *
     * Param :
     * - $id : identifiant de l'annonce
     *
     * Retour :
     * - array | false
     */
    public function find($id)
    {
        // Ici on utilise un placeholder positionnel (?), parfaitement valide
        // mais tu pourrais uniformiser avec des placeholders nommés si tu préfères.
        $stmt = $this->db->prepare("SELECT * FROM annonces WHERE a_id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crée une annonce.
     *
     * Param :
     * - $titre, $description, $prix : valeurs attendues propres/validées côté contrôleur
     * - $image : nom du fichier ou chemin relatif (attention validé côté upload)
     * - $userId : identifiant de l'auteur
     *
     * Retour :
     * - string (lastInsertId) en cas de succès
     * - false en cas d'erreur (PDOException capturée)
     *
     * Remarque :
     * - On utilise un try/catch basique pour retourner false en cas d'erreur.
     * - En production, loguer $e->getMessage() et ne pas afficher l'erreur brute.
     */
    public function create($titre, $description, $prix, $image, $userId)
    {
        try {
            $sql = "
                INSERT INTO annonces (a_title, a_description, a_price, a_picture, u_id)
                VALUES (:titre, :description, :prix, :image, :userId)
            ";

            $stmt = $this->db->prepare($sql);

            // Binding des paramètres nommés (lisible et sûr)
            $stmt->execute([
                ':titre' => $titre,
                ':description' => $description,
                ':prix' => $prix,
                ':image' => $image,
                ':userId' => $userId
            ]);

            // Retourne l'ID de la nouvelle ligne (utile pour redirection ou log)
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            // En dev on pourrait throw ou loguer $e->getMessage()
            // Ici on renvoie false pour indiquer l'échec à l'appelant
            return false;
        }
    }

    /**
     * Met à jour une annonce.
     *
     * - Si $image est null, on laisse la colonne a_picture inchangée.
     * - Paramètres attendus propres/validés côté contrôleur.
     *
     * Retour :
     * - bool : true si l'exécution s'est bien passée, false sinon.
     */
    public function update($id, $titre, $description, $prix, $image = null): bool
    {
        try {
            if ($image !== null) {
                // Mise à jour incluant l'image
                $sql = "
                    UPDATE annonces
                    SET a_title = :title, a_description = :description, a_price = :price, a_picture = :picture
                    WHERE a_id = :id
                ";
                $params = [
                    ':title' => $titre,
                    ':description' => $description,
                    ':price' => $prix,
                    ':picture' => $image,
                    ':id' => $id
                ];
            } else {
                // Mise à jour sans toucher à la colonne a_picture
                $sql = "
                    UPDATE annonces
                    SET a_title = :title, a_description = :description, a_price = :price
                    WHERE a_id = :id
                ";
                $params = [
                    ':title' => $titre,
                    ':description' => $description,
                    ':price' => $prix,
                    ':id' => $id
                ];
            }

            $stmt = $this->db->prepare($sql);

            // execute renvoie true/false ; on caste en bool pour garantir le type de retour
            return (bool) $stmt->execute($params);
        } catch (PDOException $e) {
            // En production, loguer $e->getMessage() et retourner false
            return false;
        }
    }

    /**
     * Supprime une annonce par son ID.
     *
     * Retour :
     * - bool : true si suppression OK, false sinon
     *
     * Note sécurité :
     * - Pense à vérifier côté contrôleur que l'utilisateur a le droit de supprimer l'annonce
     *   avant d'appeler cette méthode (contrôle d'autorisation).
     */
    public function delete($id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM annonces WHERE a_id = :id");
            return (bool) $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            // En prod, loguer l'erreur et renvoyer false
            return false;
        }
    }
}