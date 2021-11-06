<?php

namespace App\Controller;

use App\Model\CompanyManager;
use App\Model\UserManager;
use App\Service\FormValidator;

class UserController extends AbstractController
{
    public function index(): string
    {
        $userManager = new UserManager();
        $userCompany = $userManager->selectCompanyByUser(1);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST['user_id'] = 1;
            $userCompany = $userManager->updateCompanyAdvancement($_POST);
            header('Location: /accueil');
        }
        $errors = [];
        $success = '';
        if (!empty($_GET['errors'])) {
            $errors['error'] = $_GET['errors'];
        }
        if (!empty($_GET['success'])) {
            $success = $_GET['success'];
        }

        return $this->twig->render('User/index.html.twig', ['user_company' => $userCompany, 'errors' => $errors,
            'success' => $success]);
    }

    public function addCompany()
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
            $qstr = http_build_query([
                'errors' => $errors,
                'success' => $success,
                ]);
            header('Location: accueil?' . $qstr);
        }
    }
    public function register(): string
    {
        $formValidator = new FormValidator();
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $posts = [];
            foreach ($_POST as $key => $value) {
                $posts[$key] = trim($value);
            }
            $userManager = new UserManager();
            $mailVerif = $userManager->selectOneByEmail($_POST['InputEmail1']);
            $formValidator->checkName($_POST['firstname'], 'prénom');
            $formValidator->checkName($_POST['lastname'], 'nom');
            $formValidator->checkMail($_POST['InputEmail1'], $mailVerif);
            $formValidator->checkPassword($_POST['InputPassword1']);
            $errors = $formValidator->getErrors();
            if (count($errors) === 0) {
                $userManager = new UserManager();
                $_POST['InputPassword1'] = password_hash($_POST['InputPassword1'], PASSWORD_DEFAULT);
                $userManager->create($posts);
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
                    header('Location: accueil');
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

    public function logout()
    {
        session_destroy();
        header('Location: /');
    }
}
