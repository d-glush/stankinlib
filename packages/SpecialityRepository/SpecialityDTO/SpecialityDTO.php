<?php

namespace Packages\SpecialityRepository\SpecialityDTO;

use Packages\DTO\DTO;

class SpecialityDTO extends DTO
{
    public ?int $id;
    public string $name;
    public string $number;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'];
        $this->number = $data['number'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}