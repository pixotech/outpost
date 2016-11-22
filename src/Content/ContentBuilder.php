<?php

namespace Outpost\Content;

use Outpost\Content\Builders\ObjectBuilder;
use phpDocumentor\Reflection\DocBlockFactory;

class ContentBuilder extends ObjectBuilder
{
    /**
     * @var DocBlockFactory
     */
    protected static $parser;

    /**
     * @return DocBlockFactory
     */
    protected static function getParser()
    {
        if (!isset(self::$parser)) {
            self::$parser = DocBlockFactory::createInstance();
        }
        return self::$parser;
    }

    public function __construct($className)
    {
        parent::__construct($className, $this->findContentProperties($className));
    }

    protected function extractBuilderParametersFromProperty($className, \ReflectionProperty $property)
    {
        $variable = null;
        $callback = null;
        $doc = self::getParser()->create($property->getDocComment());
        foreach ($doc->getTags() as $tag) {
            switch ($tag->getName()) {
                case 'outpost\content\callback':
                    $callback = $this->normalizeCallback((string)$tag, $className);
                    break;
                case 'outpost\content\variable':
                    $variable = (string)$tag;
                    break;
            }
        }
        return [$variable, $callback];
    }

    protected function findContentProperties($className)
    {
        $properties = [];
        $clas = new \ReflectionClass($className);
        foreach ($clas->getProperties() as $property) {
            $name = $property->getName();
            list($variable, $callback) = $this->extractBuilderParametersFromProperty($className, $property);
            if ($variable || $callback) {
                $properties[$name] = new PropertyBuilder($variable, $callback);
            }
        }
        return $properties;
    }

    protected function makeObject(array $properties)
    {
        $clas = new \ReflectionClass($this->className);
        $obj = $clas->newInstanceWithoutConstructor();
        foreach ($properties as $name => $value) {
            $property = $clas->getProperty($name);
            $property->setAccessible(true);
            $property->setValue($obj, $value);
        }
        return $obj;
    }

    protected function normalizeCallback($callback, $parentClass)
    {
        if (is_string($callback)) {
            if (false !== strpos($callback, '::')) {
                list($className, $method) = explode('::', $callback, 2);
                if (empty($className) || $className == 'self') {
                    $className = $parentClass;
                }
                $callback = [$className, $method];
            }
        }
        return $callback;
    }
}
