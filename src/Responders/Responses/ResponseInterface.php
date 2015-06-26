<?php

namespace Outpost\Responders\Responses;

interface ResponseInterface {
  public function getContent();
  public function getHeaders();
  public function getStatusCode();
}