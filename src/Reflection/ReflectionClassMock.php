<?php

namespace Outpost\Reflection;

use Outpost\Files\SourceFileInterface;

class ReflectionClassMock implements ReflectionClassInterface
{
    public $name;

    public $properties = [];

    public function __construct($name = null)
    {
        if (isset($name)) $this->name = $name;
    }

    public function getDescription()
    {
        // TODO: Implement getDescription() method.
    }

    public function getEndLine()
    {
        // TODO: Implement getEndLine() method.
    }

    public function getFile()
    {
        // TODO: Implement getFile() method.
    }

    public function getFilename()
    {
        // TODO: Implement getFilename() method.
    }

    public function getName()
    {
        return $this->name;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function getStartLine()
    {
        // TODO: Implement getStartLine() method.
    }

    public function getSummary()
    {
        // TODO: Implement getSummary() method.
    }

    public function getTemplate()
    {
        // TODO: Implement getTemplate() method.
    }

    public function hasTemplate()
    {
        // TODO: Implement hasTemplate() method.
    }

    public function isEntityClass()
    {
        // TODO: Implement isEntityClass() method.
    }
}
