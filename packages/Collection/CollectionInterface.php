<?php

namespace Packages\Collection;

use IteratorAggregate;

interface CollectionInterface extends IteratorAggregate
{
    public function __construct();

    /**
     * @param mixed $item
     * @return mixed
     */
    public function add(mixed $item): mixed;
}