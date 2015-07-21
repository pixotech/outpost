<?php

namespace Outpost\Events;

class EventMessage {

  const BLACK_ON_CYAN = '%6%K';
  const BLACK_ON_GREEN = '%2%K';
  const BLACK_ON_YELLOW = '%3%K';
  const WHITE_ON_BLUE = '%4%W';
  const WHITE_ON_CYAN = '%6%W';
  const WHITE_ON_GREEN = '%2%W';
  const WHITE_ON_RED = '%1%W';
  const WHITE_ON_YELLOW = '%3%W';

  public function __construct(EventInterface $event) {
    $this->event = $event;
  }

  public function __toString() {
    return (string) $this->toString();
  }

  public function toString($color = true) {
    $timestamp = $this->getTimestamp();
    $location = strtoupper($this->event->getLocation());
    $message = $this->event->getLogMessage();
    return "$timestamp  %_{$location}:%n $message";
  }

  public function getLocation() {
    return sprintf("%s %s %s", $this->event->getColor(), str_pad($this->event->getLocation(), 10), "%n");
  }

  public function getTimestamp() {
    $color = $this->event->getColor();
    switch ($color) {
      case self::BLACK_ON_CYAN:
      case self::WHITE_ON_CYAN:
        $color2 = '%c';
        break;
      case self::BLACK_ON_GREEN:
      case self::WHITE_ON_GREEN:
        $color2 = '%g';
        break;
      case self::BLACK_ON_YELLOW:
      case self::WHITE_ON_YELLOW:
        $color2 = '%y';
        break;
      case self::WHITE_ON_RED:
        $color2 = '%r';
        break;
      default:
        $color2 = '';
    }
    $date = $this->event->getTime()->format('ymd');
    $clock = $this->event->getTime()->format('H:i:s');
    $micro = $this->event->getTime()->format('u');
    return "{$color2}{$date}%n {$color} {$clock} %n {$color2}{$micro}%n";
  }
}