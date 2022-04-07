<?php

namespace Packages\CourseRepository\CourseDTO;

use Packages\DTO\DTO;

class CourseDTO extends DTO
{
    public ?int $id;
    public string $name;
    public int $education_year;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'];
        $this->education_year = $data['education_year'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEducationYear(): int
    {
        return $this->education_year;
    }
}