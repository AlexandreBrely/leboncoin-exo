<?php

namespace App\Controllers;

use App\Models\User;

class UserController
{
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errors = [];
            $old = [
                'username' => $_POST['username'] ?? '',
                'email'    => $_POST['email'] ?? ''
            ];

            // username
            $username = trim($_POST['username'] ?? '');
            if ($username === '') {
                $errors['username'] = 'Pseudo obligatoire';
            } elseif (User::checkUsername($username)) {
                $errors['username'] = 'Pseudo déjà utilisé';
            }

            // email
            $email = trim($_POST['email'] ?? '');
            if ($email === '') {
                $errors['email'] = 'Mail obligatoire';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Mail non valide';
            } elseif (User::checkMail($email)) {
                $errors['email'] = 'Mail déjà utilisé';
            }

            // password
            $password = $_POST['password'] ?? '';
            if ($password === '') {
                $errors['password'] = 'Mot de passe obligatoire';
            } elseif (strlen($password) < 8) {
                $errors['password'] = 'Mot de passe trop court (minimum 8 caractères)';
            }

            // confirm password (nom identique à la vue)
            $confirm_password = $_POST['confirm_password'] ?? '';
            if ($confirm_password === '') {
                $errors['confirm_password'] = 'Confirmation du mot de passe obligatoire';
            } elseif ($confirm_password !== $password) {
                $errors['confirm_password'] = 'Les mots de passe ne sont pas identiques';
            }

            // si pas d'erreurs -> création utilisateur
            if (empty($errors)) {
                $objetUser = new User();
                // create($username, $email, $password)
                if ($objetUser->create($username, $email, password_hash($password, PASSWORD_DEFAULT))) {
                    header('Location: index.php?url=profil');
                    exit;
                } else {
                    $errors['server'] = "Une erreur s'est produite, veuillez réessayer ultérieurement";
                }
            }
        } // afficher la vue avec $errors et $old
        require_once __DIR__ . '/../Views/register.php';
        return;
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errors = [];

            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = new \App\Models\User();
            $user = $userModel->findByEmail($email);

            if (!$user || !password_verify($password, $user['u_password'])) {
                $errors = "Identifiants incorrects.";
                require_once __DIR__ . '/../Views/login.php';
                return;
            }

            // Démarrer la session
            // if (session_status() === PHP_SESSION_NONE) {
            //     session_start();
            // }
            $_SESSION['user'] = [
                'id' => $user['u_id'],
                'username' => $user['u_username'],
                'email' => $user['u_email']
            ];

            header('Location: index.php?url=home');
            exit;
        }


        require_once __DIR__ . '/../Views/login.php';
    }
    public function profil()
    {
        $userId = $_SESSION['user']['id'];
        $annonceModel = new \App\Models\Annonce();
        $annonces = $annonceModel->getByUser($userId);

        require_once __DIR__ . '/../Views/profil.php';
    }
}
