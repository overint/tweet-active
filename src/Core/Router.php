<?php
declare(strict_types=1);

namespace App\Core;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

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
    private $params = [];


    public function __construct(RequestInterface $request, array $routes)
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

        // Loop through routes and try regex match them
        foreach ($this->routes as $route => $action) {
            // Regex should only match routes with params
            if(strpos($route, ':') === false) {
                continue;
            }

            $routeRegex = '#' . preg_replace('#\\\:\w+#', '([A-Za-z0-9-_\.]+)', preg_quote($route)) . '#';

            if (preg_match($routeRegex, $path, $match)) {
                array_shift($match);
                foreach ($match as $key => $paramValue) {
                    $this->params[] = $paramValue;
                }

                $this->setRoute($action);
                return;
            }
        }
    }


    private function setRoute(string $routeTarget)
    {
        $routeData = explode('@', $routeTarget);

        $this->matched = true;
        $this->targetController = 'App\Controller\\' . $routeData[0];
        $this->targetMethod = $routeData[1];
    }
}