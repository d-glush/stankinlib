<?php

namespace Packages\PublicationFileRepository\PublicationFileDTO;

use ArrayIterator;
use IteratorAggregate;
use JetBrains\PhpStorm\Pure;

class PublicationFileDTOCollection implements IteratorAggregate
{
    /** @var array<PublicationFileDTO> $items */
    protected array $items = [];
    protected int $pointer = 0;

    public function __construct(PublicationFileDTO ...$items)
    {
        foreach ($items as $item) {
            $this->items[] = $item;
        }
    }

    public function add(PublicationFileDTO $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    #[Pure] public function getById(int $id): ?PublicationFileDTO
    {
        foreach ($this->items as $item) {
            if ($item->getId() === $id) {
                return $item;
            }
        }
        return null;
    }

    public function getByKey(int $key): ?PublicationFileDTO
    {
        return $this->items[$key] ?? null;
    }

    public function current(): PublicationFileDTO
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
