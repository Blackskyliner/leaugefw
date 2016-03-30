<?php
declare(strict_types = 1);

namespace LeagueFw\Template;

/**
 * This interface ins an interop interface for template Engines.
 * It tries to create a basic interface for Template Engines.
 * The concrete implementation may add Engine specific functions, but then your app will be bound to that Engine.
 */
interface EngineInterface
{
    /**
     * Renders the given template and returns the result as string.
     *
     * @param string $template Name/Path of the template
     * @param array  $params   Parameters/Context of the template
     *
     * @return string Rendered Template
     */
    public function render($template, $params) : string;

    /**
     * Register an "filter" callable, if the engine does not support filters, it may register it as function.
     *
     * @param string   $name     Name of the filter
     * @param callable $callable Filter function/callable
     *
     * @return EngineInterface
     */
    public function registerFilter($name, $callable) : EngineInterface;

    /**
     * Register an callable as function in the template engine.
     *
     * @param string   $name     Name of the function
     * @param callable $callable Function callable
     *
     * @return EngineInterface
     */
    public function registerFunction($name, $callable) : EngineInterface;
}