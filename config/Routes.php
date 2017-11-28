<?php

namespace Config;

/**
 * Route configuration
 */
abstract class Routes
{

    /**
     * Set application routes
     *
     * @return array
     */
    public static function get()
    {
        return [
            '/' => 'HelloController@index',
            '/hello/:name' => 'HelloController@greet',
            '/histogram/:username' => 'TweetHisotryController@histogram',
        ];
    }
}