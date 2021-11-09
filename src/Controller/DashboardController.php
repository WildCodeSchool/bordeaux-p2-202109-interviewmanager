<?php

namespace App\Controller;

class DashboardController extends AbstractController
{
    public function index()
    {
        return $this->twig->render('Admin/index.html.twig');
    }
}
