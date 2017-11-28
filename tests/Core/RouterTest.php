<?php

namespace App\Core;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class RouterTest
 * @package App\Core
 */
class RouterTest extends TestCase
{

    /**
     * Test router getters
     */
    public function testGetters()
    {
        $request = $this->createRequestMock('/test');
        $routes = ['/test' => 'ControllerName@method'];

        $router = new Router($request, $routes);

        $this->assertSame('App\Controller\ControllerName', $router->getTargetController());
        $this->assertSame('method', $router->getTargetMethod());
        $this->assertSame([], $router->getParams());
    }


    /**
     * Test route matching
     *
     * @param string      $path
     * @param array       $routes
     * @param bool        $shouldMatch
     * @param string|null $expectedController
     * @param array       $expectedParams
     *
     * @dataProvider routingProvider
     */
    public function testRouting(
        string $path,
        array $routes,
        bool $shouldMatch,
        string $expectedController = null,
        array $expectedParams = []
    ) {
        $request = $this->createRequestMock($path);

        $router = new Router($request, $routes);

        $this->assertSame($shouldMatch, $router->routeMatched());

        if ( ! $shouldMatch) {
            return;
        }

        $this->assertSame($expectedController, $router->getTargetController());
        $this->assertSame($expectedParams, $router->getParams());
    }


    /**
     * Provider for testRouting
     *
     * @return array
     */
    public function routingProvider(): array
    {
        return [
            'match root' => [
                '/',
                [
                    '/hello' => 'Test1@test',
                    '/' => 'Test2@test',
                ],
                true,
                'App\Controller\Test2'
            ],
            'do not match empty' => [
                '',
                [
                    '/hello' => 'Test1@test',
                    '/' => 'Test2@test',
                ],
                false,
            ],
            'do not match first/last' => [
                '/b',
                [
                    '/a' => 'Test1@test',
                    '/b' => 'Test2@test',
                    '/c' => 'Test3@test',
                ],
                true,
                'App\Controller\Test2',
            ],
            'match correct route with params' => [
                '/hi/jamie',
                [
                    '/hi' => 'Test1@test',
                    '/hi/:name/bye' => 'Test2@test',
                    '/hi/:name' => 'Test3@test',
                ],
                true,
                'App\Controller\Test3',
                ['jamie']
            ],
            'do not match route with trailing slash' => [
                '/hi/lily',
                [
                    '/hi' => 'Test1@test',
                    '/hi/:name/' => 'Test3@test',
                ],
                false,
            ],
            'ensure regex is escaped' => [
                '/say/works',
                [
                    '.*' => 'Test1@test',
                    '/say/:msg' => 'Test2@test',
                ],
                true,
                'App\Controller\Test2',
                ['works'],
            ],
            'match url with multiple params' => [
                '/say/works/or/not',
                [
                    '/say/works' => 'Test1@test',
                    '/say/:a/:b/:c' => 'Test2@test',
                ],
                true,
                'App\Controller\Test2',
                ['works', 'or', 'not'],
            ],
            'match url with multiple params that ends with string' => [
                '/say/works/or/not/result',
                [
                    '/say/works' => 'Test1@test',
                    '/say/:a/:b/:c/result' => 'Test2@test',
                ],
                true,
                'App\Controller\Test2',
                ['works', 'or', 'not'],
            ],
            'does not match specicial chars' => [
                '/say/!?&',
                [
                    '/say/:msg' => 'Test1@test',
                ],
                false,
            ],
            'allow dash' => [
                '/say/my-name',
                [
                    '/say/:msg' => 'Test1@test',
                ],
                true,
                'App\Controller\Test1',
                ['my-name'],
            ],
            'allow underscore' => [
                '/say/my_name',
                [
                    '/say/:msg' => 'Test1@test',
                ],
                true,
                'App\Controller\Test1',
                ['my_name'],
            ],
            'allow dot' => [
                '/say/my.name',
                [
                    '/say/:msg' => 'Test1@test',
                ],
                true,
                'App\Controller\Test1',
                ['my.name'],
            ],
        ];
    }


    /**
     * Create a request mock
     *
     * @param string $path
     *
     * @return RequestInterface
     */
    private function createRequestMock(string $path): RequestInterface
    {
        $uriMock = $this->createMock(UriInterface::class);
        $uriMock->method('getPath')->willReturn($path);
        $requestMock = $this->createMock(RequestInterface::class);
        $requestMock->method('getUri')->willReturn($uriMock);

        return $requestMock;
    }
}
