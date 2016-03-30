<?php
declare(strict_types = 1);

namespace LeagueFw\Template;

/**
 * This interface tries to create a basic interface for Template Engines.
 * The concrete implementation may add Engine specific functions, but then your app will be bound to that Engine.
 */
interface TemplateAwareInterface
{
    /**
     * Sets the 
     * 
     * @param EngineInterface $engine
     *
     * @return TemplateAwareInterface
     */
    public function setTemplateEngine(EngineInterface $engine) : TemplateAwareInterface;

    /**
     * @return EngineInterface
     */
    public function getTemplateEngine() : EngineInterface;
}