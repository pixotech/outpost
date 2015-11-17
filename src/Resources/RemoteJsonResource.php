<?php

namespace Outpost\Resources;

class RemoteJsonResource extends RemoteResource {

  protected $config = [];

  public function __construct($url = '', array $config = []) {
    parent::__construct($url);
    $this->config = $config;
  }

  public function __invoke() {
    return parent::__invoke()->json($this->config);
  }
}