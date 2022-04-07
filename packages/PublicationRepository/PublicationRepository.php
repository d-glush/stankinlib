<?php

namespace Packages\PublicationRepository;

use Packages\DBConnection\DBConnection;
use Packages\PublicationRepository\PublicationDTO\PublicationDTO;
use Packages\QueryBuilder\QueryBuilder;

class PublicationRepository
{
    private DBConnection $dbConnection;
    private QueryBuilder $queryBuilder;
    public string $publicationTableName = 'publication';
    public string $publicationFileTableName = 'publication_file';
    public string $publicationGroupTableName = 'publication_group';
    public string $publicationSpecialityTableName = 'publication_speciality';


    public function __construct(DBConnection $dbConnection, QueryBuilder $queryBuilder)
    {
        $this->dbConnection = $dbConnection;
        $this->queryBuilder = $queryBuilder;
    }

    public function add(PublicationDTO $publicationDTO): int|bool
    {
        $publicationData = [
            'author_id' => $publicationDTO->getAuthorId(),
            'title' => $publicationDTO->getTitle(),
            'content' => $publicationDTO->getContent(),
        ];
        $publicationQuery = $this->queryBuilder->buildInsert($this->publicationTableName, $publicationData);
        $this->dbConnection->execute($publicationQuery);
        $publicationId = $this->dbConnection->getLastInsertId();

        $publicationFileData = [];
        foreach ($publicationDTO->getFileIds() as $fileId) {
            $publicationFileData[] = ['publication_id' => $publicationId, 'file_id' => $fileId];
        }
        $publicationFileQuery = $this->queryBuilder->buildInsert($this->publicationFileTableName, $publicationFileData) . ';';

        $publicationGroupData = [];
        foreach ($publicationDTO->getGroupIds() as $groupId) {
            $publicationGroupData[] = ['publication_id' => $publicationId, 'group_id' => $groupId];
        }
        $publicationGroupQuery = $this->queryBuilder->buildInsert($this->publicationGroupTableName, $publicationGroupData) . ';';

        $publicationSpecialityData = [];
        foreach ($publicationDTO->getSpecialityIds() as $specialityId) {
            $publicationSpecialityData[] = ['publication_id' => $publicationId, 'speciality_id' => $specialityId];
        }
        $publicationSpecialityQuery = $this->queryBuilder->buildInsert($this->publicationSpecialityTableName, $publicationSpecialityData) . ';';
        $this->dbConnection->execute($publicationFileQuery . $publicationGroupQuery . $publicationSpecialityQuery);

        return $publicationId;
    }

    public function getById(): PublicationDTO
    {

    }

    public function getBySpecialityAndGroup(array $specialityIds, array $groupIds): PublicationDTOCollection
    {

    }
}