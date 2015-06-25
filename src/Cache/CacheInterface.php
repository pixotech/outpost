<?php

namespace Outpost\Cache;

use Outpost\Resources\CacheableResourceInterface;

interface CacheInterface {
  public function get($key, $callback, array $args = [], $lifetime = null);
}