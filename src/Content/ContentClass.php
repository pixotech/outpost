<?php

namespace Outpost\Content;

use Outpost\Content\Properties\Property;
use Outpost\Reflection\Property as ReflectionProperty;
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
                $this->properties[] = new Property(new ReflectionProperty($property));
            } catch (\DomainException $e) {
                continue;
            }
        }
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
