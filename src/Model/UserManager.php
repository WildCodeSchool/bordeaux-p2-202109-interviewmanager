<?php

namespace App\Model;

use App\Model\AbstractManager;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    public function create(array $userData)
    {
        $statement = $this->pdo->prepare('
        INSERT INTO user (firstname, lastname, mail, password, created_at, is_admin)
        VALUES (:firstname, :lastname, :mail, :password, NOW(), false)
        ');
        $statement->bindValue(':firstname', $userData['firstname'], \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $userData['lastname'], \PDO::PARAM_STR);
        $statement->bindValue(':mail', $userData['InputEmail1'], \PDO::PARAM_STR);
        $statement->bindValue(':password', $userData['InputPassword1'], \PDO::PARAM_STR);
        $statement->execute();
    }

    public function selectOneByEmail(string $inputEmail)
    {
        $statement = $this->pdo->prepare("SELECT * FROM user WHERE mail=:mail");
        $statement->bindValue('mail', $inputEmail, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }
}
