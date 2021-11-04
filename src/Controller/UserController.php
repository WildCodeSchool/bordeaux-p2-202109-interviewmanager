<?php

namespace App\Controller;

use App\Model\UserManager;

class UserController extends AbstractController
{
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
        return $this->twig->render('User/formConnect.html.twig');
    }
}
