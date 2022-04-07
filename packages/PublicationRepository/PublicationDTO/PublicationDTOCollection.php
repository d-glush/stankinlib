<?php

namespace Packages\PublicationRepository\PublicationDTO;

use ArrayIterator;
use IteratorAggregate;

class PublicationDTOCollection implements IteratorAggregate
{
    /** @var array<PublicationDTO> $items */
    protected array $items = [];
    protected int $pointer = 0;

    public function __construct(PublicationDTO ...$items)
    {
        foreach ($items as $item) {
            $this->items[] = $item;
        }
    }

    public function add(PublicationDTO $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    public function getById(int $id): ?PublicationDTO
    {
        foreach ($this->items as $item) {
            if ($item->getId() === $id) {
                return $item;
            }
        }
        return null;
    }

    public function getByKey(int $key): ?PublicationDTO
    {
        return $this->items[$key] ?? null;
    }

    public function current(): PublicationDTO
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