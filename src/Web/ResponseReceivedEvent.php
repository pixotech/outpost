<?php

namespace Outpost\Web;

use Outpost\Events\Event;

class ResponseReceivedEvent extends Event {

  public function __construct($response, $request) {
    parent::__construct();
    $this->response = $response;
    $this->request = $request;
  }

  public function getLocation() {
    return "Web";
  }

  public function getLogMessage() {
    return "Response received";
  }
}