<?php

namespace App\Model;

use App\Model\AbstractManager;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    public function create(array $userData)
    {
        $statement = $this->pdo->prepare('
        INSERT INTO user (firstname, lastname, profil_github, mail, password, created_at, is_admin)
        VALUES (:firstname, :lastname, :profil_github, :mail, :password,  NOW(), false)
        ');
        $statement->bindValue(':firstname', $userData['firstname'], \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $userData['lastname'], \PDO::PARAM_STR);
        $statement->bindValue(':profil_github', $userData['profilGithub'], \PDO::PARAM_STR);
        $statement->bindValue(':mail', $userData['mail'], \PDO::PARAM_STR);
        $statement->bindValue(':password', $userData['password'], \PDO::PARAM_STR);
        $statement->execute();
        return $this->pdo->lastInsertId();
    }
    public function selectOneByEmail(string $mail)
    {
        $statement = $this->pdo->prepare("SELECT * FROM user WHERE mail=:mail");
        $statement->bindValue('mail', $mail, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }
}
