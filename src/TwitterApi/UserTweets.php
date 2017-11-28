<?php
declare(strict_types=1);

namespace App\TwitterApi;

use App\TwitterApi\Exception\RequestException;
use GuzzleHttp\Client;

/**
 * Class UserTweets
 * @package App\TwitterApi
 */
class UserTweets
{

    /** Api Enpoint URL */
    const USER_TWEET_ENDPOINT = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

    /** @var Oauth Twitter auth */
    private $oauth;


    /**
     * Constructor.
     *
     * @param string $bearerToken Auth token
     */
    public function __construct(Oauth $oauth)
    {
        $this->oauth = $oauth;
    }


    /**
     * Get tweets for a username
     *
     * @param string $username
     *
     * @return \StdClass
     *
     * @throws RequestException
     */
    public function get(string $username)
    {
        $client = new Client();

        $response = $client->get(self::USER_TWEET_ENDPOINT, [
            'query' => [
                'screen_name' => $username,
                'count' => 200,
                'trim_user' => 'ture',
                'exclude_replies' => 'ture',
            ],
            'headers' => [
                'Authorization' => "Bearer {$this->oauth->getBearerToken()}"
            ],
            'http_errors' => false,
        ]);

        switch ($response->getStatusCode()) {
            case 200:
                return json_decode($response->getBody()->getContents());
            case 401:
                throw new RequestException('User must have a public profile');
                break;
            case 404:
                throw new RequestException('User not found');
                break;
            default:
                throw new RequestException("Unknown error occured, received status code {$response->getStatusCode()}");
        }
    }

}