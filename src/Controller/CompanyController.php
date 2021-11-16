<?php

namespace App\Controller;

use App\Model\CompanyManager;
use App\Service\FormValidator;

class CompanyController extends AbstractController
{
    public function addCompany(): void
    {
        $userId = $_SESSION['user']['id'];
        $errors = [];
        $success = '';
        $recommendations = [];
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
                if ($company['name'] === $_POST['name'] && $company['user_id'] === $_SESSION['user']['id']) {
                    $errors[] = 'L\'entreprise existe déjà';
                }
            }
            if (empty($errors)) {
                $recommendations = $companyManager->companyRecommendatingUsers($_POST['name']);
                $success = 'Entreprise bien enregistrée';
                $companyManager->insert($_POST);
            }
            $queryString = http_build_query([
                'errors' => $errors,
                'success' => $success,
                'recommendations' => $recommendations,
            ]);
            header('Location: accueil?' . $queryString);
        } else {
            header('Location: accueil');
        }
    }
    public function show(): string
    {
        $companyManager = new CompanyManager();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $posts = [];
            foreach ($_POST as $key => $value) {
                $posts[$key] = trim($value);
            }

            $companyManager->update($posts, $_GET['id']);
            header('Location: /accueil');
        }

        $company = $companyManager->selectOneById($_GET['id']);
        $userRecom = $companyManager->CompanyRecommendatingUsers($company['name']);
        return $this->twig->render('Company/show.html.twig', [
            'company' => $company,
            'users' => $userRecom,
            ]);
    }

    public function delete(): void
    {
        $companyManager = new CompanyManager();
        $companyManager->delete($_GET['id']);
        header('Location: /profil');
    }
}
