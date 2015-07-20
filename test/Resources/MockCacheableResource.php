<?php

namespace Outpost\Resources;

use Outpost\Cache\CacheableInterface;
use Outpost\SiteInterface;

class MockCacheableResource implements CacheableInterface, ResourceInterface {

  public $cacheKey;
  public $cacheLifetime;
  public $result;

  public function __construct($result, $key = null, $lifetime = null) {
    $this->result = $result;
    $this->cacheKey = $key;
    $this->cacheLifetime = $lifetime;
  }

  public function __invoke(SiteInterface $site) {
    return $this->result;
  }

  public function getCacheKey() {
    return $this->cacheKey;
  }

  public function getCacheLifetime() {
    return $this->cacheLifetime;
  }
}