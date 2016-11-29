<?php

namespace Outpost\Content\Patterns\Time;

interface LocalTimeInterface
{
    /**
     * @return int
     */
    public function getHour();

    /**
     * @return int
     */
    public function getMinute();

    /**
     * @return int
     */
    public function getSecond();
}
