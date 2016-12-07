<?php

namespace Outpost\Events;

use Monolog\Logger;

abstract class Event implements EventInterface
{
    public function __construct()
    {
        $this->time = $this->makeTime();
    }

    public function getLocation()
    {
        return "Site";
    }

    /**
     * @return string
     */
    public function getLogLevel()
    {
        return Logger::INFO;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function makeTime()
    {
        $time = microtime(true);
        $micro = sprintf("%06d", ($time - floor($time)) * 1000000);
        return new \DateTime(date('Y-m-d H:i:s.' . $micro, $time));
    }
}
