<?php

namespace Outpost\Content\Patterns\Collections;

interface CollectionInterface
{
    /**
     * @return ItemInterface[]
     */
    public function all();
}
