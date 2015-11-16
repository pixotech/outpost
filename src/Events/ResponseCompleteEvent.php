<?php

namespace Outpost\Events;

use Symfony\Component\HttpFoundation\Request;

class ResponseCompleteEvent extends Event {

  public function __construct(Request $request, $status = 200) {
    parent::__construct();
    $this->request = $request;
    $this->status = $status;
  }

  /**
   * @return string
   */
  public function getLogMessage() {
    return sprintf("Complete: %s [%s]", $this->request->getPathInfo(), $this->status);
  }
}