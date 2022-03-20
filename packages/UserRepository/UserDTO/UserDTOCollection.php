<?php

namespace Packages\UserRepository\UserDTO;

use ArrayIterator;
use IteratorAggregate;

class UserDTOCollection implements IteratorAggregate
{
    /** @var array<UserDTO> $items */
    protected array $items = [];
    protected int $pointer = 0;

    public function __construct(UserDto ...$items)
    {
        foreach ($items as $item) {
            $this->items[] = $item;
        }
    }

    public function add(UserDTO $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    public function deleteById(int $id): ?UserDTO
    {
        foreach ($this->items as $item) {
            if ($item->getId() === $id) {
                return $item;
            }
        }
        return null;
    }

    public function deleteByKey(int $key): ?UserDTO
    {
        return $this->items[$key] ?? null;
    }

    public function current(): UserDto
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