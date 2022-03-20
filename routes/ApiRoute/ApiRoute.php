<?php

namespace Routes\ApiRoute;

use Packages\UserRepository\UserDTO\Route\Route;

class ApiRoute extends Route
{
    public function __construct(array $urls = [])
    {
        parent::__construct($urls);
    }

    protected function getSubRoutes(): array
    {
        return [
            'books' => 'Routes\ApiRoute\BooksRoute\BooksRoute',
            'users' => 'Routes\ApiRoute\UsersRoute\UsersRoute',
            'auth' => 'Routes\ApiRoute\AuthRoute\AuthRoute',
        ];
    }

    protected function getMethods(): array
    {
        return [];
    }
}