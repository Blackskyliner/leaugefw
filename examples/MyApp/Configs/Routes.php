<?php
declare(strict_types = 1);

namespace MyApp\Configs;

use League\Container\ContainerAwareTrait;
use League\Route\RouteCollectionInterface;
use LeagueFw\ConfigurationInterface;
use LeagueFw\Template\EngineInterface;
use MyApp\Actions\ContactAction;
use MyApp\Controllers\AboutController;

/**
 * This Configuration class registers all routes on the router of the application.
 */
class Routes implements ConfigurationInterface
{
    use ContainerAwareTrait;
    
    public function __invoke() : ConfigurationInterface
    {
        /** @var RouteCollectionInterface $router */
        $router = $this->getContainer()->get('router');

        $router->get(
            '/',
            function (EngineInterface $template) {
                return $template->render('home.html.twig', []);
            }
        );

        $router->get('/about', array($this->getContainer()->get(AboutController::class), 'indexAction'));
        $router->get('/contact', $this->getContainer()->get(ContactAction::class));

        return $this;
    }

}
