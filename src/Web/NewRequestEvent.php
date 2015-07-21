<?php

namespace Outpost\Web;

use Outpost\Events\Event;
use Outpost\Events\EventMessage;
use Outpost\Web\Requests\RequestInterface;

class NewRequestEvent extends Event {

  public function __construct(RequestInterface $request) {
    parent::__construct();
    $this->request = $request;
  }

  public function getColor() {
    return EventMessage::WHITE_ON_YELLOW;
  }

  public function getLocation() {
    return "Web";
  }

  public function getLogMessage() {
    return sprintf("%s %s", $this->request->getRequestMethod(), $this->request->getRequestUrl());
  }
}