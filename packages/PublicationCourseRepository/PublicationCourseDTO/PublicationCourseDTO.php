<?php

namespace Packages\PublicationCourseRepository\PublicationCourseDTO;

use Packages\DTO\DTO;

class PublicationCourseDTO extends DTO
{
    public int $publication_id;
    public int $course_id;

    public function __construct(array $data)
    {
        $this->publication_id = $data['publication_id'];
        $this->course_id = $data['course_id'];
    }

    public function getPublicationId(): ?int
    {
        return $this->publication_id;
    }

    public function getCourseId(): string
    {
        return $this->course_id;
    }
}