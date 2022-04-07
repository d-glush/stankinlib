<?php

namespace Packages\FileRepository\FileDTO;

use Packages\DTO\DTO;

class FileDTO extends DTO
{
    public ?int $id;
    public string $name;
    public string $location;
    public string $type;
    public int $size;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'];
        $this->location = $data['location'];
        $this->type = $data['type'];
        $this->size = $data['size'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}