<?php

namespace Outpost\Events;

class ExceptionEvent extends Event {

  /**
   * @var \Exception
   */
  protected $exception;

  public function __construct(\Exception $exception) {
    parent::__construct();
    $this->exception = $exception;
  }

  public function getColor() {
    return EventMessage::WHITE_ON_RED;
  }

  public function getLocation() {
    return "Error";
  }

  public function getLogMessage() {
    return $this->exception->getMessage();
  }
}