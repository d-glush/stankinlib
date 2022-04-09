<?php

namespace Packages\PublicationFileRepository;

use Packages\DBConnection\DBConnection;
use Packages\PublicationFileRepository\PublicationFileDTO\PublicationFileDTO;
use Packages\PublicationFileRepository\PublicationFileDTO\PublicationFileDTOCollection;
use Packages\QueryBuilder\QueryBuilder;

class PublicationFileRepository
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
        $this->connection->execute($query);
        return $this->connection->getLastInsertId();
    }

    public function getByPublicationId(int $id): PublicationFileDTOCollection
    {
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "publication_id=$id");
        $result = $this->connection->query($query);
        $collection = new PublicationFileDTOCollection();
        while ($row = $result->fetch()) {
            $collection->add(new PublicationFileDTO($row));
        }
        return $collection;
    }

    public function getByFileId(int $id): PublicationFileDTOCollection
    {
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "file_id=$id");
        $result = $this->connection->query($query);
        $collection = new PublicationFileDTOCollection();
        while ($row = $result->fetch()) {
            $collection->add(new PublicationFileDTO($row));
        }
        return $collection;
    }
}