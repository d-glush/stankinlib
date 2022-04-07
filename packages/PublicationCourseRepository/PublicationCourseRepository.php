<?php

namespace Packages\PublicationCourseRepository;

use Packages\DBConnection\DBConnection;
use Packages\QueryBuilder\QueryBuilder;
use Packages\PublicationCourseRepository\PublicationCourseDTO\PublicationCourseDTOCollection;
use Packages\PublicationCourseRepository\PublicationCourseDTO\PublicationCourseDTO;

class PublicationCourseRepository
{
    private DBConnection $connection;
    private QueryBuilder $queryBuilder;
    public string $tableName = 'course';


    public function __construct(DBConnection $connection, QueryBuilder $queryBuilder)
    {
        $this->connection = $connection;
        $this->queryBuilder = $queryBuilder;
    }

    public function add(PublicationCourseDTO $courseDTO): int|bool
    {
        $data = $courseDTO->getArrayData();
        $query = $this->queryBuilder->buildInsert($this->tableName, $data);
        $this->connection->execute($query);
        return $this->connection->getLastInsertId();
    }

    public function getByPublicationId(int $id): PublicationCourseDTOCollection
    {
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "id=$id");
        $result = $this->connection->query($query);
        $collection = new PublicationCourseDTOCollection();
        while ($row = $result->fetch()) {
            $collection->add(new PublicationCourseDTO($row));
        }
        return $collection;
    }

    public function getByCourseId(int $id): PublicationCourseDTOCollection
    {
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "id=$id");
        $result = $this->connection->query($query);
        $collection = new PublicationCourseDTOCollection();
        while ($row = $result->fetch()) {
            $collection->add(new PublicationCourseDTO($row));
        }
        return $collection;
    }
}