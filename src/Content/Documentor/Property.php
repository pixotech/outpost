<?php

namespace Outpost\Content\Documentor;

use Outpost\Content\PropertyInterface as ContentPropertyInterface;

class Property implements PropertyInterface
{
    protected $property;

    public function __construct(ContentPropertyInterface $property)
    {
        $this->property = $property;
    }

    public function getDescription()
    {
        return $this->property->getDescription();
    }

    public function getName()
    {
        return Documentor::camelCaseToWords(ucfirst($this->property->getName()));
    }

    public function getSummary()
    {
        return $this->property->getSummary();
    }

    public function getType()
    {
        return $this->property->getType();
    }

    public function isRequired()
    {
        return false;
    }
}
