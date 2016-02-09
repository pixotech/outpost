<?php

namespace Outpost\Routing;

interface RouterInterface extends \ArrayAccess {

  /**
   * @param ResolverInterface $resolver
   */
  public function __invoke(ResolverInterface $resolver);

  /**
   * @return \Phroute\Phroute\RouteCollector
   */
  public function getRouter();

  public function makeUrl($name, array $variables = []);

  public function route($route, callable $responder, $name = null);
}