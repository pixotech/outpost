<?php

namespace Outpost\Http;

use Outpost\Events\Event;
use Symfony\Component\HttpFoundation\Response;

class ResponseReceivedEvent extends Event
{
    public function __construct($method, $url, $code = 200)
    {
        parent::__construct();
        $this->method = $method;
        $this->url = $url;
        $this->code = $code;
    }

    public function getLocation()
    {
        return "HTTP";
    }

    public function getLogMessage()
    {
        $status = Response::$statusTexts[$this->code];
        return sprintf("RESPONSE: %s %s (%s)", $this->method, $this->url, $status);
    }
}
