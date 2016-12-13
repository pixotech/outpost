<?php

namespace Outpost\Templates;

trait ContextTrait
{
    public function getTemplateContext()
    {
        $context = [];
        $obj = new \ReflectionObject($this);
        foreach ($obj->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $context[$property->getName()] = $property->getValue($this);
        }
        return $context;
    }
}
