<?php

namespace App\Controller;

use App\Model\UserManager;

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
        return $this->twig->render('User/index.html.twig', ['user_company' => $userCompany]);
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
