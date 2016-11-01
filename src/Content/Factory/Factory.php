<?php

namespace Outpost\Content\Factory;

use Outpost\Content\ContentClass;
use Outpost\Content\Variables;

class Factory implements FactoryInterface
{
    protected $classes = [];

    public function create($className, array $variables)
    {
        return call_user_func($this->getClass($className), new Variables($variables));
    }

    protected function getClass($name)
    {
        if (!isset($this->classes[$name])) {
            $this->classes[$name] = new ContentClass($name);
        }
        return $this->classes[$name];
    }
}
