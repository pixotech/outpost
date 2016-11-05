<?php

namespace Outpost\Files;

class Directory implements DirectoryInterface
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path ?: getcwd();
        if (!is_dir($this->path)) {
            throw new \InvalidArgumentException("Not a directory: $this->path");
        }
    }

    public function getFilesWithExtension($extension)
    {
        return iterator_to_array($this->getFilesWithExtensionIterator($extension));
    }

    public function getLibraryClasses()
    {
        $classes = [];
        foreach ($this->getSourceFiles() as $file) {
            if ($file->hasLibraryClass()) {
                $clas = $file->getLibraryClass();
                $classes[$clas->getName()] = $clas;
            }
        }
        return $classes;
    }

    /**
     * @return SourceFile[]
     */
    public function getSourceFiles()
    {
        $files = [];
        /** @var \SplFileInfo $file */
        foreach ($this->getFilesWithExtensionIterator(SourceFile::EXTENSION) as $file) {
            $files[] = new SourceFile($file->getRealPath());
        }
        return $files;
    }

    public function getTemplateFiles()
    {
        $files = [];
        /** @var \SplFileInfo $file */
        foreach ($this->getFilesWithExtensionIterator(TemplateFile::EXTENSION) as $path => $file) {
            $root = substr($path, 0, strlen($this->path));
            if ($root == $this->path) {
                $path = substr($path, strlen($root) + 1);
            } else {
                $root = null;
            }
            $files[$path] = new TemplateFile($file, $path, $root);
        }
        return $files;
    }

    protected function getFilesWithExtensionIterator($extension)
    {
        $files = new \RecursiveIteratorIterator($this->getRecursiveDirectoryIterator());
        $pattern = $this->makeFileExtensionPattern($extension);
        return new \RegexIterator($files, $pattern, \RegexIterator::MATCH);
    }

    protected function getRecursiveDirectoryIterator()
    {
        return new \RecursiveDirectoryIterator($this->path);
    }

    protected function makeFileExtensionPattern($ext)
    {
        return '|' . preg_quote('.' . $ext) . '$|';
    }
}
