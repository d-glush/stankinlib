<?php

namespace Packages\SpecialityRepository;

use Packages\DBConnection\DBConnection;
use Packages\QueryBuilder\QueryBuilder;

class SpecialityRepository
{
    private DBConnection $connection;
    private QueryBuilder $queryBuilder;
    public string $tableName = 'course';


    public function __construct(DBConnection $connection, QueryBuilder $queryBuilder)
    {
        $this->connection = $connection;
        $this->queryBuilder = $queryBuilder;
    }

    public function add(CourseDTO $courseDTO): int|bool
    {
        $data = $courseDTO->getArrayData();
        $query = $this->queryBuilder->buildInsert($this->tableName, $data);
        $this->connection->execute($query);
        return $this->connection->getLastInsertId();
    }

    public function getById(int $id): CourseDTO
    {
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "id=$id");
        $result = $this->connection->query($query);
        return new CourseDTO($result->fetch());
    }
}