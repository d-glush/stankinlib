<?php

namespace Packages\PublicationCourseRepository;

use Packages\DBConnection\DBConnection;
use Packages\QueryBuilder\QueryBuilder;
use Packages\PublicationCourseRepository\PublicationCourseDTO\PublicationCourseDTOCollection;
use Packages\PublicationCourseRepository\PublicationCourseDTO\PublicationCourseDTO;
use Packages\Repository\Repository;

class PublicationCourseRepository extends Repository
{
    private DBConnection $connection;
    private QueryBuilder $queryBuilder;
    public string $tableName = 'publication_course';


    public function __construct(DBConnection $connection, QueryBuilder $queryBuilder)
    {
        $this->connection = $connection;
        $this->queryBuilder = $queryBuilder;
    }

    public function add(PublicationCourseDTO $courseDTO): int|bool
    {
        $data = $courseDTO->getArrayData();
        $query = $this->queryBuilder->buildInsert($this->tableName, $data);
        var_dump($query);
        $this->connection->execute($query);
        return $this->connection->getLastInsertId();
    }

    public function getByPublicationIds(array $ids): PublicationCourseDTOCollection
    {
        $collection = new PublicationCourseDTOCollection();
        if (!count($ids)) {
            return $collection;
        }
        $in = $this->makeIn($ids);
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "publication_id $in");
        $result = $this->connection->query($query);
        while ($row = $result->fetch()) {
            $collection->add(new PublicationCourseDTO($row));
        }
        return $collection;
    }

    public function getByCourseIds(array $ids): PublicationCourseDTOCollection
    {
        $in = $this->makeIn($ids);
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "course_id $in");
        $result = $this->connection->query($query);
        $collection = new PublicationCourseDTOCollection();
        while ($row = $result->fetch()) {
            $collection->add(new PublicationCourseDTO($row));
        }
        return $collection;
    }
}