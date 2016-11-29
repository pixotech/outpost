<?php

namespace Outpost\Content\Patterns\Time\Exceptions;
use Exception;

class InvalidTimeException extends Exception {
  protected $time;

  public function __construct($time, $message='', $code=0, $previous=NULL) {
    $message = t("The computer doesn't understand %time. It doesn't look a valid time.", array('%time' => $time));
    parent::__construct($message, $code, $previous);
    $this->time = $time;
  }
}
