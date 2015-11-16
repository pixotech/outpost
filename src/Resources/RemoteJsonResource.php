<?php

namespace Outpost\Resources;

class RemoteJsonResource extends RemoteResource {

  protected $config = [];

  public function __config(array $config = []) {
    $this->config = $config;
  }

  public function __invoke() {
    return parent::__invoke()->json($this->config);
  }
}