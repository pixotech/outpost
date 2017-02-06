<?php

namespace Outpost\Routing;

use Symfony\Component\HttpFoundation\Request;

class UnavailableMethodException extends DispatchException
{
    protected $availableMethods;

    protected $request;

    public function __construct(Request $request, array $availableMethods)
    {
        parent::__construct($request);
        $this->availableMethods = $availableMethods;
    }
}