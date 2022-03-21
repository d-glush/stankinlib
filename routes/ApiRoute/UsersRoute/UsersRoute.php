<?php

namespace Routes\ApiRoute\UsersRoute;

use JetBrains\PhpStorm\Pure;
use Packages\Route\Route;
use Packages\Route\RouteResponse;

class UsersRoute extends Route
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
        return [
            'getById' => 'getUserById',
            'getByIds' => 'getUserByIds',
        ];
    }

    public function changeEmail(): RouteResponse
    {

    }
}