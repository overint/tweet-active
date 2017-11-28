<?php

namespace App\Core;

use App\Core\Exception\InvalidResponseException;
use App\Core\Exception\InvalidRouteException;
use Config\Routes;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Mini MVC Framework
 * @package App\Core
 */
class Framework
{

    /** Not found response message */
    const NOT_FOUND_RESPONSE = '404 Not Found';

    /** @var Request PSR7 Request */
    private $request;


    /**
     * Framework constructor.
     */
    function __construct()
    {
        $this->init();
        $this->route();
    }


    /**
     * Init the framework
     */
    private function init()
    {
        define("DS", DIRECTORY_SEPARATOR);
        define('APP_ROOT', dirname(__DIR__));

        $this->request = RequestFactory::create();
    }


    /**
     * Route the request based on the path
     *
     * @throws InvalidRouteException
     */
    private function route()
    {
        $router = new Router($this->request, Routes::get());

        if ( ! $router->routeMatched()) {
            ResponseWriter::send(new Response(404, [], self::NOT_FOUND_RESPONSE));
        }

        $controllerClass = $router->getTargetController();
        $method = $router->getTargetMethod();
        $params = $router->getParams();

        if ( ! class_exists($controllerClass) || ! method_exists($controllerClass, $method)) {
            throw new InvalidRouteException('Misconfigured route - ensure that controller class & method exist');
        }

        $this->dispatch($controllerClass, $method, $params);
    }


    /**
     * Dispatch a request to a controller
     *
     * @param string $controllerClass Classname of the controller
     * @param string $method          Target Method name
     * @param array  $params          Route Params
     *
     * @throws InvalidResponseException
     */
    private function dispatch(string $controllerClass, string $method, array $params = [])
    {
        $controller = new $controllerClass($this->request);
        $response = $controller->$method(...$params);

        if ( ! $response instanceof ResponseInterface) {
            throw new InvalidResponseException('Controller must return a valid ResponseInterface object');
        }

        ResponseWriter::send($response);
    }
}