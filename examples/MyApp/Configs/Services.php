<?php
declare(strict_types = 1);

namespace MyApp\Configs;

use Illuminate\Contracts\Events\Dispatcher as EloquentEventDispatcherInterface;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Events\Dispatcher as EloquentEventDispatcher;
use League\Container\ContainerAwareTrait;
use LeagueFw\ConfigurationInterface;
use LeagueFw\Template\EngineInterface;
use LeagueFw\Template\TemplateAwareInterface;
use LeagueFw\Template\TwigEngine;
use MyApp\Actions\ContactAction;
use MyApp\Controllers\AboutController;

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
    protected function registerEloquent() : Services
    {
        // Register Eloquent
        $this->getContainer()->add('Eloquent', Manager::class);
        $this->getContainer()->share(EloquentEventDispatcherInterface::class, EloquentEventDispatcher::class);
        $this->getContainer()->share(Manager::class, function () {
            $capsule = new Manager;

            foreach ($this->getContainer()->get('eloquent_connections') as $name => $config) {
                $capsule->addConnection($config, $name);
            }

            $capsule->setEventDispatcher($this->getContainer()->get(EloquentEventDispatcherInterface::class));
            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            return $capsule;
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
            ->registerEloquent()
            ->registerSwiftMailer()
            ->registerTwig();
    }
}

