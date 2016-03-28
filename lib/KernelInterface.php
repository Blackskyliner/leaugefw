<?php
declare(strict_types = 1);

namespace LeagueFw;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface KernelInterface
{
    /**
     * Handle the given request.
     *
     * @param ServerRequestInterface $request
     *
     * @return KernelInterface
     */
    public function handle(ServerRequestInterface $request);

    /**
     * Will be called when the Kernel terminates.
     * It will flush the current Response through the wired ResponseEmitter.
     *
     * @param ServerRequestInterface  $request
     * @param ResponseInterface $response
     *
     * @return KernelInterface
     */
    public function terminate(ServerRequestInterface $request, ResponseInterface $response);
}