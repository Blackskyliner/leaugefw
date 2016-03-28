<?php
declare(strict_types = 1);

namespace MyApp\Actions;

use LeagueFw\Template\TemplateAwareInterface;
use LeagueFw\Template\TemplateAwareTrait;

class ContactAction implements TemplateAwareInterface
{
    use TemplateAwareTrait;

    public function __invoke()
    {
        return $this->renderTemplate('contact.html.twig');
    }
}