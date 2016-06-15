<?php

namespace Outpost\Routing;

class Response implements ResponseInterface
{
    protected $responder;

    protected $parameters = [];

    public function __construct(callable $responder)
    {
        $this->responder = $responder;
    }

    public function __invoke()
    {
        $this->parameters = func_get_args();
        return $this;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getResponder()
    {
        return $this->responder;
    }
}