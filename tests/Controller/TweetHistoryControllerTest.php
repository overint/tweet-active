<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Repository;
use App\TwitterApi\Exception\RequestException;
use PHPUnit\Framework\TestCase;

/**
 * TweetHistoryControllerTest
 */
class TweetHistoryControllerTest extends TestCase
{

    /**
     * Test the histogram method to ensure tweet hours are calculated and summed correctly
     */
    public function testHistogram()
    {
        $tweets = [
            new Tweet(1, new \DateTimeImmutable('Tue Nov 28 08:06:09 +0000 2017')),
            new Tweet(2, new \DateTimeImmutable('Tue Nov 28 08:01:04 +0000 2017')),
            new Tweet(3, new \DateTimeImmutable('Tue Nov 28 07:01:00 +0000 2017')),
        ];

        $expectedJson = '{"0":0,"1":0,"2":0,"3":0,"4":0,"5":0,"6":0,"7":1,"8":2,"9":0,"10":0,"11":0,"12":0,"13":0,"14":0,"15":0,"16":0,"17":0,"18":0,"19":0,"20":0,"21":0,"22":0,"23":0,"24":0}';

        $mockTweetRepo = $this->createMock(Repository\Tweet::class);
        $mockTweetRepo->method('getAllForUser')->willReturn($tweets);

        $controller = new TweetHistoryController($mockTweetRepo);

        $response = $controller->histogram('test');

        $this->assertJsonStringEqualsJsonString($expectedJson, $response->getBody()->getContents());
    }

    /**
     * Test the histogram method to ensure json error messages are returned
     */
    public function testHistogramException()
    {
        $exception = new RequestException('test error message');

        $mockTweetRepo = $this->createMock(Repository\Tweet::class);
        $mockTweetRepo->method('getAllForUser')->willThrowException($exception);

        $controller = new TweetHistoryController($mockTweetRepo);

        $response = $controller->histogram('test');

        $this->assertJsonStringEqualsJsonString('{"error":"test error message"}', $response->getBody()->getContents());
    }
}
