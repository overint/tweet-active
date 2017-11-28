<?php
declare(strict_types=1);

namespace App\Core;

use GuzzleHttp\Psr7\Request;

class Router
{
    /** @var Request PSR7 Request */
    private $request;

    /** @var array Route config */
    private $routes;

    /** @var bool If a route was matched or not */
    private $matched = false;

    /** @var string Controller FQCN */
    private $targetController;

    /** @var string Controller Method Name */
    private $targetMethod;

    /** @var array Array of URL params */
    private $params;


    public function __construct(Request $request, array $routes)
    {
        $this->request = $request;
        $this->routes = $routes;

        $this->matchRoute();
    }


    public function routeMatched(): bool
    {
        return $this->matched;
    }


    public function getTargetController(): string
    {
        return $this->targetController;
    }


    public function getTargetMethod(): string
    {
        return $this->targetMethod;
    }


    public function getParams(): array
    {
        return $this->params;
    }


    private function matchRoute()
    {
        $path = $this->request->getUri()->getPath();

        // Try a simple match first
        if (array_key_exists($path, $this->routes)) {
            $this->setRoute($this->routes[$path]);
        }

        // TODO regex route matching
    }

    private function setRoute(string $routeTarget) {
        $routeData = explode('@', $routeTarget);

        $this->matched = true;
        $this->targetController =  'App\Controller\\' . $routeData[0];
        $this->targetMethod = $routeData[1];
    }
}