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

    protected $properties;

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

    public function getProperties()
    {
        if (!isset($this->properties)) {
            $this->properties = [];
            foreach ($this->getReflection()->getProperties() as $prop) {
                $this->properties[$prop->getName()] = new Property($prop);
            }
        }
        return $this->properties;
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

    public function hasTemplate()
    {
        return $this->docblock ? $this->docblock->hasTemplate() : false;
    }

    public function isEntityClass()
    {
        return $this->isLibraryClass();
    }

    public function isLibraryClass()
    {
        return !empty($this->libraryRoot);
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
