<?php

namespace Outpost\Content\Reflection;

class UnknownAliasException extends \OutOfBoundsException
{
    protected $alias;

    public function __construct($alias, $code = 0, \Exception $previous = null)
    {
        $this->alias = $alias;
        parent::__construct("Unknown alias: {$alias}", $code, $previous);
    }
}
