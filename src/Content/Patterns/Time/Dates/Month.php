<?php

namespace Outpost\Content\Patterns\Time\Dates;

class Month implements MonthInterface, DatespanInterface
{

    /**
     * @var int
     */
    protected $number;

    /**
     * @var YearInterface
     */
    protected $year;

    public function __construct($number = null, $year = null)
    {
        if (isset($number)) {
            $this->setNumber($number);
        }
        if (isset($year)) {
            if (!($year instanceof YearInterface)) {
                $year = new Year($year);
            }
            $this->setYear($year);
        }
    }

    /**
     * @param int $number
     * @return DateInterface
     */
    public function getDay($number)
    {
        if ($number < 1 || $number > $this->getNumberOfDays()) throw new \OutOfRangeException();
        return new Date($this->year->getNumber(), $this->number, $number);
    }

    /**
     * @return DateInterface
     */
    public function getEnd()
    {
        return $this->getDay($this->getNumberOfDays());
    }

    /**
     * @param string $format
     * @return string
     */
    public function getName($format = 'F')
    {
        return $this->getDateTime()->format($format);
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
        return (int)$this->getDateTime()->format('t');
    }

    /**
     * @return DateInterface
     */
    public function getStart()
    {
        return $this->getDay(1);
    }

    /**
     * @return YearInterface
     */
    public function getYear()
    {
        return $this->year;
    }

    public function setNumber($number)
    {
        if ($number < 1 || $number > 12) {
            throw new \OutOfRangeException("Invalid month");
        }
        $this->number = $number;
    }

    public function setYear(YearInterface $year)
    {
        $this->year = $year;
    }

    /**
     * @return \DateTime
     */
    protected function getDateTime()
    {
        return \DateTime::createFromFormat('!m', $this->getNumber());
    }
}
