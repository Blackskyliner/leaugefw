<?php
declare(strict_types = 1);

namespace LeagueFw\Template;

/**
 * Implements the TemplateAwareInterface
 */
trait TemplateAwareTrait
{
    /** @var EngineInterface */
    private $engine;

    /**
     * @param EngineInterface $engine
     *
     * @return TemplateAwareInterface
     */
    public function setTemplateEngine(EngineInterface $engine) : TemplateAwareInterface
    {
        $this->engine = $engine;
        
        return $this;
    }

    /**
     * @return EngineInterface
     */
    public function getTemplateEngine() : EngineInterface
    {
        return $this->engine;
    }

    /**
     * @param string $template
     * @param array $params
     *
     * @return string
     */
    protected function renderTemplate(string $template, array $params = []) : string
    {
        return $this->engine->render($template, $params);
    }
}