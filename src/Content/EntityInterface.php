<?php

namespace Outpost\Content;

interface EntityInterface
{
    /**
     * @return PropertyInterface[]
     */
    public function getProperties();
}
