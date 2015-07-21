<?php

namespace Outpost\Web;

use Outpost\Events\Event;

class NewRequestEvent extends Event {

  public function __construct($request) {
    parent::__construct();
    $this->request = $request;
  }

  public function getLocation() {
    return "Web";
  }

  public function getLogMessage() {
    return "Request sent";
  }
}