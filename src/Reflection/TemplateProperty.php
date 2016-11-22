<?php

namespace Outpost\Reflection;

class TemplateProperty implements TemplatePropertyInterface
{
    protected $getter;

    protected $method;

    protected $name;

    protected $property;

    protected $setter;

    protected $tester;

    public static function fromClass(\ReflectionClass $clas)
    {
        $properties = [];
        $prefixes = [
            'get' => 'getter',
            'set' => 'setter',
            'is' => 'tester',
        ];
        foreach ($clas->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            if ($prop->isStatic()) continue;
            $name = $prop->getName();
            if ($name[0] == '_') continue;
            $property = new static($name);
            $property->property = $prop;
            $properties[$name] = $property;
        }
        foreach ($clas->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->isStatic()) continue;
            $foundPrefixed = false;
            foreach ($prefixes as $prefix => $type) {
                if (self::methodStartsWith($prefix, $method)) {
                    $foundPrefixed = true;
                    $name = self::getMethodNameWithoutPrefix($prefix, $method);
                    if (!isset($properties[$name])) {
                        $properties[$name] = new static($name);
                    }
                    $properties[$name]->$type = $method;
                }
            }
            if (!$foundPrefixed) {
                $name = $method->getName();
                if ($name[0] == '_') continue;
                if (!isset($properties[$name])) {
                    $properties[$name] = new static($name);
                }
                $properties[$name]->method = $method;
            }
        }
        ksort($properties);
        return $properties;
    }

    protected static function getMethodNameWithoutPrefix($prefix, \ReflectionMethod $method)
    {
        return lcfirst(substr($method->getName(), strlen($prefix)));
    }

    protected static function methodStartsWith($prefix, \ReflectionMethod $method)
    {
        $name = $method->getName();
        return strlen($name) > strlen($prefix) && substr($name, 0, strlen($prefix)) === $prefix;
    }

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
