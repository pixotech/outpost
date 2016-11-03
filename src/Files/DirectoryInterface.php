<?php

namespace Outpost\Files;

interface DirectoryInterface
{
    /**
     * @param string $extension
     * @return array
     */
    public function getFilesWithExtension($extension);
}
