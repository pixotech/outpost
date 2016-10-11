<?php

namespace Outpost\Content;

class Variables implements VariablesInterface, \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable
{
    const DELIMITER = '.';

    protected $variables = [];

    public static function decode($json)
    {
        return new static(json_decode($json, true));
    }

    public static function load($file)
    {
        return static::decode(file_get_contents($file));
    }

    protected static function getVariableByName(array $variables, $name)
    {
        $properties = null;
        if (false !== strpos($name, self::DELIMITER)) {
            list ($name, $properties) = explode(self::DELIMITER, $name, 2);
        }
        $value = array_key_exists($name, $variables) ? $variables[$name] : null;
        if (!empty($properties)) {
            $value = is_array($value) ? static::getVariableByName($value, $properties) : null;
        }
        return $value;
    }

    public function __construct(array $variables = [])
    {
        $this->variables = $variables;
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
        return static::getVariableByName($this->variables, $name);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->variables);
    }

    public function getVariables()
    {
        return $this->variables;
    }

    public function jsonSerialize()
    {
        return $this->variables;
    }

    public function offsetExists($key)
    {
        return isset($this->variables[$key]);
    }

    public function offsetGet($key)
    {
        return $this->variables[$key];
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
