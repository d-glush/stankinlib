<?php

namespace Packages\PublicationSpecialityRepository\PublicationSpecialityDTO;

use Packages\DTO\DTO;

class PublicationSpecialityDTO extends DTO
{
    public int $publication_id;
    public int $speciality_id;

    public function __construct(array $data)
    {
        $this->publication_id = $data['publication_id'];
        $this->speciality_id = $data['speciality_id'];
    }

    public function getPublicationId(): ?int
    {
        return $this->publication_id;
    }

    public function getSpecialityId(): int
    {
        return $this->speciality_id;
    }
}