<?php

namespace Outpost\Content;

use phpDocumentor\Reflection\DocBlockFactory;

class ContentClass implements ContentClassInterface
{
    protected $reflection;

    protected $definition = [];

    protected $description;

    protected $summary;

    /**
     * @var Property[]
     */
    protected $properties = [];

    public function __construct($className)
    {
        $this->reflection = new \ReflectionClass($className);
        if ($comment = $this->reflection->getDocComment()) $this->parseDocComment($comment);
        $this->findProperties();
    }

    public function __invoke(VariablesInterface $properties)
    {
        return $this->makeInstance($properties);
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function getReflection()
    {
        return $this->reflection;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    protected function findProperties()
    {
        foreach ($this->reflection->getProperties() as $property) {
            try {
                $this->properties[] = new Property($property);
            } catch (\DomainException $e) {
                continue;
            }
        }
    }

    protected function makeInstance(VariablesInterface $variables)
    {
        $obj = $this->reflection->newInstanceWithoutConstructor();
        foreach ($this->makeProperties($variables) as $name => $value) {
            $property = $this->reflection->getProperty($name);
            $property->setAccessible(true);
            $property->setValue($obj, $value);
        }
        # todo: call object constructor
        return $obj;
    }

    protected function makeProperties(VariablesInterface $variables)
    {
        $properties = [];
        foreach ($this->properties as $property) {
            $properties[$property->getName()] = call_user_func($property, $variables);
        }
        return $properties;
    }

    protected function parseDocComment($str)
    {
        $parser = DocBlockFactory::createInstance();
        $doc = $parser->create($str);
        $this->summary = (string)$doc->getSummary();
        $this->description = (string)$doc->getDescription();
        foreach ($doc->getTags() as $tag) {
            switch ($tag->getName()) {
                case 'outpost\json':
                    $json = (string)$tag;
                    if ($json && (null !== $parsed = json_decode($json, true))) {
                        $this->definition = $parsed;
                    }
                    break;
            }
        }
    }
}
