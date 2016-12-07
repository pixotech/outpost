<?php

namespace Outpost\Events;

use Outpost\Log\Color;
use Outpost\Log\String;
use Outpost\Log\Timestamp;

class EventMessage
{
    public function __construct(EventInterface $event)
    {
        $this->event = $event;
    }

    public function __toString()
    {
        $timestamp = new Timestamp($this->event->getTime());
        $location = str_pad(strtoupper($this->event->getLocation()), 12, '.');
        $message = $this->event->getLogMessage();
        $str = (string) new String([$timestamp, '  ', $location, ' ', $message], new Color(Color::CYAN));
        return $str . "\n";
    }
}
