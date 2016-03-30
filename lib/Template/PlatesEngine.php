<?php
declare(strict_types = 1);

use LeagueFw\Template\EngineInterface;

/**
 * This class implements the Plates Template Engine from ThePHPLeauge.
 */
class PlatesEngine implements EngineInterface
{
    /** @var \League\Plates\Engine  */
    protected $engine;

    /**
     * PlatesEngine constructor.
     *
     * @param \League\Plates\Engine $engine
     */
    public function __construct(League\Plates\Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * {@inheritdoc}
     */
    public function render($template, $params) : string
    {
        $this->engine->render($template, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function registerFilter($name, $callable) : EngineInterface
    {
        return $this->registerFunction($name, $callable);

    }

    /**
     * {@inheritdoc}
     */
    public function registerFunction($name, $callable) : EngineInterface
    {
        $this->engine->registerFunction($name, $callable);
        return $this;
    }

}