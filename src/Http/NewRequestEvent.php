<?php

namespace Outpost\Http;

use Outpost\Events\Event;

class NewRequestEvent extends Event
{
    protected $method;

    protected $url;

    public function __construct($method, $url)
    {
        parent::__construct();
        $this->method = $method;
        $this->url = $url;
    }

    public function getLocation()
    {
        return "HTTP";
    }

    public function getLogMessage()
    {
        return sprintf("REQUEST: %s %s", $this->method, $this->url);
    }
}
