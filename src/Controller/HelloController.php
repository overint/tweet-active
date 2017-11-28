<?php
declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;

class HelloController extends AbstractController
{

    public function index(): ResponseInterface
    {
        return $this->htmlResponse('Try /hello/:name');
    }
}