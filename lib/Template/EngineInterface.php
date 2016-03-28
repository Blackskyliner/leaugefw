<?php
declare(strict_types = 1);

namespace LeagueFw\Template;

/**
 * This interface ins an interop Interface for template engines.
 */
interface EngineInterface
{
    /**
     * Renders the given template and returns the Result as string.
     *
     * @param string $template Name/Path of the template
     * @param array  $params   Parameters/Context of the template
     *
     * @return string Rendered Template
     */
    public function render($template, $params);

    /**
     * Register an "filter" callable, if the engine does not support filters, it may register it as function.
     *
     * @param string   $name     Name of the filter
     * @param callable $callable Filter function/callable
     *
     * @return void
     */
    public function registerFilter($name, $callable);

    /**
     * Register an callable as function in the template engine.
     *
     * @param string   $name     Name of the function
     * @param callable $callable Function callable
     *
     * @return void
     */
    public function registerFunction($name, $callable);
}