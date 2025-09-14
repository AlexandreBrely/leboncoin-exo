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
            $titre = $_POST['titre'];
            $description = $_POST['description'];
            $prix = $_POST['prix'];
            $image = null;

            // Gestion de l'image
            if (!empty($_FILES['image']['name'])) {
                $imageName = uniqid() . '_' . $_FILES['image']['name'];
                $uploadPath = __DIR__ . '/../../public/uploads/' . $imageName;
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);
                $image = $imageName;
            }

            // ID utilisateur fictif (à remplacer par session plus tard)
            $userId = 1;

            $annonceModel = new \App\Models\Annonce();
            $annonceModel->create($titre, $description, $prix, $image, $userId);

            header('Location: index.php?url=annonces');
            exit;
        }

        require_once __DIR__ . '/../Views/create.php';
    }
}
