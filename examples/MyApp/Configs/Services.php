<?php
declare(strict_types = 1);

namespace MyApp\Configs;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use League\Container\ContainerAwareTrait;
use LeagueFw\ConfigurationInterface;
use LeagueFw\Template\EngineInterface;
use LeagueFw\Template\TemplateAwareInterface;
use LeagueFw\Template\TwigEngine;
use MyApp\Actions\ContactAction;
use MyApp\Controllers\AboutController;
use MyApp\Models\UserManager;

/**
 * This Configuration class registers all services within the application container.
 */
class Services implements ConfigurationInterface
{
    use ContainerAwareTrait;

    /**
     * @return Services
     */
    protected function registerControllers() : Services
    {
        // Register Controller/Actions
        $this->getContainer()->add(ContactAction::class, ContactAction::class);
        $this->getContainer()->add(AboutController::class, AboutController::class);

        return $this;
    }

    /**
     * @return Services
     */
    protected function registerDoctrine() : Services
    {
        // LiveCycle Events
        $this->getContainer()->share(EventManager::class, EventManager::class);

        // Annotation Stuff
        $this->getContainer()->share(Reader::class, function(){
            return new AnnotationReader();
        });

        // Configuration
        $this->getContainer()->share(Configuration::class, function () : Configuration {
            $config = Setup::createConfiguration(
                $this->getContainer()->get('debug'),
                null,
                $this->getContainer()->get(Cache::class)
            );

            $config->setMetadataDriverImpl(
                $this->getContainer()->get(MappingDriver::class)
            );

            $config->setAutoGenerateProxyClasses($this->getContainer()->get('debug'));

            // Shorthand Namespace for Annotation, Query and Repository Stuff
            $config->addEntityNamespace('MyApp', 'MyApp\\Models');

            return $config;
        });

        // Cache
        $this->getContainer()->share(Cache::class, function () : Cache {
            // (or no cache in this ArrayCache case)
            return new ArrayCache();
        });

        // Doctrine! No Really! The EntityManager ;)
        $this->getContainer()->share(EntityManagerInterface::class, function () : EntityManagerInterface {
            return EntityManager::create(
                $this->getContainer()->get('doctrine_connection'),
                $this->getContainer()->get(Configuration::class),
                $this->getContainer()->get(EventManager::class)
            );
        });
        
        // Now lets wrap doctrine
        $this->getContainer()->share(UserManager::class, function(){
            return new UserManager($this->container->get(EntityManagerInterface::class));
        });

        return $this;
    }

    /**
     * @return Services
     */
    protected function registerSwiftMailer() : Services
    {
        // Register Mailer
        $this->getContainer()->share(\Swift_Transport::class, function () {
            return \Swift_NullTransport::newInstance();
        });
        $this->getContainer()->share(\Swift_Mailer::class, function () {
            return \Swift_Mailer::newInstance($this->getContainer()->get(\Swift_Transport::class));
        });

        return $this;
    }

    /**
     * @return Services
     */
    protected function registerTwig() : Services
    {
        // Template Stuff
        // Register Twig
        $this->getContainer()->share(\Twig_LoaderInterface::class, function () {
            return new \Twig_Loader_Chain(
                $this->getContainer()->get('twig_loaders')
            );
        });
        $this->getContainer()->share(\Twig_Environment::class, function () {
            $env = new \Twig_Environment($this->getContainer()->get(\Twig_LoaderInterface::class));

            return $env;
        });
        // Register TemplateEngine
        $this->getContainer()->share(EngineInterface::class, function () {
            return new TwigEngine(
                $this->getContainer()->get(\Twig_Environment::class)
            );
        });
        $this->getContainer()
            ->inflector(TemplateAwareInterface::class)
            ->invokeMethod('setTemplateEngine', [EngineInterface::class]);

        return $this;
    }

    /**
     * @return ConfigurationInterface
     */
    public function __invoke() : ConfigurationInterface
    {
        return $this->registerControllers()
            ->registerDoctrine()
            ->registerSwiftMailer()
            ->registerTwig();
    }
}

