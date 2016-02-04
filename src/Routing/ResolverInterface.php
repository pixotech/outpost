<?php

namespace Outpost\Routing;

use Phroute\Phroute\HandlerResolverInterface;

interface ResolverInterface extends HandlerResolverInterface {

  /**
   * @return \Symfony\Component\HttpFoundation\Request
   */
  public function getRequest();
}