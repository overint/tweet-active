<?php
declare(strict_types=1);

namespace App\TwitterApi;

use App\TwitterApi\Exception\RequestException;
use GuzzleHttp\Client;

/**
 * Twitter Tweet Fetcher for a user
 */
class TweetFetcher
{

    /** Api Enpoint URL */
    const USER_TWEET_ENDPOINT = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

    /** @var Client Guzzle Client */
    private $client;

    /** @var Oauth Twitter auth */
    private $oauth;


    /**
     * Constructor
     *
     * @param Client $client Guzzle Client
     * @param Oauth  $oauth  Oauth
     */
    public function __construct(Client $client, Oauth $oauth)
    {
        $this->client = $client;
        $this->oauth = $oauth;
    }


    /**
     * Get tweets for a username
     *
     * @param string $username Screen name
     * @param int    $max      Max tweets to retrive
     *
     * @return \StdClass
     * @throws RequestException
     */
    public function get(string $username, int $max)
    {
        $tweets = [];

        $requestOptions = [
            'query' => [
                'screen_name' => $username,
                'count' => 200,
                'trim_user' => 'true',
                'exclude_replies' => 'true',
            ],
            'headers' => [
                'Authorization' => "Bearer {$this->oauth->getBearerToken()}"
            ],
            'http_errors' => false,
        ];

        do {
            $response = $this->client->get(self::USER_TWEET_ENDPOINT, $requestOptions);

            switch ($response->getStatusCode()) {
                case 200:
                    break;
                case 401:
                    throw new RequestException('User must have a public profile');
                case 404:
                    throw new RequestException('User not found');
                case 429:
                    throw new RequestException('You have been rate limited. Please wait 15 minutes and try again');
                default:
                    throw new RequestException("Unknown error occured, received status code {$response->getStatusCode()}");
            }

            $results = json_decode($response->getBody()->getContents());

            if (empty($results)) {
                break;
            }

            $tweets = array_merge($tweets, $results);

            $requestOptions['query']['max_id'] = end($tweets)->id - 1;

        } while (count($tweets) < $max);

        return array_slice($tweets, 0, $max);
    }
}