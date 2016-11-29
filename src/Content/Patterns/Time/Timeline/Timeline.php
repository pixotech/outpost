<?php

namespace Outpost\Content\Patterns\Time\Timeline;

class Timeline implements TimelineInterface
{
    /**
     * @var EventInterface[]
     */
    protected $events = array();

    /**
     * @param EventInterface[] $events
     */
    public function __construct(array $events = [])
    {
        array_map([$this, 'addEvent'], $events);
    }

    /**
     * @param EventInterface $event
     */
    public function addEvent(EventInterface $event)
    {
        $this->events[] = $event;
    }

    /**
     * @return EventInterface[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->events);
    }

    /**
     * @param $start
     * @param $end
     * @return Timeline
     */
    public function slice($start, $end)
    {
        $timeline = new Timeline();
        foreach ($this->getEvents() as $event) {
            if ($event->isAfter($start) && $event->isBefore($end)) {
                $timeline->addEvent($event);
            }
        }
        return $timeline;
    }
}
