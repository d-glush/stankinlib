<?php

namespace Routes\ApiRoute\BooksRoute;

use Packages\UserRepository\UserDTO\Route\Route;

class BooksRoute extends Route
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
            '' => 'getBooks',
            '\d+' => 'getBookById',
        ];
    }

    public function getBookById(): string
    {
        return 'ONE BOOK';
    }

    public function getBooks(): array
    {
        return [
            'book1',
            'book2',
            'book2',
            'book4',
        ];
    }
}