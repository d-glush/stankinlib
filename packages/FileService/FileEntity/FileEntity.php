<?php

namespace Packages\FileService\FileEntity;

use JetBrains\PhpStorm\Pure;
use Packages\FileRepository\FileDTO\FileDTO;

class FileEntity
{
    private ?int $id;
    private string $name;
    private string $location;
    private string $type;
    private int $size;

    #[Pure] public function __construct(FileDTO $fileDTO)
    {
        $this->id = $fileDTO->getId() ?? null;
        $this->name = $fileDTO->getName();
        $this->location = $fileDTO->getLocation();
        $this->type = $fileDTO->getType();
        $this->size = $fileDTO->getSize();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;
        return $this;
    }
}