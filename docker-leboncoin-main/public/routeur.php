<?php

use App\Controllers\AnnonceController;
use App\Controllers\HomeController;

// si le param url est présent on prend sa valeur, sinon on donne la valeur home
$url = $_GET['url'] ?? 'home';

// je transforme $url en un tableau à l'aide de explode()
$arrayUrl = explode('/', $url);

// je récupère la page demandée index 0
$page = $arrayUrl[0];

switch ($page) {

    case 'home':
        $objController = new HomeController();
        $objController->index();
        break;

    case 'annonces':
        $objController = new AnnonceController();
        $objController->index();
        break;

    case 'create':
        $objController = new AnnonceController();
        $objController->create();
        break;

    case 'details':
        if (isset($arrayUrl[1])) {
            $id = $arrayUrl[1];
            $objController = new AnnonceController();
            $objController->show($id);
        } else {
            require_once __DIR__ . "/../src/Views/page404.php";
        }
        break;

    case 'register':
        $objController = new \App\Controllers\UserController();
        $objController->register();
        break;

    case 'login':
        $objController = new \App\Controllers\UserController();
        $objController->login();
        break;

    case 'profil':
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?url=login');
            exit;
        }
        $objController = new \App\Controllers\UserController();
        $objController->profil();
        break;

    case 'logout':
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        require_once __DIR__ . '/../src/Views/logout.php';
        exit;

    default:
        // aucun cas reconnu = on charge la 404
        require_once __DIR__ . "/../src/Views/page404.php";
}
