<?php

namespace Packages\SpecialityRepository;

use Packages\SpecialityRepository\SpecialityDTO\SpecialityDTO;
use Packages\DBConnection\DBConnection;
use Packages\QueryBuilder\QueryBuilder;
use Packages\Repository\Repository;
use Packages\SpecialityRepository\SpecialityDTO\SpecialityDTOCollection;

class SpecialityRepository extends Repository
{
    private DBConnection $connection;
    private QueryBuilder $queryBuilder;
    public string $tableName = 'speciality';


    public function __construct(DBConnection $connection, QueryBuilder $queryBuilder)
    {
        $this->connection = $connection;
        $this->queryBuilder = $queryBuilder;
    }

    public function add(SpecialityDTO $courseDTO): int|bool
    {
        $data = $courseDTO->getArrayData();
        $query = $this->queryBuilder->buildInsert($this->tableName, $data);
        $this->connection->execute($query);
        return $this->connection->getLastInsertId();
    }

    public function getById(int $id): SpecialityDTO
    {
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "id=$id");
        $result = $this->connection->query($query);
        return new SpecialityDTO($result->fetch());
    }

    public function getByIds(array $ids): SpecialityDTOCollection
    {
        $collection = new SpecialityDTOCollection();
        if (!count($ids)) {
            return $collection;
        }
        $in = $this->makeIn($ids);
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "id $in");
        $result = $this->connection->query($query);
        while ($specData = $result->fetch()) {
            $collection->add(new SpecialityDTO($specData));
        }
        return $collection;
    }
}