<?php

namespace Outpost\Routing;

use Outpost\SiteInterface;
use Phroute\Phroute\HandlerResolverInterface;
use Symfony\Component\HttpFoundation\Request;

class ResponderResolver implements HandlerResolverInterface {

  /**
   * @var Request
   */
  protected $request;

  /**
   * @var SiteInterface
   */
  protected $site;

  /**
   * @param SiteInterface $site
   * @param Request $request
   */
  public function __construct(SiteInterface $site, Request $request) {
    $this->site = $site;
    $this->request = $request;
  }

  /**
   * @param $responder
   * @return array
   */
  public function resolve($responder) {
    if ($responder instanceof ResponderInterface) {
      $responder = clone $responder;
      $responder->setRequest($this->request);
      $responder->setSite($this->site);
    }
    return $responder;
  }
}