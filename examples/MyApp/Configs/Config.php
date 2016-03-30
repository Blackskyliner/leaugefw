<?php
declare(strict_types = 1);

namespace MyApp\Configs;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use League\Container\ContainerAwareTrait;
use LeagueFw\ConfigurationInterface;

/**
 * This Configuration class registers all variable configuration values.
 */
class Config implements ConfigurationInterface
{
    use ContainerAwareTrait;

    /**
     * @return Config
     */
    protected function registerDoctrine() : Config {
        // Configure Database Layer (Doctrine)
        $this->getContainer()->add('doctrine_connection', [
            'url' => 'sqlite://'.$this->getContainer()->get('vars_directory') . '/database.sqlite',
        ]);

        // Doctrine Metatadata Driver
        $this->getContainer()->share(MappingDriver::class, function(){
            // register the doctrine annotations
            AnnotationRegistry::registerFile(
                realpath(
                    __DIR__.'/../../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'
                )
            );

            return AnnotationDriver::create([
                realpath(__DIR__ . '/../Models')
            ]);
        });

        return $this;
    }

    /**
     * @return ConfigurationInterface
     */
    public function __invoke() : ConfigurationInterface
    {
        // Debug Toggle
        $this->getContainer()->add('debug', false);

        // Set some directory defaults
        $this->getContainer()->add('vars_directory', realpath(__DIR__ . '/../../../var'));

        // Register Twig TemplateLoader
        $this->getContainer()->add('twig_loaders', [
            new \Twig_Loader_Filesystem([
                realpath(__DIR__ . '/../Views')
            ])
        ]);

        $this->registerDoctrine();
        
        return $this;
    }
}
