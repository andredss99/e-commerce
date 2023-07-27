<?php

namespace Ecommerce\Domain\Repository;

use DateTimeImmutable;
use Ecommerce\Domain\Model\User;
use Ecommerce\Infrastructure\Repository\UserRepositoryInterface;
use PDO;
use PDOStatement;

class UserRepository
{
    private string $tableName = 'tb_users';
    public function __construct(
        private readonly PDO $connection
    ) {
    }

    public function getAll(): array
    {
        $query = "SELECT * FROM $this->tableName";
        $statement = $this->connection->query($query);

        return $this->hydrateUsersList($statement);

        //return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOneBy(string $searchField, string $value): User|false
    {
        $query = "SELECT * FROM $this->tableName WHERE $searchField = :value";

        $statement = $this->connection->prepare("SELECT * FROM $this->tableName WHERE $searchField = :value");
        $statement->bindValue(':value', $value, PDO::PARAM_STR);
        $statement->execute();

        return $this->hydrateUser($statement);
    }

    public function save(User $user): bool
    {
        if ($user->getId() === null) {
            return $this->insert($user);
        }

        return $this->update($user);
    }

    public function insert(User $user): bool
    {
        $query = "INSERT INTO {$this->tableName} VALUES (:login, :password, :admin, :registerdate)";
    }

    public function update(User $user): bool
    {
        $query = "UPDATE $this->tableName SET
                      deslogin = :login,
                      despassword = :password,
                      inadmin = :admin
                  WHERE iduser = :id";

        $statement = $this->connection->prepare($query);

        $statement->bindValue(':login', $user->getLogin());
        $statement->bindValue(':password', $user->getPassword());
        $statement->bindValue(':admin', ($user->isAdmin()) ? 1 : 0);
        $statement->bindValue(':id', $user->getId());

        return $statement->execute();
    }

    public function delete(User $user): bool
    {
        $query = 'DELETE FROM tb_users WHERE iduser = :id';
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':id', $this->user->getId());

        return $statement->execute();
    }

    public function hydrateUser(PDOStatement $statement): User|false
    {
        $userData = $statement->fetch();

        if (!$userData) {
            return false;
        }

        return new User(
            $userData['iduser'],
            $userData['deslogin'],
            $userData['despassword'],
            $userData['inadmin'] == 1,
            new DateTimeImmutable($userData['dtregister'])
        );
    }

    public function hydrateUsersList(PDOStatement $statement): array
    {
        $usersDataList = $statement->fetchAll(PDO::FETCH_ASSOC);
        $usersList = [];

        foreach ($usersDataList as $userData) {
            $usersList[] = new User(
                $userData['iduser'],
                $userData['deslogin'],
                $userData['despassword'],
                $userData['inadmin'] == 1,
                new DateTimeImmutable($userData['dtregister'])
            );
        }

        return $usersList;
    }
}
