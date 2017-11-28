<?php

namespace Config;

/**
 * Class Twitter
 * @package Config
 */
class Twitter
{

    /**
     * Gets twitter config settings
     * You should never commit secrets into version control, but I am doing so for demo purposes
     *
     * @return array
     */
    public static function get()
    {
        return [
            'consumer_key' => '1lJpTGgv7DFH94qq9M8OYresJ',
            'consumer_secret' => 'rBIfEvAQVlc3H1z1lcEo7x9RYfeWj1BQjUewFRHT7wzRYcvvh1',
        ];
    }
}