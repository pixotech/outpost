<?php

namespace Outpost\Content\Patterns\Time\Calendar;
use DateTimeZone;
use Outpost\Content\Patterns\Time\Day as DayObject;
use Outpost\Content\Patterns\Time\Dates\Month as MonthObject;

require_once __DIR__ . '/Calendar.php';

class Day extends Calendar {

  protected $day;
  protected $month;

  public function __construct(DayObject $day, MonthObject $month=NULL) {
    $this->day = $day;
    $this->month = $month;
  }

  public function getStartTime() {
    return $this->day->getStartTime();
  }

  public function getEndTime() {
    return $this->day->getEndTime();
  }

  public function number() {
    return $this->day->getNumber();
  }

  public function is_today() {
    return $this->day->isToday();
  }

  public function in_month() {
    return isset($this->month) ? $this->month->containsDay($this->day) : TRUE;
  }

  public function day() {
    return $this->day;
  }

  public function month() {
    return $this->day->getMonth();
  }

  public function year() {
    return $this->day->getYear();
  }
}
