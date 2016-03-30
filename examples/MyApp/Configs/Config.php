<?php
declare(strict_types = 1);

namespace MyApp\Configs;

use League\Container\ContainerAwareTrait;
use LeagueFw\ConfigurationInterface;

/**
 * This Configuration class registers all variable configuration values.
 */
class Config implements ConfigurationInterface
{
    use ContainerAwareTrait;

    public function __invoke() : ConfigurationInterface
    {
        // Set some directory defaults
        $this->getContainer()->add('vars_directory', realpath(__DIR__ . '/../../var'));

        // Configure Database Layer (Eloquent)
        $this->getContainer()->add('eloquent_connections', [
            'default' => [
                'prefix' => '',
                'driver' => 'sqlite',
                'charset' => 'utf8',
                'database' => $this->getContainer()->get('vars_directory') . '/database.sqlite',
            ]
        ]);

        // Register Twig TemplateLoader
        $this->getContainer()->add('twig_loaders', [
            new \Twig_Loader_Filesystem([
                realpath(__DIR__ . '/../Views')
            ])
        ]);
        
        return $this;
    }
}
