<?php

namespace Outpost\Cache\Events;

class ItemFoundEvent extends CacheEvent {

  public function getLogMessage() {
    return sprintf("Found: %s", $this->getItemKey());
  }
}