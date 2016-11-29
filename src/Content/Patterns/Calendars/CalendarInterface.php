<?php

namespace Outpost\Content\Patterns\Calendars;

interface CalendarInterface extends \IteratorAggregate
{
    /**
     * @param EventInterface $event
     */
    public function add(EventInterface $event);

    /**
     * @return EventInterface[]
     */
    public function getEvents();
}
