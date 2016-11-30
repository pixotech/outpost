<?php

namespace Outpost\Content\Patterns\Collections;

class Collection implements \Countable, \IteratorAggregate, CollectionInterface
{
    protected $items = [];

    protected $sorted = true;

    public function __construct(array $items = [])
    {
        $this->addAll($items);
    }

    public function add($item)
    {
        $this->items[] = $item;
        $this->sorted = false;
    }

    public function addAll(array $items)
    {
        array_map([$this, 'add'], $items);
    }

    public function count()
    {
        return count($this->items);
    }

    public function getItems()
    {
        $this->sortIfUnsorted();
        return $this->items;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->getItems());
    }

    protected function getSortCallback()
    {
        return null;
    }

    protected function sort()
    {
        if (is_callable($callback = $this->getSortCallback())) {
            usort($this->items, $callback);
        }
        $this->sorted = true;
    }

    protected function sortIfUnsorted()
    {
        if (!$this->sorted) $this->sort();
    }
}
