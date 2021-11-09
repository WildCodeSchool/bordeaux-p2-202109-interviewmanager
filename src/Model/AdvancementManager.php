<?php

namespace App\Model;

class AdvancementManager extends AbstractManager
{
    public const TABLE = 'advancement';

    public function selectAdvancementsById(int $id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM advancement WHERE user_id=:id AND ");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
