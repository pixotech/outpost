<?php

namespace Outpost\Content\Documentor;

use Outpost\Content\PropertyInterface as ContentPropertyInterface;
use Outpost\Content\Reflection\FileReflection;

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

    public function getEntity()
    {
        if (!$this->isObject()) return null;
        $entityClass = substr($this->getType(), 1);
        /** @var \ReflectionProperty $property */
        $property = $this->property->getReflection();
        $propertyClass = $property->class;
        $classPaths = array_reverse(explode('\\', $propertyClass));
        $classShortName = array_shift($classPaths);
        if ($entityClass == $classShortName) {
            return $propertyClass;
        }
        if (class_exists($entityClass)) {
            return $entityClass;
        }
        $file = new FileReflection($property->getDeclaringClass()->getFileName());
        if ($file->hasAlias($entityClass)) {
            return $file->getAlias($entityClass);
        }
        $nsEntityClass = $file->getNamespace() . '\\' . $entityClass;
        if (class_exists($nsEntityClass)) {
            return $nsEntityClass;
        }
        return $entityClass;
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

    public function isObject()
    {
        return substr($this->getType(), 0, 1) == '\\';
    }

    public function isRequired()
    {
        return false;
    }
}
