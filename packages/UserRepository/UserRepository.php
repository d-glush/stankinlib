<?php

namespace Packages\UserRepository;

use Packages\DBConnection\DBConnection;
use Packages\QueryBuilder\QueryBuilder;
use Packages\UserRepository\UserDTO\UserDTO;
use Packages\UserRepository\UserDTO\UserDTOCollection;

class UserRepository {

    private DBConnection $connection;
    private QueryBuilder $queryBuilder;
    private string $tableName = 'user';

    public function __construct(DBConnection $connection, QueryBuilder $queryBuilder)
    {
        $this->connection = $connection;
        $this->queryBuilder = $queryBuilder;
    }

    public function getById(int $id): UserDTO
    {
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "id=$id");
        $result = $this->connection->query($query);
        return new UserDTO($result->fetch());
    }

    public function getByLogin(string $login): UserDTO|bool
    {
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "login='$login'");
        $result = $this->connection->query($query)->fetch();
        return $result ? new UserDTO($result) : $result;
    }

    public function getByEmail(string $email): UserDTO|bool
    {
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "email='$email'");
        $result = $this->connection->query($query)->fetch();
        return $result ? new UserDTO($result) : $result;
    }

    public function getByRoleId(int $roleId): UserDTOCollection|bool
    {
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "role_id='$roleId'");
        $result = $this->connection->query($query);
        if (!$result) {
            return false;
        }
        $collection = new UserDTOCollection();
        while ($row = $result->fetch()) {
            $collection->add(new UserDTO($row));
        }
        return $collection;
    }

    public function add(UserDTO $userDTO): int|bool
    {
        $data = $userDTO->getArrayData();
        $query = $this->queryBuilder->buildInsert($this->tableName, $data);
        $this->connection->execute($query);
        return $this->connection->getLastInsertId();
    }

    public function updateByLogin(string $login, array $setArray): bool|int
    {
        $query = $this->queryBuilder->buildUpdate($this->tableName, $setArray, ['login' => $login]);
        return $this->connection->execute($query);
    }
}