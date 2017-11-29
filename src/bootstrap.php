<?php

use App\TwitterApi\Oauth;
use DI\ContainerBuilder;

/**
 * Build the dependancy injection container
 */

$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions([
    Oauth::class => function() {
        return new Oauth(
            new \GuzzleHttp\Client(),
            Config\Twitter::get()['consumer_key'],
            Config\Twitter::get()['consumer_secret'],
            APP_ROOT . DS . '..' . DS . 'storage' . DS . 'oauth_token'
    );
    }
]);

$container = $containerBuilder->build();

return $container;
