<?php

namespace Outpost\Content;

use Outpost\Content\Properties\Property;

class ContentClass extends \ReflectionClass implements ContentClassInterface
{
    /**
     * @var Property[]
     */
    protected $contentProperties = [];

    public function __construct($className)
    {
        parent::__construct($className);
        $this->findContentProperties();
    }

    public function __invoke(VariablesInterface $properties)
    {
        return $this->makeContentInstance($properties);
    }

    public function getContentProperties()
    {
        return $this->contentProperties;
    }

    protected function findContentProperties()
    {
        foreach ($this->getProperties() as $property) {
            try {
                $this->contentProperties[] = new Property(new \Outpost\Reflection\Property($property));
            } catch (\DomainException $e) {
                continue;
            }
        }
    }

    protected function makeContentInstance(VariablesInterface $variables)
    {
        $obj = $this->newInstanceWithoutConstructor();
        foreach ($this->makeProperties($variables) as $name => $value) {
            $property = $this->getProperty($name);
            $property->setAccessible(true);
            $property->setValue($obj, $value);
        }
        return $obj;
    }

    protected function makeProperties(VariablesInterface $variables)
    {
        $properties = [];
        foreach ($this->contentProperties as $property)
        {
            $properties[$property->getName()] = call_user_func($property, $variables);
        }
        return $properties;
    }
}
