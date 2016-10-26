<?php

namespace Outpost\Content;

use phpDocumentor\Reflection\DocBlockFactory;

class Property implements PropertyInterface
{
    /**
     * @var string
     */
    protected $callback;

    /**
     * @var \ReflectionProperty
     */
    protected $property;

    /**
     * @var string
     */
    protected $variable;

    public function __construct(\ReflectionProperty $property)
    {
        $this->property = $property;
        if ($comment = $property->getDocComment()) $this->parseDocComment($comment);
        $this->validate();
    }

    public function __invoke(Variables $variables)
    {
        if ($this->hasVariable()) {
            $value = $variables->get($this->getVariable());
        } else {
            $value = $variables->getVariables();
        }
        if ($this->hasCallback()) {
            $value = call_user_func($this->getCallback(), $value);
        }
        return $value;
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
    public function getName()
    {
        return $this->property->getName();
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

    protected function normalizeCallback($callback)
    {
        if (is_string($callback)) {
            if (false !== strpos($callback, '::')) {
                list($className, $method) = explode('::', $callback, 2);
                if (empty($className) || $className == 'self') {
                    $className = $this->property->getDeclaringClass();
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
        $parser = DocBlockFactory::createInstance();
        $doc = $parser->create($str);
        foreach ($doc->getTags() as $tag) {
            switch ($tag->getName()) {
                case 'outpost\content\callback':
                    $this->callback = $this->normalizeCallback((string)$tag);
                    break;
                case 'outpost\content\variable':
                    $this->variable = $this->normalizeVariable((string)$tag);
                    break;
            }
        }
    }

    protected function validate()
    {
        if (!$this->hasCallback() && !$this->hasVariable()) {
            throw new \DomainException("Invalid content property");
        }
    }
}
