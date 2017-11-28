<?php

namespace App\Repository;

use App\TwitterApi\TweetFetcher;
use PHPUnit\Framework\TestCase;

/**
 * Tweet Repository Test
 */
class TweetTest extends TestCase
{

    /**
     * Test getting tweets entities from the repository
     */
    public function testGetTweets()
    {
        $tweets = [
            (object)['id' => 1, 'created_at' => 'Tue Nov 28 08:06:09 +0000 2017'],
            (object)['id' => 2, 'created_at' => 'Tue Nov 28 08:01:04 +0000 2017'],
            (object)['id' => 3, 'created_at' => 'Tue Nov 28 07:01:00 +0000 2017'],
        ];

        $expected = [
            new \App\Entity\Tweet(1, new \DateTimeImmutable('Tue Nov 28 08:06:09 +0000 2017')),
            new \App\Entity\Tweet(2, new \DateTimeImmutable('Tue Nov 28 08:01:04 +0000 2017')),
            new \App\Entity\Tweet(3, new \DateTimeImmutable('Tue Nov 28 07:01:00 +0000 2017')),
        ];

        $mockTweetFetcher = $this->createMock(TweetFetcher::class);
        $mockTweetFetcher->method('get')->willReturn($tweets);

        $repo = new Tweet($mockTweetFetcher);

        $tweets = $repo->getAllForUser('test');

        $this->assertEquals($expected, $tweets);
    }
}
