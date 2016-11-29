<?php

namespace Outpost\Content\Patterns\Calendars\Filters;

interface CalendarFilterIteratorInterface {

  /**
   * @return \Outpost\Content\Patterns\Calendars\CalendarInterface
   */
  public function getCalendar();

  /**
   * @return \Outpost\Content\Patterns\Calendars\CalendarInterface
   */
  public function getFilteredCalendar();
}
