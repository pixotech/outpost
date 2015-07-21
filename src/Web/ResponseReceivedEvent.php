<?php

namespace Outpost\Web;

use GuzzleHttp\Message\Response;
use Outpost\Events\Event;
use Outpost\Events\EventMessage;
use Outpost\Web\Requests\RequestInterface;

class ResponseReceivedEvent extends Event {

  public function __construct(Response $response, RequestInterface $request) {
    parent::__construct();
    $this->response = $response;
    $this->request = $request;
  }

  public function getColor() {
    return $this->response->getStatusCode() == 200 ? EventMessage::WHITE_ON_GREEN : EventMessage::WHITE_ON_RED;
  }

  public function getLocation() {
    return "Web";
  }

  public function getLogMessage() {
    return sprintf("%s: %s %s", $this->response->getReasonPhrase(), $this->request->getRequestMethod(), $this->request->getRequestUrl());
  }
}