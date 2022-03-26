<?php

namespace Routes\ApiRoute\PublicationsRoute;

use Packages\HttpDataManager\HttpData;
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

    public function getPublications(HttpData $httpData): RouteResponse
    {
        $getData = $httpData->getGetData();
        $offset = $getData['offset'] ?? 0;
        $limit = $getData['limit'] ?? 10;

        $publications = [
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
        ];
        foreach ($publications as $key => &$publication) {
            $publication['title'] = "$key " . $publication['title'];
            $publication['id'] .= $key;
        }

        $selectedPublications = array_slice($publications, $offset, $limit);

        return new RouteResponse([
            'totalCount' => count($publications),
            'currentCount' => count($selectedPublications),
            'publications' => $selectedPublications
        ], 200);
    }
}