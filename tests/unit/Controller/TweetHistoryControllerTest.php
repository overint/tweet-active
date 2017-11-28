<?php

namespace App\Controller;

use App\TwitterApi\UserTweets;
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
            (object) ['created_at' => 'Tue Nov 28 08:06:09 +0000 2017'],
            (object) ['created_at' => 'Tue Nov 28 08:01:04 +0000 2017'],
            (object) ['created_at' => 'Tue Nov 28 07:01:00 +0000 2017'],
            (object) ['created_at' => 'Mon Nov 27 15:04:01 +0000 2017'],
            (object) ['created_at' => 'Sun Nov 26 21:01:13 +0000 2017'],
            (object) ['created_at' => 'Sun Nov 26 07:01:03 +0000 2017'],
            (object) ['created_at' => 'Fri Nov 24 10:00:36 +0000 2017'],
        ];

        $expectedJson = '{"0":0,"1":0,"2":0,"3":0,"4":0,"5":0,"6":0,"7":2,"8":2,"9":0,"10":1,"11":0,"12":0,"13":0,"14":0,"15":1,"16":0,"17":0,"18":0,"19":0,"20":0,"21":1,"22":0,"23":0,"24":0}';

        $mockUserTweets = $this->createMock(UserTweets::class);
        $mockUserTweets->method('get')->willReturn($tweets);

        $controller = new TweetHistoryController($mockUserTweets);

        $response = $controller->histogram('test');

        $this->assertJsonStringEqualsJsonString($expectedJson, $response->getBody()->getContents());
    }
}
