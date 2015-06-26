<?php

namespace Outpost\Html;

use Outpost\Responders\Responses\ResponseInterface;

class Page extends Document implements ResponseInterface {

  public function getContent() {
    return $this->toString();
  }

  public function getHeaders() {
    return [];
  }

  public function getStatusCode() {
    return 200;
  }
}