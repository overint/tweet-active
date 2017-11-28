<?php
declare(strict_types=1);

namespace App\Core;

use GuzzleHttp\Psr7\Request;

/**
 * Request Factory
 */
class RequestFactory
{

    /**
     * Create a request entity from PHP internal server variables
     *
     * @return Request
     */
    public static function create(): Request
    {
        return new Request($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], getallheaders(),
            file_get_contents('php://input'));
    }
}