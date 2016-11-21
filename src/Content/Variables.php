<?php

namespace Outpost\Content;

use Outpost\Content\Builders\VariableBuilder;

class Variables implements VariablesInterface
{
    protected $variables = [];

    public static function decode($json)
    {
        return new static(json_decode($json, true));
    }

    public static function load($file)
    {
        return static::decode(file_get_contents($file));
    }

    public function __construct(array $variables = [])
    {
        $this->variables = $variables;
    }

    public function get($name)
    {
        $builder = new VariableBuilder(str_replace('.', '/', $name));
        return $builder->make($this->variables);
    }

    public function getVariables()
    {
        return $this->variables;
    }

    public function hasVariable($name)
    {
        return array_key_exists($name, $this->variables);
    }
}
