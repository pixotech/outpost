<?php

namespace Outpost\Content;

use Outpost\Content\Builders\VariableBuilder;

class Variables implements VariablesInterface, \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable
{
    const DELIMITER_PATTERN = '#[/\.]#';

    protected $strict = false;

    protected $variables = [];

    public static function decode($json)
    {
        return new static(json_decode($json, true));
    }

    public function isCompoundName($name)
    {
        return preg_match(self::DELIMITER_PATTERN, $name);
    }

    public static function isReference($value)
    {
        if (!is_array($value)) return false;
        $keys = array_keys($value);
        return count($keys) == 1 && $keys[0] === '$ref';
    }

    public static function load($file)
    {
        return static::decode(file_get_contents($file));
    }

    public static function splitName($name, $limit = -1)
    {
        return preg_split(self::DELIMITER_PATTERN, $name, $limit);
    }

    public function __construct(array $variables = [], $strict = null)
    {
        $this->variables = $variables;
        if (isset($strict)) $this->strict = $strict;
    }

    public function __invoke($name)
    {
        return $this->get($name);
    }

    public function count()
    {
        return count($this->variables);
    }

    public function get($name)
    {
        return $this->getVariable($name);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->variables);
    }

    public function getVariable($name)
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

    public function isStrict()
    {
        return (bool)$this->strict;
    }

    public function jsonSerialize()
    {
        return $this->variables;
    }

    public function offsetExists($key)
    {
        return $this->hasVariable($key);
    }

    public function offsetGet($key)
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value)
    {
        $this->variables[$key] = $value;
    }

    public function offsetUnset($key)
    {
        unset($this->variables[$key]);
    }
}
