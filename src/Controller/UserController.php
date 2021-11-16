<?php

namespace App\Controller;

use App\Model\AdvancementManager;
use App\Model\CompanyManager;
use App\Model\UserManager;
use App\Service\FormValidator;

class UserController extends AbstractController
{
    public function index(): string
    {
        if (empty($_SESSION)) {
            header('Location: /');
        }
        $userId = $_SESSION['user']['id'];
        $companyManager = new CompanyManager();
        $allCompanies = $companyManager->selectAll();
        $nameCompanies = [];
        foreach ($allCompanies as $company) {
            $nameCompanies[] = $company['name'];
        }
        $companies = array_unique($nameCompanies);
        if (isset($_GET['advancement']) && !empty($_GET['advancement'])) {
            $userCompanies = $companyManager->selectCompaniesByLevel($userId, $_GET['advancement']);
        } else {
            $userCompanies = $companyManager->selectCompaniesByUserOrderDESC($userId);
        }


        foreach ($userCompanies as $key => $userCompany) {
            $countRecommendating = $companyManager->countUserForCompanyiesIsRecommendating($userCompany['name']);
            $userCompanies[$key]['count_recommendating'] = $countRecommendating;
        }
        $advancementManager = new AdvancementManager();
        $advancements = $advancementManager->selectAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST['user_id'] = $userId;
            $companyManager->updateCompanyAdvancement($_POST);
            header('Location: /accueil');
        }
        $errors = [];
        $success = '';
        $recommendations = [];
        if (!empty($_GET['errors'])) {
            $errors['error'] = $_GET['errors'];
        }
        if (!empty($_GET['success'])) {
            $success = $_GET['success'];
        }
        if (!empty($_GET['recommendations'])) {
            $recommendations = $_GET['recommendations'];
        }

        return $this->twig->render('User/index.html.twig', [
            'user_companies' => $userCompanies,
            'advancements' => $advancements,
            'companies' => $companies,
            'errors' => $errors,
            'success' => $success,
            'recommendations' => $recommendations,
        ]);
    }

    public function register(): string
    {
        if (!empty($_SESSION)) {
            header('Location: /accueil');
        }
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
                $posts['InputPassword1'] = password_hash($_POST['InputPassword1'], PASSWORD_DEFAULT);
                $userManager->create($posts);
            }
        }
        return $this->twig->render('User/formRegister.html.twig', ['errors' => $errors]);
    }
    public function connect(): string
    {
        if (!empty($_SESSION)) {
            header('Location: /accueil');
        }
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
                'error' => $error,
                ]);
    }

    public function logout(): void
    {
        if (empty($_SESSION)) {
            header('Location: /');
        }
        session_destroy();
        header('Location: /');
    }

    public function profil(): string
    {
        $userId = $_SESSION['user']['id'];
        $companyManager = new CompanyManager();
        $recomCompanies = $companyManager->recommendatingCompanies($userId);
        $recomCompaniesCount = $companyManager->companiesRecommendatingCount($userId);
        $interestedCompaniesCount = $companyManager->companiesInterestedCount($userId);

        return $this->twig->render('User/pageProfil.html.twig', [
            'recommendating_companies'       => $recomCompanies,
            'recommendating_companies_count' => $recomCompaniesCount,
            'interested_companies_count'     => $interestedCompaniesCount
        ]);
    }
}
