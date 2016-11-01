<?php

namespace Outpost\Content\Documentor;

use Outpost\Content\ContentClass;
use Outpost\Content\Reflection\FileReflection;

class Entity implements EntityInterface
{
    protected $className;

    protected $description;

    protected $file;

    protected $fileReflection;

    protected $properties = [];

    protected $startLine;

    protected $endLine;

    protected $summary;

    public function __construct($className, FileReflection $file)
    {
        $this->className = $className;
        $this->fileReflection = $file;
        $this->findProperties();
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getName()
    {
        $names = $this->splitClassName();
        return array_shift($names);
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

    public function getSummary()
    {
        return $this->summary;
    }

    public function getUrl()
    {
        return 'entity-' . str_replace('\\', '-', $this->className) . '.html';
    }

    public function getStartLine()
    {
        return $this->startLine;
    }

    public function getEndLine()
    {
        return $this->endLine;
    }

    protected function findProperties()
    {
        $entity = new ContentClass($this->className);
        $this->file = $entity->getReflection()->getFileName();
        $this->startLine = $entity->getReflection()->getStartLine();
        $this->endLine = $entity->getReflection()->getEndLine();
        $this->summary = $entity->getSummary();
        $this->description = $entity->getDescription();
        foreach ($entity->getProperties() as $name => $property) {
            $this->properties[$name] = new Property($property);
        }
    }

    protected function splitClassName()
    {
        return array_map([Documentor::class, 'camelCaseToWords'], array_reverse(explode('\\', $this->className)));
    }
}
