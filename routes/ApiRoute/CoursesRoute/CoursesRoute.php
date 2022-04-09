<?php

namespace Routes\ApiRoute\CoursesRoute;

use JetBrains\PhpStorm\Pure;
use Packages\HttpDataManager\HttpData;
use Packages\Route\Route;
use Packages\Route\RouteResponse;


class CoursesRoute extends Route
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
                'name' => 'Бакалавриат 1 курс',
                'education_year' => '1',
            ],
            [
                'id' => 2,
                'name' => 'Бакалавриат 2 курс',
                'education_year' => '2',
            ],
            [
                'id' => 3,
                'name' => 'Бакалавриат 3 курс',
                'education_year' => '3',
            ],
            [
                'id' => 4,
                'name' => 'Бакалавриат 4 курс',
                'education_year' => '4',
            ],
            [
                'id' => 5,
                'name' => 'Магистратура 1 курс',
                'education_year' => '1',
            ],
            [
                'id' => 6,
                'name' => 'Магистратура 2 курс',
                'education_year' => '2',
            ],
        ];
        return new RouteResponse(['courses' => $result], 200);
    }
}