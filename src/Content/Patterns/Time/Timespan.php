<?php

namespace Outpost\Content\Patterns\Time;

class Timespan implements TimespanInterface
{
    /**
     * @var TimeInterface
     */
    protected $end;

    /**
     * @var TimeInterface
     */
    protected $start;

    /**
     * @param TimeInterface $start
     * @param TimeInterface $end
     */
    public function __construct(TimeInterface $start, TimeInterface $end)
    {
        if (!$end->isAfter($start)) {
            throw new \OutOfBoundsException("End time must be after start time");
        }
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return TimeInterface
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return TimeInterface
     */
    public function getStart()
    {
        return $this->start;
    }
}
