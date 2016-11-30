<?php

namespace Outpost\Files;

use Outpost\Content\Patterns\Collections\SearchableCollectionInterface;

class TemplateFileCollection extends FileCollection implements TemplateFileCollectionInterface, SearchableCollectionInterface
{
    public function add($file)
    {
        if (!($file instanceof TemplateFileInterface)) {
            throw new \InvalidArgumentException("Not a template file");
        }
        parent::add($file);
    }

    public function contains($name)
    {
        /** @var TemplateFileInterface $file */
        foreach ($this->getItems() as $file) {
            if ($file->getTemplateName() == $name) return true;
        }
        return false;
    }

    public function find($name)
    {
        /** @var TemplateFileInterface $file */
        foreach ($this->getItems() as $file) {
            if ($file->getTemplateName() == $name) return $file;
        }
        throw new \OutOfBoundsException("Unknown template: $name");
    }
}
