<?php

namespace App\Controllers;

use App\Models\User;

class UserController
{
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $error = [];

            // if (isset($_POST["username"])) {
            //     // on va vérifier si c'est vide
            //     if (empty($_POST["username"])) {
            //         // si c'est vide, je créé une erreur dans mon tableau
            //         $errors['username'] = 'Pseudo obligatoire';
            //     } else if (User::checkUsername($_POST["username"])) {
            //         // si le pseudo déjà présent dans notre bdd, on créé un message d'erreur
            //         $errors['username'] = 'Pseudo déjà utilisé';
            //     }
            // }
            $username = $_POST['username'];
            if ($username) {
                // on va vérifier si c'est vide
                if (empty($username)) {
                    // si c'est vide, je créé une erreur dans mon tableau
                    $errors['username'] = 'Pseudo obligatoire';
                } else if (User::checkUsername($username)) {
                    // si le pseudo déjà présent dans notre bdd, on créé un message d'erreur
                    $errors['username'] = 'Pseudo déjà utilisé';
                }
            }


            if (isset($_POST["email"])) {
                // on va vérifier si c'est vide
                if (empty($_POST["email"])) {
                    // si c'est vide, je créé une erreur dans mon tableau
                    $errors['email'] = 'Mail obligatoire';
                } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                    // si mail non valide, on créé une erreur
                    $errors['email'] = 'Mail non valide';
                } else if (User::checkMail($_POST["email"])) {
                    // si mail déjà utilisé, on créé un message d'erreur dans notre tableau
                    $errors['email'] = 'Mail déjà utilisé';
                }
            }
            // $email = $_POST['email'];

            if (isset($_POST["password"])) {
                // on va vérifier si c'est vide
                if (empty($_POST["password"])) {
                    // si c'est vide, je créé une erreur dans mon tableau
                    $errors['password'] = 'Mot de passe obligatoire';
                } else if (strlen($_POST["password"]) < 8) {
                    // si le mot de passe est trop court, on créé une erreur
                    $errors['password'] = 'Mot de passe trop court (minimum 8 caractères)';
                }
            }
            $password = $_POST['password'];


            if (isset($_POST["confirmPassword"])) {
                // on va vérifier si c'est vide
                if (empty($_POST["confirmPassword"])) {
                    // si c'est vide, je créé une erreur dans mon tableau
                    $errors['confirmPassword'] = 'Confirmation du mot de passe obligatoire';
                } else if ($_POST["confirmPassword"] !== $_POST["password"]) {
                    // si les deux mots de passe ne sont pas identiques, on créé une erreur
                    $errors['confirmPassword'] = 'Les mots de passe ne sont pas identiques';
                }
            }
            $confirmPassword = $_POST['confirm_password'];

            if ($password !== $confirmPassword) {
                $error = "Les mots de passe ne correspondent pas.";
                require_once __DIR__ . '/../Views/register.php';
                return;
            }

            // nous vérifions s'il n'y a pas d'erreur = on regarde si le tableau est vide.
            if (empty($errors)) {

                // j'instancie mon objet selon la classe User
                $objetUser = new User();
                // je vais créer mon User selon la méthode createUser() et j'essaie de créer mon User
                if ($objetUser->create($_POST["email"], $_POST["password"], $_POST["username"])) {
                    header('Location: index.php?url=create-success');
                    exit;
                } else {
                    $errors['server'] = "Une erreur s'est produite veuillez rééssayer ultèrieurement";
                }
            }
        }

        require_once __DIR__ . "/../Views/register.php";
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
