<?php
declare(strict_types=1);

namespace App\Controller;

use PHPUnit\Framework\TestCase;

/**
 * Hello Controller Test
 */
class HelloControllerTest extends TestCase
{

    /**
     * Test the index method
     */
    public function testIndex()
    {
        $controller = new HelloController();

        $response = $controller->index();

        $this->assertSame('Try /hello/:name', $response->getBody()->getContents());
    }


    /**
     * Test the greet method
     *
     * @param string $name            Name to greet
     * @param string $expectedMessage Expected response body
     *
     * @dataProvider greetProvider
     */
    public function testGreet(string $name, string $expectedMessage)
    {
        $controller = new HelloController();

        $response = $controller->greet($name);

        $this->assertSame($expectedMessage, $response->getBody()->getContents());
    }


    /**
     * Provider for testGreet
     *
     * @return array
     */
    public function greetProvider(): array
    {
        return [
            'lowercase name' => [
                'jamie',
                'Hello jamie'
            ],
            'other lowercase name' => [
                'lily',
                'Hello lily'
            ],
            'uppercase name' => [
                'EVAN',
                'Hello EVAN'
            ],
            'Capitalised name' => [
                'Rob',
                'Hello Rob'
            ],
        ];
    }

}
