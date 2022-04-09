<?php

namespace Packages\PublicationRepository\PublicationDTO;

class PublicationDTO
{
    public ?int $id;
    public int $author_id;
    public string $title;
    public string $content;
    public ?string $create_date;
    public ?string $edit_date;
    /** @var array<int> $specialityIds */
    public array $specialityIds;
    /** @var array<int> $fileIds */
    public array $fileIds;
    /** @var array<int> $courseIds */
    public array $courseIds;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->author_id = $data['author_id'];
        $this->title = $data['title'];
        $this->content = $data['content'];
        $this->create_date = $data['create_date'] ?? null;
        $this->edit_date = $data['edit_date'] ?? null;
        $this->specialityIds = $data['specialityIds'] ?? [];
        $this->fileIds = $data['fileIds'] ?? [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreateDate(): ?string
    {
        return $this->create_date;
    }

    public function getEditDate(): ?string
    {
        return $this->edit_date;
    }

    public function getSpecialityIds(): array
    {
        return $this->specialityIds;
    }

    public function getFileIds(): array
    {
        return $this->fileIds;
    }

    public function getCourseIds(): array
    {
        return $this->courseIds;
    }

    public function setCourseIds(array $courseIds): self
    {
        $this->courseIds = $courseIds;
        return $this;
    }
}