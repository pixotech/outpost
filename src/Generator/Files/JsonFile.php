<?php

namespace Outpost\Generator\Files;

class JsonFile extends File implements JsonFileInterface
{
    public static function encode($data)
    {
        return json_encode($data, JSON_PRETTY_PRINT);
    }

    public function __construct($path, $data)
    {
        parent::__construct($path, self::encode($data));
    }

    public function getTime()
    {
        return time();
    }
}
