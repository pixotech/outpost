<?php

namespace Outpost\Routing;

interface RouteUrlInterface {

  public function getUrl(array $variables = []);
}