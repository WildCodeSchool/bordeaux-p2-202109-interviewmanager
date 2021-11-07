<?php

namespace App\Service;

class FormValidator
{
    private $errors = [];

    public function checkName(string $string, $name): void
    {
        if (empty($string)) {
            $this->errors[] = 'Le champs ' . $name . ' est requis';
        }
        if (!preg_match("/^[a-zA-Z-' ]*$/", $string)) {
            $this->errors[] = 'Seuls des lettres et espaces sont autorisées.';
        }
    }

    public function checkMail(string $mail, $isMailExist): void
    {
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Format d\'email invalide';
        }
        if ($isMailExist) {
            $this->errors[] = 'Email déjà existant';
        }
    }

    public function checkPassword(string $pass): void
    {
        if (empty($pass)) {
            $this->errors[] = 'Un mot-de-passe est requis';
        }
        if (strlen($pass) < 2 && strlen($pass) > 0) {
            $this->errors[] = 'Le mot-de-passe est trop court';
        }
    }
    public function getErrors(): array
    {
        return $this->errors;
    }
}
