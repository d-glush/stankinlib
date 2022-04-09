<?php

namespace Routes\ApiRoute\SpecialitiesRoute;

use JetBrains\PhpStorm\Pure;
use Packages\HttpDataManager\HttpData;
use Packages\Route\Route;
use Packages\Route\RouteResponse;


class SpecialitiesRoute extends Route
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
            'get' => 'get',
        ];
    }

    public function get(HttpData $httpData): RouteResponse
    {
        $result = [
            [
                'id' => 1,
                'name' => '1 Информационный системы и технологии 1',
                'number' => '09.03.01',
            ],
            [
                'id' => 2,
                'name' => '2 Информационный системы и технологии 2',
                'number' => '09.03.02',
            ],
            [
                'id' => 3,
                'name' => '3 Информационный системы и технологии 3',
                'number' => '09.03.03',
            ],
            [
                'id' => 4,
                'name' => '4 Информационный системы и технологии 4',
                'number' => '09.03.04',
            ],
        ];
        return new RouteResponse(['specialities' => $result], 200);
    }
}