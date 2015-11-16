<?php

namespace Outpost\Routing;

use Outpost\Cache\CacheableInterface;
use Outpost\Resources\SiteResource;

class RouterDataResource extends SiteResource implements CacheableInterface {

  public function __invoke() {
    return $this->getRouterData();
  }

  public function getCacheKey() {
    return "router/data";
  }

  public function getCacheLifetime() {
    return null;
  }

  protected function getRouter() {
    return $this->getSite()->getRouter();
  }

  protected function getRouterData() {
    return $this->getRouter()->getData();
  }
}