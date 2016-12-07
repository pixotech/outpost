<?php

namespace Outpost\Log;

class Timestamp
{
    /**
     * @var \DateTime
     */
    protected $time;

    public function __construct(\DateTime $time)
    {
        $this->time = $time;
    }

    public function __toString()
    {
        $date = $this->time->format('ymd');
        $clock = $this->time->format('H:i:s');
        $micro = $this->time->format('u');
        return "{$date} {$clock} {$micro}";
    }
}
