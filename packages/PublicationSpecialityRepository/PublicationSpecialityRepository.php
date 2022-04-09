<?php

namespace Packages\PublicationSpecialityRepository;

use Packages\DBConnection\DBConnection;
use Packages\PublicationSpecialityRepository\PublicationSpecialityDTO\PublicationSpecialityDTO;
use Packages\PublicationSpecialityRepository\PublicationSpecialityDTO\PublicationSpecialityDTOCollection;
use Packages\QueryBuilder\QueryBuilder;

class PublicationSpecialityRepository
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
        $this->connection->execute($query);
        return $this->connection->getLastInsertId();
    }

    public function getByPublicationId(int $id): PublicationSpecialityDTOCollection
    {
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "publication_id=$id");
        $result = $this->connection->query($query);
        $collection = new PublicationSpecialityDTOCollection();
        while ($row = $result->fetch()) {
            $collection->add(new PublicationSpecialityDTO($row));
        }
        return $collection;
    }

    public function getBySpecialityId(int $id): PublicationSpecialityDTOCollection
    {
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "speciality_id=$id");
        $result = $this->connection->query($query);
        $collection = new PublicationSpecialityDTOCollection();
        while ($row = $result->fetch()) {
            $collection->add(new PublicationSpecialityDTO($row));
        }
        return $collection;
    }
}