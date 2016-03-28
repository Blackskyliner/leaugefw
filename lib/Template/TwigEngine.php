<?php
declare(strict_types = 1);

namespace LeagueFw\Template;

class TwigEngine implements EngineInterface
{
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
    public function render($template, $params)
    {
        return $this->environment->render($template, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function registerFilter($name, $callable)
    {
        $this->environment->addFilter($name, $callable);
    }

    /**
     * {@inheritdoc}
     */
    public function registerFunction($name, $callable)
    {
        $this->environment->addFunction($name, $callable);
    }

}