<?php

namespace App\Controller;

use App\Model\AdvancementManager;
use App\Model\CompanyManager;

class DashboardController extends AbstractController
{
    public function index(): string
    {
        if (empty($_SESSION)) {
            header('Location: /');
        }
        $userId = $_SESSION['user']['id'];
        $companyManager = new CompanyManager();
        $advancementManager = new AdvancementManager();
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
        $advancements = $advancementManager->selectAll();
        $nameAdvancements = [];
        $stats = [];
        foreach ($advancements as $advancement) {
            $stats[] = $companyManager->countCompanyFromAdvancement($advancement['id'])['nb_status'];
            $nameAdvancements[] = $advancement['name'];
        }
        $allCompanies = $companyManager->selectAll();
        $nameCompanies = [];
        foreach ($allCompanies as $company) {
            $nameCompanies[] = $company['name'];
        }
        $companies = array_unique($nameCompanies);
        $recomCompanies = $companyManager->allRecommending();
        $allInteresting = $companyManager->allName();
        return $this->twig->render('Admin/index.html.twig', [
            'recommendating_companies' => $recomCompanies,
            'all_interesting' => $allInteresting,
            'stats' => $stats,
            'companies' => $companies,
            'name_advancements' => $nameAdvancements,
            'errors' => $errors,
            'success' => $success,
        ]);
    }
}
