<?php

namespace Outpost\Responders;

use Outpost\SiteInterface;
use Symfony\Component\HttpFoundation\Request;

class RenderTask extends Task
{
    protected $template;

    public function __construct($template)
    {
        $this->template = $template;
    }

    public function __invoke(SiteInterface $site, Request $request, $context = null)
    {
        try {
            return $site->render($this->template, $context ? (array)$context : []);
        } catch (\Twig_Error $e) {
            throw new RenderException($this->template, $context, $e);
        }
    }
}