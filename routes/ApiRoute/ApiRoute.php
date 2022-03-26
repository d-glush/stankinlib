<?php

namespace Routes\ApiRoute;

use Packages\Route\Route;

class ApiRoute extends Route
{
    public function __construct(array $urls = [])
    {
        parent::__construct($urls);
    }

    protected function getSubRoutes(): array
    {
        return [
            'publications' => 'Routes\ApiRoute\PublicationsRoute\PublicationsRoute',
            'users' => 'Routes\ApiRoute\UsersRoute\UsersRoute',
            'auth' => 'Routes\ApiRoute\AuthRoute\AuthRoute',
        ];
    }

    protected function getMethods(): array
    {
        return [];
    }
}