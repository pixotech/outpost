<?php

namespace Outpost\Content\Patterns\Navigation\Menus;

class Item implements ItemInterface, \Countable, \IteratorAggregate
{
    /**
     * @var bool
     */
    protected $active = false;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var MenuInterface
     */
    protected $menu;

    /**
     * @var ItemInterface
     */
    protected $parent;

    /**
     * @var string
     */
    protected $url;

    public function __construct()
    {
        $this->menu = $this->makeMenu();
    }

    public function __toString()
    {
        return (string)($this->getLabel() ?: $this->getUrl());
    }

    public function add(ItemInterface $item)
    {
        $item->setParent($this);
        $this->getMenu()->add($item);
    }

    public function classes()
    {
        $classes = [];
        if ($this->isActive()) {
            $classes[] = 'current-menu-item';
        }
        if ($this->isActive() || $this->hasActiveItem()) {
            $classes[] = 'current-page-ancestor';
        }
        if ($this->count()) {
            $classes[] = 'menu-item-has-children';
        }
        if ($this->hasSpotlight()) {
            $classes[] = 'has-spotlight';
        }
        return $classes;
    }

    public function clearActive()
    {
        $this->active = false;
        $this->getMenu()->clearActiveUrl();
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->getMenu()->count();
    }

    public function current()
    {
        return $this->isActive();
    }

    public function current_item_ancestor()
    {
        return $this->hasActiveItem();
    }

    public function getClass()
    {
        return implode(' ', $this->classes());
    }

    public function getIterator()
    {
        return new \IteratorIterator($this->getMenu());
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @return ItemInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return bool
     */
    public function hasActiveItem()
    {
        return $this->getMenu()->hasActiveItem();
    }

    /**
     * @return bool
     */
    public function hasMenu()
    {
        return $this->menu->count() > 0;
    }

    /**
     * @return bool
     */
    public function hasParent()
    {
        return !empty($this->parent);
    }

    /**
     * @return bool;
     */
    public function hasSpotlight()
    {
        return !empty($this->spotlight);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    public function isExternal()
    {
        return false;
    }

    /**
     * @param bool $active
     */
    public function setActive($active = true)
    {
        $this->active = $active;
    }

    public function setActiveUrl($url)
    {
        $this->active = $url == $this->getUrl();
        $this->getMenu()->setActiveUrl($url);
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function setMenu(MenuInterface $menu)
    {
        $this->menu = $menu;
    }

    /**
     * @param ItemInterface $item
     */
    public function setParent(ItemInterface $item)
    {
        $this->parent = $item;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    protected function makeMenu()
    {
        return new Menu();
    }
}
