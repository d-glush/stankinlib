<?php

namespace Routes\ApiRoute\PublicationsRoute;

use JetBrains\PhpStorm\Pure;
use Packages\CourseRepository\CourseDTO\CourseDTO;
use Packages\CourseRepository\CourseRepository;
use Packages\CurrentUserService\CurrentUserService;
use Packages\DBConnection\DBConnection;
use Packages\FileRepository\FileDTO\FileDTO;
use Packages\FileRepository\FileRepository;
use Packages\FileService\FileAttachType\FileAttachType;
use Packages\FileService\FileService;
use Packages\HttpDataManager\HttpData;
use Packages\Limit\Limit;
use Packages\PublicationCourseRepository\PublicationCourseRepository;
use Packages\PublicationFileRepository\PublicationFileRepository;
use Packages\PublicationRepository\PublicationDTO\PublicationDTO;
use Packages\PublicationRepository\PublicationRepository;
use Packages\PublicationService\PublicationEntity\PublicationEntity;
use Packages\PublicationService\PublicationEntity\PublicationEntityCollection;
use Packages\PublicationService\PublicationService;
use Packages\PublicationSpecialityRepository\PublicationSpecialityRepository;
use Packages\QueryBuilder\QueryBuilder;
use Packages\Route\Route;
use Packages\Route\RouteResponse;
use Packages\SpecialityRepository\SpecialityDTO\SpecialityDTO;
use Packages\SpecialityRepository\SpecialityRepository;
use Packages\UserRepository\UserDTO\UserDTO;
use Packages\UserRepository\UserRepository;
use Packages\UserService\UserEntity\Role\Role;
use Packages\UserService\UserService;

class PublicationsRoute extends Route
{
    #[Pure] public function __construct(array $urls = [])
    {
        parent::__construct($urls);
    }

    protected function getSubRoutes(): array
    {
        return [];
    }

    protected function getMethods(): array
    {
        return [
            'get' => 'getPublications',
            'add' => 'createPublication',
            'get_by_id' => 'getById',
        ];
    }

    public function getById(HttpData $httpData): RouteResponse
    {
        $getData = $httpData->getGetData();
        if (!isset($getData['id'])) {
            return $this->getResponseWrongData();
        }
        $publicationId = $getData['id'];

        $connection = new DBConnection();
        $queryBuilder = new QueryBuilder();
        $pubSpecRep = new PublicationSpecialityRepository($connection, $queryBuilder);
        $pubFileRep = new PublicationFileRepository($connection, $queryBuilder);
        $pubCourseRep = new PublicationCourseRepository($connection, $queryBuilder);
        $publicationsRep = new PublicationRepository(
            $connection,
            $queryBuilder,
            $pubFileRep,
            $pubCourseRep,
            $pubSpecRep
        );
        $publicationService = new PublicationService($publicationsRep);
        $publicationCollection = $publicationService->getByIds([$publicationId]);
        if (!$publicationCollection->length()) {
            return new RouteResponse([], 404, 'no publication with this id');
        }
        $publications = $this->compositePubsData($publicationCollection);
        return new RouteResponse(['publication' => $publications[0]], 200);
    }

    public function getPublications(HttpData $httpData): RouteResponse
    {
        $getData = $httpData->getGetData();
        $offset = $getData['offset'] ?? 0;
        $limit = $getData['limit'] ?? 10;
        if (isset($getData['specialities'])) {
            $specialityIds = explode(',', $getData['specialities']);
        } else {
            $specialityIds = [];
        }
        if (isset($getData['courses'])) {
            $courseIds = explode(',', $getData['courses']);
        } else {
            $courseIds = [];
        }
        if (isset($getData['authors'])) {
            $authorIds = explode(',', $getData['authors']);
        } else {
            $authorIds = [];
        }

        $connection = new DBConnection();
        $queryBuilder = new QueryBuilder();
        $pubSpecRep = new PublicationSpecialityRepository($connection, $queryBuilder);
        $pubFileRep = new PublicationFileRepository($connection, $queryBuilder);
        $pubCourseRep = new PublicationCourseRepository($connection, $queryBuilder);
        $publicationsRep = new PublicationRepository(
            $connection,
            $queryBuilder,
            $pubFileRep,
            $pubCourseRep,
            $pubSpecRep
        );
        $publicationService = new PublicationService($publicationsRep);
        $limit = new Limit($limit, $offset);
        $publicationCollection = $publicationService->getByParams(
            $limit,
            $authorIds,
            $specialityIds,
            $courseIds
        );

        $totalCount = $publicationService->getCountByParams($authorIds, $specialityIds, $courseIds);
        $currentCount = $publicationCollection->length();
        $publications = $this->compositePubsData($publicationCollection);

        return new RouteResponse([
            'totalCount' => $totalCount,
            'currentCount' => $currentCount,
            'publications' => $publications
        ], 200);
    }

    public function createPublication(HttpData $httpData): RouteResponse
    {
        $dbConnection = new DBConnection();
        $queryBuilder = new QueryBuilder();
        $userRepository = new UserRepository($dbConnection, $queryBuilder);
        $userService = new UserService($userRepository);
        $currentUserService = new CurrentUserService($userService);

        $postData = $httpData->getPostData();
        $currentUser = $currentUserService->getUser();

        if ($currentUser->getRole() === Role::STUDENT) {
            return $this->getResponseAccessDenied();
        }
        $publicationData = json_decode($postData['data']);

        $title = $publicationData->title;
        $content = $publicationData->content;
        $specialtyIds = $publicationData->specialties ?? [];
        $courseIds = $publicationData->courses ?? [];
        $files = $httpData->getFiles();
        $authorId = $currentUser->getId();

        $fileRepository = new FileRepository($dbConnection, $queryBuilder);
        $fileService = new FileService($fileRepository);
        $pubFileRep = new PublicationFileRepository($dbConnection, $queryBuilder);
        $pubSpecRep = new PublicationSpecialityRepository($dbConnection, $queryBuilder);
        $pubCourseRep = new PublicationCourseRepository($dbConnection, $queryBuilder);
        $publicationRepository = new PublicationRepository(
            $dbConnection,
            $queryBuilder,
            $pubFileRep,
            $pubCourseRep,
            $pubSpecRep,
        );
        $publicationService = new PublicationService($publicationRepository);

        $filesIds = $fileService->addFiles($files, FileAttachType::PUBLICATION);
        $publicationDTO = new PublicationDTO([
            'author_id' => $authorId,
            'title' => $title,
            'content' => $content,
            'courseIds' => $courseIds,
            'specialityIds' => $specialtyIds,
            'fileIds' => $filesIds,
        ]);
        $publicationEntity = new PublicationEntity($publicationDTO);
        $publicationEntity = $publicationService->create($publicationEntity);

        return new RouteResponse(['publicationId' => $publicationEntity->getId()], 200);
    }

    private function compositePubsData(PublicationEntityCollection $publicationCollection): array
    {
        $allCourseIds = [];
        $allSpecialityIds = [];
        $allFileIds = [];
        $allAuthorIds = [];
        /** @var PublicationEntity $publicationEntity */
        foreach ($publicationCollection as $publicationEntity) {
            $allCourseIds = array_merge($allCourseIds, $publicationEntity->getCourseIds());
            $allSpecialityIds = array_merge($allSpecialityIds, $publicationEntity->getSpecialityIds());
            $allFileIds = array_merge($allFileIds, $publicationEntity->getFileIds());
            $allAuthorIds = array_merge($allAuthorIds, [$publicationEntity->getAuthorId()]);
        }
        $allCourseIds = array_unique($allCourseIds);
        $allSpecialityIds = array_unique($allSpecialityIds);
        $allFileIds = array_unique($allFileIds);
        $allAuthorIds = array_unique($allAuthorIds);

        $connection = new DBConnection();
        $queryBuilder = new QueryBuilder();
        $courseRepository = new CourseRepository($connection, $queryBuilder);
        $specRepository = new SpecialityRepository($connection, $queryBuilder);
        $fileRepository = new FileRepository($connection, $queryBuilder);
        $userRepository = new UserRepository($connection, $queryBuilder);

        $allCourse = $courseRepository->getByIds($allCourseIds);
        $allSpeciality = $specRepository->getByIds($allSpecialityIds);
        $allFile = $fileRepository->getByIds($allFileIds);
        $allAuthors = $userRepository->getByIds($allAuthorIds);
        $allCourseById = [];
        $allSpecialityById = [];
        $allFileById = [];
        $allAuthorsById = [];
        /** @var CourseDTO $courseDTO */
        foreach ($allCourse as $courseDTO) {
            $allCourseById[$courseDTO->getId()] = $courseDTO;
        }
        /** @var SpecialityDTO $specDTO */
        foreach ($allSpeciality as $specDTO) {
            $allSpecialityById[$specDTO->getId()] = $specDTO;
        }
        /** @var FileDTO $fileDTO */
        foreach ($allFile as $fileDTO) {
            $allFileById[$fileDTO->getId()] = $fileDTO;
        }
        /** @var UserDTO $userDTO */
        foreach ($allAuthors as $userDTO) {
            $allAuthorsById[$userDTO->getId()] = $userDTO;
        }

        $publications = [];
        /** @var PublicationEntity $publicationEntity */
        foreach ($publicationCollection as $publicationEntity) {
            $currentAuthor = $allAuthorsById[$publicationEntity->getAuthorId()];
            $currentSpecialisations = [];
            $currentCourses = [];
            $currentFiles = [];
            foreach ($publicationEntity->getSpecialityIds() as $specialityId) {
                $currentSpecialisations[] = $allSpecialityById[$specialityId];
            }
            foreach ($publicationEntity->getCourseIds() as $courseId) {
                $currentCourses[] = $allCourseById[$courseId];
            }
            foreach ($publicationEntity->getFileIds() as $fileId) {
                $currentFiles[] = $allFileById[$fileId];
            }
            $publications[] = [
                'id' => $publicationEntity->getId(),
                'title' => $publicationEntity->getTitle(),
                'content' => $publicationEntity->getContent(),
                'author' => [
                    'id' => $currentAuthor->getId(),
                    'firstName' => $currentAuthor->getFirstName(),
                    'middleName' => $currentAuthor->getMiddleName(),
                    'lastName' => $currentAuthor->getLastName(),
                ],
                'courses' => $currentCourses,
                'specialisations' => $currentSpecialisations,
                'files' => $currentFiles,
                'createDate' => $publicationEntity->getCreateDate(),
                'editDate' => $publicationEntity->getEditDate(),
            ];
        }

        return $publications;
    }
}