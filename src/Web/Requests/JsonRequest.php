<?php

namespace Outpost\Web\Requests;

use GuzzleHttp\Message\ResponseInterface;

class JsonRequest extends Request {

  public function processResponse(ResponseInterface $response) {
    return $response->json();
  }
}