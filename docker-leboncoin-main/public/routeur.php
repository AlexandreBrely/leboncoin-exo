<?php

use App\Controllers\AnnonceController;
use App\Controllers\HomeController;

// si le param url est présent on prend sa valeur, sinon on donne la valeur home
$url = $_GET['url'] ?? 'home';

// je transforme $url en un tableau à l'aide de explode()
$arrayUrl = explode('/', $url);

// je récupère la page demandée index 0
$page = $arrayUrl[0];

switch($page){
    case 'home':
        $objController = new HomeController();
        $objController->index();
        break;
    case 'annonces':
        $objController = new AnnonceController();
        $objController->index();
        break;

    default:
        // aucun cas reconnu = on charge la 404
        require_once __DIR__ . "/../src/Views/page404.php";
}