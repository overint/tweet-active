# tweet-active
An application determine what hour of the day a twitter user is most active.

### Architecture 
Custom microframework built from scratch (only dependencies are guzzle, for HTTP requests
 to the twitter API, and PHP-DI for the dependancy injection).    
There is full unit test coverage on the controllers & Twitter API classes, however the framework
 is currently not tested.

## Running tweet-active
 - Run `composer install` to install the dependencies
 - Run `php -S localhost:8000 server.php` to serve the site using the built in PHP server

## Running Tests
 - Run `composer install` to install the dependencies (not required if done above)
 - Run PHPunit with the config file
 	- Windows: `php path/to/phpunit.phar --configuration tests/phpunit.xml`
 	- Linux: `./vendor/bin/phpunit --configuration tests/phpunit.xml`