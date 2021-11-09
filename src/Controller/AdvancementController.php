<?php

namespace App\Controller;

use App\Model\AdvancementManager;


class AdvancementController extends AbstractController
{
    public function index()
    {
        $dataUser = $_SESSION['user'];
        $advancementManager = new AdvancementManager();
        $companiesAdvancement = $advancementManager->selectAdvancementsById($dataUser['id']);

        return $this->twig->render('User/index.html.twig', ['companies_advancement' => $companiesAdvancement],);
    }

}