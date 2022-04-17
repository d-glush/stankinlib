<?php

namespace Packages\PublicationService\PublicationEntity;

use DateTime;
use JetBrains\PhpStorm\Pure;
use Packages\PublicationRepository\PublicationDTO\PublicationDTO;

class PublicationEntity
{
    private ?int $id;
    private int $author_id;
    private string $title;
    private string $content;
    private ?DateTime $create_date;
    private ?DateTime $edit_date;
    /** @var array<int> $specialityIds */
    private array $specialityIds;
    /** @var array<int> $fileIds */
    private array $fileIds;
    /** @var array<int> $courseIds */
    private array $courseIds;

    #[Pure] public function __construct(PublicationDTO $publicationDTO)
    {
        $this->id = $publicationDTO->getId();
        $this->author_id = $publicationDTO->getAuthorId();
        $this->title = $publicationDTO->getTitle();
        $this->content = $publicationDTO->getContent();
        $this->create_date = $publicationDTO->getCreateDate() ? new DateTime($publicationDTO->getCreateDate()) : null;
        $this->edit_date = $publicationDTO->getEditDate() ? new DateTime($publicationDTO->getEditDate()) : null;
        $this->specialityIds = $publicationDTO->getSpecialityIds();
        $this->courseIds = $publicationDTO->getCourseIds();
        $this->fileIds = $publicationDTO->getFileIds();
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

    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): self
    {
        $this->author_id = $author_id;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getCreateDate(): ?DateTime
    {
        return $this->create_date;
    }

    public function setCreateDate(?DateTime $create_date): self
    {
        $this->create_date = $create_date;
        return $this;
    }

    public function getEditDate(): ?DateTime
    {
        return $this->edit_date;
    }

    public function setEditDate(?DateTime $edit_date): self
    {
        $this->edit_date = $edit_date;
        return $this;
    }

    /**
     * @return int[]
     */
    public function getSpecialityIds(): array
    {
        return $this->specialityIds;
    }

    /**
     * @param int[] $specialityIds
     */
    public function setSpecialityIds(array $specialityIds): self
    {
        $this->specialityIds = $specialityIds;
        return $this;
    }

    /**
     * @return int[]
     */
    public function getFileIds(): array
    {
        return $this->fileIds;
    }

    /**
     * @param int[] $fileIds
     */
    public function setFileIds(array $fileIds): self
    {
        $this->fileIds = $fileIds;
        return $this;
    }

    /**
     * @return int[]
     */
    public function getCourseIds(): array
    {
        return $this->courseIds;
    }

    /**
     * @param int[] $courseIds
     */
    public function setCourseIds(array $courseIds): self
    {
        $this->courseIds = $courseIds;
        return $this;
    }
}