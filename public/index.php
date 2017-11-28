<?php
require __DIR__ . '/../vendor/autoload.php';

$container = require __DIR__ . '/../src/bootstrap.php';

$framework = new \App\Core\Framework($container);