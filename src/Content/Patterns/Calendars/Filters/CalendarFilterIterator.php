<?php

namespace Outpost\Content\Patterns\Calendars\Filters;

use Outpost\Content\Patterns\Calendars\Calendar;
use Outpost\Content\Patterns\Calendars\CalendarInterface;

abstract class CalendarFilterIterator extends \FilterIterator implements CalendarFilterIteratorInterface {

  protected $calendar;

  public function __construct(CalendarInterface $calendar) {
    $this->calendar = $calendar;
    parent::__construct(new \IteratorIterator($calendar));
  }

  public function getCalendar() {
    return $this->calendar;
  }

  public function getFilteredCalendar() {
    return new Calendar(iterator_to_array($this, false));
  }
}
