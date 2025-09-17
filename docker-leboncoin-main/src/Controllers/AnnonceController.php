<?php

namespace App\Controllers;

use App\Models\Annonce;

class AnnonceController
{
    // On instancie le modèle une seule fois pour l'utiliser dans toutes les méthodes
    private $annonceModel;

    public function __construct()
    {
        $this->annonceModel = new Annonce();
    }

    // Affiche toutes les annonces
    public function index()
    {
        $annonces = $this->annonceModel->getAll();
        require_once __DIR__ . '/../Views/annonces.php';
    }

    // Affiche les détails d'une annonce spécifique
    public function show($id)
    {
        $annonce = $this->annonceModel->getById($id);

        if (!$annonce) {
            require_once __DIR__ . '/../Views/page404.php';
            return;
        }

        require_once __DIR__ . '/../Views/details.php';
    }

    // Crée une nouvelle annonce
    public function create()
    {
        $this->ensureAuthenticated();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $prixRaw = $_POST['prix'] ?? ''; // <- valeur brute du formulaire
            $result = $this->validateFields($titre, $description, $prixRaw);
            $errors = $result['errors'];
            $prixFloat = $result['prix'];
            $image = null;

            // Si une image est envoyée, on la traite
            if (!empty($_FILES['image']['name'])) {
                $image = $this->handleImageUpload($_FILES['image'], $errors);
            }

            // Si aucune erreur, on enregistre l'annonce
            if (empty($errors)) {
                $userId = $_SESSION['user']['id'];
                $this->annonceModel->create($titre, $description, $prixRaw, $image, $userId);
                header('Location: index.php?url=annonces');
                exit;
            }

            // En cas d'erreur, on renvoie les données à la vue
            extract(['errors' => $errors, 'old' => compact('titre', 'description', 'prix')]);
        }

        require_once __DIR__ . '/../Views/create.php';
    }

    // Modifie une annonce existante
    public function edit($id)
    {
        $annonce = $this->annonceModel->find($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $prixRaw = $_POST['prix'] ?? '';
            $result = $this->validateFields($title, $description, $prixRaw);
            $errors = $result['errors'];
            $image = $annonce['a_image']; // On garde l'image actuelle par défaut

            // Si une nouvelle image est envoyée, on la remplace
            if (!empty($_FILES['image']['name'])) {
                $image = $this->handleImageUpload($_FILES['image'], $errors, $annonce['a_picture']);
            }

            if (empty($errors)) {
                $this->annonceModel->update($id, $title, $description, $prixRaw, $image);
                header('Location: index.php?url=profil');
                exit;
            }

            extract([
                'errors' => $errors,
                'old' => compact('title', 'description', 'price'),
                'annonce' => $annonce
            ]);
        }

        require_once __DIR__ . '/../Views/editAnnonce.php';
    }

    // Supprime une annonce
    public function delete($id)
    {
        $this->annonceModel->delete($id);
        header('Location: index.php?url=profil');
        exit;
    }

    // Vérifie que l'utilisateur est connecté
    private function ensureAuthenticated()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: index.php?url=login');
            exit;
        }
    }

    // Valide les champs texte de l'annonce
    private function validateFields(string $titre, string $description, $prixRaw): array
    {
        $errors = [];
        //trim poour enlever les espaces inutiles

        if (trim($titre) === '') {
            $errors['titre'] = 'Le titre est obligatoire.';
        }

        if (trim($description) === '') {
            $errors['description'] = 'La description est obligatoire.';
        }

        // Prix : trim et remplacer la virgule par un point pour accepter "12,34"
        $priceSanitized = str_replace(',', '.', trim((string)$prixRaw));

        // Vérifier présence et format numérique, autoriser 0
        if ($priceSanitized === '') {
            $errors['prix'] = 'Le prix est obligatoire.';
            $prixFloat = null;
        } elseif (!is_numeric($priceSanitized) || (float)$priceSanitized < 0) {
            $errors['prix'] = 'Le prix doit être un nombre positif ou zéro.';
            $prixFloat = null;
        } else {
            $prixFloat = (float) $priceSanitized;
        }

        return ['errors' => $errors, 'prix' => $prixFloat];
    }

    // Gère l'upload d'une image avec vérifications
    private function handleImageUpload(array $file, array &$errors, ?string $oldImage = null): ?string
    {
        $maxSize = 8 * 1024 * 1024; // Taille max : 8 Mo
        $allowedMime = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp'
        ];

        // Vérifie les erreurs d'upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors['image'] = 'Erreur lors de l\'upload du fichier.';
            return null;
        }

        // Vérifie la taille du fichier
        if ($file['size'] > $maxSize) {
            $errors['image'] = 'Le fichier est trop volumineux (max 8 Mo).';
            return null;
        }

        // Vérifie le type MIME réel
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!array_key_exists($mime, $allowedMime)) {
            $errors['image'] = 'Type de fichier non autorisé.';
            return null;
        }

        // Génère un nom de fichier unique et sécurisé
        $extension = $allowedMime[$mime];
        $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
        $sanitizedName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
        $truncatedName = substr($sanitizedName, 0, 20);
        $uniqueId = bin2hex(random_bytes(8));
        $uniqueName = $uniqueId . '_' . $truncatedName . '.' . $extension;

        // Définit le dossier d'upload
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $destination = $uploadDir . $uniqueName;

        // Déplace le fichier vers le dossier final
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $errors['image'] = 'Impossible de sauvegarder l\'image.';
            return null;
        }

        // Supprime l'ancienne image si elle existe
        if ($oldImage) {
            $oldPath = $uploadDir . $oldImage;
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        return $uniqueName;
    }
}
