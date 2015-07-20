<?php

namespace Outpost\Web;

class ServiceResource extends WebResource {

  protected $path;
  protected $service;

  public function __construct(ServiceInterface $service, $path, $method = 'GET', array $query = []) {
    $this->service = $service;
    $this->path = $path;
    parent::__construct($service->makeRequestUrl($path), $method, $query);
  }

  public function getAuthentication() {
    return $this->service->getAuthentication();
  }
}