<?php

namespace Packages\PublicationService\PublicationEntity;

use ArrayIterator;
use IteratorAggregate;
use JetBrains\PhpStorm\Pure;

class PublicationEntityCollection implements IteratorAggregate
{
    /** @var array<PublicationEntity> $items */
    protected array $items = [];
    protected int $pointer = 0;

    public function __construct(PublicationEntity ...$items)
    {
        foreach ($items as $item) {
            $this->items[] = $item;
        }
    }

    public function add(PublicationEntity $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    #[Pure] public function getById(int $id): ?PublicationEntity
    {
        foreach ($this->items as $item) {
            if ($item->getId() === $id) {
                return $item;
            }
        }
        return null;
    }

    public function getByKey(int $key): ?PublicationEntity
    {
        return $this->items[$key] ?? null;
    }

    public function current(): PublicationEntity
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

    public function length(): int
    {
        return count($this->items);
    }
}