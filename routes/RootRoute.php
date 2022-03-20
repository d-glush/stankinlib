<?php

namespace Routes;

use Packages\Route\Route;

class RootRoute extends Route
{
    public function __construct(array $urls = [])
    {
        parent::__construct($urls);
    }

    protected function getSubRoutes(): array
    {
        return [
            'api' => 'Routes\ApiRoute\ApiRoute',
        ];
    }

    protected function getMethods(): array
    {
        return [];
    }
}