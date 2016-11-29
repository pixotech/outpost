<?php

namespace Outpost\Content\Patterns\Time\Dates;

class Datespan implements DatespanInterface
{

    /**
     * @var DateInterface
     */
    protected $end;

    /**
     * @var DateInterface
     */
    protected $start;

    /**
     * @param DateInterface $start
     * @param DateInterface $end
     */
    public function __construct(DateInterface $start = null, DateInterface $end = null)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return DateInterface
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return DateInterface
     */
    public function getStart()
    {
        return $this->start;
    }

    public function setEnd(DateInterface $end)
    {
        $this->end = $end;
    }

    public function setStart(DateInterface $start)
    {
        $this->start = $start;
    }
}
