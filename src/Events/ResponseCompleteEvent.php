<?php

namespace Outpost\Events;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseCompleteEvent extends Event {

  public function __construct(Response $response, Request $request) {
    parent::__construct();
    $this->response = $response;
    $this->request = $request;
  }

  /**
   * @return string
   */
  public function getLogMessage() {
    return sprintf("Complete: %s %s", $this->response->getStatusCode(), $this->request->getPathInfo());
  }
}