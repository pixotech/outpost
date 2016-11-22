<?php

namespace Outpost\Content;

use Outpost\Content\Builders\ObjectBuilder;
use Outpost\Reflection\Property;

class ContentBuilder extends ObjectBuilder
{
    public function __construct($className)
    {
        parent::__construct($className, $this->findContentProperties($className));
    }

    protected function findContentProperties($className)
    {
        $properties = [];
        foreach ($this->getReflection($className)->getProperties() as $property) {
            try {
                $prop = new Property($property);
                if (!$prop->getVariable() && !$prop->getCallback()) continue;
                $properties[$prop->getName()] = new PropertyBuilder($prop->getVariable(), $prop->getCallback());
            } catch (\DomainException $e) {
                continue;
            }
        }
        return $properties;
    }

    protected function getReflection($className)
    {
        return new \ReflectionClass($className);
    }

    protected function makeObject(array $properties)
    {
        $clas = $this->getReflection($this->className);
        $obj = $clas->newInstanceWithoutConstructor();
        foreach ($properties as $name => $value) {
            $property = $clas->getProperty($name);
            $property->setAccessible(true);
            $property->setValue($obj, $value);
        }
        return $obj;
    }
}
