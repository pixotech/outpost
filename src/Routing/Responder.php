<?php

namespace Outpost\Routing;

use Outpost\Resources\SiteResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class Responder extends SiteResource implements ResponderInterface {

  protected $request;

  public function getRequest() {
    return $this->request;
  }

  public function setRequest(Request $request) {
    $this->request = $request;
  }

  protected function respond($content = '', $status = 200, $headers = []) {
    $response = new Response($content, $status, $headers);
    $response->prepare($this->getRequest());
    $response->send();
  }
}