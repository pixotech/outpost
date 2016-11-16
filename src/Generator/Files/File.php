<?php

namespace Outpost\Generator\Files;

use Outpost\Generator\Paths\Path;

class File implements FileInterface
{
    protected $content = '';

    protected $path;

    public function __construct($path, $content = '')
    {
        $this->path = $path;
        $this->content = $content;
    }

    public function getPath()
    {
        return new Path($this->path);
    }

    public function getTime()
    {
        return time();
    }

    public function put($path)
    {
        file_put_contents($path, $this->content);
    }

    protected function makePath(array $segments)
    {
        return implode(DIRECTORY_SEPARATOR, $segments);
    }
}
