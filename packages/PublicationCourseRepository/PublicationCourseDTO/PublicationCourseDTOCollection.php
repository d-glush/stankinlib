<?php

namespace Packages\PublicationCourseRepository\PublicationCourseDTO;

use ArrayIterator;
use IteratorAggregate;
use JetBrains\PhpStorm\Pure;

class PublicationCourseDTOCollection implements IteratorAggregate
{
    /** @var array<PublicationCourseDTO> $items */
    protected array $items = [];
    protected int $pointer = 0;

    public function __construct(PublicationCourseDTO ...$items)
    {
        foreach ($items as $item) {
            $this->items[] = $item;
        }
    }

    public function add(PublicationCourseDTO $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    #[Pure] public function getById(int $id): ?PublicationCourseDTO
    {
        foreach ($this->items as $item) {
            if ($item->getId() === $id) {
                return $item;
            }
        }
        return null;
    }

    public function getByKey(int $key): ?PublicationCourseDTO
    {
        return $this->items[$key] ?? null;
    }

    public function current(): PublicationCourseDTO
    {
        return $this->items[$this->pointer];
    }

    public function key(): int
    {
        return $this->pointer;
    }

    public function next()
    {
        $this->pointer++;
    }

    public function rewind()
    {
        $this->pointer = 0;
    }

    public function valid(): bool|int
    {
        return $this->pointer < count($this->items);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }
}
