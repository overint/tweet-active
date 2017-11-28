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
            Config\Twitter::get()['consumer_key'],
            Config\Twitter::get()['consumer_secret']
        );
    }
]);

$container = $containerBuilder->build();

return $container;