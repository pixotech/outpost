<?php

namespace Outpost\Files;

class TemplateFile extends File implements TemplateFileInterface
{
    const EXTENSION = 'twig';

    protected $templateFile;

    protected $templatePath;

    protected $templateRoot;

    public function __construct(\SplFileInfo $file, $path, $root)
    {
        parent::__construct($file->getRealPath());
        $this->templateFile = $file;
        $this->templatePath = $path;
        $this->templateRoot = $root;
    }
}
