<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Annonce;

/**
 * Contrôleur utilisateur : gère l'inscription, la connexion et l'affichage du profil.
 */
class UserController
{
    /**
     * Affiche le formulaire d'inscription et traite la soumission.
     */
    public function register()
    {
        // Si le formulaire est soumis en POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Initialisation des erreurs et des anciennes valeurs
            $errors = [];
            $old = [
                'username' => $_POST['username'] ?? '',
                'email'    => $_POST['email'] ?? ''
            ];

            // 🔹 Validation du pseudo
            $username = trim($_POST['username'] ?? '');
            if ($username === '') {
                $errors['username'] = 'Pseudo obligatoire';
            } elseif (User::checkUsername($username)) {
                $errors['username'] = 'Pseudo déjà utilisé';
            }

            // 🔹 Validation de l'email
            $email = trim($_POST['email'] ?? '');
            if ($email === '') {
                $errors['email'] = 'Mail obligatoire';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Mail non valide';
            } elseif (User::checkMail($email)) {
                $errors['email'] = 'Mail déjà utilisé';
            }

            // 🔹 Validation du mot de passe
            $password = $_POST['password'] ?? '';
            if ($password === '') {
                $errors['password'] = 'Mot de passe obligatoire';
            } elseif (strlen($password) < 8) {
                $errors['password'] = 'Mot de passe trop court (minimum 8 caractères)';
            }

            // 🔹 Confirmation du mot de passe
            $confirm_password = $_POST['confirm_password'] ?? '';
            if ($confirm_password === '') {
                $errors['confirm_password'] = 'Confirmation du mot de passe obligatoire';
            } elseif ($confirm_password !== $password) {
                $errors['confirm_password'] = 'Les mots de passe ne sont pas identiques';
            }

            // 🔹 Si aucune erreur, on crée l'utilisateur
            if (empty($errors)) {
                $userModel = new User();
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                if ($userModel->create($username, $email, $hashedPassword)) {
                    // Redirection vers le profil après inscription
                    header('Location: index.php?url=profil');
                    exit;
                } else {
                    $errors['server'] = "Une erreur s'est produite, veuillez réessayer ultérieurement";
                }
            }

            // Les erreurs et anciennes valeurs seront utilisées dans la vue
        }

        // Affichage du formulaire d'inscription
        require_once __DIR__ . '/../Views/register.php';
    }

    /**
     * Affiche le formulaire de connexion et traite la soumission.
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errors = [];

            // 🔹 Récupération des données du formulaire
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // 🔹 Recherche de l'utilisateur par email
            $userModel = new User();
            $user = $userModel->findByEmail($email);

            // 🔹 Vérification des identifiants
            if (!$user || !password_verify($password, $user['u_password'])) {
                $errors['login'] = "Identifiants incorrects.";
                require_once __DIR__ . '/../Views/login.php';
                return;
            }

            // 🔹 Connexion réussie : on stocke l'utilisateur en session
            $_SESSION['user'] = [
                'id'       => $user['u_id'],
                'username' => $user['u_username'],
                'email'    => $user['u_email']
            ];

            // 🔹 Redirection vers la page d'accueil
            header('Location: index.php?url=home');
            exit;
        }

        // Affichage du formulaire de connexion
        require_once __DIR__ . '/../Views/login.php';
    }

    /**
     * Affiche le profil de l'utilisateur connecté avec ses annonces.
     */
    public function profil()
    {
        // 🔹 Récupération de l'ID utilisateur depuis la session
        $userId = $_SESSION['user']['id'];

        // 🔹 Récupération des annonces de l'utilisateur
        $annonceModel = new Annonce();
        $annonces = $annonceModel->getByUser($userId);

        // 🔹 Affichage de la vue profil
        require_once __DIR__ . '/../Views/profil.php';
    }

    public function logout()
    {
        // 🔹 Nettoyage de la session
        session_unset();
        session_destroy();

        // 🔹 Redirection vers l'accueil
        header('Location: index.php?url=home');
        exit;
    }
}
