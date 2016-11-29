<?php

namespace Outpost\Content\Patterns\Calendars;

use Outpost\Content\Patterns\Time\Dates\DateInterface;
use Outpost\Content\Patterns\Time\Dates\DatespanInterface;
use Outpost\Content\Patterns\Time\TimeInterface;
use Outpost\Content\Patterns\Time\TimespanInterface;

class Duration implements DurationInterface {

  const DATE = 'date';
  const DATESPAN = 'datespan';
  const TIME = 'time';
  const TIMESPAN = 'timespan';

  protected $time;

  public static function getScope($time) {
    switch (true) {
      case $time instanceof DateInterface:
        return self::DATE;
      case $time instanceof DatespanInterface:
        return self::DATESPAN;
      case $time instanceof TimeInterface:
        return self::TIME;
      case $time instanceof TimespanInterface:
        return self::TIMESPAN;
      default:
        throw new \InvalidArgumentException();
    }
  }
}
