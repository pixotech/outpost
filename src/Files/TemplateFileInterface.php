<?php

namespace Outpost\Files;

interface TemplateFileInterface extends FileInterface
{
    public function getFixture();

    public function getTemplateName();

    public function hasFixture();
}
