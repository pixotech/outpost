<?php

namespace Outpost\Routing;

use Symfony\Component\HttpFoundation\Request;

abstract class DispatchException extends \RuntimeException
{
    protected $request;

    public function __construct(Request $request, $message = null, \Exception $previous = null)
    {
        $this->request = $request;
        parent::__construct($message, 0, $previous);
    }
}