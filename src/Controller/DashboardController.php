<?php

namespace App\Controller;

use App\Model\CompanyManager;

class DashboardController extends AbstractController
{
    public function index(): string
    {
        $userId = $_SESSION['user']['id'];
        $companyManager = new CompanyManager();
        $errors = [];
        $success = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST['user_id'] = $userId;
            $_POST['is_recommendating'] = true;
            if (empty($_POST['name'])) {
                $errors[] = 'Le champs ne doit pas être vide';
            } elseif ($companyManager->selectOneByName($_POST)) {
                $errors[] = 'L\'entreprise existe déjà';
            } else {
                $success = 'Entreprise bien enregistrée';
            }
            $queryString = [
                'errors' => $errors,
                'success' => $success];
            if (empty($errors)) {
                $companyManager->insert($_POST);
            }
            header('Location: admin?' . http_build_query($queryString));
        }
        if (!empty($_GET['errors'])) {
            $errors['erreur'] = $_GET['errors'];
        }
        if (!empty($_GET['success'])) {
            $success = $_GET['success'];
        }
        $recommendatingCompanies = $companyManager->allRecommending();
        $allInteresting = $companyManager->allName();
        return $this->twig->render('Admin/index.html.twig', [
            'companies_recommendating' => $recommendatingCompanies,
            'all_interesting' => $allInteresting,
            'errors' => $errors,
            'success' => $success,
        ]);
    }
}
