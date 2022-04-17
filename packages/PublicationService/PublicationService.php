<?php

namespace Packages\PublicationService;

use Packages\Limit\Limit;
use Packages\PublicationRepository\PublicationDTO\PublicationDTO;
use Packages\PublicationRepository\PublicationRepository;
use Packages\PublicationService\PublicationEntity\PublicationEntity;
use Packages\PublicationService\PublicationEntity\PublicationEntityCollection;

class PublicationService
{
    private PublicationRepository $publicationRepository;

    public function __construct(PublicationRepository $publicationRepository)
    {
        $this->publicationRepository = $publicationRepository;
    }

    public function create(PublicationEntity $publicationEntity): PublicationEntity|bool
    {
        $publicationDTO = new PublicationDTO([
            'author_id' => $publicationEntity->getAuthorId(),
            'title' => $publicationEntity->getTitle(),
            'content' => $publicationEntity->getContent(),
            'specialityIds' => $publicationEntity->getSpecialityIds(),
            'courseIds' => $publicationEntity->getCourseIds(),
            'fileIds' => $publicationEntity->getFileIds(),
        ]);
        $newId = $this->publicationRepository->add($publicationDTO);
        if (!$newId) {
            return false;
        }
        $publicationEntity->setId($newId);
        return $publicationEntity;
    }

    public function getByIds(array $ids): PublicationEntityCollection
    {
        $dtoCollection = $this->publicationRepository->getByIds($ids);
        $result = new PublicationEntityCollection();
        foreach ($dtoCollection as $dto) {
            $result->add(new PublicationEntity($dto));
        }
        return $result;
    }

    public function getByParams(
        Limit $limit,
        array $authorIds = [],
        array $specialityIds = [],
        array $courseIds = []
    ): PublicationEntityCollection {
        $dtoCollection = $this->publicationRepository->getByParams(
            $limit,
            $authorIds,
            $specialityIds,
            $courseIds
        );
        $result = new PublicationEntityCollection();
        foreach ($dtoCollection as $dto) {
            $result->add(new PublicationEntity($dto));
        }
        return $result;
    }

    public function getCountByParams(array $authorIds, array $specialityIds, array $courseIds): int
    {
        return $this->publicationRepository->getCountByParams($authorIds, $specialityIds, $courseIds);
    }
}