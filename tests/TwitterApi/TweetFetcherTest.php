<?php

namespace App\TwitterApi;

use App\TwitterApi\Exception\RequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * User Tweet Fetcher Test
 */
class TweetFetcherTest extends TestCase
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

        $tweetApi = new TweetFetcher($client, $mockOauth);
        $data = $tweetApi->get('testUser', 1);

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

        $tweetApi = new TweetFetcher($client, $mockOauth);
        $tweetApi->get('testUser', 1);
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


    /**
     * Test getting multiple pages of tweets
     *
     * @param int $max
     * @param int $expectedCount
     *
     * @dataProvider getMaxTweetsProvider
     */
    public function testGetMaxTweets(int $max, int $expectedCount)
    {
        $mock = new MockHandler([
            new Response(200, [], '[{"created_at":"Tue Nov 28 09:02:01 +0000 2017","id":1},{"created_at":"Tue Nov 28 09:02:01 +0000 2017","id":2},{"created_at":"Tue Nov 28 09:02:01 +0000 2017","id":3},{"created_at":"Tue Nov 28 09:02:01 +0000 2017","id":4}]'),
            new Response(200, [], '[{"created_at":"Tue Nov 28 09:02:01 +0000 2017","id":1},{"created_at":"Tue Nov 28 09:02:01 +0000 2017","id":2},{"created_at":"Tue Nov 28 09:02:01 +0000 2017","id":3},{"created_at":"Tue Nov 28 09:02:01 +0000 2017","id":4}]'),
            new Response(200, [], '[{"created_at":"Tue Nov 28 09:02:01 +0000 2017","id":1},{"created_at":"Tue Nov 28 09:02:01 +0000 2017","id":2},{"created_at":"Tue Nov 28 09:02:01 +0000 2017","id":3},{"created_at":"Tue Nov 28 09:02:01 +0000 2017","id":4}]'),
            new Response(200, [], '[]'),
        ]);

        $client = new Client(['handler' => $mock]);

        $mockOauth = $this->createMock(Oauth::class);
        $mockOauth->method('getBearerToken')->willReturn('token');

        $tweetApi = new TweetFetcher($client, $mockOauth);
        $data = $tweetApi->get('testUser', $max);

        $this->assertCount($expectedCount, $data);
    }

    public function getMaxTweetsProvider()
    {
        return [
            [1, 1],
            [2, 2],
            [4, 4],
            [5, 5],
            [8, 8],
            [9, 9],
            [12, 12],
            [13, 12],
        ];
    }

}
