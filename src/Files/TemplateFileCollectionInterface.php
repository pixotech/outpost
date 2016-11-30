<?php

namespace Outpost\Files;

interface TemplateFileCollectionInterface extends FileCollectionInterface
{
    /**
     * @param string $name
     * @return TemplateFileInterface
     * @throws \OutOfBoundsException
     */
    public function find($name);
}
