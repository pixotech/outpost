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
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $variable;

    public function __construct(\ReflectionProperty $property)
    {
        $this->name = $property->getName();
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
        return $this->name;
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

    protected function parseDocComment($str)
    {
        $parser = DocBlockFactory::createInstance();
        $doc = $parser->create($str);
        foreach ($doc->getTags() as $tag) {
            switch ($tag->getName()) {
                case 'outpost\content\callback':
                    $this->callback = (string)$tag;
                    break;
                case 'outpost\content\variable':
                    $this->variable = (string)$tag;
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
