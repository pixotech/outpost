<?php

namespace Outpost\Content\Patterns\Time\Dates;

class Date implements DateInterface
{
    /**
     * @var int
     */
    protected $dayOfMonth;

    /**
     * @var MonthInterface
     */
    protected $month;

    public static function fromDateTime(\DateTime $datetime)
    {
        $month = (int)$datetime->format('n');
        $day = (int)$datetime->format('j');
        $year = (int)$datetime->format('Y');

        $date = new static();
        $date->setMonth(new Month($month, $year));
        $date->setDayOfMonth($day);
        return $date;
    }

    /**
     * @return int
     */
    public function getDayOfMonth()
    {
        return $this->dayOfMonth;
    }

    /**
     * @return MonthInterface
     */
    public function getMonth()
    {
        return $this->month;
    }

    public function setDayOfMonth($day)
    {
        if ($day < 1 || $day > $this->getMonth()->getNumberOfDays()) {
            throw new \OutOfRangeException();
        }
        $this->dayOfMonth = $day;
    }

    public function setMonth(MonthInterface $month)
    {
        $this->month = $month;
    }
}
