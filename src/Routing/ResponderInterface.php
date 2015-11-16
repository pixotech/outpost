<?php

namespace Outpost\Routing;

use Outpost\Resources\SiteResourceInterface;
use Symfony\Component\HttpFoundation\Request;

interface ResponderInterface extends SiteResourceInterface {

  /**
   * @return Request
   */
  public function getRequest();

  /**
   * @param Request $request
   */
  public function setRequest(Request $request);
}