<?php

// Importation des contrôleurs nécessaires
use App\Controllers\AnnonceController;
use App\Controllers\HomeController;
use App\Controllers\UserController;

//  Récupération de l'URL demandée via le paramètre GET 'url'
// Si aucun paramètre n'est fourni, on redirige vers la page d'accueil par défaut
$url = $_GET['url'] ?? 'home';

//  Découpage de l'URL en segments (ex: 'details/42' devient ['details', '42'])
$arrayUrl = explode('/', $url);

//  La première partie de l'URL détermine la page ou l'action à exécuter
$page = $arrayUrl[0];

$id = $arrayUrl[1] ?? null;

//  Routeur principal : chaque 'case' correspond à une route de l'application
switch ($page) {

    //  Page d'accueil
    case 'home':
        $objController = new HomeController();
        $objController->index(); // Affiche la vue d'accueil
        break;

    //  Liste des annonces
    case 'annonces':
        $objController = new AnnonceController();
        $objController->index(); // Affiche toutes les annonces
        break;

    //  Création d'une nouvelle annonce
    case 'create':
        $objController = new AnnonceController();
        $objController->create(); // Affiche le formulaire et traite la création
        break;

    //  Modification d'une annonce existante
    case 'edit-annonce':
        $objController = new AnnonceController();
        $objController->edit($_GET['id']); // Charge l'annonce à modifier via son ID
        break;

    //  Suppression d'une annonce
    case 'delete-annonce':
        $objController = new AnnonceController();
        $objController->delete($id, $_SESSION['user']['id']); // Supprime l'annonce via son ID
        break;

    //  Détail d'une annonce spécifique
    case 'details':
        // Vérifie que l'ID est bien présent dans l'URL (ex: details/42)
        if (isset($arrayUrl[1])) {
            $id = $arrayUrl[1];
            $objController = new AnnonceController();
            $objController->show($id); // Affiche les détails de l'annonce
        } else {
            // Si aucun ID n'est fourni, on affiche une page 404
            require_once __DIR__ . "/../src/Views/page404.php";
        }
        break;

    //  Inscription d'un nouvel utilisateur
    case 'register':
        $objController = new \App\Controllers\UserController();
        $objController->register(); // Affiche le formulaire et traite l'inscription
        break;

    //  Connexion utilisateur
    case 'login':
        $objController = new \App\Controllers\UserController();
        $objController->login(); // Affiche le formulaire et traite la connexion
        break;

    //  Profil de l'utilisateur connecté
    case 'profil':
        // Vérifie que l'utilisateur est bien connecté
        if (!isset($_SESSION['user'])) {
            // Si non connecté, redirige vers la page de connexion
            header('Location: index.php?url=login');
            exit;
        }
        $objController = new \App\Controllers\UserController();
        $objController->profil(); // Affiche les annonces de l'utilisateur
        break;

    //  Déconnexion de l'utilisateur
    case 'logout':
        $objController = new \App\Controllers\UserController();
        $objController->logout(); // Détruit la session
        require_once __DIR__ . '/../src/Views/logout.php'; // Affiche la confirmation
        exit;

    //  Route inconnue : on affiche une page 404
    default:
        require_once __DIR__ . "/../src/Views/page404.php";
}