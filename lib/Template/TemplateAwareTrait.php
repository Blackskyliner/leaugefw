<?php
declare(strict_types = 1);

namespace LeagueFw\Template;

trait TemplateAwareTrait
{
    /** @var EngineInterface */
    private $engine;

    public function setTemplateEngine(EngineInterface $engine)
    {
        $this->engine = $engine;
    }

    public function getTemplateEngine()
    {
        return $this->engine;
    }

    protected function renderTemplate($template, array $params = [])
    {
        return $this->engine->render($template, $params);
    }
}