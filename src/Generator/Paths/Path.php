<?php

namespace Outpost\Generator\Paths;

class Path implements PathInterface
{
    protected $segments = [];

    public static function split($path)
    {
        return preg_split('|[\\\/]+|', $path, -1, PREG_SPLIT_NO_EMPTY);
    }

    public function __construct($path)
    {
        $this->segments = self::split($path);
    }

    public function __toString()
    {
        return implode(DIRECTORY_SEPARATOR, $this->segments);
    }
}
