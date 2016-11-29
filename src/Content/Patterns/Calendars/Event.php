<?php

namespace Outpost\Content\Patterns\Calendars;

class Event implements EventInterface
{
    protected $eventTime;

    public function getEventTime()
    {
        return $this->eventTime;
    }

    public function setEventTime($time)
    {
        $this->eventTime = $time;
    }
}
