<?php

namespace Packages\PublicationRepository;

use Packages\DBConnection\DBConnection;
use Packages\Limit\Limit;
use Packages\PublicationCourseRepository\PublicationCourseDTO\PublicationCourseDTO;
use Packages\PublicationCourseRepository\PublicationCourseRepository;
use Packages\PublicationFileRepository\PublicationFileDTO\PublicationFileDTO;
use Packages\PublicationFileRepository\PublicationFileRepository;
use Packages\PublicationRepository\PublicationDTO\PublicationDTO;
use Packages\PublicationRepository\PublicationDTO\PublicationDTOCollection;
use Packages\PublicationSpecialityRepository\PublicationSpecialityDTO\PublicationSpecialityDTO;
use Packages\PublicationSpecialityRepository\PublicationSpecialityRepository;
use Packages\QueryBuilder\QueryBuilder;
use Packages\Repository\Repository;

class PublicationRepository extends Repository
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
        PublicationSpecialityRepository $pubSpecRepository,
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

        foreach ($publicationDTO->getSpecialityIds() as $specialityId) {
            $this->pubSpecRepository->add(new PublicationSpecialityDTO([
                'publication_id' => $publicationId,
                'speciality_id' => $specialityId,
            ]));
        }
        foreach ($publicationDTO->getFileIds() as $fileId) {
            $this->pubFileRepository->add(new PublicationFileDTO([
                'publication_id' => $publicationId,
                'file_id' => $fileId,
            ]));
        }
        foreach ($publicationDTO->getCourseIds() as $courseId) {
            $this->pubCourseRepository->add(new PublicationCourseDTO([
                'publication_id' => $publicationId,
                'course_id' => $courseId,
            ]));
        }

        return $publicationId;
    }

    public function getByIds(array $ids): PublicationDTOCollection
    {
        $pubDTOCollection = new PublicationDTOCollection();
        if (!count($ids)) {
            return $pubDTOCollection;
        }
        $pubCourseDTOCollection = $this->pubCourseRepository->getByPublicationIds($ids);
        /** @var PublicationCourseDTO $pubCourseDTO */
        $pubCourseIds = [];
        foreach ($pubCourseDTOCollection as $pubCourseDTO) {
            if (isset($pubCourseIds[$pubCourseDTO->getPublicationId()])) {
                $pubCourseIds[$pubCourseDTO->getPublicationId()][] = $pubCourseDTO->getCourseId();
            } else {
                $pubCourseIds[$pubCourseDTO->getPublicationId()] = [$pubCourseDTO->getCourseId()];
            }
        }

        $pubSpecDTOCollection = $this->pubSpecRepository->getByPublicationIds($ids);
        /** @var PublicationSpecialityDTO $pubSpecDTO */
        $pubSpecIds = [];
        foreach ($pubSpecDTOCollection as $pubSpecDTO) {
            if (isset($pubSpecIds[$pubSpecDTO->getPublicationId()])) {
                $pubSpecIds[$pubSpecDTO->getPublicationId()][] = $pubSpecDTO->getSpecialityId();
            } else {
                $pubSpecIds[$pubSpecDTO->getPublicationId()] = [$pubSpecDTO->getSpecialityId()];
            }
        }

        $pubFileDTOCollection = $this->pubFileRepository->getByPublicationIds($ids);
        /** @var PublicationFileDTO $pubFileDto */
        $pubFileIds = [];
        foreach ($pubFileDTOCollection as $pubFileDto) {
            if (isset($pubFileIds[$pubFileDto->getPublicationId()])) {
                $pubFileIds[$pubFileDto->getPublicationId()][] = $pubFileDto->getFileId();
            } else {
                $pubFileIds[$pubFileDto->getPublicationId()] = [$pubFileDto->getFileId()];
            }
        }

        $in = $this->makeIn($ids);
        $publicationQuery = $this->queryBuilder->buildSelect($this->tableName, '*', "id $in");
        $pubResponse = $this->dbConnection->query($publicationQuery);
        while ($pubRow = $pubResponse->fetch()) {
            $rowData = $pubRow;
            $rowData['specialityIds'] = $pubSpecIds[$pubRow['id']];
            $rowData['courseIds'] = $pubCourseIds[$pubRow['id']];
            $rowData['fileIds'] = $pubFileIds[$pubRow['id']];
            $pubDTOCollection->add(new PublicationDTO($rowData));
        }

        return $pubDTOCollection;
    }

    public function getByParams(
        Limit $limit,
        array $authorIds = [],
        array $specialityIds = [],
        array $courseIds = []
    ): PublicationDTOCollection {

        $isFilterBySpec = !!count($specialityIds);
        $isFilterByCourses = !!count($courseIds);
        $isFilterByAuthors = !!count($authorIds);

        $courseIn = $this->makeIn($courseIds);
        $specIn = $this->makeIn($specialityIds);
        $authorIn = $this->makeIn($authorIds);

        $query = 'SELECT DISTINCT p.id, p.create_date FROM publication p'
        . ($isFilterByCourses
                ? " JOIN publication_course pc on p.id = pc.publication_id AND pc.course_id $courseIn"
                : '')
        . ($isFilterBySpec
                ? " JOIN publication_speciality ps on p.id = ps.publication_id AND ps.speciality_id $specIn"
                : '')
        . ($isFilterByAuthors
                ? " WHERE author_id $authorIn"
                : '')
        . ' ORDER BY p.create_date DESC '
        . ' LIMIT ' . $limit->getOffset() . ', ' . $limit->getLimit();
        $response = $this->dbConnection->query($query);
        $filteredPublicationIds = [];
        while ($row = $response->fetch()) {
            $filteredPublicationIds[] = $row['id'];
        }
        return $this->getByIds($filteredPublicationIds);
    }

    public function getCountByParams(
        array $authorIds = [],
        array $specialityIds = [],
        array $courseIds = []
    ): int {
        $isFilterBySpec = !!count($specialityIds);
        $isFilterByCourses = !!count($courseIds);
        $isFilterByAuthors = !!count($authorIds);

        $courseIn = $this->makeIn($courseIds);
        $specIn = $this->makeIn($specialityIds);
        $authorIn = $this->makeIn($authorIds);

        $query = 'SELECT count(*) as `count` FROM (SELECT DISTINCT p.id FROM publication p'
            . ($isFilterByCourses
                ? " JOIN publication_course pc on p.id = pc.publication_id AND pc.course_id $courseIn"
                : '')
            . ($isFilterBySpec
                ? " JOIN publication_speciality ps on p.id = ps.publication_id AND ps.speciality_id $specIn"
                : '')
            . ($isFilterByAuthors
                ? " WHERE author_id $authorIn"
                : '')
            . ') as publication_ids;';
        $response = $this->dbConnection->query($query);
        return $response->fetch()['count'];
    }
}