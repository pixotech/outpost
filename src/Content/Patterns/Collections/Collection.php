<?php

namespace Outpost\Content\Patterns\Collections;

abstract class Collection implements CollectionInterface, \Countable, \IteratorAggregate
{
    protected $items = [];

    protected $sorted = false;

    public function __construct(array $items = [])
    {
        array_map([$this, 'add'], $items);
    }

    public function add($item)
    {
        $this->items[] = $item;
        $this->sorted = false;
    }

    public function all()
    {
        $this->sortIfUnsorted();
        return $this->items;
    }

    public function count()
    {
        return count($this->items);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->all());
    }

    protected function isSorted()
    {
        return $this->sorted;
    }

    protected function sort() {
        $this->sorted = true;
    }

    protected function sortIfUnsorted()
    {
        if (!$this->isSorted()) {
            $this->sort();
        }
    }
}
