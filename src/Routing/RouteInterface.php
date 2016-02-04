<?php

namespace Outpost\Routing;

interface RouteInterface {

  public function getKey();

  public function getMethod();

  public function getResponder();

  public function getRoute();
}