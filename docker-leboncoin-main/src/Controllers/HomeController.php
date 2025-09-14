<?php

namespace App\Controllers;

use App\Models\Annonce;

class HomeController {
    public function index(){
        $annonceModel = new Annonce();
        $annonces = $annonceModel->getAll();

        require_once __DIR__ . "/../Views/annonces.php";
    }
}

?>