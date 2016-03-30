<?php
declare(strict_types = 1);

namespace LeagueFw\Template;

/**
 * This class implements the Twig Template Engine from SensioLabs.
 */
class TwigEngine implements EngineInterface
{
    /** @var \Twig_Environment  */
    protected $environment;

    /**
     * TwigEngine constructor.
     *
     * @param \Twig_Environment $env
     */
    public function __construct(\Twig_Environment $env)
    {
        $this->environment = $env;
    }

    /**
     * {@inheritdoc}
     */
    public function render($template, $params) : string
    {
        return $this->environment->render($template, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function registerFilter($name, $callable) : EngineInterface
    {
        $this->environment->addFilter($name, $callable);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function registerFunction($name, $callable) : EngineInterface
    {
        $this->environment->addFunction($name, $callable);
        return $this;
    }

}