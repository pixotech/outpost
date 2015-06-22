<?php

namespace Outpost\Web\Requests;

use GuzzleHttp\Message\ResponseInterface;

abstract class JsonRequest extends Request {

  public function processResponse(ResponseInterface $response) {
    return $response->json();
  }
}