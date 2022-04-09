<?php

namespace Packages\PublicationRepository;

use Packages\DBConnection\DBConnection;
use Packages\PublicationCourseRepository\PublicationCourseRepository;
use Packages\PublicationFileRepository\PublicationFileRepository;
use Packages\PublicationRepository\PublicationDTO\PublicationDTO;
use Packages\PublicationSpecialityRepository\PublicationSpecialityRepository;
use Packages\QueryBuilder\QueryBuilder;

class PublicationRepository
{
    private DBConnection $dbConnection;
    private QueryBuilder $queryBuilder;
    public string $tableName = 'publication';
    private PublicationFileRepository $pubFileRepository;
    private PublicationCourseRepository $pubCourseRepository;
    private PublicationSpecialityRepository $pubSpecRepository;


    public function __construct(
        DBConnection $dbConnection,
        QueryBuilder $queryBuilder,
        PublicationFileRepository $pubFileRepository,
        PublicationCourseRepository $pubCourseRepository,
        PublicationSpecialityRepository $pubSpecRepository
    ) {
        $this->dbConnection = $dbConnection;
        $this->queryBuilder = $queryBuilder;
        $this->pubFileRepository = $pubFileRepository;
        $this->pubCourseRepository = $pubCourseRepository;
        $this->pubSpecRepository = $pubSpecRepository;
    }

    public function add(PublicationDTO $publicationDTO): int|bool
    {
        $publicationData = [
            'author_id' => $publicationDTO->getAuthorId(),
            'title' => $publicationDTO->getTitle(),
            'content' => $publicationDTO->getContent(),
        ];
        $publicationQuery = $this->queryBuilder->buildInsert($this->tableName, $publicationData);
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
        $publicationGroupQuery = $this->queryBuilder->buildInsert($this->publicationCourseTableName, $publicationGroupData) . ';';

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