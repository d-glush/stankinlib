<?php

namespace Packages\UserService\UserEntity;

use ArrayIterator;
use IteratorAggregate;
use Packages\Collection\Collection;

class UserEntityCollection implements IteratorAggregate
{
    /** @var array<UserEntity> $items */
    protected array $items = [];
    protected int $pointer = 0;

    public function __construct(UserEntity ...$items)
    {
        foreach ($items as $item) {
            $this->items[] = $item;
        }
    }

    public function add(UserEntity $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    public function deleteById(int $id): ?UserEntity
    {
        foreach ($this->items as $item) {
            if ($item->getId() === $id) {
                return $item;
            }
        }
        return null;
    }

    public function deleteByKey(int $key): ?UserEntity
    {
        return $this->items[$key] ?? null;
    }

    public function current(): UserEntity
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


