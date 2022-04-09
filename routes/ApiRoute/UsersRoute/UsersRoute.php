<?php

namespace Routes\ApiRoute\UsersRoute;

use JetBrains\PhpStorm\Pure;
use Packages\DBConnection\DBConnection;
use Packages\HttpDataManager\HttpData;
use Packages\QueryBuilder\QueryBuilder;
use Packages\Route\Route;
use Packages\Route\RouteResponse;
use Packages\UserRepository\UserRepository;
use Packages\UserService\UserEntity\Role\Role;
use Packages\UserService\UserEntity\UserEntity;
use Packages\UserService\UserService;

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
            'getAuthors' => 'getAuthors',
        ];
    }

    public function getAuthors(HttpData $httpData): RouteResponse
    {
        $role = Role::PUBLICIST;

        $connection = new DBConnection();
        $queryBuilder = new QueryBuilder();
        $userRepository = new UserRepository($connection, $queryBuilder);
        $userService = new UserService($userRepository);
        /** @var array<UserEntity> $authorsCollection */
        $authorsCollection = $userService->getByRole($role);

        $authors = [];
        foreach ($authorsCollection as $userEntity) {
            $author['id'] = $userEntity->getId();
            $author['firstName'] = $userEntity->getFirstName();
            $author['middleName'] = $userEntity->getMiddleName();
            $author['lastName'] = $userEntity->getLastName();
            $author['login'] = $userEntity->getLogin();
            $author['name'] = sprintf('%s %s %s', $author['lastName'], $author['firstName'], $author['middleName']);
            $authors[] = $author;
        }

        return new RouteResponse(['authors' => $authors], 200);
    }
}