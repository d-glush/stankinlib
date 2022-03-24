<?php

namespace Packages\Route;

use JetBrains\PhpStorm\Pure;
use Packages\HttpDataManager\HttpData;

abstract class Route
{
    protected array $urls;
    protected array $subRoutes = [];
    protected array $methods = [];

    public function __construct(array $urls = [])
    {
        $this->urls = $urls;
    }

    public function process(): RouteResponse
    {
        if (count($this->urls) > 1) {
            $this->subRoutes = $this->getSubRoutes();
            return $this->callSubRoute($this->urls);
        } else {
            $this->methods = $this->getMethods();
            $methodRoute = $this->urls[0];
            return $this->callMethod($methodRoute);
        }
    }

    abstract protected function getSubRoutes(): array;

    abstract protected function getMethods(): array;

    #[Pure] protected function getResponseWrongData(): RouteResponse
    {
        return new RouteResponse([], 400, 'wrong input data');
    }

    #[Pure] protected function getResponseOk(): RouteResponse
    {
        return new RouteResponse([], 200, 'ok');
    }

    private function callSubRoute($urls): RouteResponse
    {
        $wrongMethodNameResponse = new RouteResponse([], 404, RouteResponse::WRONG_METHOD_NAME);
        if (!isset($this->subRoutes[$urls[0]])) {
            return $wrongMethodNameResponse;
        }
        $newUrls = array_splice($urls, 1);
        /** @var Route $subRoute */
        $subRoute = new $this->subRoutes[$urls[0]]($newUrls);
        return $subRoute->process();
    }

    private function callMethod($methodRoute): RouteResponse
    {
        $wrongMethodNameResponse = new RouteResponse([], 404, RouteResponse::WRONG_METHOD_NAME);
        if (!isset($this->methods[$methodRoute])) {
            return $wrongMethodNameResponse;
        }

        $methodName = $this->methods[$methodRoute];
        if (is_callable(array($this, $methodName))) {
            $httpData = new HttpData();
            $httpData->collectData();
            return $this->$methodName($httpData);
        } else {
            return $wrongMethodNameResponse;
        }
    }
}