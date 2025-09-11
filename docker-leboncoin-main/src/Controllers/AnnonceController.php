<?php

namespace App\Controllers;
use App\Models\Annonce;

class AnnonceController {
    public function index() {
        $annonceModel = new Annonce();
        $annonces = $annonceModel->getAll();
        require_once __DIR__ . '/../Views/annonces.php';
    }

    public function show($id) {
        // à venir pour la page détails
    }

    public function create() {
        // à venir pour déposer une annonce
    }
}