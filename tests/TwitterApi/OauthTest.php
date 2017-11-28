<?php

namespace App\TwitterApi;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Oauth Test
 */
class OauthTest extends TestCase
{

    /**
     * Test getting token (no cache)
     */
    public function testGetToken()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"token_type":"bearer","access_token":"token"}'),
            new Response(200, [], '{"token_type":"bearer","access_token":"differentToken"}'),
        ]);

        $client = new Client(['handler' => $mock]);

        $mockOauth = $this->createMock(Oauth::class);
        $mockOauth->method('getBearerToken')->willReturn('token');

        $oauth = new Oauth($client, 'key', 'secret', null);

        $this->assertSame('token', $oauth->getBearerToken());
        $this->assertSame('differentToken', $oauth->getBearerToken());
    }


    /**
     * Test getting token (with cache)
     */
    public function testGetTokenCache()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"token_type":"bearer","access_token":"token"}'),
            new Response(200, [], '{"token_type":"bearer","access_token":"differentToken"}'),
        ]);

        $tempFile = __DIR__ . '/temp';

        $client = new Client(['handler' => $mock]);

        $mockOauth = $this->createMock(Oauth::class);
        $mockOauth->method('getBearerToken')->willReturn('token');

        $oauth = new Oauth($client, 'key', 'secret', $tempFile);

        $this->assertSame('token', $oauth->getBearerToken());
        $this->assertSame('token', $oauth->getBearerToken());

        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
    }

}
