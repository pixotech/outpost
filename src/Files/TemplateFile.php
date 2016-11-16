<?php

namespace Outpost\Files;

class TemplateFile extends File implements TemplateFileInterface
{
    const EXTENSION = 'twig';

    protected $templateFile;

    protected $templatePath;

    protected $templateRoot;

    public static function loadFixture(\SplFileInfo $fixture)
    {
        return include($fixture->getPathname());
    }

    public function __construct(\SplFileInfo $file, $path, $root)
    {
        parent::__construct($file->getRealPath());
        $this->templateFile = $file;
        $this->templatePath = $path;
        $this->templateRoot = $root;
    }

    public function getFixture()
    {
        return self::loadFixture($this->getFixtureFile());
    }

    public function getTemplateName()
    {
        return $this->templatePath;
    }

    public function hasFixture()
    {
        return $this->hasSiblingFile('fixture.php');
    }

    protected function getFixtureFile()
    {
        return $this->getSiblingFile('fixture.php');
    }

    protected function getSiblingFile($extension)
    {
        return new \SplFileInfo($this->getSiblingPath($extension));
    }

    protected function getSiblingPath($extension)
    {
        $pathWithoutExtension = substr($this->templateFile->getRealPath(), 0, -1 - strlen(self::EXTENSION));
        return $pathWithoutExtension . '.' . $extension;
    }

    protected function hasSiblingFile($extention)
    {
        return is_file($this->getSiblingPath($extention));
    }
}
