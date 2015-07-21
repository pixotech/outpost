<?php

namespace Outpost\Cache\Events;

use Outpost\Cache\CacheableInterface;
use Outpost\Events\Event;

abstract class CacheEvent extends Event {

  protected $key;

  public function __construct($key, $callback, $lifetime) {
    parent::__construct();
    $this->key = $key;
    $this->callback = $key;
    $this->lifetime = $lifetime;
  }

  public function getItemKey() {
    return $this->key;
  }

  public function getItemLifetime() {
    return $this->lifetime;
  }

  public function getLocation() {
    return 'Cache';
  }
}