<?php

namespace Outpost\Content\Builders;

abstract class Builder implements BuilderInterface
{
    public static function create($definition)
    {
        $isCollection = false;
        if (is_array($definition)) {
            if (isset($definition['$class'])) {
                $name = $definition['$class'];
                unset($definition['$class']);
                if (self::isCollectionName($name)) {
                    $isCollection = true;
                    $name = self::getNameFromCollectionName($name);
                }
                $builder = new ObjectBuilder($name, $definition);
            } else {
                $builder = new ArrayBuilder($definition);
            }
        } elseif (is_string($definition)) {
            $name = $definition;
            if (self::isCollectionName($name)) {
                $isCollection = true;
                $name = self::getNameFromCollectionName($name);
            }
            $builder = new VariableBuilder($name);
        } else {
            throw new \DomainException("Unrecognized extraction");
        }
        if ($isCollection) {
            $builder = new CollectionBuilder($builder);
        }
        return $builder;
    }

    protected static function getNameFromCollectionName($name)
    {
        return substr($name, 0, -2);
    }

    protected static function isCollectionName($name)
    {
        return substr($name, -2) === '[]';
    }

    public function __invoke(array $data = [])
    {
        return $this->make($data);
    }

    abstract public function make(array $data);
}
