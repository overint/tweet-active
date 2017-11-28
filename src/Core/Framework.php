<?php

namespace App\Core;

use App\Core\Exception\InvalidRouteException;
use Config\Routes;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class Framework
{

    const NOT_FOUND_RESPONSE = '404 Not Found';

    /** @var Request PSR7 Request */
    private $request;


    function __construct()
    {
        $this->init();
        $this->route();
    }


    private function init()
    {
        define("DS", DIRECTORY_SEPARATOR);
        define('APP_ROOT', dirname(__DIR__));

        $this->request = RequestFactory::create();
    }


    private function route()
    {
        $router = new Router($this->request, Routes::get());

        if ( ! $router->routeMatched()) {
            return ResponseWriter::send(new Response(404, [], self::NOT_FOUND_RESPONSE));
        }

        $controllerClass = $router->getTargetController();
        $method = $router->getTargetMethod();

        if ( ! class_exists($controllerClass) || ! method_exists($controllerClass, $method)) {
            throw new InvalidRouteException('Misconfigured route - ensure that controller class & method exist');
        }

        //TODO handle params

        $controller = new $controllerClass($this->request);
        $response = $controller->$method();

        return ResponseWriter::send($response);
    }
}