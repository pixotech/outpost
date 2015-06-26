<?php

namespace Outpost\Cache;

use Stash\Interfaces\DriverInterface;
use Stash\Pool;

class Cache implements CacheInterface {

  protected $cache;

  public function __construct(DriverInterface $driver, $ns = null) {
    $this->cache = new Pool($driver);
    if (isset($ns)) $this->cache->setNamespace($ns);
  }

  public function get($key, callable $callback, array $args = [], $lifetime = null) {
    $cached = $this->cache->getItem($key);
    $content = $cached->get();
    if ($cached->isMiss()) {
      $cached->lock();
      $content = call_user_func_array($callback, $args);
      $cached->set($content, $lifetime);
    }
    return $content;
  }

  /**
   * @return \Stash\Pool
   */
  public function getCache() {
    return $this->cache;
  }
}