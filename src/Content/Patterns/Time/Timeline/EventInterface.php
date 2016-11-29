<?php

namespace Outpost\Content\Patterns\Time\Timeline;

interface EventInterface
{
    /**
     * @param $time
     * @return bool
     */
    public function isBefore($time);

    /**
     * @param $time
     * @return bool
     */
    public function isAfter($time);
}
