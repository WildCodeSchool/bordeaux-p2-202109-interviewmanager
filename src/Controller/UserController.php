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

        $selectedValue = '';
        if (isset($_GET['advancement']) && !empty($_GET['advancement'])) {
            $userCompanies = $companyManager->selectCompaniesByLevel($userId, $_GET['advancement']);
            $selectedValue = $_GET['advancement'];
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
            'test' => $selectedValue,
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
            $mailVerif = $userManager->selectOneByEmail($_POST['mail']);
            $formValidator->checkName($_POST['firstname'], 'prénom');
            $formValidator->checkName($_POST['lastname'], 'nom');
            $formValidator->checkMail($_POST['mail'], $mailVerif);
            $formValidator->checkPassword($_POST['password']);
            if (empty($_POST['profilGithub'])) {
                $posts['profilGithub'] = 'wildcodeschool';
            }
            $errors = $formValidator->getErrors();
            if (count($errors) === 0) {
                $userManager = new UserManager();
                $posts['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $userId = $userManager->create($posts);
                $_SESSION['user'] = $userManager->selectOneById($userId);
                header('Location: accueil');
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
            $userData = $userManager->selectOneByEmail($_POST['mail']);
            if ($userData !== false) {
                if (password_verify($_POST['password'], $userData['password'])) {
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

    public function profil(): string
    {
        $userId = $_SESSION['user']['id'];
        $companyManager = new CompanyManager();
        $advancementManager = new AdvancementManager();
        $recomCompanies = $companyManager->recommendatingCompanies($userId);
        $recomCompaniesCount = $companyManager->companiesRecommendatingCount($userId);
        $interestedCompaniesCount = $companyManager->companiesInterestedCount($userId);
        $advancements = $advancementManager->selectAll();
        $datas = [];
        foreach ($advancements as $advancement) {
            $datas[] = $companyManager->countCompanyFromAdvancementByUser($advancement['id'], $userId)['nb_status'];
            $nameAdvancements[] = $advancement['name'];
        }
        return $this->twig->render('User/pageProfil.html.twig', [
            'recommendating_companies' => $recomCompanies,
            'recommendating_companies_count' => $recomCompaniesCount,
            'interested_companies_count' => $interestedCompaniesCount,
            'datas' => $datas,
            'name_advancements' => $nameAdvancements,
        ]);
    }

    public function updateAdvancement()
    {
        $json = json_decode(file_get_contents('php://input'));
        $datas = [
            'user_id' => $json->userId,
            'company-id' => $json->companyId,
            'advancement' => $json->advancement,
        ];
        $companyManager = new CompanyManager();
        $companyManager->updateCompanyAdvancement($datas);
        return json_encode('ok');
    }

    public function logout(): void
    {
        if (empty($_SESSION)) {
            header('Location: /');
        }
        session_destroy();
        header('Location: /');
    }
}
