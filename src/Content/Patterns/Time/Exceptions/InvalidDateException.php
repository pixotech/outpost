<?php

namespace Outpost\Content\Patterns\Time\Exceptions;
use Exception;

class InvalidDateException extends Exception {
  protected $date;

  public function __construct($date, $message='', $code=0, $previous=NULL) {
    $message = t("The computer doesn't understand %date. It doesn't look a valid date.", array('%date' => $date));
    parent::__construct($message, $code, $previous);
    $this->date = $date;
  }
}
