<?php

namespace Outpost\Content\Patterns\Navigation\Breadcrumbs;

class Here extends Breadcrumb implements HereInterface
{
    public function __construct($label = null, $url = null)
    {
        parent::__construct($url, $label);
    }

    public function isHere()
    {
        return true;
    }
}
