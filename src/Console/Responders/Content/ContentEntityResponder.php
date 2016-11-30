<?php

namespace Outpost\Console\Responders\Content;

use Outpost\Console\ConsoleSite;
use Symfony\Component\HttpFoundation\Request;

class ContentEntityResponder
{
    public function __invoke(ConsoleSite $site, Request $request)
    {
        $classes = $site->getSite()->getLibraryClasses();
        try {
            $entity = $classes->find($request->query->get("name"));
            $vars = $site->getTemplateVariables();
            $vars['entity'] = $entity;
            print $site->render("console/content/entity.twig", $vars);
        } catch (\OutOfBoundsException $e) {
            header("HTTP/1.0 404 Not Found");
            print "Not Found";
            return;
        }
    }
}
