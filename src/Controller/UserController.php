<?php

namespace App\Controller;

use App\Model\CompanyManager;

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
        return $this->twig->render('User/formRegister.html.twig');
    }
    public function connect(): string
    {
        return $this->twig->render('User/formConnect.html.twig');
    }
}
