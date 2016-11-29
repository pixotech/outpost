<?php

namespace Outpost\Content\Patterns\Time\Calendar;
use Outpost\Content\Patterns\Time\Dates\Month as MonthObject;
use Outpost\Content\Patterns\Time\Dates\Week as WeekObject;

require_once __DIR__ . '/Calendar.php';
require_once __DIR__ . '/../Month.php';
require_once __DIR__ . '/../Week.php';

class Week extends Calendar {

  protected $week;
  protected $month;

  public function __construct(WeekObject $week, MonthObject $month=NULL) {
    $this->week = $week;
    $this->month = $month;
  }

  public function getStartTime() {
    return $this->week->getStartTime();
  }

  public function getEndTime() {
    return $this->week->getEndTime();
  }

  public function days() {
    include_once __DIR__ . '/Day.php';
    $days = array();
    foreach ($this->week->getDays() as $day) {
      $day = new Day($day, $this->month);
      $day->useTimeline($this->timeline);
      $days[] = $day;
    }
    return $days;
  }

  public function in_month() {
    return isset($this->month) ? $this->month->containsWeek($this->week) : TRUE;
  }
}
