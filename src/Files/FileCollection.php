<?php

namespace Outpost\Files;

use Outpost\Content\Patterns\Collections\Collection;

class FileCollection extends Collection implements FileCollectionInterface
{
    public function add($file)
    {
        if (!($file instanceof FileInterface)) {
            throw new \InvalidArgumentException("Not a file");
        }
        parent::add($file);
    }

    protected function getSortCallback()
    {
        return function (FileInterface $a, FileInterface $b) {
            $aTime = $a->getTimeModified();
            $bTime = $b->getTimeModified();
            return $aTime == $bTime ? 0 : ($aTime > $bTime ? -1 : 1);
        };
    }
}
