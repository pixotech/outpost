<?php

namespace Outpost\Content\Patterns\Time\Calendar;
use Outpost\Content\Patterns\Time\TimelineInterface as Timeline;

require_once __DIR__ . '/../TimelineInterface.php';
require_once __DIR__ . '/CalendarInterface.php';

abstract class Calendar implements CalendarInterface {

  protected $timeline;

  public function __construct(Timeline $timeline) {
    $this->useTimeline($timeline);
  }

  public function useTimeline(Timeline $timeline) {
    $this->timeline = $timeline->slice($this->getStartTime(), $this->getEndTime());
  }

  public function has_events() {
    return !$this->timeline->isEmpty();
  }

  public function events() {
    return $this->timeline->getEvents();
  }
}
