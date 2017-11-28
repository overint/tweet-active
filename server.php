<?php

/**
 *  For use with PHP built in web server
 *  php -S localhost:8000 server.php
 */

$path = parse_url($_SERVER['REQUEST_URI'])['path'];

if ($path !== '/' && file_exists(__DIR__ . "/public/$path")) {
    return false;
} else {
    include_once __DIR__ . '/public/index.php';
    return true;
}
