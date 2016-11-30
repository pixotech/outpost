<?php

namespace Outpost\Console\Responders\Templates;

use Outpost\Console\ConsoleSite;

class TemplatePreviewResponder
{
    public function __invoke(ConsoleSite $site)
    {
        $templates = $site->getSite()->getTemplates();
        if (!empty($_GET['template'])) {
            try {
                $template = $templates->find($_GET['template']);
                $vars = [];
                if ($template->hasFixture()) {
                    $vars = $template->getFixture();
                }
                $preview = $site->getSite()->render($template->getTemplateName(), $vars);
                if ($templates->contains('_outpost/preview.twig')) {
                    $pym = $site->getPym();
                    $script = $site->render("console/templates/preview-script.twig", ['pym' => $pym]);
                    $preview = $site->getSite()->render("_outpost/preview.twig", ['preview' => $preview, 'script' => $script]);
                }
                print $preview;
                return;
            } catch (\OutOfBoundsException $e) {
                header("HTTP/1.0 404 Not Found");
                print "Not found";
            }
        }
        header("HTTP/1.0 404 Not Found");
        print "Not found";
    }
}
