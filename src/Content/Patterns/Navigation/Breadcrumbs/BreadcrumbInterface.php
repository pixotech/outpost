<?php

namespace Outpost\Content\Patterns\Navigation\Breadcrumbs;

interface BreadcrumbInterface
{
    public function isHere();

    public function isHome();
}
