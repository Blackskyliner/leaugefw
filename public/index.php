<?php
declare(strict_types = 1);

use LeagueFw\Bootstrap;
use MyApp\Configs\Config;
use MyApp\Configs\Routes;
use MyApp\Configs\Services;

require_once __DIR__ . '/../vendor/autoload.php';

// You should adjust it to your application after testing with the example.
Bootstrap::createAndRun([
    new Config(),
    new Services(),
    new Routes()
]);
