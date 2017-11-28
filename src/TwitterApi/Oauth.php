<?php

namespace App\TwitterApi;

use GuzzleHttp\Client;

/**
 * Gets Twitter Oauth Tokens
 */
class Oauth
{

    /** Oauth Endpoint */
    const OAUTH_ENDPOINT = "https://api.twitter.com/oauth2/token";

    /** @var Client Guzzle Client */
    private $client;

    /** @var string */
    private $consumerKey;

    /** @var string */
    private $consumerSecret;

    /** @var string */
    private $cachePath;


    /**
     * Constructor.
     *
     * @param Client $client         Guzzle Client
     * @param string $consumerKey    Consumer Key
     * @param string $consumerSecret Consumer Secret Key
     * @param null   $cachePath      Path to save key to
     */
    public function __construct(Client $client, $consumerKey, $consumerSecret, $cachePath = null)
    {
        $this->client = $client;
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        $this->cachePath = $cachePath;
    }


    /**
     *  Get bearer token, optinal caching
     */
    public function getBearerToken()
    {

        if ($this->cachePath && file_exists($this->cachePath)) {
            return file_get_contents($this->cachePath);
        }

        $token = $this->fetchBearerToken();

        if ($this->cachePath) {
            file_put_contents($this->cachePath, $token);
        }

        return $token;
    }


    /**
     *  Get bearer token from twitter oauth
     */
    private function fetchBearerToken()
    {
        $response = $this->client->post(self::OAUTH_ENDPOINT, [
            'auth' => [$this->consumerKey, $this->consumerSecret],
            'headers' => [
                'User-Agent' => 'Tweetactive v1',
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
            ]
        ]);

        $jsonResponse = json_decode($response->getBody()->getContents());

        return $jsonResponse->access_token;
    }

}