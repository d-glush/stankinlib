<?php

namespace Routes\ApiRoute\UsersRoute;

use Packages\UserRepository\UserDTO\Route\Route;
use Packages\UserRepository\UserDTO\Route\RouteResponse;

class UsersRoute extends Route
{
    public function __construct(array $urls = [])
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
            'add' => 'addUser',
        ];
    }
}