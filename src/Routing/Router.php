<?php

namespace Outpost\Routing;

use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\RouteCollector;

class Router implements RouterInterface {

  /**
   * @var RouteCollector
   */
  protected $router;

  /**
   * @var Route[]
   */
  protected $routes = [];

  public function __invoke(ResolverInterface $resolver) {
    $request = $resolver->getRequest();
    return $this->makeDispatcher($resolver)->dispatch($request->getMethod(), $request->getPathInfo());
  }

  /**
   * @return RouteCollector
   */
  public function getRouter() {
    if (!isset($this->router)) $this->router = $this->makeRouter();
    return $this->router;
  }

  public function makeUrl($name, array $variables = []) {
    return $this->getRouter()->route($name, $variables);
  }

  public function offsetExists($name) {
    return $this->getRouter()->hasRoute($name);
  }

  public function offsetGet($name) {
    return new RouteUrl($this->getRouter(), $name);
  }

  public function offsetSet($route, $responder) {
    if (is_array($responder)) {
      $name = $route;
      list($route, $responder) = each($responder);
    }
    if (!is_callable($responder)) throw new \InvalidArgumentException();
    $this->route($route, $responder, !empty($name) ? $name : null);
    $this->clearRouter();
  }

  public function offsetUnset($route) {
    unset($this->routes[Route::normalize($route)]);
    $this->clearRouter();
  }

  public function route($route, callable $responder, $name = null) {
    $route = new Route($route, $responder, $name);
    $this->routes[$route->getKey()] = $route;
  }

  protected function clearRouter() {
    $this->router = null;
  }

  protected function makeDispatcher(ResolverInterface $resolver) {
    return new Dispatcher($this->getRouter()->getData(), $resolver);
  }

  protected function makeRouter() {
    $router = new RouteCollector();
    foreach ($this->routes as $route) {
      $router->addRoute($route->getMethod(), $route->getRoute(), $route);
    }
    return $router;
  }
}