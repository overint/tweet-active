<?php
declare(strict_types=1);

namespace App\Controller;

use App\TwitterApi\Exception\RequestException;
use App\TwitterApi\UserTweets;
use Psr\Http\Message\ResponseInterface;

class TweetHistoryController extends AbstractController
{

    /** @var UserTweets Tweet API client */
    private $tweetApi;


    /**
     * Constructor.
     *
     * @param UserTweets $tweetApi
     */
    public function __construct(UserTweets $tweetApi)
    {
        $this->tweetApi = $tweetApi;
    }


    public function histogram(string $username): ResponseInterface
    {
        try {
            $tweets = $this->tweetApi->get($username);
        } catch (RequestException $e) {
            return $this->jsonResponse([
                'error' => $e->getMessage(),
            ], 400);
        }

        $times = [];

        for ($i = 0; $i <= 24; $i++) {
            $times[] = 0;
        }

        foreach ($tweets as $tweet) {
            $tweetTime = new \DateTimeImmutable($tweet->created_at);
            $tweetHour = (int) $tweetTime->format('H');

            $times[$tweetHour]++;
        }

        return $this->jsonResponse((object) $times);
    }
}