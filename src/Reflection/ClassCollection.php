<?php

namespace Outpost\Reflection;

use Outpost\Content\Patterns\Collections\Collection;
use Outpost\Content\Patterns\Collections\SearchableCollectionInterface;

class ClassCollection extends Collection implements ClassCollectionInterface, SearchableCollectionInterface
{
    public function add($clas)
    {
        if (!($clas instanceof ReflectionClassInterface)) {
            throw new \InvalidArgumentException("Not a class");
        }
        parent::add($clas);
    }

    public function contains($name)
    {
        /** @var ReflectionClassInterface $file */
        foreach ($this->getItems() as $file) {
            if ($file->getName() == $name) return true;
        }
        return false;
    }

    public function find($name)
    {
        /** @var ReflectionClassInterface $file */
        foreach ($this->getItems() as $file) {
            if ($file->getName() == $name) return $file;
        }
        throw new \OutOfBoundsException("Unknown class: $name");
    }

    protected function getSortCallback()
    {
        return function (ReflectionClassInterface $a, ReflectionClassInterface $b) {
            $aTime = $a->getFile()->getTimeModified();
            $bTime = $b->getFile()->getTimeModified();
            return $aTime == $bTime ? 0 : ($aTime > $bTime ? -1 : 1);
        };
    }
}
