<?php

namespace Outpost\Cache\Events;

use Outpost\Cache\CacheableInterface;
use Outpost\Events\Event;

abstract class CacheEvent extends Event {

  /**
   * @var CacheableInterface
   */
  protected $item;

  public function __construct(CacheableInterface $item) {
    parent::__construct();
    $this->item = $item;
  }

  public function getItem() {
    return $this->item;
  }

  public function getItemKey() {
    return $this->getItem()->getCacheKey();
  }

  public function getItemLifetime() {
    return $this->getItem()->getCacheLifetime();
  }

  public function getLocation() {
    return 'Cache';
  }
}