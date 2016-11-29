<?php

namespace Outpost\Content\Patterns\Time;

use Outpost\Content\Patterns\Time\Dates\DateInterface;

class Time implements TimeInterface
{
    /**
     * @var DateInterface
     */
    protected $date;

    /**
     * @var LocalTimeInterface
     */
    protected $localTime;

    /**
     * @var string
     */
    protected $timezone = 'UTC';

    /**
     * @return DateInterface
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return LocalTimeInterface
     */
    public function getLocalTime()
    {
        return $this->localTime;
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param DateInterface $date
     */
    public function setDate(DateInterface $date)
    {
        $this->date = $date;
    }

    /**
     * @param LocalTimeInterface $time
     */
    public function setLocalTime(LocalTimeInterface $time)
    {
        $this->localTime = $time;
    }

    /**
     * @param string $timezone
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }
}
