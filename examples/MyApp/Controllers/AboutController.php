<?php
declare(strict_types = 1);

namespace MyApp\Controllers;

use LeagueFw\Template\TemplateAwareInterface;
use LeagueFw\Template\TemplateAwareTrait;

class AboutController implements TemplateAwareInterface
{
    use TemplateAwareTrait;

    public function indexAction()
    {
        return $this->renderTemplate('about.html.twig');
    }
}