<?php

namespace Routes\ApiRoute\AuthorsRoute;

use JetBrains\PhpStorm\Pure;
use Packages\Route\Route;

class AuthorsRoute extends Route
{
    #[Pure] public function __construct(array $urls = [])
    {
        parent::__construct($urls);
    }

    protected function getSubRoutes(): array
    {
        return [];
    }

    protected function getMethods(): array
    {
        // TODO: Implement getMethods() method.
    }
}