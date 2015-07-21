<?php

namespace Outpost\Events;

class ErrorEvent extends Event {

  public function __construct($level, $message, $file, $line) {
    parent::__construct();
    $this->level = $level;
    $this->message = $message;
    $this->file = $file;
    $this->line = $line;
  }

  public function getColor() {
    return EventMessage::WHITE_ON_RED;
  }

  public function getLogMessage() {
    return $this->message;
  }
}