<?php

namespace Outpost\Templates;

interface RenderableInterface
{
    /**
     * @return string
     */
    public function getTemplate();

    /**
     * @return array
     */
    public function getTemplateContext();
}
