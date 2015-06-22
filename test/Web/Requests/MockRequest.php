<?php

namespace Outpost\Web\Requests;

use GuzzleHttp\Message\ResponseInterface;

class MockRequest implements RequestInterface {

  public $body;
  public $headers = [];
  public $method;
  public $options = [];
  public $url;

  public function getRequestBody() {
    return $this->body;
  }

  public function getRequestHeaders() {
    return $this->headers;
  }

  public function getRequestMethod() {
    return $this->method;
  }

  public function getRequestOptions() {
    return $this->options;
  }

  public function getRequestUrl() {
    return $this->url;
  }

  public function processResponse(ResponseInterface $response) {
    return $response;
  }

  public function validateResponse(ResponseInterface $response) {
  }
}