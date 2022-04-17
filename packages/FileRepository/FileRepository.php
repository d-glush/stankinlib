<?php

namespace Packages\FileRepository;

use Packages\DBConnection\DBConnection;
use Packages\FileRepository\FileDTO\FileDTO;
use Packages\QueryBuilder\QueryBuilder;
use Packages\FileRepository\FileDTO\FileDTOCollection;
use Packages\Repository\Repository;

class FileRepository extends Repository
{
    private DBConnection $connection;
    private QueryBuilder $queryBuilder;
    private string $tableName = 'file';

    public function __construct(DBConnection $connection, QueryBuilder $queryBuilder)
    {
        $this->connection = $connection;
        $this->queryBuilder = $queryBuilder;
    }

    public function getById(int $id): FileDTO
    {
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "id=$id");
        $result = $this->connection->query($query);
        return new FileDTO($result->fetch());
    }

    public function getByIds(array $ids): FileDTOCollection
    {
        $collection = new FileDTOCollection();
        if (!count($ids)) {
            return $collection;
        }
        $in = $this->makeIn($ids);
        $query = $this->queryBuilder->buildSelect($this->tableName, '*', "id $in");
        $result = $this->connection->query($query);
        while ($fileData = $result->fetch()) {
            $collection->add(new FileDTO($fileData));
        }
        return $collection;
    }

    public function add(FileDTO $userDTO): int|bool
    {
        $data = $userDTO->getArrayData();
        $query = $this->queryBuilder->buildInsert($this->tableName, $data);
        $this->connection->execute($query);
        return $this->connection->getLastInsertId();
    }

    public function addCollection(FileDTOCollection $collection): bool
    {
        $data = [];
        foreach ($collection as $fileDTO) {
            $data[] = $fileDTO->getArrayData();
        }
        $query = $this->queryBuilder->buildInsert($this->tableName, $data);
        $this->connection->execute($query);
        return true;
    }
}