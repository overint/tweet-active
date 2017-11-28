<?php
declare(strict_types=1);

namespace App\Controller;

use App\TwitterApi\Exception\RequestException;
use App\TwitterApi\UserTweets;
use Config;
use App\TwitterApi\Oauth;

class TweetHistoryController extends AbstractController
{

    public function histogram(string $username)
    {
        $auth = new Oauth(Config\Twitter::get()['consumer_key'], Config\Twitter::get()['consumer_secret']);

        $token = $auth->getBearerToken();

        $tweetApi = new UserTweets($token);

        try {
            $tweets = $tweetApi->get($username);
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