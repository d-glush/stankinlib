<?php

namespace Packages\PublicationFileRepository\PublicationFileDTO;

use Packages\DTO\DTO;

class PublicationFileDTO extends DTO
{
    public int $publication_id;
    public int $file_id;

    public function __construct(array $data)
    {
        $this->publication_id = $data['publication_id'];
        $this->file_id = $data['file_id'];
    }

    public function getPublicationId(): int
    {
        return $this->publication_id;
    }

    public function getFileId(): int
    {
        return $this->file_id;
    }
}