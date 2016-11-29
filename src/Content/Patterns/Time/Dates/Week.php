<?php

namespace Outpost\Content\Patterns\Time\Dates;

use Outpost\Content\Patterns\Time\Day;

class Week {

  protected $first_day;

  public function __construct(Day $first_day) {
    $this->first_day = $first_day;
  }

  public function getStartTime() {
    return $this->first_day->getStartTime();
  }

  public function getEndTime() {
    return $this->getNextWeek()->getStartTime();
  }

  public function getPreviousWeek() {
    return new Week($this->first_day->minusDays(7));
  }

  public function getNextWeek() {
    return new Week($this->first_day->plusDays(7));
  }

  public function getFirstDay() {
    return $this->first_day;
  }

  public function getLastDay() {
    return $this->first_day->plusDays(6);
  }

  public function getDays() {
    $days = array();
    foreach (range(0, 6) as $i) {
      $days[] = !$i ? $this->first_day : $this->first_day->plusDays($i);
    }
    return $days;
  }

  public function getStartingDayOfWeek() {
    return 0;
  }
}
