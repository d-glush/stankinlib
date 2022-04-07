<?php

namespace Packages\FileRepository\FileDTO;

use ArrayIterator;
use IteratorAggregate;
use JetBrains\PhpStorm\Pure;

class FileDTOCollection implements IteratorAggregate
{
    /** @var array<FileDTO> $items */
    protected array $items = [];
    protected int $pointer = 0;

    public function __construct(FileDTO ...$items)
    {
        foreach ($items as $item) {
            $this->items[] = $item;
        }
    }

    public function add(FileDTO $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    #[Pure] public function getById(int $id): ?FileDTO
    {
        foreach ($this->items as $item) {
            if ($item->getId() === $id) {
                return $item;
            }
        }
        return null;
    }

    public function getByKey(int $key): ?FileDTO
    {
        return $this->items[$key] ?? null;
    }

    public function current(): FileDTO
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