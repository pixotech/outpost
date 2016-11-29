<?php

namespace Outpost\Content\Patterns\Time\Calendar;
use Outpost\Content\Patterns\Time\Dates\Month as MonthObject;

require_once __DIR__ . '/Calendar.php';

class Month extends Calendar {

  protected $month;

  public function __construct(MonthObject $month) {
    $this->month = $month;
  }

  public function getStartTime() {
    return $this->month->getStartTime();
  }

  public function getEndTime() {
    return $this->month->getEndTime();
  }

  public function month() {
    return $this->month;
  }

  public function weeks() {
    include_once __DIR__ . '/Week.php';
    $weeks = array();
    foreach ($this->month->getWeeks() as $week) {
      $week = new Week($week, $this->month);
      $week->useTimeline($this->timeline);
      $weeks[] = $week;
    }
    return $weeks;
  }

  function ktq_midnight($date) {
    return strtotime('midnight', $date);
  }

  function ktq_date_range($start_date, $end_date) {
    $dates = array();
    $i = ktq_date_index(ktq_midnight($start_date));
    $end_i = ktq_date_index(ktq_midnight($end_date));
    while ($i <= $end_i) {
      $dates[$i] = $i;
      $i = ktq_date_index(strtotime('+1 day', strtotime($i)));
    }
    return $dates;
  }

  function ktq_date_index($time) {
    return date('Y-m-d', $time);
  }


  function ktq_events_calendar_page($year=NULL, $month=NULL) {
    module_load_include('inc', 'ktq_events', 'ktq_events.api');

    if (!$year) {
      $year = date('Y');
      $month = date('F');
    }
    if (!$month) {
      $month = date('F');
    }

    $month = strtotime("{$month} 1, {$year}");
    $start_date = ktq_start_of_calendar(ktq_start_of_month($month));
    $end_date = ktq_end_of_calendar(ktq_end_of_month($month));
    $days = ktq_date_range($start_date, $end_date);

    $dates = ktq_get_events(array('after' => $start_date, 'before' => strtotime('+1 day', $end_date)));
    $events = node_load_multiple(array_keys($dates));
    foreach ($events as $event) {
      $key = substr($dates[$event->nid], 0, 10);
      if (!isset($days[$key])) continue;
      if (!is_array($days[$key])) $days[$key] = array();
      $days[$key][] = theme('event_mini', array('event' => $event, 'date' => FALSE));

    }

    $vars['month'] = $month;
    $vars['weeks'] = array_chunk($days, 7, TRUE);

    $last_month = strtotime('-1 month', $month);
    $vars['last_month'] = date('F Y', $last_month);
    $vars['last_url'] = ktq_calendar_url($last_month);

    $next_month = strtotime('+1 month', $month);
    $vars['next_month'] = date('F Y', $next_month);
    $vars['next_url'] = ktq_calendar_url($next_month);

    drupal_set_title(date('F Y', $month));
    return theme('calendar_page', $vars);
  }

  protected function start_time() {

  }

  function kartemquin_preprocess_calendar(&$vars) {

    // Start date
    $start_date = isset($vars['start_date']) ? $vars['start_date'] : date('Y-m-d');
    $start_time = strtotime("$start_date 00:00:00");

    // Start time is midnight of the first day of the month
    $start_of_month = mktime(0, 0, 0, date('n', $start_time), 1, date('Y', $start_time));

    // Number of days in the month
    $days_in_month = date('t', $start_of_month);

    // Find the number of days on the calendar before the first day
    $days_before = (int)date('w', $start_of_month);

    // The start of the calendar includes the days before
    $start_of_calendar = $days_before ? strtotime("-{$days_before} days", $start_of_month) : $start_of_month;

    // Last day of month
    $start_of_next_month = strtotime('+1 month', $start_of_month);

    // Unless the next month starts at the beginning of the week, we have days at the end
    $days_after = (int)date('w', $start_of_next_month);
    if ($days_after > 0) {
      $days_after = 7 - $days_after;
    }

    // The end of the calendar includes the days after
    $end_of_calendar = $days_after ? strtotime("+{$days_after} days", $start_of_next_month) : $start_of_next_month;

    // Event data
    if (!empty($vars['days'])) {
      $events = $vars['days'];
    }
    else {
      $events = ktq_events_calendar_data($start_of_calendar, $end_of_calendar);
    }

    $days_in_calendar = $days_before + $days_in_month + $days_after;
    $weeks_in_calendar = ceil($days_in_calendar/7);

    $vars['month_name'] = date('F', $start_of_month);

    $vars['weeks'] = array();
    $output = '<table class="ktq-calendar">';
    for ($week_i = 0; $week_i < $weeks_in_calendar; $week_i++) {
      $week = array();
      for ($day_i = 0; $day_i < 7; $day_i++) {

        $days_since_start = ($week_i * 7) + $day_i;
        $day = $days_since_start ? strtotime("+{$days_since_start} days", $start_of_calendar) : $start_of_calendar;

        $is_today = date('Y-m-d', $day) == date('Y-m-d');
        $in_month = date('Y-m', $day) == date('Y-m', $start_of_month);
        $has_events = !empty($events[date('Y-m-d', $day)]);

        $day_classes = array('day', $in_month ? 'in-month' : 'not-in-month', $has_events ? 'has-events' : 'no-events');
        if ($is_today) $day_classes[] = 'today';

        $week[] = array(
          'day' => $day,
          'classes' => $day_classes,
        );
      }
      $vars['weeks'][] = $week;
    }
  }

}
