<?php

namespace Outpost\Events;

class EventMessage {

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
    $location = $this->getLocation();
    $message = $this->event->getLogMessage();
    return "$timestamp $location $message";
  }

  public function getLocation() {
    return sprintf("%s %s %s", $this->event->getColor(), str_pad($this->event->getLocation(), 10), "%n");
  }

  public function getTimestamp() {
    $clock = $this->event->getTime()->format('H:i:s');
    $micro = $this->event->getTime()->format('u');
    return "%w{$clock}%n%c.{$micro}%n";
  }
}