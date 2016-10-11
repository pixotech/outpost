<?php

namespace Outpost\Content;

interface PropertyInterface
{
    /**
     * @return string
     */
    public function getCallback();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getVariable();
}
