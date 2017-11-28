<?php

namespace App\TwitterApi;

use GuzzleHttp\Client;

class Oauth
{

    /** Oauth Endpoint */
    const OAUTH_ENDPOINT = "https://api.twitter.com/oauth2/token";

    /** @var string */
    private $consumerKey;

    /** @var string */
    private $consumerSecret;


    /**
     * Constructor.
     *
     * @param string $consumerKey    Consumer Key
     * @param string $consumerSecret Consumer Secret Key
     */
    public function __construct($consumerKey, $consumerSecret)
    {
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
    }


    /**
     *  Get bearer token
     */
    public function getBearerToken()
    {
        $tokenPath = APP_ROOT . DS . '..' . DS . 'storage' . DS . 'oauth_token';

        if (file_exists($tokenPath)) {
            return file_get_contents($tokenPath);
        }

        $token = $this->fetchBearerToken();

        file_put_contents($tokenPath, $token);

        return $token;
    }


    /**
     *  Get bearer token from twitter oauth
     */
    private function fetchBearerToken()
    {
        $client = new Client();

        $response = $client->post(self::OAUTH_ENDPOINT, [
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