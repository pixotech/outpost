<?php

namespace Outpost\Content;

interface VariablesInterface
{
    /**
     * @param string $name
     * @return mixed
     */
    public function get($name);

    /**
     * @return array
     */
    public function getVariables();
}

