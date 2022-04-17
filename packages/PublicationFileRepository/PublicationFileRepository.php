<?php

namespace Packages\PublicationFileRepository;

use Packages\DBConnection\DBConnection;
use Packages\PublicationFileRepository\PublicationFileDTO\PublicationFileDTO;
use Packages\PublicationFileRepository\PublicationFileDTO\PublicationFileDTOCollection;
use Packages\QueryBuilder\QueryBuilder;
use Packages\Repository\Repository;

class PublicationFileRepository extends Repository
{
    private DBConnection $connection;
    private QueryBuilder $queryBuilder;
    public string $tableName = 'publication_file';


    public function __construct(DBConnection $connection, QueryBuilder $queryBuilder)
    {
        $this->connection = $connection;
        $this->queryBuilder = $queryBuilder;
    }

    public function add(PublicationFileDTO $fileDTO): int|bool
    {
        $data = $fileDTO->getArrayData();
        $query = $this->queryBuilder->buildInsert($this->tableName, $data);
        var_dump($query);
        $this->connection->execute($query);
        return $this->connection->getLastInsertId();
    }

    public function getByPublicationIds(array $ids): PublicationFileDTOCollection
    {
        $collection = new PublicationFileDTOCollection();
        if (!count($ids)) {
            return $collection;
        }
        $in = $this->makeIn($ids);
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "publication_id $in");
        $result = $this->connection->query($query);
        while ($row = $result->fetch()) {
            $collection->add(new PublicationFileDTO($row));
        }
        return $collection;
    }

    public function getByFileIds(array $ids): PublicationFileDTOCollection
    {
        $in = $this->makeIn($ids);
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "file_id $in");
        $result = $this->connection->query($query);
        $collection = new PublicationFileDTOCollection();
        while ($row = $result->fetch()) {
            $collection->add(new PublicationFileDTO($row));
        }
        return $collection;
    }
}