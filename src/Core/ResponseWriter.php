<?php

namespace App\Core;

use Psr\Http\Message\ResponseInterface;

/**
 * ResponseWriter
 * Functionality taken from https://github.com/http-interop/response-sender
 */
class ResponseWriter
{

    /**
     * Send an HTTP response
     *
     * @param ResponseInterface $response
     *
     * @return bool
     */
    public static function send(ResponseInterface $response): bool
    {
        $http_line = sprintf('HTTP/%s %s %s', $response->getProtocolVersion(), $response->getStatusCode(),
            $response->getReasonPhrase());
        header($http_line, true, $response->getStatusCode());
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header("$name: $value", false);
            }
        }
        $stream = $response->getBody();
        if ($stream->isSeekable()) {
            $stream->rewind();
        }
        while ( ! $stream->eof()) {
            echo $stream->read(1024 * 8);
        }

        return true;
    }
}