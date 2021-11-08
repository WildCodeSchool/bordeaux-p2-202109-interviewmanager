<?php

namespace App\Controller;

use App\Model\CompanyManager;
use App\Service\FormValidator;

class CompanyController extends AbstractController
{
    public function addCompany()
    {
        $userId = $_SESSION['user']['id'];
        $errors = [];
        $success = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST['user_id'] = $userId;
            $_POST['name'] = trim($_POST['name']);
            if (empty($_POST['name'])) {
                $errors[] = 'Merci de rentrer le nom d\'une entreprise';
            } elseif (strlen($_POST['name']) < 2) {
                $errors[] = 'Le nom de l\'entreprise doit contenir minimum 2 caractères';
            }
            if (isset($_POST['is_recommendating'])) {
                $_POST['is_recommendating'] = true;
            } else {
                $_POST['is_recommendating'] = false;
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
            $queryString = http_build_query([
                'errors' => $errors,
                'success' => $success,
            ]);
            header('Location: accueil?' . $queryString);
        } else {
            header('Location: accueil');
        }
    }

    public function show(): string
    {
        $companyManger = new CompanyManager();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $posts = [];
            foreach ($_POST as $key => $value) {
                $posts[$key] = trim($value);
            }
            $companyManger->update($posts);
            header('Location: /accueil');
        }
        $company = $companyManger->selectOneById($_GET['id']);
        return $this->twig->render('Company/show.html.twig', [
            'company' => $company
            ]);
    }
}
