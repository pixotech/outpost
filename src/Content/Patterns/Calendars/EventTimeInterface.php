<?php

namespace Outpost\Content\Patterns\Calendars;

interface EventTimeInterface
{

    public function getStart();

    public function getEnd();

    /**
     * @return bool
     */
    public function hasEnd();

    /**
     * @return bool
     */
    public function isAllDay();
}
