<?php

namespace Outpost\Events;

use Symfony\Component\HttpFoundation\Request;

class RequestReceivedEvent extends Event {

  public function __construct(Request $request) {
    parent::__construct();
    $this->request = $request;
  }

  public function getColor() {
    return EventMessage::BLACK_ON_CYAN;
  }

  public function getLocation() {
    return "Request";
  }

  /**
   * @return string
   */
  public function getLogMessage() {
    return $this->request->getPathInfo();
  }
}