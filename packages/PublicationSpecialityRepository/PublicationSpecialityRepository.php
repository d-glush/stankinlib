<?php

namespace Packages\PublicationSpecialityRepository;

use Packages\DBConnection\DBConnection;
use Packages\PublicationSpecialityRepository\PublicationSpecialityDTO\PublicationSpecialityDTO;
use Packages\PublicationSpecialityRepository\PublicationSpecialityDTO\PublicationSpecialityDTOCollection;
use Packages\QueryBuilder\QueryBuilder;
use Packages\Repository\Repository;

class PublicationSpecialityRepository extends Repository
{
    private DBConnection $connection;
    private QueryBuilder $queryBuilder;
    public string $tableName = 'publication_speciality';


    public function __construct(DBConnection $connection, QueryBuilder $queryBuilder)
    {
        $this->connection = $connection;
        $this->queryBuilder = $queryBuilder;
    }

    public function add(PublicationSpecialityDTO $specialityDTO): int|bool
    {
        $data = $specialityDTO->getArrayData();
        $query = $this->queryBuilder->buildInsert($this->tableName, $data);
        var_dump($query);
        $this->connection->execute($query);
        return $this->connection->getLastInsertId();
    }

    public function getByPublicationIds(array $ids): PublicationSpecialityDTOCollection
    {
        $collection = new PublicationSpecialityDTOCollection();
        if (!count($ids)) {
            return $collection;
        }
        $in = $this->makeIn($ids);
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "publication_id $in");
        $result = $this->connection->query($query);
        while ($row = $result->fetch()) {
            $collection->add(new PublicationSpecialityDTO($row));
        }
        return $collection;
    }

    public function getBySpecialityIds(array $ids): PublicationSpecialityDTOCollection
    {
        $in = $this->makeIn($ids);
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "speciality_id $in");
        $result = $this->connection->query($query);
        $collection = new PublicationSpecialityDTOCollection();
        while ($row = $result->fetch()) {
            $collection->add(new PublicationSpecialityDTO($row));
        }
        return $collection;
    }
}