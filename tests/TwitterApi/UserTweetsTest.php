<?php

namespace App\TwitterApi;

use App\TwitterApi\Exception\RequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTweetsTest
 * @package App\TwitterApi
 */
class UserTweetsTest extends TestCase
{

    /**
     * Test getting tweets
     */
    public function testGetTweets()
    {
        $exampleJson = file_get_contents(__DIR__ . '/tweetResponse.json');

        $mock = new MockHandler([
            new Response(200, [], $exampleJson),
        ]);

        $client = new Client(['handler' => $mock]);

        $mockOauth = $this->createMock(Oauth::class);
        $mockOauth->method('getBearerToken')->willReturn('token');

        $tweetApi = new UserTweets($client, $mockOauth);
        $data = $tweetApi->get('testUser');

        $this->assertEquals(json_decode($exampleJson), $data);
    }


    /**
     * Test getting tweets when there was an error
     *
     * @param int    $statusCode
     * @param string $exceptionClass
     * @param string $exceptionMessage
     *
     * @dataProvider getTweetsErrorProvider
     */
    public function testGetTweetsError(int $statusCode, string $exceptionClass, string $exceptionMessage)
    {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);

        $mock = new MockHandler([
            new Response($statusCode, [], '{}'),
        ]);

        $client = new Client(['handler' => $mock]);

        $mockOauth = $this->createMock(Oauth::class);
        $mockOauth->method('getBearerToken')->willReturn('token');

        $tweetApi = new UserTweets($client, $mockOauth);
        $tweetApi->get('testUser');
    }


    public function getTweetsErrorProvider(): array
    {
        return [
            [
                401,
                RequestException::class,
                'User must have a public profile',
            ],
            [
                404,
                RequestException::class,
                'User not found',
            ],
            [
                403,
                RequestException::class,
                'Unknown error occured, received status code 403',
            ],
            [
                429,
                RequestException::class,
                'You have been rate limited. Please wait 15 minutes and try again',
            ],
            [
                500,
                RequestException::class,
                'Unknown error occured, received status code 500',
            ],
        ];
    }

}
