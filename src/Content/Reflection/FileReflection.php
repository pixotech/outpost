<?php

namespace Outpost\Content\Reflection;

class FileReflection implements FileReflectionInterface
{
    protected $aliases = [];

    protected $classes = [];

    protected $ns;

    protected $path;

    private $tokens;

    public function __construct($path)
    {
        $this->path = $path;
        $this->parse();
    }

    public function getAlias($name)
    {
        if ($this->hasAlias($name)) {
            throw new UnknownAliasException($name);
        }
        return $this->aliases[$name];
    }

    public function getAliases()
    {
        return $this->aliases;
    }

    public function getClasses()
    {
        return $this->classes;
    }

    public function getNamespace()
    {
        return $this->ns;
    }

    public function hasAlias($name)
    {
        return array_key_exists($name, $this->aliases);
    }

    private function getUpcomingAlias()
    {
        $alias = '';
        $classname = '';
        $classComplete = false;
        while ($token = array_shift($this->tokens)) {
            if (is_array($token)) {
                switch ($token[0]) {

                    case T_WHITESPACE:
                        continue;

                    case T_AS:
                        $alias = '';
                        $classComplete = true;
                        continue;

                    case T_NS_SEPARATOR:
                    case T_STRING:
                        if ($classComplete) {
                            $alias .= $token[1];
                        } else {
                            $alias = $token[1];
                            $classname .= $token[1];
                        }
                        continue;

                    default:
                        array_unshift($this->tokens, $token);
                        break(2);
                }
            }
        }
        return [$alias => $classname];
    }

    private function getUpcomingClassName()
    {
        $classname = '';
        while ($token = array_shift($this->tokens)) {
            if (is_array($token)) {
                switch ($token[0]) {

                    case T_WHITESPACE:
                        if (!empty($classname)) break(2);
                        continue;

                    case T_STRING:
                        $classname .= $token[1];
                        continue;

                    default:
                        throw new \Exception();
                }
            }
        }
        if ($classname && $this->getNamespace()) {
            $classname = $this->getNamespace() . '\\' . $classname;
        }
        return $classname;
    }

    private function getUpcomingNamespace()
    {
        $ns = '';
        while ($token = array_shift($this->tokens)) {
            if (is_array($token)) {
                switch ($token[0]) {

                    case T_WHITESPACE:
                        if (!empty($ns)) break(2);
                        continue;

                    case T_NS_SEPARATOR:
                    case T_STRING:
                        $ns .= $token[1];
                        continue;
                }
            }
        }
        return $ns;
    }

    private function parse()
    {
        $this->tokens = token_get_all(file_get_contents($this->path));
        while ($token = array_shift($this->tokens)) {
            if (is_array($token)) {
                try {
                    switch ($token[0]) {

                        case T_CLASS:
                            $this->classes[] = $this->getUpcomingClassName();
                            continue;

                        case T_NAMESPACE:
                            $this->ns = $this->getUpcomingNamespace();
                            continue;

                        case T_USE:
                            $this->aliases += $this->getUpcomingAlias();
                            continue;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
        $this->tokens = null;
    }
}
