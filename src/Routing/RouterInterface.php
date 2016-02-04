<?php

namespace Outpost\Routing;

interface RouterInterface {

  /**
   * @param ResolverInterface $resolver
   */
  public function __invoke(ResolverInterface $resolver);

  public function makeUrl($name, array $variables = []);

  public function route($route, callable $responder, $name = null);
}