<?php

namespace Outpost\Routing;

class Route implements RouteInterface {

  protected $method;

  protected $name;

  protected $path;

  protected $responder;

  public static function normalize($route) {
    return implode(' ', self::split($route));
  }

  public static function split($route) {
    $split = preg_match('|^([A-Z]+)\s+(.+)$|', $route, $matches);
    return $split ? [$matches[1], $matches[2]] : ['GET', $route];
  }

  public function __construct($route, callable $responder, $name = null) {
    list($this->method, $this->path) = self::split($route);
    $this->responder = $responder;
    $this->name = $name;
  }

  public function __invoke() {
    return $this->responder;
  }

  public function getKey() {
    return "{$this->method} {$this->path}";
  }

  public function getMethod() {
    return $this->method;
  }

  public function getResponder() {
    return $this->responder;
  }

  public function getRoute() {
    return $this->name ? [$this->path, $this->name] : $this->path;
  }
}