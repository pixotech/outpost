<?php

namespace Outpost\Generator\Files;

class AssetFile extends File implements AssetFileInterface
{
    protected $file;

    public function __construct($path, $file)
    {
        parent::__construct($path);
        $this->file = $file;
    }

    public function getTime()
    {
        return filemtime($this->file);
    }

    public function put($path)
    {
        copy($this->file, $path);
    }
}
