<?php

namespace Outpost\Reflection;

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
     * @var \ReflectionProperty
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

    public function __construct(\ReflectionProperty $reflection)
    {
        $this->reflection = $reflection;
        if ($comment = $reflection->getDocComment()) $this->parseDocComment($comment);
    }

    /**
     * @return string
     */
    public function getCallback()
    {
        return $this->callback;
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
        return $this->variable;
    }

    /**
     * @return bool
     */
    public function hasCallback()
    {
        return !empty($this->callback);
    }

    /**
     * @return bool
     */
    public function hasVariable()
    {
        return !empty($this->variable);
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

    protected function parseDocComment($str)
    {
        $docblock = new Docblock($str);
        $this->summary = $docblock->getSummary();
        $this->description = $docblock->getDescription();
        $this->definition = $docblock->getDefinition();
        $this->type = $docblock->getType();
        if ($callback = $docblock->getCallback()) {
            $this->callback = $this->normalizeCallback($callback);
        }
        if ($variable = $docblock->getVariable()) {
            $this->variable = $this->normalizeVariable($variable);
        }
    }
}
