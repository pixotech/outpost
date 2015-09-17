<?php

namespace Outpost\Cache\Events;

class ItemMissingEvent extends CacheEvent {

  public function getLogMessage() {
    return sprintf("Not found: %s", $this->getItemKey());
  }
}