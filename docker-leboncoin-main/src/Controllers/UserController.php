<?php

namespace App\Controllers;

use App\Models\User;

class UserController
{
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($password !== $confirmPassword) {
                $error = "Les mots de passe ne correspondent pas.";
                require_once __DIR__ . '/../Views/register.php';
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $userModel = new User();
            $userModel->create($username, $email, $hashedPassword);

            header('Location: index.php?url=login');
            exit;
        }

        require_once __DIR__ . '/../Views/register.php';
    }


    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = new \App\Models\User();
            $user = $userModel->findByEmail($email);

            if (!$user || !password_verify($password, $user['u_password'])) {
                $error = "Identifiants incorrects.";
                require_once __DIR__ . '/../Views/login.php';
                return;
            }

            // Démarrer la session
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
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
