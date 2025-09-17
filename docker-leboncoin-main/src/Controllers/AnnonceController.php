<?php

namespace App\Controllers;

use App\Models\Annonce;

class AnnonceController
{
    public function index()
    {
        $annonceModel = new Annonce();
        $annonces = $annonceModel->getAll();
        require_once __DIR__ . '/../Views/annonces.php';
    }

    public function show($id)
    {
        $annonceModel = new \App\Models\Annonce();
        $annonce = $annonceModel->getById($id);

        if (!$annonce) {
            require_once __DIR__ . '/../Views/page404.php';
            return;
        }

        require_once __DIR__ . '/../Views/details.php';
    }

    public function create()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        };
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?url=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // var_dump($_FILES);
            // exit;


            $titre = $_POST['titre'];
            $description = $_POST['description'];
            $prix = $_POST['prix'];
            $image = null;

            // Gestion de l'image
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $file=$_FILES['image'];

                // 1. Vérifier les erreurs d'upload
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    $errors['image'] = "Erreur lors de l'upload du fichier.";
                } else {
                    // 2. Vérifier la taille du fichier
                    $maxSize = 2 * 1024 * 1024; // 2 Mo
                    if ($file['size'] > $maxSize) {
                        $errors['image'] = 'Le fichier est trop volumineux (max 2 Mo).';
                    } else {
                        // 3. Vérifier le type MIME réel
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mime = finfo_file($finfo, $file['tmp_name']);
                        finfo_close($finfo);

                        $allowedMime = [
                            'image/jpeg' => 'jpg',
                            'image/png'  => 'png',
                            'image/webp' => 'webp'
                        ];

                        if (!array_key_exists($mime, $allowedMime)) {
                            $errors['image'] = 'Type de fichier non autorisé. Formats acceptés : jpg, png, webp.';
                        } else {
                            // 4. Générer un nom unique et sécurisé
                            $extension = $allowedMime[$mime];
                            $uniqueName = bin2hex(random_bytes(8)) . '.' . $extension;

                            // 5. Définir le chemin de destination
                            $uploadDir = __DIR__ . '/../../public/uploads/';
                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0755, true);
                            }
                            $destination = $uploadDir . $uniqueName;

                            // 6. Déplacer le fichier
                            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                                $errors['image'] = 'Impossible de sauvegarder l\'image.';
                            } else {
                                $image = $uniqueName; // Nom à stocker en base
                            }
                        }
                    }
                }
            }

            // var_dump($image);
            // exit;

            // ID utilisateur 
            $userId = $_SESSION['user']['id'];

            $annonceModel = new \App\Models\Annonce();
            $annonceModel->create($titre, $description, $prix, $image, $userId);

            header('Location: index.php?url=annonces');
            exit;
        }

        require_once __DIR__ . '/../Views/create.php';
    }
    public function edit($id)
{
    $annonceModel = new \App\Models\Annonce();
    $annonce = $annonceModel->find($id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = [];

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = $_POST['price'] ?? '';
        $image = $annonce['a_image']; // image actuelle

        // Validation simple des champs texte
        if ($title === '') {
            $errors['title'] = 'Le titre est obligatoire.';
        }
        if ($description === '') {
            $errors['description'] = 'La description est obligatoire.';
        }
        if (!is_numeric($price) || $price <= 0) {
            $errors['price'] = 'Le prix doit être un nombre positif.';
        }

        // Traitement de l'image si une nouvelle est envoyée
        if (!empty($_FILES['image']['name'])) {
            $file = $_FILES['image'];
            $maxSize = 2 * 1024 * 1024; // 2 Mo
            $allowedMime = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp'
            ];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errors['image'] = 'Erreur lors de l\'upload du fichier.';
            } elseif ($file['size'] > $maxSize) {
                $errors['image'] = 'Le fichier est trop volumineux (max 2 Mo).';
            } else {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (!array_key_exists($mime, $allowedMime)) {
                    $errors['image'] = 'Type de fichier non autorisé.';
                } else {
                    $ext = $allowedMime[$mime];
                    $uniqueName = bin2hex(random_bytes(8)) . '.' . $ext;
                    $uploadDir = __DIR__ . '/../../public/uploads/';
                    $destination = $uploadDir . $uniqueName;

                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    if (!move_uploaded_file($file['tmp_name'], $destination)) {
                        $errors['image'] = 'Impossible de sauvegarder l\'image.';
                    } else {
                        // Supprimer l'ancienne image si elle existe
                        if (!empty($annonce['a_image'])) {
                            $oldPath = $uploadDir . $annonce['a_image'];
                            if (file_exists($oldPath)) {
                                unlink($oldPath);
                            }
                        }
                        $image = $uniqueName;
                    }
                }
            }
        }

        // Si pas d'erreurs, on met à jour
        if (empty($errors)) {
            $annonceModel->update($id, $title, $description, $price, $image);
            header('Location: index.php?url=profil');
            exit;
        }

        // Sinon, on affiche la vue avec les erreurs
        extract([
            'errors' => $errors,
            'old' => [
                'title' => $title,
                'description' => $description,
                'price' => $price
            ],
            'annonce' => $annonce
        ]);
        require_once __DIR__ . '/../Views/editAnnonce.php';
        return;
    }
        require_once __DIR__ . '/../Views/editAnnonce.php';
    }

    public function delete($id)
    {
        $annonceModel = new \App\Models\Annonce();
        $annonceModel->delete($id);

        header('Location: index.php?url=profil');
        exit;
    }
}
