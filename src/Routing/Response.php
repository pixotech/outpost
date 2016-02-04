<?php

namespace Outpost\Routing;

use Outpost\SiteInterface;
use Symfony\Component\HttpFoundation\Request;

class Response implements ResponseInterface {

  protected $request;

  protected $responder;

  protected $site;

  public function __construct(SiteInterface $site, Request $request, callable $responder) {
    $this->site = $site;
    $this->request = $request;
    $this->responder = $responder;
  }

  public function __invoke() {
    call_user_func($this->getResponder(), $this->getSite(), $this->getRequest(), func_get_args());
  }

  protected function getRequest() {
    return $this->request;
  }

  protected function getResponder() {
    return $this->responder;
  }

  protected function getSite() {
    return $this->site;
  }
}