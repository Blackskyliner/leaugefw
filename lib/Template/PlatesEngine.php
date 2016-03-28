<?php
declare(strict_types = 1);

use LeagueFw\Template\EngineInterface;

class PlatesEngine implements EngineInterface
{
    protected $engine;

    public function __construct(League\Plates\Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * {@inheritdoc}
     */
    public function render($template, $params)
    {
        $this->engine->render($template, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function registerFilter($name, $callable)
    {
        $this->registerFunction($name, $callable);
    }

    /**
     * {@inheritdoc}
     */
    public function registerFunction($name, $callable)
    {
        $this->engine->registerFunction($name, $callable);
    }

}