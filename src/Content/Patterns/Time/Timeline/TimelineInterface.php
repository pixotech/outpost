<?php

namespace Outpost\Content\Patterns\Time\Timeline;

interface TimelineInterface
{
    /**
     * @param EventInterface $event
     */
    public function addEvent(EventInterface $event);

    /**
     * @return EventInterface[]
     */
    public function getEvents();

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * @param $start
     * @param $end
     * @return TimelineInterface
     */
    public function slice($start, $end);
}
