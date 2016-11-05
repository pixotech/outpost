<?php

namespace Outpost\Files;

use Outpost\Files\UnknownAliasException;
use Outpost\Reflection\ReflectionClass;

class SourceFile extends File implements SourceFileInterface
{
    const EXTENSION = 'php';

    protected $aliases;

    protected $classes = [];

    protected $ns;

    private $tokens;

    public static function stripExtension($path)
    {
        $end = '.' . self::EXTENSION;
        if (substr($path, -strlen($end)) == $end) $path = substr($path, 0, -strlen($end));
        return $path;
    }

    public function __construct($path)
    {
        parent::__construct($path);
        $this->parse();
    }

    public function getAlias($name)
    {
        if (!$this->hasAlias($name)) {
            throw new UnknownAliasException($name);
        }
        return $this->aliases[$name];
    }

    public function getAliases()
    {
        if (!isset($this->aliases)) $this->parse();
        return $this->aliases;
    }

    public function getClass($name)
    {
        return $this->classes[$name];
    }

    /**
     * @return ReflectionClass[]
     */
    public function getClasses()
    {
        return $this->classes;
    }

    public function getExcerpt($startLine = null, $endLine = null)
    {
        return implode("", $this->getLines($startLine, $endLine));
    }

    /**
     * @return ReflectionClass
     */
    public function getLibraryClass()
    {
        foreach ($this->getClasses() as $clas) {
            if ($clas->isLibraryClass()) return $clas;
        }
        return null;
    }

    public function getLines($startLine = null, $endLine = null)
    {
        $source = [];
        foreach (file($this->path) as $i => $line) {
            $lineNumber = $i + 1;
            if (isset($endLine) && $lineNumber > $endLine) {
                break;
            }
            if (isset($startLine) && $lineNumber >= $startLine) {
                $source[$lineNumber] = $line;
            }
        }
        return implode("", $source);
    }

    public function getNamespace()
    {
        return $this->ns;
    }

    public function hasAlias($name)
    {
        return array_key_exists($name, $this->aliases);
    }

    public function hasLibraryClass()
    {
        return $this->getLibraryClass() ? true : false;
    }

    public function resolveClassName($className)
    {
        if ($this->hasAlias($className)) return $this->getAlias($className);
        if ($this->getNamespace()) $className = $this->getNamespace() . '\\' . $className;
        return $className;
    }

    private function getUpcomingAlias()
    {
        $alias = '';
        $classname = '';
        $classComplete = false;
        while ($token = array_shift($this->tokens)) {
            if ($token === ';') {
                break;
            }
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
            if ($token === '{') {
                break;
            }
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
            if ($token === ';') {
                break;
            }
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
        $this->aliases = [];
        $this->tokens = token_get_all(file_get_contents($this->path));
        while ($token = array_shift($this->tokens)) {
            if (is_array($token)) {
                try {
                    switch ($token[0]) {

                        case T_CLASS:
                            $this->classes[] = new ReflectionClass($this->getUpcomingClassName(), $this);
                            continue;

                        case T_NAMESPACE:
                            $this->ns = $this->getUpcomingNamespace();
                            continue;

                        case T_USE:
                            $this->aliases += array_filter($this->getUpcomingAlias());
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
