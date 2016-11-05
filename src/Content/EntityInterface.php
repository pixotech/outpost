<?php

namespace Outpost\Content;

use Outpost\Content\Properties\PropertyInterface;

interface EntityInterface
{
    /**
     * @return PropertyInterface[]
     */
    public function getProperties();
}
