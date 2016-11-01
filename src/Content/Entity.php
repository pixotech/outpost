<?php

namespace Outpost\Content;

class Entity implements EntityInterface
{
    protected $properties = [];

    public static function fromFile($path)
    {

    }

    public static function fromClassName($className)
    {

    }

    public function getProperties()
    {
        return $this->properties;
    }
}
