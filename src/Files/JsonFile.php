<?php

namespace Outpost\Files;

class JsonFile extends File implements JsonFileInterface
{
    public function parse()
    {
        return json_decode($this->getContents(), true);
    }
}
