<?php

namespace Outpost\Content\Patterns\Navigation\Breadcrumbs;

class Home extends Breadcrumb implements HomeInterface
{
    public function __construct($url = '/', $label = 'Home')
    {
        parent::__construct($label, $url);
    }

    public function isHome()
    {
        return true;
    }
}
