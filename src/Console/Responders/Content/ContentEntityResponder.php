<?php

namespace Outpost\Console\Responders\Content;

use Outpost\Console\ConsoleSite;
use Symfony\Component\HttpFoundation\Request;

class ContentEntityResponder
{
    public function __invoke(ConsoleSite $site, Request $request)
    {
        $classes = $site->getSite()->getLibraryClasses();
        if (!isset($classes[$request->query->get("name")])) {
            header("HTTP/1.0 404 Not Found");
            print "Not Found";
            return;
        }
        $entity = $classes[$request->query->get("name")];
        $vars = [
            'stylesheet' => $site->getStylesheet(),
            'script' => $site->getScript(),
        ];
        $vars['entity'] = $entity;
        print $site->render("console/content/entity.twig", $vars);
    }
}
