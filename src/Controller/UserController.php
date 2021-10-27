<?php

namespace App\Controller;

class UserController extends AbstractController
{
    public function register(): string
    {
        return $this->twig->render('formRegister.html.twig');
    }
}
