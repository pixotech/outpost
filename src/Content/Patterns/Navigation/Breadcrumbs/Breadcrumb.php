<?php

namespace Outpost\Content\Patterns\Navigation\Breadcrumbs;

use Outpost\Content\Patterns\Navigation\Menus\ItemInterface;

class Breadcrumb implements BreadcrumbInterface
{
    public static function fromNavigationItem(ItemInterface $item)
    {
        return new static($item->getLabel(), $item->getUrl());
    }

    /**
     * @deprecated
     */
    public function getLink()
    {
        return $this->url;
    }

    /**
     * @deprecated
     */
    public function getTitle()
    {
        return $this->label;
    }

    public function isHere()
    {
        return false;
    }

    public function isHome()
    {
        return false;
    }
}
