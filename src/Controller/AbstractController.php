<?php
declare(strict_types=1);

namespace App\Controller;

use GuzzleHttp\Psr7\Response;

/**
 * AbstractController Class
 */
class AbstractController
{

    /**
     * Make a HTML PSR7 response object
     *
     * @param string $body   Response Body
     * @param int    $status Response status code
     *
     * @return Response
     */
    protected function htmlResponse(string $body, int $status = 200)
    {
        return new Response($status, ['Content-Type' => 'text/html'], $body);
    }


    /**
     * Make a text PSR7 response object
     *
     * @param string $body   Response Body
     * @param int    $status Response status code
     *
     * @return Response
     */
    protected function textResponse(string $body, int $status = 200)
    {
        return new Response($status, ['Content-Type' => 'text/plain'], $body);
    }


    /**
     * Make a json PSR7 response object
     *
     * @param array $data   Data to be Json encoded
     * @param int   $status Response status code
     *
     * @return Response
     */
    protected function jsonResponse(array $data, int $status = 200)
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode($data));
    }

}