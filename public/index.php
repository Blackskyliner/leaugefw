<?php
declare(strict_types = 1);

use League\Container\ContainerAwareTrait;
use LeagueFw\Bootstrap;
use LeagueFw\ConfigurationInterface;
use MyApp\Configs\Config;
use MyApp\Configs\Routes;
use MyApp\Configs\Services;

require_once __DIR__ . '/../vendor/autoload.php';

// You should adjust it to your application after testing with the example.
Bootstrap::createAndRun([
    new Config(),
    new class implements ConfigurationInterface{
        use ContainerAwareTrait;
        public function __invoke() : ConfigurationInterface
        {
            $this->getContainer()->add('debug', true);
            return $this;
        }
    },
    new Services(),
    new Routes()
]);
