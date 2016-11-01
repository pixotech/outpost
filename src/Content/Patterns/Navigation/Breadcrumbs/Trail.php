<?php

namespace Outpost\Content\Patterns\Navigation\Breadcrumbs;

use Outpost\Content\Patterns\Navigation\Menus\ItemInterface;

class Trail implements TrailInterface, \Countable, \IteratorAggregate
{
    protected $crumbs = [];

    public static function fromNavigationItem(ItemInterface $item)
    {
        $items = [$item];
        while ($item->hasParent()) {
            $item = $item->getParent();
            $items[] = $item;
        }
        $items = array_reverse($items);
        $trail = new static();
        $trail->setHome(new Home());
        foreach ($items as $item) {
            $trail->addBreadcrumb(Breadcrumb::fromNavigationItem($item));
        }
        return $trail;
    }

    public function addBreadcrumb(BreadcrumbInterface $crumb)
    {
        $this->crumbs[] = $crumb;
    }

    public function count()
    {
        return count($this->crumbs);
    }

    public function getBreadcrumbs()
    {
        return $this->crumbs;
    }

    public function getHere()
    {
        return $this->hasHere() ? end($this->crumbs) : null;
    }

    public function getHome()
    {
        return $this->hasHome() ? reset($this->crumbs) : null;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->getBreadcrumbs());
    }

    public function hasHere()
    {
        return count($this->crumbs) > 1;
    }

    public function hasHome()
    {
        return count($this->crumbs) > 2;
    }

    public function setHere(BreadcrumbInterface $here)
    {
        array_push($this->crumbs, $here);
    }

    public function setHome(BreadcrumbInterface $home)
    {
        array_unshift($this->crumbs, $home);
    }
}
