<?php
declare(strict_types = 1);

namespace LeagueFw;

use League\Container\Container;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerInterface;
use League\Container\ImmutableContainerAwareInterface;
use League\Route\RouteCollection;
use League\Route\Strategy\ParamStrategy;
use League\Route\Strategy\StrategyAwareInterface;
use League\Route\Strategy\StrategyInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Whoops\Handler\HandlerInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\EmitterInterface;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Encapsulate the Bootstrap process in its own class/function so we don't leak variables to the global scope.
 * Through extending we also allow for modification of the whole bootstrap process, without rewriting tedious parts.
 * Like initializing basic DI Container functionality or configuration stuff.
 */
class Bootstrap
{
    /** @var ContainerInterface */
    protected $container;

    /**
     * Contains the different flags about the state of the bootstrapping.
     *
     * Available Keys:
     * - application: Defined if the ConfigurationInterface's were already called/registered.
     * - container: Defined if the Container was already created.
     * - kernel: Defined if the Kernel was already created.
     *
     * @var array
     */
    protected $booted = [
        'application' => false,
        'container' => false,
        'kernel' => false,
    ];

    /** @var \ArrayObject|ConfigurationInterface[] */
    private $ci;

    /** @var Kernel */
    private $kernel;

    /**
     * Bootstrap constructor.
     *
     * @param array $configurationInterfaces
     */
    public function __construct(array $configurationInterfaces = [])
    {
        $this->ci = new \ArrayObject($configurationInterfaces);
    }

    /**
     * This function bootstraps the application.
     * It set's up the basic environment, creates the Request object and calls into the Kernel.
     *
     * Application specific configurations can be achieved through the 3 parameters.
     *
     * @param array|ConfigurationInterface[] $configurationInterfaces
     *
     * @return KernelInterface
     */
    public static function createAndRun(array $configurationInterfaces = []) : KernelInterface
    {
        return self::create($configurationInterfaces)->run();
    }

    /**
     * Creates the application, but won't boot, nor run it.
     *
     * @param array $configurationInterfaces
     *
     * @return Bootstrap
     */
    public static function create(array $configurationInterfaces = []) : Bootstrap
    {
        return new self($configurationInterfaces);
    }

    /**
     * Boot the basic DI Container.
     */
    public function boot() : Bootstrap
    {
        return $this->createContainer()
            ->registerExceptionHandler()
            ->registerContainerReflection()
            ->registerRouter()
            ->registerKernel()
            ->registerConfigurationInterfaces();
    }

    /**
     * @return Bootstrap
     */
    protected function registerConfigurationInterfaces() : Bootstrap
    {
        if ($this->booted['application']) {
            return $this;
        }

        // Call Configurations
        foreach ($this->ci as $ci) {
            $ci->setContainer($this->container);
            call_user_func($ci);
        }

        $this->booted['application'];

        return $this;
    }

    /**
     * @return Bootstrap
     */
    protected function registerKernel() : Bootstrap
    {
        // Early test if we are already booted/registered.
        if ($this->booted['kernel']) {
            return $this;
        }

        // Register Request/Response
        $this->container->share(
            ResponseInterface::class,
            function () {
                return $this->container->get('response');
            }
        );
        $this->container->share(
            RequestInterface::class,
            function () {
                return $this->container->get('request');
            }
        );

        $this->container->share('response', Response::class);
        $this->container->share(
            'request',
            function () {
                return ServerRequestFactory::fromGlobals(
                    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
                );
            }
        );

        // Register Emitter
        $this->container->share(EmitterInterface::class, SapiEmitter::class);

        // Create our kernel
        $kernel = new Kernel();
        $kernel->setContainer($this->container);
        $this->setKernel($kernel);

        // The container is intentionally not added to the Container.
        // If it is really necessary to use the Kernel within the Application, just overwrite this Bootstrap
        // And add the needed functionality to it, the Kernel is protected accessible for this specific case.

        // Set booted flag for kernel.
        $this->booted['kernel'] = true;

        return $this;
    }

    /**
     * @return Bootstrap
     */
    protected function registerRouter() : Bootstrap
    {
        if (!$this->container->has('router')) {
            // Register Router + Strategy
            $this->container
                ->inflector(StrategyAwareInterface::class)
                ->invokeMethod(
                    'setStrategy',
                    [StrategyInterface::class]
                );
            $this->container
                ->share(StrategyInterface::class, ParamStrategy::class);
            $this->container
                ->share(RouteCollection::class, RouteCollection::class)
                ->withArgument($this->container);
            $this->container
                ->share(
                    'router',
                    function () {
                        return $this->container->get(RouteCollection::class);
                    }
                );
        }

        return $this;
    }

    /**
     * @return Bootstrap
     */
    protected function registerContainerReflection() : Bootstrap
    {
        // Container Reflections if not already registered
        if (!$this->container->has(Container::class)) {
            $this->container
                ->share(Container::class, $this->container);
            $this->container
                ->inflector(ImmutableContainerAwareInterface::class)
                ->invokeMethod('setContainer', [Container::class]);
            $this->container
                ->inflector(ContainerAwareInterface::class)
                ->invokeMethod('setContainer', [Container::class]);
        }

        return $this;
    }

    /**
     * @return Bootstrap
     */
    protected function registerExceptionHandler() : Bootstrap
    {
        // Better Exception Handling.
        /*
        $runner = new Runner();
        $runner->pushFormatter(new HtmlTableFormatter());
        $runner->setErrorPageFormatter(new HtmlTableFormatter());
        $runner->register();
        $this->container->share(Runner::class, $runner);
        */

        // Because leauge/booboo is not PHP7 compatible
        $whoopsFormatter = new PrettyPageHandler();
        $whoopsFormatter->setEditor('phpstorm');

        $whoops = new Run();
        $whoops->pushHandler($whoopsFormatter);
        $whoops->register();

        $this->container->share(Run::class, $whoops);
        $this->container->share(HandlerInterface::class, $whoopsFormatter);

        return $this;
    }

    /**
     * @return Bootstrap
     */
    protected function createContainer() : Bootstrap
    {
        if ($this->booted['container']) {
            return $this;
        }

        // Construct Container
        $this->container = new Container();
        $this->booted['container'] = true;

        return $this;
    }

    /**
     * Run the application.
     *
     * It will boot the DI-Container and create the Kernel if both are not already there.
     * It will then call all registered ConfigurationInterfaces.
     * Finally the Request gets hand down into the Kernel::handle to get the Application up and running.
     *
     * @return KernelInterface
     */
    public function run() : KernelInterface
    {
        return $this
            ->boot()
            ->getKernel()
            ->handle($this->container->get('request'));
    }

    /**
     * @param ConfigurationInterface $ci
     *
     * @return Bootstrap
     */
    public function addConfigurationInterface(ConfigurationInterface $ci) : Bootstrap
    {
        $this->ci->append($ci);

        if ($this->booted['application']) {
            // If the application is already booted, directly register the given interface.
            $ci->setContainer($this->container);
            call_user_func($ci);
        }
        return $this;
    }

    /**
     * @param KernelInterface $kernel
     *
     * @return Bootstrap
     */
    private function setKernel(KernelInterface $kernel) : Bootstrap
    {
        $this->kernel = $kernel;

        return $this;
    }

    /**
     * @return KernelInterface
     */
    protected function getKernel() : KernelInterface
    {
        return $this->kernel;
    }

    /**
     * @return \ArrayObject|ConfigurationInterface[]
     */
    protected function getConfigurationInterfaces() : \ArrayObject
    {
        return $this->ci;
    }
}
