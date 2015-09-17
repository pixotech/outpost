<?php

namespace Outpost;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Responder implements ResponderInterface {

  protected $site;

  public function __construct(SiteInterface $site) {
    $this->site = $site;
  }

  public function __invoke(Request $request = null) {
    if (!isset($request)) $request = Request::createFromGlobals();
    try {
      $response = $this->site->makeResponse($request);
    }
    catch (\Exception $e) {
      $response = new Response('', 500);
    }
    $response->prepare($request);
    $response->send();
  }
}