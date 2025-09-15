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
                $tmpName = $_FILES['image']['tmp_name'];
                $originalName = basename($_FILES['image']['name']);
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);

                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array(strtolower($extension), $allowed)) {
                    $imageName = uniqid() . '_' . $originalName;
                    $uploadPath = __DIR__ . '/../../public/uploads/' . $imageName;

                    if (move_uploaded_file($tmpName, $uploadPath)) {
                        $image = $imageName;
                    }
                }
            }

            // var_dump($image);
            // exit;

            // ID utilisateur fictif (à remplacer par session plus tard)
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
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $image = isset($annonce['a_image']) ? $annonce['a_image'] : null;

            if (!empty($_FILES['image']['name'])) {
                $imageName = uniqid() . '_' . $_FILES['image']['name'];
                $uploadPath = __DIR__ . '/../../public/uploads/' . $imageName;
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);
                $image = $imageName;
            }

            $annonceModel->update($id, $title, $description, $price, $image);
            header('Location: index.php?url=profil');
            exit;
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
