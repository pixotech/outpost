<?php

namespace Outpost\Content\Patterns\Time;

class LocalTime implements LocalTimeInterface
{
    /**
     * @var int
     */
    protected $hour;

    /**
     * @var int
     */
    protected $minute;

    /**
     * @var int
     */
    protected $second;

    /**
     * @return int
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * @return int
     */
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * @return int
     */
    public function getSecond()
    {
        return $this->second;
    }

    /**
     * @param int $hour
     */
    public function setHour($hour)
    {
        $this->hour = $hour;
    }

    /**
     * @param int $minute
     */
    public function setMinute($minute)
    {
        $this->minute = $minute;
    }

    /**
     * @param int $second
     */
    public function setSecond($second)
    {
        $this->second = $second;
    }
}
