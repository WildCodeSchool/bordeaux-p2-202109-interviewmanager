<?php

namespace App\Model;

use App\Model\AbstractManager;

class UserManager extends AbstractManager
{
    public function selectCompanyByUser(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM company WHERE user_id=:id");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function selectAdvancementById(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM advancement WHERE user_id=:id");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function updateCompanyAdvancement(array $data)
    {
        $statement = $this->pdo->prepare(
            "UPDATE company SET advancement_id=:advancement WHERE user_id=:user_id AND id=:id"
        );
        $statement->bindValue(':advancement', $data['advancement'], \PDO::PARAM_INT);
        $statement->bindValue(':user_id', $data['user_id'], \PDO::PARAM_INT);
        $statement->bindValue(':id', $data['company-id'], \PDO::PARAM_INT);


        return $statement->execute();
    }
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