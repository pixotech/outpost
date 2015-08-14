<?php

namespace Outpost\Cache;

interface CacheInterface {
  public function clear($key);
  public function get($key, callable $callback, $lifetime = null);
}