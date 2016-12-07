<?php

namespace Outpost\Events;

use Symfony\Component\HttpFoundation\Request;

class RequestReceivedEvent extends Event
{
    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    public function getLocation()
    {
        return "Request";
    }

    /**
     * @return string
     */
    public function getLogMessage()
    {
        return sprintf("%s %s", $this->request->getMethod(), $this->request->getPathInfo());
    }
}
