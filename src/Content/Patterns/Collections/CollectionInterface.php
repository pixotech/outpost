<?php

namespace Outpost\Content\Patterns\Collections;

interface CollectionInterface
{
    public function add($item);

    public function addAll(array $items);

    public function getItems();
}
