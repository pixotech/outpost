<?php

namespace Outpost\Console\Responders;

use Outpost\Console\ConsoleSite;

class DashboardResponder
{
    public function __invoke(ConsoleSite $site)
    {
        $vars = [
            'stylesheet' => $site->getStylesheet(),
            'script' => $site->getScript(),
        ];
        print $site->render("console/index.twig", $vars);
    }
}
