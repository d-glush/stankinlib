<?php

namespace Routes\ApiRoute\PublicationsRoute;

use DateTime;
use Packages\CurrentUserService\CurrentUserService;
use Packages\DBConnection\DBConnection;
use Packages\FileRepository\FileRepository;
use Packages\FileService\FileAttachType\FileAttachType;
use Packages\FileService\FileService;
use Packages\HttpDataManager\HttpData;
use Packages\PublicationRepository\PublicationDTO\PublicationDTO;
use Packages\PublicationRepository\PublicationRepository;
use Packages\PublicationService\PublicationEntity\PublicationEntity;
use Packages\PublicationService\PublicationService;
use Packages\QueryBuilder\QueryBuilder;
use Packages\Route\Route;
use Packages\Route\RouteResponse;
use Packages\UserRepository\UserRepository;
use Packages\UserService\UserEntity\Role\Role;
use Packages\UserService\UserEntity\UserEntity;
use Packages\UserService\UserService;

class PublicationsRoute extends Route
{
    public int $mockPublicationNum = 1000;
    public array $mockPublication = [
        'id' => 0,
        'title' => 'Исследование говна с палками',
        'content' => 'Этот пост я замутил для вас ребята!!',
        'author' => [
            'id' => '222',
            'firstName' => 'Алексей',
            'middleName' => 'Иванович',
            'lastName' => 'Сосенушкин'
        ],
        'specializations' => [
            [
                'id' => 1,
                'name' => 'Информационные системы и технологии 1',
                'number' => '09.03.02'
            ],
            [
                'id' => 2,
                'name' => 'Информационные системы и технологии 2',
                'number' => '09.03.02'
            ],
            [
                'id' => 3,
                'name' => 'Информационные системы и технологии 3',
                'number' => '09.03.02'
            ],
        ],
        'courses' => [
            [
                'id' => 1,
                'name' => 'Бакалавриат 1 курс',
                'education_year' => 1
            ],
            [
                'id' => 3,
                'name' => 'Бакалавриат 3 курс',
                'education_year' => 3
            ],
        ],
        'files' => [
            [
                'name' => 'Методичка по лр 1.pdf',
                'location' => '/storage/publication_files/testPdf.pdf',
                'type' => 'file/pdf'
            ],
            [
                'name' => 'Схема.img',
                'location' => '/storage/publication_files/testImg.img',
                'type' => 'image/img'
            ],
        ],
        'createDate' => '13.08.2000',
        'editDate' => '24.03.2022',
    ];
    public array $mockPublications = [
    ];

    public function __construct(array $urls = [])
    {
        parent::__construct($urls);
        for ($i = 0; $i < $this->mockPublicationNum; $i++) {
            $this->mockPublications[] = $this->mockPublication;
        }
        foreach ($this->mockPublications as $index => &$publication) {
            $publication['id'] = $index;
            $publication['title'] = "$index " . $publication['title'] . " $index";
            $randContentDublicates = rand(1, 6);
            for ($i = 0; $i < $randContentDublicates; $i++) {
                $publication['content'] .= $publication['content'];
            }
        }
    }

    protected function getSubRoutes(): array
    {
        return [

        ];
    }

    protected function getMethods(): array
    {
        return [
            'get' => 'getPublications',
            'add' => 'createPublication',
            'getById' => 'getById',
        ];
    }

    public function getById(HttpData $httpData): RouteResponse
    {
        $getData = $httpData->getGetData();
        $id = $getData['id'];


    }

    public function getPublications(HttpData $httpData): RouteResponse
    {
        $getData = $httpData->getGetData();
        $offset = $getData['offset'] ?? 0;
        $limit = $getData['limit'] ?? 10;

        $publications = $this->mockPublications;

        $selectedPublications = array_slice($publications, $offset, $limit);

        return new RouteResponse([
            'totalCount' => count($publications),
            'currentCount' => count($selectedPublications),
            'publications' => $selectedPublications
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
        $specialtyIds = $publicationData->specialties;
        $files = $httpData->getFiles();
        $authorId = $currentUser->getId();

        $fileRepository = new FileRepository($dbConnection, $queryBuilder);
        $fileService = new FileService($fileRepository);
        $publicationRepository = new PublicationRepository($dbConnection, $queryBuilder);
        $publicationService = new PublicationService($publicationRepository);

        $filesIds = $fileService->addFiles($files, FileAttachType::PUBLICATION);
        $publicationDTO = new PublicationDTO([
            'author_id' => $authorId,
            'title' => $title,
            'content' => $content,
            'specialityIds' => $specialtyIds,
            'fileIds' => $filesIds,
        ]);
        $publicationEntity = new PublicationEntity($publicationDTO);
        $publicationEntity = $publicationService->create($publicationEntity);

        return new RouteResponse(['publicationId' => $publicationEntity->getId()], 200);
    }
}