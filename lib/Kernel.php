<?php
declare(strict_types = 1);

namespace LeagueFw;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Route\Http\Exception;
use League\Route\RouteCollection;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\EmitterInterface;

/**
 * The Kernel is the main entry point of an application.
 * It will handle the Request and ask the Router component of the Application to resolve it.
 *
 * After handling it will {@link Kernel::terminate} and send the resulting Response to the client.
 *
 * @package LeagueFw
 */
class Kernel implements ContainerAwareInterface, KernelInterface
{
    use ContainerAwareTrait;

    /**
     * Handle the given request.
     *
     * @param ServerRequestInterface $request
     *
     * @return Kernel
     */
    public function handle(ServerRequestInterface $request)
    {
        /** @var ResponseInterface $response */
        $response = $this->getContainer()->get('response');

        try {
            $this->getRouter()->dispatch($request, $response);
        } catch (Exception $e) {
            // TODO: Frontend Friendly handling?
            $response = $response->withStatus($e->getStatusCode());
            $response->getBody()->write($e->getMessage());
        }

        $this->terminate($request, $response);

        return $this;
    }

    /**
     * Will be called when the Kernel terminates.
     * It will flush the current Response through the wired ResponseEmitter.
     *
     * @param ServerRequestInterface  $request
     * @param ResponseInterface $response
     *
     * @return Kernel
     */
    public function terminate(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->getResponseEmitter()->emit($response);

        return $this;
    }

    /**
     * Returns the ResponseEmitter from the configured Container.
     *
     * @return EmitterInterface
     */
    protected function getResponseEmitter()
    {
        return $this->getContainer()->get(EmitterInterface::class);
    }

    /**
     * Returns the Router(Collection) from the configured Container.
     *
     * @return RouteCollection
     */
    protected function getRouter()
    {
        return $this->getContainer()->get('router');
    }
}
