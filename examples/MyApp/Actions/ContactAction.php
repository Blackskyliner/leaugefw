<?php
declare(strict_types = 1);

namespace MyApp\Actions;

use LeagueFw\Template\TemplateAwareInterface;
use LeagueFw\Template\TemplateAwareTrait;

/**
 * Because of the ParamStrategy for the Route
 * Requires: https://github.com/thephpleague/container/pull/100
 *
 * @package MyApp\Actions
 */
class ContactAction implements TemplateAwareInterface
{
    use TemplateAwareTrait;

    public function __invoke() : string
    {
        return $this->renderTemplate('contact.html.twig');
    }
}