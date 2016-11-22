<?php

namespace Outpost\Content\Properties;

use Outpost\Reflection\PropertyInterface as ReflectionPropertyInterface;

class Property implements PropertyInterface
{
    /**
     * @var string
     */
    protected $callback;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var array
     */
    protected $definition = [];

    /**
     * @var ReflectionPropertyInterface
     */
    protected $reflection;

    /**
     * @var string
     */
    protected $summary;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $variable;

    public static function cast(ReflectionPropertyInterface $property)
    {
        switch ($property->getType()) {
            case 'array':
                return new ArrayProperty($property);
            case 'bool':
            case 'boolean':
                return new BooleanProperty($property);
            case 'float':
                return new FloatProperty($property);
            case 'int':
            case 'integer':
                return new IntegerProperty($property);
            case 'string':
                return new StringProperty($property);
            default:
                return new Property($property);
        }
    }

    public function __construct(ReflectionPropertyInterface $property)
    {
        $this->reflection = $property;
    }

    /**
     * @return string
     */
    public function getCallback()
    {
        return $this->reflection->getCallback();
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->reflection->getName();
    }

    /**
     * @return \ReflectionProperty
     */
    public function getReflection()
    {
        return $this->reflection;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getVariable()
    {
        return $this->reflection->getVariable();
    }

    /**
     * @return bool
     */
    public function hasCallback()
    {
        return (bool)$this->getCallback();
    }

    /**
     * @return bool
     */
    public function hasVariable()
    {
        return (bool)$this->getVariable();
    }

    /**
     * @return bool
     */
    public function isObject()
    {
        return substr($this->getType(), 0, 1) == '\\';
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return false;
    }

    protected function normalizeCallback($callback)
    {
        if (is_string($callback)) {
            if (false !== strpos($callback, '::')) {
                list($className, $method) = explode('::', $callback, 2);
                if (empty($className) || $className == 'self') {
                    $className = $this->reflection->class;
                }
                $callback = [$className, $method];
            }
        }
        return $callback;
    }

    protected function normalizeVariable($var)
    {
        if (empty($var)) $var = $this->getName();
        return $var;
    }
}
