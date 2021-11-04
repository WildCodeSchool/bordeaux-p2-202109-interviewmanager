<?php

namespace App\Model;

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
}
