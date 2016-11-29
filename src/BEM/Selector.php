<?php

namespace Outpost\BEM;

class Selector
{
    const DELIMTER = '.';

    const ELEMENT_DELIMITER = '__';

    const MODIFIER_DELIMITER = '--';

    /**
     * @var string
     */
    protected $block;

    /**
     * @var string
     */
    protected $element;

    /**
     * @var string
     */
    protected $modifier;

    public static function make($path)
    {
        list($block, $element, $modifier) = static::splitPath($path);
        return new Selector($block, $element, $modifier);
    }

    protected static function escape($str)
    {
        return preg_replace('|[^0-9a-zA-Z]+|', '-', $str);
    }

    protected static function splitPath($path)
    {
        return array_pad(explode(static::DELIMTER, $path), 3, null);
    }

    public function __construct($block, $element = null, $modifier = null)
    {
        $this->block = $block;
        $this->element = $element;
        $this->modifier = $modifier;
    }

    public function __get($name)
    {
        return empty($this->element) ? $this->element($name) : $this->modifier($name);
    }

    public function __isset($name)
    {
        return true;
    }

    public function __toString()
    {
        return implode(' ', $this->getClasses());
    }

    public function e($element, $modifier = null)
    {
        return $this->element($element, $modifier);
    }

    public function element($element, $modifier = null)
    {
        return new static($this->block, $element, $modifier);
    }

    public function getClasses()
    {
        $classes = [];
        $className = static::escape($this->block);
        if ($this->element) {
            $className .= static::ELEMENT_DELIMITER . static::escape($this->element);
        }
        $classes[] = $className;
        if ($this->modifier) {
            foreach ((array)$this->modifier as $modifier) {
                $classes[] = $className .= static::MODIFIER_DELIMITER . static::escape($modifier);
            }
        }
        return $classes;
    }

    public function m($modifier)
    {
        return $this->modifier($modifier);
    }

    public function modifier($modifier)
    {
        return new static($this->block, $this->element, $modifier);
    }
}
