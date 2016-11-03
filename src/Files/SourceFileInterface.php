<?php

namespace Outpost\Files;

interface SourceFileInterface extends FileInterface
{
    public function resolveClassName($className);
}
