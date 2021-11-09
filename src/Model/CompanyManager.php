<?php

namespace App\Model;

class CompanyManager extends AbstractManager
{
    public const TABLE = 'company';
    public function selectCompaniesByUser(int $id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM company WHERE user_id=:id AND is_recommendating=false");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function updateCompanyAdvancement(array $data)
    {
        $statement = $this->pdo->prepare(
            "UPDATE company SET advancement_id=:advancement WHERE user_id=:user_id AND id=:company_id"
        );
        $statement->bindValue(':advancement', $data['advancement'], \PDO::PARAM_INT);
        $statement->bindValue(':user_id', $data['user_id'], \PDO::PARAM_INT);
        $statement->bindValue(':company_id', $data['company-id'], \PDO::PARAM_INT);


        return $statement->execute();
    }

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

    public function update(array $posts)
    {
        $statement = $this->pdo->prepare('
                UPDATE company 
                SET description=:description, address=:address, 
                phone_number=:phone_number, mail=:mail');
        $statement->bindValue(':description', $posts['description'], \PDO::PARAM_STR);
        $statement->bindValue(':address', $posts['address'], \PDO::PARAM_STR);
        $statement->bindValue(':phone_number', $posts['phone_number'], \PDO::PARAM_STR);
        $statement->bindValue(':mail', $posts['mail'], \PDO::PARAM_STR);
        $statement->execute();
    }

    public function countUserForCompanyiesIsRecommendating(string $name): int
    {
       $statement = $this->pdo->prepare('
                SELECT COUNT(c.id) AS number
                FROM company AS c
                WHERE  c.name =:name AND c.is_recommendating = true
                ');
       $statement->bindValue('name', $name, \PDO::PARAM_STR);
       $statement->execute();
       return $statement->fetch()['number'];
    }
}
