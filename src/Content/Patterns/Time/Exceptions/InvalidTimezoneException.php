<?php

namespace Outpost\Content\Patterns\Time\Exceptions;
use Exception;

class InvalidTimezoneException extends Exception {
  protected $timezone;

  public function __construct($timezone, $message='', $code=0, $previous=NULL) {
    parent::__construct($message, $code, $previous);
    $this->timezone = $timezone;
  }
}
