<?php

namespace Packages\PublicationService;

use Packages\PublicationRepository\PublicationDTO\PublicationDTO;
use Packages\PublicationRepository\PublicationRepository;
use Packages\PublicationService\PublicationEntity\PublicationEntity;

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
            'groupIds' => $publicationEntity->getGroupIds(),
            'fileIds' => $publicationEntity->getFileIds(),
        ]);
        $newId = $this->publicationRepository->add($publicationDTO);
        if (!$newId) {
            return false;
        }
        $publicationEntity->setId($newId);
        return $publicationEntity;
    }
}