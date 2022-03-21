<?php

namespace Packages\HttpDataManager;

class HttpData
{
    private array $postData = [];
    private array $getData = [];
    private array $cookiesData = [];
    private array $headersData = [];
    private array $inputStreamData = [];

    public function getPostData(): array
    {
        return $this->postData;
    }

    public function getGetData(): array
    {
        return $this->getData;
    }

    public function getCookiesData(): array
    {
        return $this->cookiesData;
    }

    public function getHeadersData(): array
    {
        return $this->headersData;
    }

    public function getInputStreamData(): array
    {
        return $this->inputStreamData;
    }

    public function collectData()
    {
        $this->postData = $_POST;
        $this->getData = $_GET;
        $this->cookiesData = $_COOKIE;
        $this->headersData = $this->collectHeadersData();
        $this->inputStreamData = $this->collectInputStreamData();
    }

    private function collectInputStreamData(): array
    {
        $data = array();
        $exploded = explode('&', file_get_contents('php://input'));

        foreach($exploded as $pair) {
            $item = explode('=', $pair);
            if (count($item) == 2) {
                $data[urldecode($item[0])] = urldecode($item[1]);
            }
        }

        return $data;
    }

    private function collectHeadersData(): array
    {
        $headers = [];
        foreach($_SERVER as $key => $value) {
            if (!str_starts_with($key, 'HTTP_')) {
                continue;
            }
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }
        return $headers;
    }
}