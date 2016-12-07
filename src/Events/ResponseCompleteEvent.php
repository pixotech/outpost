<?php

namespace Outpost\Events;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseCompleteEvent extends Event
{
    public function __construct(Response $response, Request $request)
    {
        parent::__construct();
        $this->response = $response;
        $this->request = $request;
    }

    public function getLocation()
    {
        return "Response";
    }

    /**
     * @return string
     */
    public function getLogMessage()
    {
        $status = Response::$statusTexts[$this->response->getStatusCode()];
        return sprintf("%s %s (%s)", $this->request->getMethod(), $this->request->getPathInfo(), $status);
    }
}
