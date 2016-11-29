<?php

namespace Outpost\Content\Patterns\Time\Dates;

class Year implements YearInterface, DatespanInterface
{

    /**
     * @var int
     */
    protected $number;

    public function __construct($number = null)
    {
        if (isset($number)) {
            $this->number = $number;
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getNumber();
    }

    /**
     * @param $number
     * @return DateInterface
     */
    public function getDay($number)
    {
        if ($number < 1 || $number > $this->getNumberOfDays()) {
            throw new \OutOfRangeException();
        }
        $date = \DateTime::createFromFormat('!Y-z', sprintf('%d-%d', $this->number, $number - 1));
        return new Date($date->format('Y'), $date->format('n'), $date->format('j'));
    }

    /**
     * @return DateInterface
     */
    public function getEnd()
    {
        return $this->getDay($this->getNumberOfDays());
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return int
     */
    public function getNumberOfDays()
    {
        return $this->isLeapYear() ? 366 : 365;
    }

    /**
     * @return DateInterface
     */
    public function getStart()
    {
        return $this->getDay(1);
    }

    /**
     * @return bool
     */
    public function isLeapYear()
    {
        return (bool)($this->toDateTime()->format('L'));
    }

    /**
     * @return \DateTime
     */
    public function toDateTime()
    {
        return \DateTime::createFromFormat('!Y', $this->getNumber());
    }
}
