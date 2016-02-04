<?php

namespace Outpost\Routing;

use Phroute\Phroute\RouteCollector;

class RouteUrl implements RouteUrlInterface {

  /**
   * @var string
   */
  protected $name;

  /**
   * @var RouteCollector
   */
  protected $router;

  public function __construct(RouteCollector $router, $name) {
    $this->router = $router;
    $this->name = $name;
  }

  public function __toString() {
    return (string) $this->__invoke();
  }

  public function __invoke() {
    return $this->getUrl(func_get_args());
  }

  public function getUrl(array $variables = []) {
    return '/' . $this->router->route($this->name, $variables);
  }
}