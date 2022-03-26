<?php

namespace Packages\Route;

class RouteResponse
{
    public CONST WRONG_METHOD_NAME = 'wrong method name';
    public CONST ACCESS_DENIED = 'access denied';

    private array $data;
    private int $code;
    private string $message;

    public function __construct(array $data, int $code, string $message = '')
    {
        $this->data = $data;
        $this->code = $code;
        $this->message = $message;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function display(): void
    {
        echo json_encode([
            "data" => $this->data,
            "code" => $this->code,
            "errorMessage" => $this->message,
        ]);
    }
}