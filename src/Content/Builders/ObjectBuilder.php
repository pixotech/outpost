<?php

namespace Outpost\Content\Builders;

class ObjectBuilder extends ArrayBuilder implements ObjectBuilderInterface
{
    protected $className;

    public function __construct($className, array $values)
    {
        parent::__construct($values);
        $this->className = $className;
    }

    public function make(array $data)
    {
        return $this->makeObject(parent::make($data));
    }

    protected function makeObject(array $properties)
    {
        $objClass = new \ReflectionClass($this->className);
        $obj = $objClass->newInstance();
        foreach ($properties as $name => $value) {
            $setter = $this->makeSetterMethodName($name);
            if ($objClass->hasMethod($setter) && $objClass->getMethod($setter)->isPublic()) {
                $objClass->getMethod($setter)->invoke($obj, $value);
            }
            elseif ($objClass->hasProperty($name) && $objClass->getProperty($name)->isPublic()) {
                $obj->$name = $value;
            }
        }
        return $obj;
    }

    protected function makeSetterMethodName($property)
    {
        return 'set' . ucfirst($property);
    }
}
