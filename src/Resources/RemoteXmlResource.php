<?php

namespace Outpost\Resources;

class RemoteXmlResource extends RemoteResource {

  protected $config = [];

  public function __construct($url = '', array $config = []) {
    parent::__construct($url);
    $this->config = $config;
  }

  public function __invoke() {
    return parent::__invoke()->xml($this->config);
  }
}