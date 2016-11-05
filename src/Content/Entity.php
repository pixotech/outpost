<?php

namespace Outpost\Content;

use Outpost\Content\Properties\Property;
use Outpost\Files\TemplateFileInterface;
use Outpost\Reflection\ReflectionClassInterface;

class Entity implements EntityInterface
{
    protected $className;

    protected $description;

    protected $endLine;

    protected $file;

    /**
     * @var Property[]
     */
    protected $properties = [];

    protected $startLine;

    protected $summary;

    public static function fromFile($path)
    {

    }

    public static function fromClassName($className)
    {

    }

    public function __construct(ReflectionClassInterface $libraryClass, TemplateFileInterface $template)
    {
        $this->libraryClass = $libraryClass;
        $this->template = $template;

        $this->className = $libraryClass->getName();
        $this->findProperties($libraryClass);
    }

    public function __invoke(VariablesInterface $properties)
    {
        return $this->makeInstance($properties);
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function getSource()
    {
        $source = [];
        foreach (file($this->file) as $i => $line) {
            $lineNumber = $i + 1;
            if (isset($this->endLine) && $lineNumber > $this->endLine) {
                break;
            }
            if (isset($this->startLine) && $lineNumber >= $this->startLine) {
                $source[$lineNumber] = $line;
            }
        }
        return implode("", $source);
    }

    protected function findProperties(ReflectionClassInterface $entity)
    {
        $this->file = $entity->getFileName();
        $this->startLine = $entity->getStartLine();
        $this->endLine = $entity->getEndLine();
        $this->summary = $entity->getSummary();
        $this->description = $entity->getDescription();
        foreach ($entity->getProperties() as $name => $property) {
            try {
                $this->properties[$name] = Property::cast($property);
            } catch (\DomainException $e) {
                continue;
            }
        }
    }

    protected function makeInstance(VariablesInterface $variables)
    {
        $reflection = new \ReflectionClass($this->className);
        $obj = $reflection->newInstanceWithoutConstructor();
        foreach ($this->makeProperties($variables) as $name => $value) {
            $property = $reflection->getProperty($name);
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
}
