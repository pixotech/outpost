<?php

namespace Outpost\Content\Patterns\Calendars;

use Outpost\Content\Patterns\Collections\Collection;

class Calendar extends Collection implements \Countable, \JsonSerializable, CalendarInterface
{
    /**
     * @var EventInterface[]
     */
    protected $events = [];

    /**
     * @param EventInterface[] $events
     */
    public function __construct(array $events = [])
    {
        foreach ($events as $event) {
            $this->add($event);
        }
    }

    /**
     * @param EventInterface $event
     */
    public function add(EventInterface $event)
    {
        $this->events[] = $event;
        $this->sort();
    }

    public function count()
    {
        return count($this->events);
    }

    /**
     * @return EventInterface[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->events);
    }

    public function jsonSerialize()
    {
        return $this->events;
    }

    protected function sort()
    {
        usort($this->events, $cmp);
    }
}
