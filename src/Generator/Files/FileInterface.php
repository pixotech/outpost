<?php

namespace Outpost\Generator\Files;

interface FileInterface
{
    public function getPath();

    public function getTime();

    public function put($path);
}
