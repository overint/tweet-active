<?php

namespace App\Controller;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * AbstractControllerTest
 * @package App\Controller
 */
class AbstractControllerTest extends TestCase
{

    /**
     * Test html response method
     */
    public function testHtmlResponse()
    {
        $controller = new class() extends TestController {};
        $response = $controller->htmlResponse('test', 123);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame('test', $response->getBody()->getContents());
        $this->assertSame(123, $response->getStatusCode());
        $this->assertSame('text/html', $response->getHeaderLine('Content-Type'));
    }


    /**
     * Test text response method
     */
    public function testTextResponse()
    {
        $controller = new class() extends TestController {};
        $response = $controller->textResponse('test text', 456);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame('test text', $response->getBody()->getContents());
        $this->assertSame(456, $response->getStatusCode());
        $this->assertSame('text/plain', $response->getHeaderLine('Content-Type'));
    }


    /**
     * Test json response method
     *
     * @param        $data
     * @param int    $statusCode
     * @param string $expectedJson
     *
     * @dataProvider jsonResponseProvider
     */
    public function testJsonResponse($data, int $statusCode, string $expectedJson)
    {
        $controller = new class() extends TestController {};
        $response = $controller->jsonResponse($data, $statusCode);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertJsonStringEqualsJsonString($expectedJson, $response->getBody()->getContents());
        $this->assertSame($statusCode, $response->getStatusCode());
        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));
    }


    /**
     * Provider for testJsonResponse
     *
     * @return array
     */
    public function jsonResponseProvider(): array
    {
        return [
            'array' => [
                ['a' => 'b'],
                200,
                '{"a":"b"}',
            ],
            'object' => [
                (object) ['c' => 'd'],
                200,
                '{"c":"d"}',
            ],
        ];
    }
}
