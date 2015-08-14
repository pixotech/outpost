<?php

namespace Outpost\Cache;

use Outpost\Cache\Events\ItemFoundEvent;
use Outpost\Cache\Events\ItemMissingEvent;
use Outpost\SiteInterface;
use Stash\Interfaces\DriverInterface;
use Stash\Pool;

class Cache implements CacheInterface {

  protected $cache;
  protected $site;

  public function __construct(SiteInterface $site, DriverInterface $driver, $ns = null) {
    $this->site = $site;
    $this->cache = new Pool($driver);
    if (isset($ns)) $this->cache->setNamespace($ns);
  }

  public function clear($key) {
    $item = $this->getCache()->getItem($key);
    $item->clear();
  }

  public function get($key, callable $callback, $lifetime = null) {
    $cached = $this->cache->getItem($key);
    $content = $cached->get();
    if ($cached->isMiss()) {
      $this->site->handleEvent(new ItemMissingEvent($key, $callback, $lifetime));
      $cached->lock();
      $content = call_user_func($callback, $this->site);
      $cached->set($content, $lifetime);
    }
    else {
      $this->site->handleEvent(new ItemFoundEvent($key, $callback, $lifetime));
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