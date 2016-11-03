<?php

namespace Outpost\Files;

class File implements FileInterface
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
        if (!is_file($this->path)) {
            throw new \InvalidArgumentException("Not a file: $this->path");
        }
    }
}
