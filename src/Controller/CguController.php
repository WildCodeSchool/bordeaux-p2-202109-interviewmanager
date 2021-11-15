<?php

namespace App\Controller;

class CguController extends AbstractController
{
    public function cgu()
    {
        return $this->twig->render('User/pageCgu.html.twig');
    }
}
