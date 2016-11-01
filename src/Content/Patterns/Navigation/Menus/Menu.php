<?php

namespace Outpost\Content\Patterns\Navigation\Menus;

use Outpost\Content\Patterns\Navigation\Breadcrumbs\Trail;

class Menu implements \Countable, \RecursiveIterator, MenuInterface
{
    protected $id;

    /**
     * @var ItemInterface[]
     */
    protected $items = [];

    /**
     * @var string
     */
    protected $label;

    /**
     * @var int
     */
    private $position = 0;

    public function __clone()
    {
        foreach ($this->items as $i => $item) {
            $this->items[$i] = clone $item;
        }
    }

    /**
     * @param ItemInterface $item
     */
    public function add(ItemInterface $item)
    {
        $this->items[] = $item;
    }

    public function clearActiveUrl()
    {
        foreach ($this->getItems() as $item) {
            $item->clearActiveUrl();
        }
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @return ItemInterface
     */
    public function current()
    {
        return $this->items[$this->position];
    }

    public function getActiveItem()
    {
        $iterator = new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator as $item) {
            if ($item->isActive()) return $item;
        }
        return null;
    }

    public function getActiveSection()
    {
        foreach ($this->getItems() as $item)
        {
            if ($item->isActive() || $item->hasActiveItem()) {
                return $item;
            }
        }
        return null;
    }

    public function getBreadcrumbs()
    {
        if (!$this->hasActiveItem()) return null;
        if (!$active = $this->getActiveItem()) return null;
        return Trail::fromNavigationItem($active);
    }

    public function getChildren()
    {
        return $this->current()->getMenu();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getName()
    {
        return $this->getLabel();
    }

    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return bool
     */
    public function hasActiveItem()
    {
        foreach ($this->getItems() as $item) {
            if ($item->isActive()) return true;
            if ($item->getMenu()->hasActiveItem()) return true;
        }
        return false;
    }

    public function hasChildren()
    {
        return !$this->current()->getMenu()->isEmpty();
    }

    public function hasItems()
    {
        return !$this->isEmpty();
    }

    public function hasLabel()
    {
        return !empty($this->label);
    }

    public function isEmpty()
    {
        return empty($this->items);
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function setActiveUrl($url)
    {
        foreach ($this->getItems() as $item) {
            $item->setActiveUrl($url);
        }
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function valid()
    {
        return $this->position < $this->count();
    }
}
