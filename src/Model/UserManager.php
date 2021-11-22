<?php

namespace App\Model;

use App\Model\AbstractManager;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    public function create(array $userData)
    {
        $statement = $this->pdo->prepare('
        INSERT INTO user (profil_github, created_at, is_admin)
        VALUES (:profil_github, NOW(), false)
        ');
        $statement->bindValue(':profil_github', $userData['profil_github'], \PDO::PARAM_STR);
        $statement->execute();
        return $this->pdo->lastInsertId();
    }
    public function createWithGoogle(array $userData)
    {
        $statement = $this->pdo->prepare('
        INSERT INTO user (mail, firstname, profil_github, created_at, is_admin)
        VALUES (:mail, :firstname, :profil_github, NOW(), false)
        ');
        $statement->bindValue(':mail', $userData['email'], \PDO::PARAM_STR);
        $statement->bindValue(':firstname', $userData['name'], \PDO::PARAM_STR);
        $statement->bindValue(':profil_github', '', \PDO::PARAM_STR);
        $statement->execute();
        return $this->pdo->lastInsertId();
    }

    public function creatByForm(array $data)
    {
        $statement = $this->pdo->prepare('
        INSERT INTO user (firstname, lastname, profil_github, created_at, is_admin, password, mail)
        VALUES (:firstname, :lastname, :profil_github, NOW(), false, :password, :mail)
        ');
        $statement->bindValue(':firstname', $data['firstname'], \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $data['lastname'], \PDO::PARAM_STR);
        $statement->bindValue(':profil_github', $data['profilGithub'], \PDO::PARAM_STR);
        $statement->bindValue(':password', $data['password'], \PDO::PARAM_STR);
        $statement->bindValue(':mail', $data['mail'], \PDO::PARAM_STR);
        $statement->execute();

        return $this->pdo->lastInsertId();
    }
    public function selectOneByPseudo(string $profilGithub)
    {
        $statement = $this->pdo->prepare("SELECT * FROM user WHERE profil_github=:profil_github");
        $statement->bindValue(':profil_github', $profilGithub, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }
    public function selectOneByEmail(string $mail)
    {
        $statement = $this->pdo->prepare("SELECT * FROM user WHERE mail=:mail");
        $statement->bindValue(':mail', $mail, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }
    public function update(array $data)
    {
        $statement = $this->pdo->prepare('
        UPDATE user
        SET firstname=:firstname, lastname=:lastname, profil_github=:profil_github
        WHERE id=:id
        ');
        $statement->bindValue(':firstname', $data['firstname'], \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $data['lastname'], \PDO::PARAM_STR);
        $statement->bindValue(':profil_github', $data['profilGithub'], \PDO::PARAM_STR);
        $statement->bindValue(':id', $data['id'], \PDO::PARAM_INT);
        $statement->execute();
    }
}
