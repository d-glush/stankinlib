<?php

namespace Packages\CourseRepository;

use Packages\CourseRepository\CourseDTO\CourseDTO;
use Packages\CourseRepository\CourseDTO\CourseDTOCollection;
use Packages\DBConnection\DBConnection;
use Packages\QueryBuilder\QueryBuilder;
use Packages\Repository\Repository;

class CourseRepository extends Repository
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

    public function getByIds(array $ids): CourseDTOCollection
    {
        $collection = new CourseDTOCollection();
        if (!count($ids)) {
            return $collection;
        }
        $in = $this->makeIn($ids);
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "id $in");
        $result = $this->connection->query($query);
        while ($courseData = $result->fetch()) {
            $collection->add(new CourseDTO($courseData));
        }
        return $collection;
    }
}