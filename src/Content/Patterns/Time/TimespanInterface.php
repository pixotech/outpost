<?php

namespace Outpost\Content\Patterns\Time;

interface TimespanInterface extends DurationInterface
{
    /**
     * @return TimeInterface
     */
    public function getEnd();

    /**
     * @return TimeInterface
     */
    public function getStart();
}
