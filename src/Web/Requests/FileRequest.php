<?php

namespace Outpost\Web\Requests;

use GuzzleHttp\Message\ResponseInterface;

class FileRequest extends Request {

  public function getRequestOptions() {
    return ['stream' => true];
  }

  public function processResponse(ResponseInterface $response) {
    return $response->getBody();
  }
}