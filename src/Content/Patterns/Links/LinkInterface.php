<?php

namespace Outpost\Content\Patterns\Links;

interface LinkInterface
{
    /**
     * @return LabelInterface
     */
    public function getLabel();

    /**
     * @return UrlInterface
     */
    public function getUrl();
}
