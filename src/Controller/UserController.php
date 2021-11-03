<?php

namespace App\Controller;

class UserController extends AbstractController
{
    public function index(): string
    {
        return $this->twig->render('User/index.html.twig');
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
