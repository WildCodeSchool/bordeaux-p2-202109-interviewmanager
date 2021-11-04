<?php

namespace App\Model;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    public function selectOneByEmail(string $inputEmail)
    {
        $statement = $this->pdo->prepare("SELECT * FROM user WHERE mail=:mail");
        $statement->bindValue('mail', $inputEmail, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }
}
