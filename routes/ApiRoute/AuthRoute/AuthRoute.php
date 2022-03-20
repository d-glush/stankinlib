<?php

namespace Routes\ApiRoute\AuthRoute;

use Packages\HttpDataManager\HttpData;
use Packages\Route\Route;
use Packages\Route\RouteResponse;

class AuthRoute extends Route
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
            'login' => 'login',
            'register' => 'registerNewUser',
            'logout' => 'logout',
            'export_users' => 'exportUsers',
        ];
    }

    public function login(HttpData $httpData): RouteResponse
    {
        $postData = $httpData->getPostData();
        $login = $postData['login'];
        $pwd = $postData['password'];
        return new RouteResponse(
            [
                "login" => $login,
                "password" => $pwd,
            ],
            200
        );
    }

    public function registerNewUser(HttpData $httpData): RouteResponse
    {
        $postData = $httpData->getPostData();
        $login = $postData['login'];
        $pwd = $postData['password'];
        $role = $postData['role'];
    }

    public function logout(HttpData $httpData): RouteResponse
    {

    }
}