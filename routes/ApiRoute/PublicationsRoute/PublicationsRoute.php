<?php

namespace Routes\ApiRoute\PublicationsRoute;

use Packages\Route\Route;
use Packages\Route\RouteResponse;

class PublicationsRoute extends Route
{
    public function __construct(array $urls = [])
    {
        parent::__construct($urls);
    }

    protected function getSubRoutes(): array
    {
        return [

        ];
    }

    protected function getMethods(): array
    {
        return [
            'get' => 'getPublications',
        ];
    }

    public function getPublications(): RouteResponse
    {
        return new RouteResponse([
            'totalCount' => 4,
            'currentCount' => 2,
            'publications' => [
                [
                    'id' => '123',
                    'title' => 'Исследование говна с палками',
                    'specializationName' => 'Информационрные системы и технологии',
                    'specializationNumber' => '09.03.02',
                    'createDate' => '13.08.2000',
                    'editDate' => '24.03.2022',
                    'author' => [
                        'id' => '222',
                        'firstName' => 'Алексей',
                        'middleName' => 'Иванович',
                        'lastName' => 'Сосенушкин'
                    ],
                ],
                [
                    'id' => '111',
                    'title' => 'Исследование говна с палками',
                    'specializationName' => 'Информационрные системы и технологии',
                    'specializationNumber' => '09.03.02',
                    'createDate' => '13.08.2000',
                    'editDate' => '24.03.2022',
                    'author' => [
                        'id' => '222',
                        'firstName' => 'Алексей',
                        'middleName' => 'Иванович',
                        'lastName' => 'Сосенушкин'
                    ],
                ],
            ],
        ], 200);
    }
}