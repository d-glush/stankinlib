<?php

namespace Packages\Collection;

abstract class Collection implements CollectionInterface
{
    protected array $items = [];
    protected int $pointer = 0;

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
}