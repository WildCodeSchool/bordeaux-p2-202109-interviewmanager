<?php

namespace App\Controller;


use App\Model\CompanyManager;
use App\Model\UserManager;

class UserController extends AbstractController
{
    public function index(): string
    {
        $errors = [];
        $success = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST['user_id'] = 1;
            $_POST['name'] = trim($_POST['name']);
            if (empty($_POST['name'])) {
                $errors[] = 'Merci de rentrer le nom d\'une entreprise';
            } elseif (strlen($_POST['name']) < 2) {
                $errors[] = 'Le nom de l\'entreprise doit contenir minimum 2 caractères';
            }
            if (isset($_POST['is_recommendating'])) {
                $_POST['is_recommendating'] = false;
            } else {
                $_POST['is_recommendating'] = true;
            }
            $companyManager = new CompanyManager();
            $company = $companyManager->selectOneByName($_POST);
            if ($company) {
                $errors[] = 'L\'entreprise existe déjà';
            }
            if (empty($errors)) {
                $companyManager->insert($_POST);
                $success = 'Entreprise bien enregistrée';
            }
        }
        return $this->twig->render('User/index.html.twig', [
            'errors' => $errors,
            'success' => $success,
            ]);
    }
    public function register(): string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post = [];
            foreach ($_POST as $value) {
                $post[] = trim($value);
            }
            if (empty($_POST["firstname"])) {
                $errors[] = "Un nom est requis.";
            }
            if (!preg_match("/^[a-zA-Z-' ]*$/", 'firstname')) {
                    $errors[] = "Seuls des lettres et espaces sont autorisées.";
            }
            if (empty($_POST["lastname"])) {
                $errors[] = "Un prénom est requis.";
            }
            if (!preg_match("/^[a-zA-Z-' ]*$/", 'lastname')) {
                    $errors[] = "Seuls des lettres et espaces sont autorisées.";
            }
            if (empty($_POST["InputEmail1"])) {
                $errors[] = "Un email est requis";
            }
            if (!filter_var($_POST["InputEmail1"], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Format d'email invalide";
            }
            if (empty($_POST["InputPassword1"])) {
                $errors[] = "Un mot-de-passe est requis.";
            }
            if (strlen($_POST['InputPassword1']) < 2) {
                $errors[] = "Le mot-de-passe est trop court";
            }
            $userManager = new UserManager();
            $mailVerif = $userManager->selectOneByEmail($_POST['InputEmail1']);
            if ($mailVerif !== false) {
                $errors[] = 'Cet email existe déjà';
            }
            if (count($errors) === 0) {
                $userManager = new UserManager();
                $_POST['InputPassword1'] = password_hash($_POST['InputPassword1'], PASSWORD_DEFAULT);
                $userManager->create($post);
            }
        }
        return $this->twig->render('User/formRegister.html.twig', ['errors' => $errors]);
    }
    public function connect(): string
    {
        $error = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userManager = new UserManager();
            $userData = $userManager->selectOneByEmail($_POST['InputEmail1']);
            if ($userData !== false) {
                if (password_verify($_POST['InputPassword1'], $userData['password'])) {
                    $_SESSION['user'] = $userData;
                } else {
                    $error = 'Vos identifiants sont incorrects';
                }
            } else {
                $error = 'Vos identifiants sont incorrects';
            }
        }
        return $this->twig->render('User/formConnect.html.twig', [
                'session' => $_SESSION,
                'error' => $error,
                ]);
    }
}
