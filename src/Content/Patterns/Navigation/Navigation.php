<?php

namespace Outpost\Content\Patterns\Navigation;

use Outpost\Content\Patterns\Navigation\Menus\MenuInterface;

class Navigation implements NavigationInterface, \ArrayAccess, \IteratorAggregate
{
    protected $activeUrl;

    /**
     * @var MenuInterface[]
     */
    protected $menus = [];

    public function __construct($activeUrl = null)
    {
        $this->activeUrl = $activeUrl;
    }

    public function addMenu($name, MenuInterface $menu)
    {
        $menu = clone $menu;
        $menu->setActiveUrl($this->getActiveUrl());
        $this->menus[$name] = $menu;
    }

    public function getActiveMenu()
    {
        foreach ($this->menus as $menu) {
            if ($menu->hasActiveItem()) return $menu;
        }
        return null;
    }

    public function getActiveUrl()
    {
        return $this->activeUrl;
    }

    public function getBreadcrumbs()
    {
        if (!$menu = $this->getActiveMenu()) return null;
        return $menu->getBreadcrumbs();
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->menus);
    }

    public function getMenu($name)
    {
        return $this->hasMenu($name) ? $this->menus[$name] : null;
    }

    public function hasMenu($name)
    {
        return array_key_exists($name, $this->menus);
    }

    public function offsetExists($name)
    {
        return $this->hasMenu($name);
    }

    public function offsetGet($name)
    {
        return $this->getMenu($name);
    }

    public function offsetSet($name, $menu)
    {
        $this->addMenu($name, $menu);
    }

    public function offsetUnset($name)
    {
        $this->unsetMenu($name);
    }

    public function unsetMenu($name)
    {
        unset($this->menus[$name]);
    }
}
