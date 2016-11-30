<?php

namespace Outpost\Content\Patterns\Collections;

interface SearchableCollectionInterface
{
    public function contains($name);

    public function find($name);
}
