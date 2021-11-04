<?php

namespace App\Model;

class CompanyManager extends AbstractManager
{
    public const TABLE = 'company';

    public function insert(array $data)
    {
        $statement = $this->pdo->prepare('
        INSERT INTO company (name, user_id, created_at, is_recommendating, advancement_id) 
        VALUES (:name, :user_id, NOW(), :is_recommendating, 1)');
        $statement->bindValue(':name', $data['name'], \PDO::PARAM_STR);
        $statement->bindValue(':user_id', $data['user_id'], \PDO::PARAM_INT);
        $statement->bindValue(':is_recommendating', $data['is_recommendating'], \PDO::PARAM_INT);
        $statement->execute();
    }

    public function selectOneByName(array $data)
    {
        $statement = $this->pdo->prepare('SELECT name FROM company WHERE name=:name AND user_id=:user_id');
        $statement->bindValue(':name', $data['name'], \PDO::PARAM_STR);
        $statement->bindValue(':user_id', $data['user_id'], \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }
}
