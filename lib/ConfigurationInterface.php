<?php
declare(strict_types = 1);

namespace LeagueFw;

use League\Container\ContainerAwareInterface;

interface ConfigurationInterface extends ContainerAwareInterface
{
    public function __invoke();
}