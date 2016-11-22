<?php

namespace Outpost\Reflection;

use Outpost\Files\FileInterface;
use Outpost\Files\SourceFile;

class ReflectionClass implements ReflectionClassInterface
{
    protected $docblock;

    protected $file;

    protected $libraryClass;

    protected $libraryNamespace;

    protected $libraryPath;

    protected $libraryRoot;

    protected $reflection;

    public function __construct($clas, FileInterface $file = null)
    {
        if ($clas instanceof \ReflectionClass) {
            $this->reflection = $clas;
        } elseif (is_string($clas)) {
            $this->reflection = new \ReflectionClass($clas);
        } else {
            throw new \InvalidArgumentException("Unrecognized class");
        }
        if (isset($file)) {
            $this->file = $file;
        }
        if ($docblock = $this->reflection->getDocComment()) {
            $this->docblock = new Docblock($docblock);
        }
        $this->findLibraryInformation();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getDescription()
    {
        return $this->docblock ? $this->docblock->getDescription() : null;
    }

    public function getEndLine()
    {
        return $this->getReflection()->getEndLine();
    }

    public function getFile()
    {
        if (!isset($this->file)) {
            $this->file = new SourceFile($this->getFileName());
        }
        return $this->file;
    }

    public function getFileName()
    {
        return $this->getReflection()->getFileName();
    }

    public function getName()
    {
        return $this->getReflection()->getName();
    }

    public function getSource()
    {
        return $this->getFile()->getExcerpt($this->getStartLine(), $this->getEndLine());
    }

    public function getStartLine()
    {
        return $this->getReflection()->getStartLine();
    }

    public function getSummary()
    {
        return $this->docblock ? $this->docblock->getSummary() : null;
    }

    public function getTemplate()
    {
        return $this->docblock ? $this->docblock->getTemplate() : null;
    }

    public function getTemplateProperties()
    {
        return TemplateProperty::fromClass($this->getReflection());
    }

    public function hasGetterMethod($name)
    {
        return $this->hasPublicNonStaticMethod('get' . ucfirst($name));
    }

    public function hasMagicGetMethod()
    {
        return $this->reflection->hasMethod('__get') && $this->reflection->getMethod('__get')->isPublic();
    }

    public function hasMagicIssetMethod()
    {
        return $this->reflection->hasMethod('__isset') && $this->reflection->getMethod('__isset')->isPublic();
    }

    public function hasSetterMethod($name)
    {
        return $this->hasPublicNonStaticMethod('set' . ucfirst($name));
    }

    public function hasTemplate()
    {
        return $this->docblock ? $this->docblock->hasTemplate() : false;
    }

    public function hasTestMethod($name)
    {
        return $this->hasPublicNonStaticMethod('is' . ucfirst($name));
    }

    public function isEntityClass()
    {
        return $this->isLibraryClass();
    }

    public function isLibraryClass()
    {
        return !empty($this->libraryRoot);
    }

    protected function getPublicMethods()
    {
        return $this->getReflection()->getMethods(\ReflectionMethod::IS_PUBLIC);
    }

    protected function getPublicGetterMethods()
    {
        $methods = [];
        foreach ($this->getPublicMethods() as $method) {
            $name = $method->getName();
            if (strlen($name) > 3 && substr($name, 0, 3) == 'get') {
                $methods[lcfirst(substr($name, 3))] = $method;
            }
        }
        return $methods;
    }

    protected function getNamespaceName()
    {
        return $this->getReflection()->getNamespaceName();
    }

    /**
     * @return \ReflectionClass
     */
    protected function getReflection()
    {
        return $this->reflection;
    }

    protected function hasPublicNonStaticMethod($name)
    {
        if (!$this->getReflection()->hasMethod($name)) return false;
        $method = $this->getReflection()->getMethod($name);
        return $method->isPublic() && !$method->isStatic();
    }

    private function findLibraryInformation()
    {
        $classSegments = array_reverse(explode('\\', $this->getName()));
        $pathSegments = array_reverse(explode(DIRECTORY_SEPARATOR, $this->getFileName()));
        $len = min(count($classSegments), count($pathSegments));
        for ($i = 0; $i < $len; $i++) {
            $pathSegment = $pathSegments[$i];
            if (!$i) $pathSegment = SourceFile::stripExtension($pathSegment);
            if ($classSegments[$i] != $pathSegment) break;
        }
        if ($i) {
            $this->libraryRoot = $this->makeLibraryRoot($pathSegments, $i);
            $this->libraryPath = $this->makeLibraryPath($pathSegments, $i);
            $this->libraryNamespace = $this->makeLibraryNamespace($classSegments, $i);
            $this->libraryClass = $this->makeLibraryClass($classSegments, $i);
        }
    }

    private function makeLibraryClass($segments, $index)
    {
        return implode('\\', array_reverse(array_slice($segments, 0, $index)));
    }

    private function makeLibraryNamespace($segments, $index)
    {
        return implode('\\', array_reverse(array_slice($segments, $index)));
    }

    private function makeLibraryPath($segments, $index)
    {
        return implode(DIRECTORY_SEPARATOR, array_reverse(array_slice($segments, 0, $index)));
    }

    private function makeLibraryRoot($segments, $index)
    {
        return implode(DIRECTORY_SEPARATOR, array_reverse(array_slice($segments, $index)));
    }
}
