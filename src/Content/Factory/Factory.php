<?php

namespace Outpost\Content\Factory;

class Factory implements FactoryInterface
{
    protected $classes = [];

    public function create($className, array $variables)
    {
        return call_user_func([$className, 'make'], $variables);
    }
}
