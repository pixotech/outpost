<?php

namespace Outpost\Content\Patterns\Time;

interface TimeInterface extends InstanceInterface
{
    /**
     * @return \Outpost\Content\Patterns\Time\Dates\DateInterface
     */
    public function getDate();

    /**
     * @return LocalTimeInterface
     */
    public function getLocalTime();

    /**
     * @return string
     */
    public function getTimezone();
}
