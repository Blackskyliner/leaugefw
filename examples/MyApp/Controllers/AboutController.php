<?php
declare(strict_types = 1);

namespace MyApp\Controllers;

use LeagueFw\Template\TemplateAwareInterface;
use LeagueFw\Template\TemplateAwareTrait;

/**
 * Class AboutController
 *
 * @package MyApp\Controllers
 */
class AboutController implements TemplateAwareInterface
{
    use TemplateAwareTrait;

    /**
     * @return string
     */
    public function indexAction() : string
    {
        return $this->renderTemplate('about.html.twig');
    }
}