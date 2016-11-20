<?php

namespace Outpost\Console\Responders\Templates;

use Outpost\Console\ConsoleSite;

class TemplateIndexResponder
{
    public function __invoke(ConsoleSite $site)
    {
        $templates = $site->getSite()->getTemplates();
        $vars = [
            'stylesheet' => $site->getStylesheet(),
            'script' => $site->getScript(),
        ];
        if (!empty($_GET['template'])) {
            if (isset($templates[$_GET['template']])) {
                $vars['template'] = $templates[$_GET['template']];
                print $site->render("console/templates/template.twig", $vars);
            } else {
                header("HTTP/1.0 404 Not Found");
                print "Not found";
            }
        } else {
            $vars['templates'] = $templates;
            print $site->render("console/templates/index.twig", $vars);
        }

    }
}
