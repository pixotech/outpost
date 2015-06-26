<?php

namespace Outpost\Cache;

interface CacheInterface {
  public function get($key, callable $callback, $lifetime = null);
}