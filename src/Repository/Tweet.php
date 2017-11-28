<?php
declare(strict_types=1);

namespace App\Repository;

use App\TwitterApi\TweetFetcher;

/**
 * Tweet Repository
 */
class Tweet
{

    /** @var TweetFetcher */
    private $tweetFetcher;


    /**
     * Tweet Repository constructor.
     *
     * @param TweetFetcher $tweetFetcher
     */
    public function __construct(TweetFetcher $tweetFetcher)
    {
        $this->tweetFetcher = $tweetFetcher;
    }


    /**
     * Get all tweets for a user
     *
     * @param $username
     *
     * @return \App\Entity\Tweet[]
     */
    public function getAllForUser($username)
    {
        return array_map(function ($tweet) {
            return new \App\Entity\Tweet(
                $tweet->id,
                new \DateTimeImmutable($tweet->created_at)
            );
        }, $this->tweetFetcher->get($username));
    }
}