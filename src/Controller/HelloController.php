<?php
declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;

/**
 * Hello Controller
 */
class HelloController extends AbstractController
{

    /**
     * Index page of the application
     *
     * @return ResponseInterface
     */
    public function index(): ResponseInterface
    {
        return $this->textResponse('Try /hello/:name');
    }


    /**
     * Greet based on URL param
     *
     * @param string $name Name to greet
     *
     * @return ResponseInterface
     */
    public function greet(string $name): ResponseInterface
    {
        return $this->textResponse("Hello $name");
    }
}