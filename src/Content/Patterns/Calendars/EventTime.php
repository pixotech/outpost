<?php

namespace Outpost\Content\Patterns\Calendars;

use Outpost\Content\Patterns\Time\Dates\Date;
use Outpost\Content\Patterns\Time\Dates\DateInterface;
use Outpost\Content\Patterns\Time\InstanceInterface;
use Outpost\Content\Patterns\Time\Time;
use Outpost\Content\Patterns\Time\TimeInterface;

class EventTime implements EventTimeInterface
{
    protected $allDay = false;

    protected $end;

    protected $start;

    public function getStart()
    {
        return $this->start;
    }

    public function getEnd()
    {
        if (!$this->hasEnd()) {
            throw new \BadMethodCallException("Event does not have end");
        }
        return $this->end;
    }

    public function hasEnd()
    {
        return !empty($this->end);
    }

    public function isAllDay()
    {
        return (bool)$this->allDay;
    }

    public function setEnd(InstanceInterface $end) {
        $this->validateInstance($end);
        $this->end = $end;
    }

    public function setStart(InstanceInterface $start) {
        $this->validateInstance($start);
        $this->start = $start;
    }

    protected function makeTimeInstance($source)
    {
        return $this->isAllDay() ? new Date($source) : new Time($source);
    }

    protected function validateInstance(InstanceInterface $instance)
    {
        if ($this->isAllDay() && !($instance instanceof DateInterface)) {
            throw new \InvalidArgumentException("Event start must be a date");
        } elseif (!$this->isAllDay() && !($instance instanceof TimeInterface)) {
            throw new \InvalidArgumentException("Event start must be a time");
        }
    }
}
