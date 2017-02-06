<?php

namespace Outpost\Responders;

class ResourceException extends \RuntimeException
{
    protected $resource;

    public function __construct(callable $resource, \Exception $previous = null)
    {
        $this->resource = $resource;
        parent::__construct("Unavailable resource", 0, $previous);
    }
}
