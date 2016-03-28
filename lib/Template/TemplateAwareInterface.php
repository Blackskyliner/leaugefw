<?php
declare(strict_types = 1);

namespace LeagueFw\Template;

interface TemplateAwareInterface
{
    public function setTemplateEngine(EngineInterface $engine);

    public function getTemplateEngine();
}