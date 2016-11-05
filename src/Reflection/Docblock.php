<?php

namespace Outpost\Reflection;

use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use phpDocumentor\Reflection\DocBlockFactory;

class Docblock implements DocblockInterface
{
    /**
     * @var DocBlockFactory
     */
    protected static $parser;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var string
     */
    protected $definition;

    /**
     * @var array
     */
    protected $description;

    /**
     * @var string
     */
    protected $docblock;

    /**
     * @var string
     */
    protected $summary;

    /**
     * @var array
     */
    protected $tags;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $variable;

    /**
     * @return DocBlockFactory
     */
    protected static function getParser()
    {
        if (!isset(self::$parser)) {
            self::$parser = DocBlockFactory::createInstance();
        }
        return self::$parser;
    }

    public function __construct($docblock)
    {
        $this->docblock = $docblock;
        $this->parse();
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @return array
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getDocblock()
    {
        return $this->docblock;
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
    public function getTemplate()
    {
        return $this->template;
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
    public function hasTemplate()
    {
        return !empty($this->template);
    }

    protected function parse()
    {
        $doc = self::getParser()->create($this->docblock);
        $this->summary = (string)$doc->getSummary();
        $this->description = (string)$doc->getDescription();
        foreach ($doc->getTags() as $tag) {
            switch ($tag->getName()) {
                case 'outpost\content\callback':
                    $this->callback = (string)$tag;
                    break;
                case 'outpost\content\variable':
                    $this->variable = (string)$tag;
                    break;
                case 'outpost\json':
                    $json = (string)$tag;
                    if ($json && (null !== $parsed = json_decode($json, true))) {
                        $this->definition = $parsed;
                    }
                    break;
                case 'outpost\template':
                    $this->template = (string)$tag;
                    break;
                case 'var':
                    /** @var Var_ $tag */
                    $this->type = (string)$tag->getType();
                    break;
                default:
                    $this->tags[$tag->getName()][] = (string)$tag;
            }
        }
    }
}
