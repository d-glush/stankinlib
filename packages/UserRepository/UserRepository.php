<?php

namespace Packages\UserRepository;

use Packages\DBConnection\DBConnection;
use Packages\QueryBuilder\QueryBuilder;
use Packages\UserRepository\UserDTO\UserDTO;

class UserRepository {

    private DBConnection $connection;
    private QueryBuilder $queryBuilder;
    private string $tableName = 'user';

    public function __construct(DBConnection $connection, QueryBuilder $queryBuilder)
    {
        $this->connection = $connection;
        $this->queryBuilder = $queryBuilder;
    }

    public function getUserById(int $id): UserDTO
    {
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "id=$id");
        $result = $this->connection->query($query);
        return new UserDTO($result->fetch());
    }

    public function addUser(UserDTO $userDTO): int|bool
    {
        $data = $userDTO->getArrayData();
        $query = $this->queryBuilder->buildInsert($this->tableName, $data);
        return $this->connection->execute($query);
    }

}